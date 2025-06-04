<?php
require_once __DIR__ . '/../../class/evenement.php';

$eventid = isset($_GET['id']) ? intval($_GET['id']) : 0;

$evenement = new Evenement($db);

$event = $evenement->getEventById($eventid);

if (isset($_POST['reserve']) && !empty($_POST['numPlace']) && !empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['numTel']) && !empty($_POST['email']) && !empty($_POST['adresse'])) {
    $data = [
        'numPlace' => $_POST['numPlace'],
        'nom' => trim($_POST['nom']),
        'prenom' => trim($_POST['prenom']),
        'telephone' => trim($_POST['numTel']),
        'email' => trim($_POST['email']),
        'adresse' => trim($_POST['adresse']),
        'eventid' => $eventid
    ];
    $evenement->createReservation($data);
    header('Location: index.php?element=pages&action=reservation&id=' . urlencode($event['eventid']));
    
    exit;
}

// Génération PDF du plan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['downloadPdf'])) {
    // Nettoyage des buffers pour éviter le mélange d'HTML
    while (ob_get_level() > 0) {
        ob_end_clean();
    }
    require_once __DIR__ . '/../../vendor/autoload.php';
    $dompdf = new \Dompdf\Dompdf();
    // définition des zones
    $zones = [
        'Gradins' => ['rows' => ['X','W','V','U','T','S','R','Q','P','O','N','M','L','K','J','I','H','G','F'], 'place' => 22],
        'Chaises' => ['rows' => ['E5','E4','E3','E2'], 'place' => 25],
        'Fosse'   => ['rows' => ['E1','D','C','B','A'], 'place' => 22],
    ];
    $reservedPlaces = array_column($evenement->getReservation($eventid), 'numPlace');
    // récupérer la liste des réservations pour affichage
    $reservationsList = $evenement->getReservation($eventid);
    // construction HTML
    $html = '<html><head><meta charset="utf-8"><style>
        table{width:100%;border-collapse:collapse;}th,td{border:1px solid #000;padding:3px;text-align:center;}
        .reserved{background:#f88;} .unavailable{background:#ccc;} .available{background:#8f8;}
        h2 { page-break-after: avoid; }
        .no-page-break { page-break-inside: avoid; }
    </style></head><body>';
    // infos événement
    $html .= '<h1>' . htmlspecialchars($event['nom']) . '</h1>';
    $html .= '<p><strong>Date :</strong> ' . htmlspecialchars($event['jour']) . '</p>';
    $html .= '<p><strong>Prix place :</strong> ' . htmlspecialchars($event['prix']) . ' €</p>';
    $html .= '<p><strong>Tranche :</strong> ' . htmlspecialchars($event['fourchette']) . '</p>';
    $html .= '<h2>Plan de l\'événement ' . htmlspecialchars($event['nom']) . '</h2>';
    foreach ($zones as $zoneName => $zone) {
        $html .= '<h3>' . $zoneName . '</h3><table>';
        foreach ($zone['rows'] as $row) {
            $html .= '<tr><th>' . $row . '</th>';
            for ($place = 1; $place <= $zone['place']; $place++) {
                $code = "{$row}{$place}";
                // déterminer statut
                if ($zoneName === 'Gradins' && ((in_array($row, ['F','G','H','I','J','K','L','M','N','O']) && in_array($place, [3,20])) || ($row === 'X' && $place >= 6 && $place <= 17))) {
                    $cls = 'unavailable';
                } elseif (in_array($code, $reservedPlaces)) {
                    $cls = 'reserved';
                } elseif (!isPlaceInRanges($row, $place, $event['fourchette'])) {
                    $cls = 'unavailable';
                } else {
                    $cls = 'available';
                }
                $html .= '<td class="' . $cls . '">' . $place . '</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</table>';
    }
    // terminer section plan
    $html .= '</body>';
    // ajouter liste des réservations
    $html .= '<div class="no-page-break">';
    $html .= '<h2>Liste des réservations</h2>';
    $html .= '<table style="width:100%;border-collapse:collapse;"><thead><tr><th>Nom</th><th>Prénom</th><th>Téléphone</th><th>Email</th><th>Adresse</th><th>Place</th></tr></thead><tbody>';
    foreach ($reservationsList as $res) {
        $html .= '<tr><td>' . htmlspecialchars($res['nom']) . '</td><td>' . htmlspecialchars($res['prenom']) . '</td><td>' . htmlspecialchars($res['telephone']) . '</td><td>' . htmlspecialchars($res['email']) . '</td><td>' . htmlspecialchars($res['adresse']) . '</td><td>' . htmlspecialchars($res['numPlace']) . '</td></tr>';
    }
    $html .= '</tbody></table></div></html>';
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();
    $dompdf->stream('plan_event_' . $event['eventid'] . '.pdf', ['Attachment' => true]);
}

if (isset($_POST['titre']) && !empty($_POST['titre'])) {
    $reservations = $evenement->getResByName($_POST['titre'], $eventid);
} else {
    $reservations = $evenement->getReservation($eventid);
}

function isPlaceInRanges($row, $place, $rangesStr) {
    $ranges = explode(',', $rangesStr);
    foreach ($ranges as $range) {
        if (preg_match('/^([A-Z0-9]+):(\d+)-([A-Z0-9]+):(\d+)$/', $range, $m)) {
            list(, $startRow, $startNum, $endRow, $endNum) = $m;
            $rows = [
                'A','B','C','D','E1','E2','E3','E4','E5','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X'
            ];
            $startIdx = array_search($startRow, $rows);
            $endIdx = array_search($endRow, $rows);
            $rowIdx = array_search($row, $rows);
            if ($rowIdx === false || $startIdx === false || $endIdx === false) continue;
            if ($startIdx < $endIdx ||
                ($startIdx == $endIdx && $startNum <= $endNum)) {
                if (
                    ($rowIdx > $startIdx && $rowIdx < $endIdx) ||
                    ($rowIdx == $startIdx && $rowIdx == $endIdx && $place >= $startNum && $place <= $endNum) ||
                    ($rowIdx == $startIdx && $rowIdx != $endIdx && $place >= $startNum) ||
                    ($rowIdx == $endIdx && $rowIdx != $startIdx && $place <= $endNum)
                ) {
                    return true;
                }
            }
        }
    }
    return false;
}

if (isset($_POST['deleteRes']) && isset($_POST['delete_id'])) {
    $id = intval($_POST['delete_id']);
    $evenement->deleteReservation($id);
    header('Location: index.php?element=pages&action=reservation&id=' . urlencode($eventid));
    exit;
}

if (isset($_POST['deleteEvent']) && isset($_POST['delete_id'])) {
    $id = intval($_POST['delete_id']);
    $evenement->deleteEvent($id);
    header('Location: index.php');
    
}
