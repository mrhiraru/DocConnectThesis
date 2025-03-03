<?php
require_once("../classes/database.php");

class MedCon
{
    public $medcon_id;
    public $medcon_name;

    protected $db;

    function __construct()
    {
        $this->db = new Database();
    }

    // function get_medical_history($patient_id)
    // {
    //     $sql = "SELECT * FROM medical_history 
    //     WHERE patient_id = :patient_id AND is_deleted != 1";

    //     $query = $this->db->connect()->prepare($sql);
    //     $query->bindParam(':patient_id', $patient_id);

    //     $data = null;
    //     if ($query->execute()) {
    //         $data = $query->fetchAll();
    //     }
    //     return $data;
    // }

    function add_medhis()
    {
        $sql = "INSERT INTO medical_condition (medcon_name) VALUES (:medcon_name)";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':medcon_name', $this->medcon_name);

        if ($query->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function show_conditions()
    {
        $sql = "SELECT * FROM medical_condtion ORDER BY medcon_name";

        $query = $this->db->connect()->prepare($sql);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll();
        }
        return $data;
    }
}
