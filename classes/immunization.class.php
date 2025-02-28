<?php
require_once("../classes/database.php");

class Immunization
{
    public $patient_id;
    public $immu_id;
    public $immunization_name;
    public $is_created;
    public $is_updated;
    public $is_deleted;

    protected $db;

    function __construct()
    {
        $this->db = new Database();
    }

    function get_immunization($patient_id)
    {
        $sql = "SELECT * FROM immunization 
        WHERE patient_id = :patient_id AND is_deleted != 1";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':patient_id', $patient_id);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll();
        }
        return $data;
    }

    function add_immu()
    {
        $sql = "INSERT INTO immunization (patient_id, immunization_name) VALUES (:patient_id, :immunization_name)";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':immunization_name', $this->immunization_name);
        $query->bindParam(':patient_id', $this->patient_id);

        if ($query->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
