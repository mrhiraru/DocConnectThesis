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
}
?>