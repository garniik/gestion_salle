<?php
class User {
    private $pdo;
    private $rowid;

    // Constructeur pour initialiser la connexion à la base de données et l'identifiant utilisateur
    public function __construct($pdo, $rowid=1) {
        $this->pdo = $pdo;
        $this->rowid = $rowid;
    }

    public function createUser($username, $password, $firstname, $lastname, $admin = 0) {
        try {
            // Vérifier si le nom d'utilisateur existe déjà
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM mpUser WHERE username = :username");
            $stmt->execute([':username' => $username]);
            if ($stmt->fetchColumn() > 0) {
                return "Le nom d'utilisateur existe déjà.";
            }

            // Hacher le mot de passe avec bcrypt
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Insérer l'utilisateur dans la base de données
            $stmt = $this->pdo->prepare(
                "INSERT INTO mpUser (username, password, firstname, lastname, admin) 
                 VALUES (:username, :password, :firstname, :lastname, :admin)"
            );
            $stmt->execute([
                ':username' => $username,
                ':password' => $hashedPassword,
                ':firstname' => $firstname,
                ':lastname' => $lastname,
                ':admin' => $admin,
            ]);

            return "Utilisateur créé avec succès.";
        } catch (PDOException $e) {
            return "Erreur lors de la création de l'utilisateur : " . $e->getMessage();
        }
    }

    public function getUser($username) {
        try {
            $stmt = $this->pdo->prepare(
                "SELECT * 
                 FROM mpUser 
                 WHERE username = :username"
            );
            $stmt->execute([':username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                return $user;
            } else {
                return "Utilisateur non trouvé.";
            }
        } catch (PDOException $e) {
            return "Erreur lors de la récupération de l'utilisateur : " . $e->getMessage();
        }
    }
}
?>

    


    
    


