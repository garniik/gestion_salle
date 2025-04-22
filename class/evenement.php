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
    
    public function getByDate($date)
    {
        $stmt = $this->db->prepare("
            SELECT * from evenement where date = :date
        ");
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
?>
