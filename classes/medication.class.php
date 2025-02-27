<?php
require_once("../classes/database.php");

class Medication
{
    public $patient_id;
    public $medication_id;
    public $medication_name;
    public $dosage;
    public $med_usage;
    public $is_created;
    public $is_updated;
    public $is_deleted;

    protected $db;

    function __construct()
    {
        $this->db = new Database();
    }

    function get_medication($patient_id)
    {
        $sql = "SELECT * FROM medication 
        WHERE patient_id = :patient_id AND is_deleted != 1";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':patient_id', $patient_id);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll();
        }
        return $data;
    }

    function add_med()
    {
        $sql = "INSERT INTO medication (patient_id, medication_name, dosage, med_usage) VALUES (:patient_id, :medication_name, :dosage, :med_usage)";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':medication_name', $this->medication_name);
        $query->bindParam(':dosage', $this->dosage);
        $query->bindParam(':med_usage', $this->med_usage);
        
        $query->bindParam(':patient_id', $this->patient_id);

        if ($query->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
