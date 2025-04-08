<?php
require_once("../classes/database.php");

class File
{
    public $account_id;
    public $sender_id;
    public $receiver_id;
    public $file_name;
    public $file_description;

    protected $db;

    function __construct()
    {
        $this->db = new Database();
    }

    function add_file()
    {
        $sql = "INSERT INTO files (sender_id, receiver_id, file_name, file_description) VALUES (:sender_id, :receiver_id, :file_name, :file_description)";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':sender_id', $this->sender_id);
        $query->bindParam(':receiver_id', $this->receiver_id);
        $query->bindParam(':file_name', $this->file_name);
        $query->bindParam(':file_description', $this->file_description);

        if ($query->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function show_files_patient($sender_id, $receiver_id)
    {
        $sql = "SELECT f.*, CONCAT(a.firstname, IF(a.middlename IS NOT NULL AND a.middlename != '', CONCAT(' ', a.middlename), ''), 
        ' ', a.lastname) AS patient_name 
        FROM files f 
        INNER JOIN account a ON a.account_id = f.sender_id
        WHERE sender_id = :sender_id AND receiver_id = :receiver_id;";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':sender_id', $sender_id);
        $query->bindParam(':receiver_id', $receiver_id);
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':sender_id', $sender_id);
        $query->bindParam(':receiver_id', $receiver_id);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll();
        }
        return $data;
    }

    function show_files_doctor($sender_id, $receiver_id)
    {
        $sql = "SELECT f.*, CONCAT(a.firstname, IF(a.middlename IS NOT NULL AND a.middlename != '', CONCAT(' ', a.middlename), ''), 
        ' ', a.lastname) AS doctor_name 
        FROM files f 
        INNER JOIN account a ON a.account_id = f.sendedr_id
        WHERE sender_id = :sender_id AND receiver_id = :receiver_id;";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':sender_id', $sender_id);
        $query->bindParam(':receiver_id', $receiver_id);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll();
        }
        return $data;
    }
}
