<?php
require_once("../classes/database.php");

class Allergy
{
    public $patient_id;
    public $allergy_id;
    public $is_created;
    public $is_updated;
    public $is_deleted;

    protected $db;

    function __construct()
    {
        $this->db = new Database();
    }

    function get_allergy($patient_id)
    {
        $sql = "SELECT * FROM allergy 
        WHERE patient_id = :patient_id AND is_deleted != 1";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':patient_id', $patient_id);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll();
        }
        return $data;
    }
}
