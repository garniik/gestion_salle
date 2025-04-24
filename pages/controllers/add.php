<?php
require_once __DIR__ . '/../../class/evenement.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eventName = trim($_POST['eventName'] ?? '');
    $eventDate = $_POST['eventDate'] ?? '';
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
    $ranges[] = "{$startRow}:{$min}-{$endRow}:{$max}";
}
    if (empty($message)) {
        $fourchettes = implode(',', $ranges);
        try {
            $evenement = new Evenement($db);
            $evenement->createEvent([
                    'eventName' => $eventName,
                    'eventDate' => $eventDate,
                    'fourchetteNumPlace' => $fourchettes,
                ]);
                $message = 'Evénement créé avec succès.';
            } catch (Exception $e) {
                $message = 'Erreur: ' . $e->getMessage();
            }
        }
    }
?>