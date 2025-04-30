<?php
/**
 * Script de migration : hashage des mots de passe existants en bcrypt
 * Usage : php scripts/hash_passwords.php
 */

require __DIR__ . '/../lib/mypdo.php';
$db = require __DIR__ . '/../lib/mypdo.php';

try {
    // Récupère tous les utilisateurs
    $stmt = $db->query("SELECT idUser, username, password FROM mpUser");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $update = $db->prepare("UPDATE mpUser SET password = :hash WHERE idUser = :id");
    $count = 0;

    foreach ($users as $u) {
        $current = $u['password'];
        // Si pas déjà hashé
        if (!preg_match('/^\$2y\$/', $current)) {
            $newHash = password_hash($current, PASSWORD_BCRYPT);
            $update->execute([':hash' => $newHash, ':id' => $u['idUser']]);
            echo "Updated user {$u['username']} (id: {$u['idUser']})\n";
            $count++;
        }
    }
    echo "Migration terminée, $count mot(s) de passe mis à jour.\n";
} catch (PDOException $e) {
    echo "Erreur de migration : " . $e->getMessage() . "\n";
    exit(1);
}
