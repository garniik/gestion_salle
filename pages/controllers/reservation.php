<?php
require_once __DIR__ . '/../../class/evenement.php';

// Récupération de l'id de l'événement
$eventid = isset($_GET['id']) ? intval($_GET['id']) : 0;

$evenement = new Evenement($db);

// Récupération des informations de l'événement
$event = $evenement->getEventById($eventid);




if (isset($_POST['titre']) && !empty($_POST['titre'])) {
    $reservations = $evenement->getResByName($_POST['titre'], $eventid);
} else {
    $reservations = $evenement->getReservation($eventid);
}


function isPlaceInRanges($row, $seat, $rangesStr) {
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
                    ($rowIdx == $startIdx && $rowIdx == $endIdx && $seat >= $startNum && $seat <= $endNum) ||
                    ($rowIdx == $startIdx && $rowIdx != $endIdx && $seat >= $startNum) ||
                    ($rowIdx == $endIdx && $rowIdx != $startIdx && $seat <= $endNum)
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
}

if (isset($_POST['deleteEvent']) && isset($_POST['delete_id'])) {
    $id = intval($_POST['delete_id']);
    $evenement->deleteEvent($id);
    header('Location: index.php');
    
}
