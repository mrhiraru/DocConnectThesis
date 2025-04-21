<?php
require_once("../classes/database.php");

class Doctor
{
    public $doctor_id;
    public $account_id;
    public $is_created;
    public $is_updated;
    public $is_deleted;

    protected $db;

    function __construct()
    {
        $this->db = new Database();
    }

    function get_doctors()
    {
        $sql = "SELECT a.*, 
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

    function fetch_doctor($account_id)
    {
        $sql = "SELECT a.*, di.*,
                CONCAT(a.firstname, IF(a.middlename IS NOT NULL AND a.middlename != '', CONCAT(' ', a.middlename), ''), ' ', a.lastname) AS doctor_name
                FROM account a
                INNER JOIN doctor_info di ON di.account_id = a.account_id
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