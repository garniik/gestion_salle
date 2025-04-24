
<!-- MLD:
evenement = (eventid INT, nom VARCHAR(50), jour DATE, fourchette VARCHAR(50));
reservation = (Resid INT, numPlace INT, nom VARCHAR(50), prenom VARCHAR(50), #eventid*);
 -->


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

    public function getAll()
    {
        $stmt = $this->db->prepare("
            SELECT * from evenement
        ");
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getEventByDate($date)
    {
        $stmt = $this->db->prepare("
            SELECT * from evenement where jour = :date
        ");
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReservation($eventid)
    {
        $stmt = $this->db->prepare("
            SELECT * from reservation where eventid = :eventid
        ");
        $stmt->bindParam(':eventid', $eventid, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createEvent($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO evenement (nom, jour , fourchette) VALUES (:nom, :date, :fourchetteNumPlace)
        ");
        $stmt->bindParam(':nom', $data['eventName'], PDO::PARAM_STR);
        $stmt->bindParam(':date', $data['eventDate'], PDO::PARAM_STR);
        $stmt->bindParam(':fourchetteNumPlace', $data['fourchetteNumPlace'], PDO::PARAM_STR);
        $stmt->execute();
    }

    public function updateEvent($data)
    {
        $stmt = $this->db->prepare("
            UPDATE evenement SET nom = :nom, jour = :date, fourchette = :fourchetteNumPlace WHERE eventid = :eventid
        ");
        $stmt->bindParam(':nom', $data['eventName'], PDO::PARAM_STR);
        $stmt->bindParam(':date', $data['eventDate'], PDO::PARAM_STR);
        $stmt->bindParam(':fourchette', $data['fourchetteNumPlace'], PDO::PARAM_STR);
        $stmt->bindParam(':eventid', $data['eventid'], PDO::PARAM_INT);
        $stmt->execute();
    }

    public function deleteEvent($eventid)
    {
        $stmt = $this->db->prepare("DELETE FROM evenement WHERE eventid = :eventid " );
        $stmt->bindParam(':eventid', $eventid, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getEventById($eventid)
    {
        $stmt = $this->db->prepare("
        SELECT * FROM evenement WHERE eventid = :eventid
        ");
        $stmt->bindParam(':eventid', $eventid, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createReservation($data)
    {
        $stm = $this->db->prepare("
            INSERT INTO reservation (numPlace, nom, prenom, eventid) VALUES (:numPlace, :nom, :prenom, :eventid)
        ");
        $stm->bindParam(':numPlace', $data['numPlace'], PDO::PARAM_INT);
        $stm->bindParam(':nom', $data['nom'], PDO::PARAM_STR);
        $stm->bindParam(':prenom', $data['prenom'], PDO::PARAM_STR);
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
     * Search events by name (partial match)
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

    // Add method to search reservations by name within an event
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
