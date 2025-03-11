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

        $sql = "SELECT diagnosis 
                FROM appointment 
                WHERE diagnosis IS NOT NULL AND diagnosis != ''";
        $query = $db->prepare($sql);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        $conditionCounts = [];

        foreach ($result as $row) {
            $diagnosis = $row['diagnosis'];

            $conditions = array_map('trim', explode(',', $diagnosis));

            foreach ($conditions as $condition) {
                if (!empty($condition)) {
                    if (isset($conditionCounts[$condition])) {
                        $conditionCounts[$condition]++;
                    } else {
                        $conditionCounts[$condition] = 1;
                    }
                }
            }
        }

        arsort($conditionCounts);

        $chartData = [];
        foreach ($conditionCounts as $condition => $count) {
            $chartData[] = [
                'diagnosis' => $condition,
                'count' => $count
            ];
        }

        $chartData = array_slice($chartData, 0, 5);

        return $chartData;
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

    public function fetchCalendarEvents()
    {
        $db = $this->db->connect();

        $sql = "SELECT a.appointment_id, a.appointment_date, a.appointment_time, a.purpose, 
                       acc.firstname, acc.lastname, acc.account_image 
                FROM appointment a 
                JOIN patient_info p ON a.patient_id = p.patient_id 
                JOIN account acc ON p.account_id = acc.account_id 
                WHERE a.is_deleted = 0 
                ORDER BY a.appointment_date ASC";
        $query = $db->prepare($sql);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        $events = [];
        foreach ($result as $row) {
            $appointmentDate = date('Y-m-d', strtotime($row['appointment_date']));

            $events[] = [
                'title' => $row['firstname'] . ' ' . $row['lastname'] . ' - ' . $row['purpose'],
                'url' => './manage-appointment.php?appointment_id=' . $row['appointment_id'],
                'start' => $appointmentDate . 'T' . $row['appointment_time']
            ];
        }

        return $events;
    }

    public function fetchAppointmentsByDiagnosis($diagnosis)
    {
        $db = $this->db->connect();

        $sql = "SELECT a.*, p.*, acc.firstname, acc.lastname, acc.email, acc.contact 
                FROM appointment a 
                JOIN patient_info p ON a.patient_id = p.patient_id 
                JOIN account acc ON p.account_id = acc.account_id 
                WHERE a.diagnosis LIKE :diagnosis 
                AND a.is_deleted = 0 
                ORDER BY a.appointment_date DESC";
        $query = $db->prepare($sql);
        $query->execute(['diagnosis' => '%' . $diagnosis . '%']);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }
}
