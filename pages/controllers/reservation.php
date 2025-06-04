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
    
    // Initialisation de Dompdf
    require_once __DIR__ . '/../../vendor/autoload.php';
    $dompdf = new \Dompdf\Dompdf();
    
    /**
     * Définition des zones de places avec leurs caractéristiques
     * - rows: Liste des rangées pour chaque zone
     * - place: Nombre de places par rangée
     */
    $zones = [
        'Gradins' => [
            'rows' => ['X','W','V','U','T','S','R','Q','P','O','N','M','L','K','J','I','H','G','F'],
            'place' => 22
        ],
        'Chaises' => [
            'rows' => ['E5','E4','E3','E2'],
            'place' => 25
        ],
        'Fosse' => [
            'rows' => ['E1','D','C','B','A'],
            'place' => 22
        ],
    ];
    
    // Récupération des places déjà réservées
    $reservedPlaces = array_column($evenement->getReservation($eventid), 'numPlace');
    $reservationsList = $evenement->getReservation($eventid);
    
    /**
     * Fonction pour déterminer la classe CSS d'une place
     * @param string $zoneName Nom de la zone
     * @param string $row Ligne de la place
     * @param int $place Numéro de la place
     * @param string $code Code complet de la place (ex: "A1")
     * @param array $reservedPlaces Liste des places réservées
     * @param string $fourchette Plage de places disponibles
     * @return string Classe CSS à appliquer
     */
    function getPlaceStatus($zoneName, $row, $place, $code, $reservedPlaces, $fourchette) {
        // Places spéciales non disponibles dans les gradins
        if ($zoneName === 'Gradins' && 
            ((in_array($row, ['F','G','H','I','J','K','L','M','N','O']) && in_array($place, [3,20])) || 
             ($row === 'X' && $place >= 6 && $place <= 17))) {
            return 'unavailable';
        }
        
        // Place déjà réservée
        if (in_array($code, $reservedPlaces)) {
            return 'reserved';
        }
        
        // Place hors de la fourchette autorisée
        if (!isPlaceInRanges($row, $place, $fourchette)) {
            return 'unavailable';
        }
        
        // Place disponible
        return 'available';
    }
    
    // Construction du contenu HTML
    $html = '<!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <style>
            /* Styles de base */
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            h1 { color: #2c3e50; text-align: center; margin-bottom: 20px; }
            h2 { color: #3498db; border-bottom: 2px solid #3498db; padding-bottom: 5px; margin-top: 30px; }
            h3 { color: #2c3e50; margin-top: 20px; }
            table { width: 100%; border-collapse: collapse; margin: 15px 0; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
            th { background-color: #f2f2f2; font-weight: bold; }
            
            /* Styles pour les places */
            .reserved { background: #f88; }    /* Rouge pour les places réservées */
            .unavailable { background: #ddd; } /* Gris clair pour les places non disponibles */
            .available { background: #8f8; }   /* Vert pour les places disponibles */
            
            /* Gestion des pages */
            .page { page-break-after: always; padding: 2cm; }
            .page:last-child { page-break-after: auto; }
            .no-break { page-break-inside: avoid; }
            
            /* Style pour la page d information */
            .event-header { 
                text-align: center; 
                margin-bottom: 2cm; 
                padding: 1.5cm; 
                background-color: #f8f9fa; 
                border-radius: 10px;
                box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            }
            .event-details {
                margin: 20px auto;
                max-width: 80%;
            }
            .event-details p {
                margin: 10px 0;
                padding: 8px;
                background-color: #f8f9fa;
                border-left: 4px solid #3498db;
            }
            
            /* Styles pour le plan des places */
            .zone { 
                margin: 0 0 30px 0;
                overflow-x: auto;
                width: 100%;
                padding: 0 10px 20px 0;
                display: block;
                box-sizing: border-box;
            }
            .zone-title { 
                background-color: #3498db; 
                color: white; 
                padding: 8px; 
                border-radius: 4px;
                text-align: center;
                left: 0;
            }
            .zone table {
                max-width: 100%;
                width: 100%;
                table-layout: fixed;
            }
            .zone th, .zone td {
                min-width: 20px;
                height: 20px;
                padding: 2px 4px;
                font-size: 8px;
                text-align: center;
                white-space: nowrap;
                position: relative;
            }
            .zone th {
                background-color: #f2f2f2;
                left: 0;
                z-index: 1;
            }
            .zone td {
                border: 1px solid #ddd;
            }
            
            /* Style pour le tableau des réservations */
            .reservations-container {
                width: 100%;
                margin: 20px 0 20px -125px;
                overflow-x: auto;
                display: block;
            }
            .reservations-table {
                width: 100%;
                min-width: 800px;
                margin: 0;
                table-layout: fixed;
                border-collapse: collapse;
                box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            }
            .reservations-table th,
            .reservations-table td {
                padding: 12px 15px;
                text-align: left;
                word-break: break-word;
                border: 1px solid #ddd;
            }
            .reservations-table th {
                background-color: #3498db;
                color: white;
                top: 0;
            }
            .reservations-table tr:nth-child(even) {
                background-color: #f2f2f2;
            }
            /* Ajustement des colonnes */
            .reservations-table th:nth-child(1),
            .reservations-table td:nth-child(1) { width: 15%; } /* Nom */
            .reservations-table th:nth-child(2),
            .reservations-table td:nth-child(2) { width: 15%; } /* Prénom */
            .reservations-table th:nth-child(3),
            .reservations-table td:nth-child(3) { width: 15%; } /* Téléphone */
            .reservations-table th:nth-child(4),
            .reservations-table td:nth-child(4) { width: 40%; } /* Email */
            .reservations-table th:nth-child(5),
            .reservations-table td:nth-child(5) { width: 15%; } /* Place */
            
        </style>
    </head>
    <body>';
    
    // Page 1 : Informations de l'événement
    $html .= '<div class="page">
        <div class="event-header">
            <h1>' . htmlspecialchars($event['nom']) . '</h1>
            <p style="font-size: 1.2em; color: #7f8c8d;">Détails de l\'événement</p>
        </div>
        
        <div class="event-details">
            <h2>Informations générales</h2>
            <p><strong>Date :</strong> ' . htmlspecialchars($event['jour']) . '</p>
            <p><strong>Prix par place :</strong> ' . htmlspecialchars($event['prix']) . ' €</p>
            <p><strong>Plages horaires :</strong> ' . htmlspecialchars($event['fourchette']) . '</p>
            <p><strong>Nombre de places réservées :</strong> ' . count($reservationsList) . '</p>
        </div>
    </div>';
    
    // Page 2 : Plan des places
    $html .= '<div class="page">
        <h2 style="margin-top: -20px;">Plan de l\'événement</h2>
        <div style="text-align: center; margin: 5px 0 15px 0;">
            <p style="font-style: italic; color: #7f8c8d; margin-bottom: 10px;">
                <strong>Légende :</strong>
            </p>
            <div style="display: flex; justify-content: center; gap: 20px; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; margin: 5px 0;">
                    <span style="display: inline-block; width: 15px; height: 15px; background: #8f8; border: 1px solid #000; margin-right: 8px;"></span>
                    <span>Disponible</span>
                </div>
                <div style="display: flex; align-items: center; margin: 5px 0;">
                    <span style="display: inline-block; width: 15px; height: 15px; background: #f88; border: 1px solid #000; margin-right: 8px;"></span>
                    <span>Réservée</span>
                </div>
                <div style="display: flex; align-items: center; margin: 5px 0;">
                    <span style="display: inline-block; width: 15px; height: 15px; background: #ddd; border: 1px solid #000; margin-right: 8px;"></span>
                    <span>Non disponible</span>
                </div>
            </div>
        </div>';
    
    // Génération du plan pour chaque zone
    foreach ($zones as $zoneName => $zone) {
        $html .= '<div class="zone">
            <h3 class="zone-title" style="width: 100%; text-align: center;">' . htmlspecialchars($zoneName) . '</h3>
            <div style="width: 100%; overflow-x: auto;">
                <table>';
        
        // En-tête des numéros de place
        $html .= '<tr><th>Rangée</th>';
        for ($i = 1; $i <= $zone['place']; $i++) {
            $html .= '<th>' . $i . '</th>';
        }
        $html .= '</tr>';
        
        // Parcours des rangées de la zone
        foreach ($zone['rows'] as $row) {
            $html .= '<tr><th>' . htmlspecialchars($row) . '</th>';
            
            // Ajout des places pour chaque rangée
            for ($place = 1; $place <= $zone['place']; $place++) {
                $code = $row . $place;
                $status = getPlaceStatus($zoneName, $row, $place, $code, $reservedPlaces, $event['fourchette']);
                $html .= '<td class="' . $status . '" title="' . $code . '">' . $place . '</td>';
            }
            
            $html .= '</tr>';
        }
        
        $html .= '</table></div></div>';
    }
    $html .= '</div>'; 
    
    // Page 3 : Liste des réservations
    $html .= '<div class="page">
        <h2>Liste des réservations</h2>
        <p>Total des réservations : ' . count($reservationsList) . '</p>
        <div class="reservations-container">
        <table class="reservations-table" style="margin-left: 0;">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Téléphone</th>
                    <th>Email</th>
                    <th>Place</th>
                </tr>
            </thead>
            <tbody>';
    
    // Ajout des lignes de réservations
    $counter = 0;
    foreach ($reservationsList as $res) {
        $counter++;
        $rowClass = $counter % 2 === 0 ? 'even' : 'odd';
        $html .= '<tr class="' . $rowClass . '">
            <td>' . htmlspecialchars($res['nom']) . '</td>
            <td>' . htmlspecialchars($res['prenom']) . '</td>
            <td>' . htmlspecialchars($res['telephone']) . '</td>
            <td>' . htmlspecialchars($res['email']) . '</td>
            <td>' . htmlspecialchars($res['numPlace']) . '</td>
        </tr>';
    }
    
    $html .= '    </tbody>
        </table>
        </div>
    </div>
</body>
</html>';
    
    // Génération et envoi du PDF
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream('plan_evenement_' . $event['eventid'] . '.pdf', ['Attachment' => true]);
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
