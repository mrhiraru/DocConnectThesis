<?php
require_once("../classes/database.php");

class Appointment
{
    public $appointment_id;
    public $doctor_id;
    public $patient_id;
    public $appointment_date;
    public $appointment_time;
    public $estimated_end;
    public $appointment_link;
    public $appointment_status;
    public $reason;

    protected $db;

    function __construct()
    {
        $this->db = new Database();
    }

    function add_appointment()
    {
        $sql = "INSERT INTO appointment (doctor_id, patient_id, appointment_date, appointment_time, estimated_end, reason, appointment_status) VALUES (:doctor_id, :patient_id, :appointment_date, :appointment_time, :estimated_end, :reason, :appointment_status);";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':doctor_id', $this->doctor_id);
        $query->bindParam(':patient_id', $this->patient_id);
        $query->bindParam(':appointment_date', $this->appointment_date);
        $query->bindParam(':appointment_time', $this->appointment_time);
        $query->bindParam(':estimated_end', $this->estimated_end);
        $query->bindParam(':appointment_status', $this->appointment_status);
        $query->bindParam(':reason', $this->reason);

        if ($query->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function add_link()
    {
        $sql = "UPDATE appointment SET appointment_link = :appointment_link WHERE appointment_id = :appointment_id";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':appointment_link', $this->appointment_link);
        $query->bindParam(':appointment_id', $this->appointment_id);

        if ($query->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function user_appointments($patient_id)
    {
        $sql = "SELECT ap.*, CONCAT(a.firstname, IF(a.middlename IS NOT NULL AND a.middlename != '', CONCAT(' ', a.middlename), ''), ' ', a.lastname) AS doctor_name 
        FROM appointment ap 
        INNER JOIN doctor_info d ON ap.doctor_id = d.doctor_id 
        INNER JOIN account a ON d.account_id = a.account_id
        WHERE ap.patient_id = :patient_id ORDER BY appointment_date, appointment_time;";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':patient_id', $patient_id);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll();
        }
        return $data;
    }

    function doctor_appointments($doctor_id, $appointment_status)
    {
        $sql = "SELECT ap.*, CONCAT(a.firstname, IF(a.middlename IS NOT NULL AND a.middlename != '', CONCAT(' ', a.middlename), ''), ' ', a.lastname) AS patient_name 
        FROM appointment ap 
        INNER JOIN patient_info p ON ap.patient_id = p.patient_id 
        INNER JOIN account a ON p.account_id = a.account_id
        WHERE ap.doctor_id = :doctor_id AND ap.appointment_status = :appointment_status ORDER BY appointment_date, appointment_time;";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':doctor_id', $doctor_id);
        $query->bindParam(':appointment_status', $appointment_status);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll();
        }
        return $data;
    }

    function get_appointment_details($appointment_id)
    {
        $sql = "SELECT ap.*, p.*, a.*, CONCAT(a.firstname, IF(a.middlename IS NOT NULL AND a.middlename != '', CONCAT(' ', a.middlename), ''), ' ', a.lastname) AS patient_name 
        FROM appointment ap
        INNER JOIN patient_info p ON ap.patient_id = p.patient_id
        INNER JOIN account a ON p.account_id = a.account_id
        WHERE ap.appointment_id = :appointment_id";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':appointment_id', $appointment_id);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetch();
        }
        return $data;
    }

    function check_availability($doctor_id, $appointment_date, $appointment_time, $appointment_id)
    {
        $sql = "SELECT ap.* FROM appointment ap
        INNER JOIN doctor_info di ON ap.doctor_id = di.doctor_id
        WHERE ap.appointment_id != :appointment_id AND ap.doctor_id = :doctor_id AND ap.appointment_date = :appointment_date AND ap.appointment_status = 'Incoming'
        AND (ap.appointment_time < ADDTIME(:appointment_time, '00:59:00') AND ap.estimated_end > :appointment_time)
        AND :appointment_time >= di.start_wt AND ADDTIME(:appointment_time, '00:59:00') <= di.end_wt";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':doctor_id', $doctor_id);
        $query->bindParam(':appointment_date', $appointment_date);
        $query->bindParam(':appointment_time', $appointment_time);
        $query->bindParam(':appointment_id', $appointment_id);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll();
        }
        return $data;
    }

    function get_appointment_schedules($doctor_id)
    {
        $sql = "SELECT ap.* FROM appointment ap
        WHERE ap.doctor_id = :doctor_id AND ap.appointment_status = 'Incoming'";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':doctor_id', $doctor_id);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll();
        }
        return $data;
    }
}
