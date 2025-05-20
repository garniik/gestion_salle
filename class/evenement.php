<?php
class Evenement
{
    private $db;
    private $data = [];

    public function __construct($dbConnection)
    {
        $this->db = $dbConnection;
    }

    public function getData($key)
    {
        return $this->data[$key] ?? null;
    }

    /**
     * Récupère tous les evenements
     * @return array
     */
    public function getAll()
    {
        $stmt = $this->db->prepare("
            SELECT * from evenement
        ");
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les evenements par date
     * @param string $date
     * @return array
     */
    public function getEventByDate($date)
    {
        $stmt = $this->db->prepare("
            SELECT * from evenement where jour = :date
        ");
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les réservations d'un evenement
     * @param int $eventid
     * @return array
     */
    public function getReservation($eventid)
    {
        $stmt = $this->db->prepare("
            SELECT * from reservation where eventid = :eventid
        ");
        $stmt->bindParam(':eventid', $eventid, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Crée un evenement
     * @param array $data
     */
    public function createEvent($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO evenement (nom, jour, fourchette, prix) VALUES (:nom, :date, :fourchetteNumPlace, :prixPlace)
        ");
        $stmt->bindParam(':nom', $data['eventName'], PDO::PARAM_STR);
        $stmt->bindParam(':date', $data['eventDate'], PDO::PARAM_STR);
        $stmt->bindParam(':fourchetteNumPlace', $data['fourchetteNumPlace'], PDO::PARAM_STR);
        $stmt->bindParam(':prixPlace', $data['prixPlace'], PDO::PARAM_STR);
        $stmt->execute();
    }

    /**
     * Met à jour un evenement
     * @param array $data
     */
    public function updateEvent($data)
    {
        $stmt = $this->db->prepare("
            UPDATE evenement SET nom = :nom, jour = :date, fourchette = :fourchetteNumPlace, prix = :prixPlace WHERE eventid = :eventid
        ");
        $stmt->bindParam(':nom', $data['eventName'], PDO::PARAM_STR);
        $stmt->bindParam(':date', $data['eventDate'], PDO::PARAM_STR);
        $stmt->bindParam(':fourchette', $data['fourchetteNumPlace'], PDO::PARAM_STR);
        $stmt->bindParam(':prixPlace', $data['prixPlace'], PDO::PARAM_STR);
        $stmt->bindParam(':eventid', $data['eventid'], PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     * Supprime un evenement 
     * @param int $eventid
     */
    public function deleteEvent($eventid)
    {
        $stmt = $this->db->prepare("DELETE FROM evenement WHERE eventid = :eventid " );
        $stmt->bindParam(':eventid', $eventid, PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     * Récupère un evenement par son id
     * @param int $eventid
     * @return array
     */ 
    public function getEventById($eventid)
    {
        $stmt = $this->db->prepare("
        SELECT * FROM evenement WHERE eventid = :eventid
        ");
        $stmt->bindParam(':eventid', $eventid, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Crée une reservation
     * @param array $data
     */
    public function createReservation($data)
    {
        $stm = $this->db->prepare("
            INSERT INTO reservation (numPlace, nom, prenom, telephone, email, adresse, eventid)
            VALUES (:numPlace, :nom, :prenom, :telephone, :email, :adresse, :eventid)
        ");
        $stm->bindParam(':numPlace', $data['numPlace'], PDO::PARAM_STR);
        $stm->bindParam(':nom', $data['nom'], PDO::PARAM_STR);
        $stm->bindParam(':prenom', $data['prenom'], PDO::PARAM_STR);
        $stm->bindParam(':telephone', $data['telephone'], PDO::PARAM_STR);
        $stm->bindParam(':email', $data['email'], PDO::PARAM_STR);
        $stm->bindParam(':adresse', $data['adresse'], PDO::PARAM_STR);
        $stm->bindParam(':eventid', $data['eventid'], PDO::PARAM_INT);
        $stm->execute();
    }

    /**
     * Supprime une réservation par son ID
     * @param int $resid
     */
    public function deleteReservation($resid)
    {
        $stmt = $this->db->prepare("DELETE FROM reservation WHERE Resid = :resid");
        $stmt->bindParam(':resid', $resid, PDO::PARAM_INT);
        $stmt->execute();
    }  

    /**
     * Recherche une evenement par son nom
     * @param string $name
     * @return array
     */
    public function getEventByName($name)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM evenement WHERE nom LIKE :name"
        );
        $param = "%{$name}%";
        $stmt->bindParam(':name', $param, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Recherche une reservation par son nom
     * @param string $name
     * @param int $eventid
     * @return array
     */
    public function getResByName($name, $eventid)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM reservation 
            WHERE eventid = :eventid AND (nom LIKE :name OR prenom LIKE :name)"
        );
        $param = "%{$name}%";
        $stmt->bindParam(':eventid', $eventid, PDO::PARAM_INT);
        $stmt->bindParam(':name', $param, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
