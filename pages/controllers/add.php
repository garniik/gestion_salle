<?php
require_once __DIR__ . '/../../class/evenement.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomEvent = trim($_POST['nomEvent'] ?? '');
    $dateEvent = $_POST['dateEvent'] ?? '';
    // Vérification nom et date
    if (empty($nomEvent) || empty($dateEvent)) {
        $message = 'Erreur: veuillez renseigner le nom et la date de l\'événement.';
    } else {
        $rowStart = $_POST['rowStart'] ?? [];
        $rowEnd = $_POST['rowEnd'] ?? [];
        $minPlaces = $_POST['minPlace'] ?? [];
        $maxPlaces = $_POST['maxPlace'] ?? [];

        $ranges = [];
        for ($i = 0; $i < count($minPlaces); $i++) {
            $startRow = trim($rowStart[$i] ?? '');
            $endRow = trim($rowEnd[$i] ?? '');
            $min = intval($minPlaces[$i]);
            $max = intval($maxPlaces[$i]);
            if (empty($startRow) || empty($endRow)) {
                $message = 'Erreur: veuillez sélectionner les rangées de début et de fin.';
                break;
            }
            if ($startRow==$endRow && $min > $max) {
                $message = 'Erreur: le numéro de place de début doit être inférieur au numéro de place de fin.';
                break;
            }
            $ranges[] = "{$startRow}:{$min}-{$endRow}:{$max}";
        }
        if (empty($message)) {
            $fourchettes = implode(',', $ranges);
            try {
                $evenement = new Evenement($db);
                $evenement->createEvent([
                    'eventName' => $nomEvent,
                    'eventDate' => $dateEvent,
                    'fourchetteNumPlace' => $fourchettes,
                ]);
                $message = 'Evénement créé avec succès.';
            } catch (Exception $e) {
                $message = 'Erreur: ' . $e->getMessage();
            }
        }
    }
}
?>