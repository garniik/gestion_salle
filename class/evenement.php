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
        
    }

    public function getByDate()
    {

    }

    public function 

}
?>

