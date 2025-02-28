<?php
require_once("../classes/database.php");

class MedHis
{
    public $patient_id;
    public $medhis_id;
    public $his_condition;
    public $diagnosis_date;
    public $is_created;
    public $is_updated;
    public $is_deleted;

    protected $db;

    function __construct()
    {
        $this->db = new Database();
    }

    function get_medical_history($patient_id)
    {
        $sql = "SELECT * FROM medical_history 
        WHERE patient_id = :patient_id AND is_deleted != 1";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':patient_id', $patient_id);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll();
        }
        return $data;
    }

    function add_medhis(){
        $sql = "INSERT INTO medical_history (patient_id, his_condition, diagnosis_date) VALUES (:patient_id, :his_condition, :diagnosis_date)";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':his_condition', $this->his_condition);
        $query->bindParam(':diagnosis_date', $this->diagnosis_date);
        $query->bindParam(':patient_id', $this->patient_id);

        if ($query->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
