<?php
require_once("../classes/database.php");

class Allergy
{
    public $patient_id;
    public $allergy_id;
    public $allergy_name;
    public $description;
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

    function add_allergy(){
        $sql = "INSERT INTO allergy (patient_id, allergy_name, description) VALUES (:patient_id, :allergy_name, :description)";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':allergy_name', $this->allergy_name);
        $query->bindParam(':description', $this->description);
        $query->bindParam(':patient_id', $this->patient_id);

        if ($query->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
