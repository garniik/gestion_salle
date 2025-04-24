<?php

    require_once dirname(__FILE__) . '/../class/evenement.php';
    $evenement = new Evenement($bd);

    // If search submitted, filter by title
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['titre'])) {
        $titre = trim($_POST['titre']);
        // First try name search
        $events = $evenement->getEventByName($titre);
        if (empty($events)) {
            // Try parsing input as date
            $date = false;
            try {
                $d = new DateTime($titre);
                $date = $d->format('Y-m-d');
            } catch (Exception $e) {
                $date = false;
            }
            if ($date) {
                $events = $evenement->getEventByDate($date);
            }
            if (empty($events)) {
                $message = 'Aucun événement trouvé.';
            }
        }
    } else {
        $events = $evenement->getAll();
    }

?>

