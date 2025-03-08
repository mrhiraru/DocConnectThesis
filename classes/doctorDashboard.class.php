<?php
require_once("../classes/database.php");

class Dashboard
{
    protected $db;

    function __construct()
    {
        $this->db = new Database();
    }

    public function fetchTotalPatients()
    {
        $db = $this->db->connect();

        $sql = "SELECT COUNT(*) as total_patients FROM patient_info WHERE is_deleted = 0";
        $query = $db->prepare($sql);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        return $result['total_patients'];
    }

    public function fetchTodayPatients()
    {
        $db = $this->db->connect();

        $sql = "SELECT COUNT(DISTINCT patient_id) as today_patients 
                FROM appointment 
                WHERE DATE(appointment_date) = CURDATE() AND is_deleted = 0";
        $query = $db->prepare($sql);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        return $result['today_patients'];
    }

    public function fetchTodayAppointments()
    {
        $db = $this->db->connect();

        $sql = "SELECT COUNT(*) as today_appointments 
                FROM appointment 
                WHERE DATE(appointment_date) = CURDATE() AND is_deleted = 0";
        $query = $db->prepare($sql);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        return $result['today_appointments'];
    }

    public function fetchOverviewData()
    {
        return [
            'total_patients' => $this->fetchTotalPatients(),
            'today_patients' => $this->fetchTodayPatients(),
            'today_appointments' => $this->fetchTodayAppointments()
        ];
    }

    public function fetchPatientSummaryChartData()
    {
        $db = $this->db->connect();

        // Example: Fetch number of appointments by diagnosis
        $sql = "SELECT diagnosis, COUNT(*) as count 
                FROM appointment 
                WHERE diagnosis IS NOT NULL AND diagnosis != '' 
                GROUP BY diagnosis 
                ORDER BY count DESC 
                LIMIT 5";
        $query = $db->prepare($sql);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function fetchNextPatientDetails()
    {
        $db = $this->db->connect();

        $sql = "SELECT a.*, p.*, acc.firstname, acc.lastname, acc.gender, acc.birthdate, acc.account_image 
                FROM appointment a 
                JOIN patient_info p ON a.patient_id = p.patient_id 
                JOIN account acc ON p.account_id = acc.account_id 
                WHERE a.appointment_date >= CURDATE() 
                AND a.is_deleted = 0 
                ORDER BY a.appointment_date ASC 
                LIMIT 1";
        $query = $db->prepare($sql);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    public function fetchTodayAppointmentsDetails()
    {
        $db = $this->db->connect();

        $sql = "SELECT a.*, p.*, acc.firstname, acc.lastname, acc.account_image 
            FROM appointment a 
            JOIN patient_info p ON a.patient_id = p.patient_id 
            JOIN account acc ON p.account_id = acc.account_id 
            WHERE DATE(a.appointment_date) = CURDATE() 
            AND a.is_deleted = 0 
            ORDER BY a.appointment_time ASC";
        $query = $db->prepare($sql);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function fetchAppointmentRequests()
    {
        $db = $this->db->connect();

        $sql = "SELECT a.*, p.*, acc.firstname, acc.lastname, acc.account_image 
            FROM appointment a 
            JOIN patient_info p ON a.patient_id = p.patient_id 
            JOIN account acc ON p.account_id = acc.account_id 
            WHERE a.appointment_status = 'Pending' 
            AND a.is_deleted = 0 
            ORDER BY a.appointment_date ASC";
        $query = $db->prepare($sql);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }
}
