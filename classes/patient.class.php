<?php
require_once("../classes/database.php");

class Patient
{
    public $patient_id;
    public $account_id;
    public $parent_name;
    public $parent_contact;
    public $is_created;
    public $is_updated;
    public $is_deleted;

    protected $db;

    function __construct()
    {
        $this->db = new Database();
    }

    function get_patients($doctor_id)
    {
        $sql = "SELECT a.*, pi.*, COUNT(ap.appointment_id) AS appointment_count, 
            CONCAT(a.firstname, IF(a.middlename IS NOT NULL AND a.middlename != '', CONCAT(' ', a.middlename), ''), ' ', a.lastname) AS patient_name,
            MAX(ap.appointment_date) AS latest_appointment_date, 
            MAX(ap.appointment_time) AS latest_appointment_time
            FROM account a
            INNER JOIN patient_info pi ON pi.account_id = a.account_id
            INNER JOIN appointment ap ON pi.patient_id = ap.patient_id
            WHERE ap.doctor_id = :doctor_id
            GROUP BY pi.patient_id
            ORDER BY latest_appointment_date ASC, latest_appointment_time ASC";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':doctor_id', $doctor_id);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll();
        }
        return $data;
    }

    function fetch_patient($account_id)
    {
        $sql = "SELECT a.*, pi.*,
        CONCAT(a.firstname, IF(a.middlename IS NOT NULL AND a.middlename != '', CONCAT(' ', a.middlename), ''), ' ', a.lastname) AS patient_name
        FROM account a
        INNER JOIN patient_info pi ON pi.account_id = a.account_id
        WHERE a.account_id = :account_id";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':account_id', $account_id);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetch();
        }
        return $data;
    }
}
