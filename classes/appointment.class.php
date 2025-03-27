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
    public $purpose;
    public $reason;
    public $diagnosis;
    public $event_id;
    public $complaint;
    public $medcon_history;
    public $allergy;
    public $medication;
    public $observation;
    public $assessment;
    public $plan;
    public $prescription;
    public $comment;

    protected $db;

    function __construct()
    {
        $this->db = new Database();
    }

    function add_appointment()
    {
        $sql = "INSERT INTO appointment (doctor_id, patient_id, appointment_date, appointment_time, estimated_end, purpose, reason, appointment_status) VALUES (:doctor_id, :patient_id, :appointment_date, :appointment_time, :estimated_end, :purpose, :reason, :appointment_status);";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':doctor_id', $this->doctor_id);
        $query->bindParam(':patient_id', $this->patient_id);
        $query->bindParam(':appointment_date', $this->appointment_date);
        $query->bindParam(':appointment_time', $this->appointment_time);
        $query->bindParam(':estimated_end', $this->estimated_end);
        $query->bindParam(':appointment_status', $this->appointment_status);
        $query->bindParam(':purpose', $this->purpose);
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
        $sql = "SELECT a.account_id, ap.*, CONCAT(a.firstname, IF(a.middlename IS NOT NULL AND a.middlename != '', CONCAT(' ', a.middlename), ''), ' ', a.lastname) AS patient_name 
        FROM appointment ap 
        INNER JOIN patient_info p ON ap.patient_id = p.patient_id 
        INNER JOIN account a ON p.account_id = a.account_id
        WHERE ap.doctor_id = :doctor_id AND ap.appointment_status = :appointment_status ORDER BY appointment_date ASC, appointment_time ASC;";

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

    function get_appointment_details_user($appointment_id)
    {
        $sql = "SELECT ap.*, di.*, a.*, CONCAT(a.firstname, IF(a.middlename IS NOT NULL AND a.middlename != '', CONCAT(' ', a.middlename), ''), ' ', a.lastname) AS doctor_name 
        FROM appointment ap
        INNER JOIN doctor_info di ON ap.doctor_id = di.doctor_id
        INNER JOIN account a ON di.account_id = a.account_id
        WHERE ap.appointment_id = :appointment_id";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':appointment_id', $appointment_id);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetch();
        }
        return $data;
    }

    function get_full_dates($doctor_id, $start, $end)
    {
        $sql = "SELECT appointment_date
        FROM appointment
        WHERE (appointment_status = 'Incoming' OR appointment_status = 'Pending')
        AND doctor_id = :doctor_id
        AND appointment_time >= :start
        AND appointment_time < :end
        GROUP BY appointment_date
        HAVING COUNT(DISTINCT appointment_time) = 
            (SELECT COUNT(*) 
             FROM (SELECT DISTINCT appointment_time FROM appointment 
                   WHERE appointment_time >= :start 
                   AND appointment_time < :end) AS time_slots);";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':doctor_id', $doctor_id);
        $query->bindParam(':start', $start);
        $query->bindParam(':end', $end);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll();
        }
        return $data;
    }

    function get_taken_hours($doctor_id, $appointment_date)
    {
        $sql = "SELECT appointment_time
        FROM appointment
        WHERE (appointment_status = 'Incoming' OR appointment_status = 'Pending')
        AND doctor_id = :doctor_id
        AND appointment_date = :appointment_date
        ORDER BY appointment_time ASC;";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':doctor_id', $doctor_id);
        $query->bindParam(':appointment_date', $appointment_date);

        $data = [];
        if ($query->execute()) {
            $results = $query->fetchAll(PDO::FETCH_ASSOC);
            foreach ($results as $row) {
                $data[] = $row['appointment_time']; // Extract just the time
            }
        }
        return $data;
    }

    function check_availability($doctor_id, $appointment_date, $appointment_time, $appointment_id)
    {
        $sql = "SELECT ap.* FROM appointment ap
        INNER JOIN doctor_info di ON ap.doctor_id = di.doctor_id
        WHERE ap.appointment_id != :appointment_id AND ap.doctor_id = :doctor_id AND ap.appointment_date = :appointment_date AND ap.appointment_status = 'Incoming'
        AND (ap.appointment_time < ADDTIME(:appointment_time, '00:59:00') AND ap.estimated_end > :appointment_time)
        AND :appointment_time >= di.start_wt AND ADDTIME(:appointment_time, '00:59:00') <= di.end_wt
        ORDER BY ap.appointment_time ASC";

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

    function get_date_schedules($doctor_id, $appointment_date, $appointment_id)
    {
        $sql = "SELECT ap.*, a.*, CONCAT(a.firstname, IF(a.middlename IS NOT NULL AND a.middlename != '', CONCAT(' ', a.middlename), ''), ' ', a.lastname) AS patient_name FROM appointment ap
        INNER JOIN patient_info p ON ap.patient_id = p.patient_id
        INNER JOIN account a ON p.account_id = a.account_id
        WHERE ap.doctor_id = :doctor_id AND ap.appointment_id != :appointment_id AND ap.appointment_date = :appointment_date AND ap.appointment_status = 'Incoming'
        ORDER BY ap.appointment_time ASC";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':doctor_id', $doctor_id);
        $query->bindParam(':appointment_id', $appointment_id);
        $query->bindParam(':appointment_date', $appointment_date);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll();
        }
        return $data;
    }

    function update_appointment()
    {
        $sql = "UPDATE appointment SET appointment_date=:appointment_date, appointment_time=:appointment_time, estimated_end=:estimated_end, appointment_link=:appointment_link, event_id=:event_id, appointment_status=:appointment_status WHERE appointment_id=:appointment_id";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':appointment_id', $this->appointment_id);
        $query->bindParam(':appointment_date', $this->appointment_date);
        $query->bindParam(':appointment_time', $this->appointment_time);
        $query->bindParam(':estimated_end', $this->estimated_end);
        $query->bindParam(':appointment_link', $this->appointment_link);
        $query->bindParam(':appointment_status', $this->appointment_status);
        $query->bindParam(':event_id', $this->event_id);

        if ($query->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function decline_appointment()
    {
        $sql = "UPDATE appointment SET appointment_status=:appointment_status WHERE appointment_id=:appointment_id";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':appointment_id', $this->appointment_id);
        $query->bindParam(':appointment_status', $this->appointment_status);

        if ($query->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function reschedule_appointment()
    {
        $sql = "UPDATE appointment SET appointment_date=:appointment_date, appointment_time=:appointment_time, estimated_end=:estimated_end, appointment_status=:appointment_status WHERE appointment_id=:appointment_id";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':appointment_date', $this->appointment_date);
        $query->bindParam(':appointment_time', $this->appointment_time);
        $query->bindParam(':estimated_end', $this->estimated_end);
        $query->bindParam(':appointment_id', $this->appointment_id);
        $query->bindParam(':appointment_status', $this->appointment_status);

        if ($query->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function cancel_appointment()
    {
        $sql = "UPDATE appointment SET appointment_link=:appointment_link, event_id=:event_id, appointment_status=:appointment_status WHERE appointment_id=:appointment_id";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':appointment_link', $this->appointment_link);
        $query->bindParam(':event_id', $this->event_id);
        $query->bindParam(':appointment_id', $this->appointment_id);
        $query->bindParam(':appointment_status', $this->appointment_status);

        if ($query->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function get_patient_appointment($doctor_id, $account_id)
    {
        $sql = "SELECT ap.*, CONCAT(a.firstname, IF(a.middlename IS NOT NULL AND a.middlename != '', CONCAT(' ', a.middlename), ''), ' ', a.lastname) AS patient_name 
        FROM appointment ap 
        INNER JOIN patient_info p ON ap.patient_id = p.patient_id 
        INNER JOIN account a ON p.account_id = a.account_id
        WHERE ap.doctor_id = :doctor_id AND p.account_id = :account_id ORDER BY FIELD(ap.appointment_status, 'Ongoing', 'Incoming', 'Pending', 'Completed', 'Cancelled'), appointment_date DESC, appointment_time DESC;";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':doctor_id', $doctor_id);
        $query->bindParam(':account_id', $account_id);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll();
        }
        return $data;
    }

    function update_appointment_status()
    {
        $sql = "UPDATE appointment SET appointment_status=:appointment_status WHERE appointment_id = :appointment_id";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':appointment_status', $this->appointment_status);
        $query->bindParam(':appointment_id', $this->appointment_id);

        if ($query->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function complete_appointment()
    {
        $sql = "UPDATE appointment SET complaint=:complaint, medcon_history=:medcon_history, allergy=:allergy, medication=:medication, observation=:observation, assessment=:assessment, plan=:plan, prescription=:prescription, comment=:comment, appointment_status=:appointment_status, diagnosis=:diagnosis WHERE appointment_id = :appointment_id";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':complaint', $this->complaint);
        $query->bindParam(':medcon_history', $this->medcon_history);
        $query->bindParam(':allergy', $this->allergy);
        $query->bindParam(':medication', $this->medication);
        $query->bindParam(':observation', $this->observation);
        $query->bindParam(':assessment', $this->assessment);
        $query->bindParam(':plan', $this->plan);
        $query->bindParam(':prescription', $this->prescription);
        $query->bindParam(':comment', $this->comment);
        $query->bindParam(':appointment_status', $this->appointment_status);
        $query->bindParam(':diagnosis', $this->diagnosis);
        $query->bindParam(':appointment_id', $this->appointment_id);

        if ($query->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function get_completed_appointment($doctor_id, $account_id)
    {
        $sql = "SELECT ap.*, CONCAT(a.firstname, IF(a.middlename IS NOT NULL AND a.middlename != '', CONCAT(' ', a.middlename), ''), ' ', a.lastname) AS patient_name 
        FROM appointment ap 
        INNER JOIN patient_info p ON ap.patient_id = p.patient_id 
        INNER JOIN account a ON p.account_id = a.account_id
        WHERE ap.doctor_id = :doctor_id AND p.account_id = :account_id AND ap.appointment_status = 'Completed' ORDER BY appointment_date DESC, appointment_time DESC;";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':doctor_id', $doctor_id);
        $query->bindParam(':account_id', $account_id);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll();
        }
        return $data;
    }

    function get_patient_appointment_user($patient_id)
    {
        $sql = "SELECT ap.*, di.*, CONCAT(a.firstname, IF(a.middlename IS NOT NULL AND a.middlename != '', CONCAT(' ', a.middlename), ''), ' ', a.lastname) AS doctor_name 
        FROM appointment ap 
        INNER JOIN doctor_info di ON di.doctor_id = ap.doctor_id 
        INNER JOIN account a ON di.account_id = a.account_id
        WHERE ap.patient_id = :patient_id ORDER BY FIELD(ap.appointment_status, 'Ongoing', 'Incoming', 'Pending', 'Completed', 'Cancelled'), appointment_date DESC, appointment_time DESC;";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':patient_id', $patient_id);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll();
        }
        return $data;
    }
}
