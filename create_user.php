<?php
require_once './lib/mypdo.php'; // adapter si besoin
require_once './class/user.class.php';

$user = new User($db);

// Crée un utilisateur avec un mot de passe connu
$username = 'admin';
$password = 'admin';

echo $user->createUser($username, $password);
