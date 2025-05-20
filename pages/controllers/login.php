<?php
$db = require(dirname(__FILE__) . '/../../lib/mypdo.php');
if (!isset($_SESSION['mesgs']) || !is_array($_SESSION['mesgs'])) {
    $_SESSION['mesgs'] = ['confirm'=>[], 'errors'=>[]];
}
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
require_once(dirname(__FILE__) . '/../../lib/myproject.lib.php');
require_once __DIR__ . '/../../class/user.class.php';


$user = new User($db);

// Récupération des messages de session pour la vue
$message = '';
$messageType = '';
if (!empty($_SESSION['mesgs']['confirm'])) {
    $message = implode('<br>', $_SESSION['mesgs']['confirm']);
    $messageType = 'success';
    unset($_SESSION['mesgs']['confirm']);
} elseif (!empty($_SESSION['mesgs']['errors'])) {
    $message = implode('<br>', $_SESSION['mesgs']['errors']);
    $messageType = 'danger';
    unset($_SESSION['mesgs']['errors']);
}

if (GETPOST('debug') == true) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uname = $_POST['Username'] ?? '';
    $psw = $_POST['password'] ?? '';


    if (empty($uname) || empty($psw)) {
        $_SESSION['mesgs']['errors'][] = 'Veuillez remplir tous les champs.';
    } elseif ($db) {
        $userconnect = $user->getUser($uname);
        var_dump($psw);
        var_dump($userconnect['password']);
        var_dump(password_verify($psw, $userconnect['password']));
        if (is_array($userconnect) && password_verify($psw, $userconnect['password'])) {
            // Connexion réussie
            $_SESSION['mesgs']['confirm'][] = 'Connexion réussie ' . htmlspecialchars($userconnect['username']);
            $_SESSION['login'] = $userconnect['username'];
            $_SESSION['user'] = $userconnect;
            $_SESSION['authenticated'] = true;
            header('Location: index.php');
        } else {
            // Identification impossible
            $_SESSION['mesgs']['errors'][] = 'Identification impossible';
            header('Location: index.php?action=login');


        }
    } else {
        $_SESSION['mesgs']['errors'][] = 'Erreur de connexion à la base de données.';
    }
}
?> 