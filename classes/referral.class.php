<?php
require_once("../classes/database.php");

class Refer
{
    public $referral_id;
    public $doctor_id;
    public $appointment_id;
    public $reason;
    public $status;
    public $is_created;
    public $is_updated;
    public $is_deleted;

    protected $db;

    function __construct()
    {
        $this->db = new Database();
    }

    function get_referral($doctor_id)
    {
        $sql = "SELECT a.*, di.*,
                CONCAT(a.firstname, IF(a.middlename IS NOT NULL AND a.middlename != '', CONCAT(' ', a.middlename), ''), ' ', a.lastname) AS doctor_name
                FROM account a
                INNER JOIN doctor_info di ON di.account_id = a.account_id
                WHERE a.user_role = 1
                ORDER BY a.lastname ASC, a.firstname ASC";

        $query = $this->db->connect()->prepare($sql);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll();
        }
        return $data;
    }

    function new_referral()
    {
        $sql = "INSERT INTO referral (doctor_id, appointment_id, reason, status) 
                VALUES (:doctor_id, :appointment_id, :reason, :status)";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':doctor_id', $this->doctor_id);
        $query->bindParam(':appointment_id', $this->appointment_id);
        $query->bindParam(':reason',  $this->reason);
        $query->bindParam(':status',  $this->status);

        if ($query->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
