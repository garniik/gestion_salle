<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Autoloader
require_once(dirname(__FILE__) . '/../vendor/autoload.php');
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__FILE__) . '/../');
$dotenv->load();

// Récupération des informations présentes dans le fichier de conf .env
$db_host = getenv('DB_HOST') ?: ($_ENV['DB_HOST'] ?? '');
$db_name = getenv('DB_NAME') ?: ($_ENV['DB_NAME'] ?? '');
$db_port = getenv('DB_PORT') ?: ($_ENV['DB_PORT'] ?? '');
$db_username = getenv('DB_USER') ?: ($_ENV['DB_USER'] ?? '');
$db_password = getenv('DB_PASS') ?: ($_ENV['DB_PASS'] ?? '');

// ouverture de la connexion
$dsn = "mysql:host=$db_host;dbname=$db_name";
if (!empty($db_port)) {
    $dsn .= ";port=$db_port";
}
$db_options = array();

try {
    $db = new PDO($dsn, $db_username, $db_password, $db_options);
} catch (PDOException $e) {
    die("DB connexion failed: " . $e->getMessage());
}
return $db;
