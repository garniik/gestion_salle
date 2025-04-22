<?php

    require_once dirname(__FILE__) . '/../class/evenement.php';
    $evenement = new Evenement($bd);

    $events = $evenement->getAll();

    

?>
