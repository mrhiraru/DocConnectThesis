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
        $sql = "SELECT r.*,
                CONCAT(a1.firstname, IF(a1.middlename IS NOT NULL AND a1.middlename != '', CONCAT(' ', a1.middlename), ''), ' ', a1.lastname) AS patient_name,
                CONCAT(a2.firstname, IF(a2.middlename IS NOT NULL AND a2.middlename != '', CONCAT(' ', a2.middlename), ''), ' ', a2.lastname) AS doctor_name
                FROM referral r
                INNER JOIN appointment ap ON r.appointment_id = ap.appointment_id
                INNER JOIN account a1 ON ap.patient_id = a1.account_id
                INNER JOIN account a2 ON ap.doctor_id = a2.account_id
                WHERE r.doctor_id = :doctor_id ORDER BY r.is_created ASC";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':doctor_id', $doctor_id);

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
