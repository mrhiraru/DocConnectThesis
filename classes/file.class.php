<?php
require_once("../classes/database.php");

class File
{
    public $account_id;
    public $purpose;
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
        $sql = "INSERT INTO files (purpose, sender_id, receiver_id, file_name, file_description) VALUES (:purpose, :sender_id, :receiver_id, :file_name, :file_description)";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':purpose', $this->purpose);
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
        WHERE sender_id = :sender_id AND receiver_id = :receiver_id
        ORDER BY f.is_created DESC;";
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
        INNER JOIN account a ON a.account_id = f.sender_id
        WHERE sender_id = :sender_id AND receiver_id = :receiver_id
        ORDER BY f.is_created DESC;";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':sender_id', $sender_id);
        $query->bindParam(':receiver_id', $receiver_id);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll();
        }
        return $data;
    }

    function show_files_doctor_to_campus($sender_id, $receiver_role, $user_role)
    {
        $sql = "SELECT f.*, CONCAT(a1.firstname, IF(a1.middlename IS NOT NULL AND a1.middlename != '', CONCAT(' ', a1.middlename), ''), 
        ' ', a1.lastname) AS doctor_name 
        FROM files f 
        INNER JOIN account a1 ON a1.account_id = f.sender_id AND a1.user_role = :user_role
        INNER JOIN account a2 ON a2.user_role = :receiver_role
        WHERE sender_id = :sender_id AND receiver_id = a2.account_id
        ORDER BY f.is_created DESC;";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':sender_id', $sender_id);
        $query->bindParam(':receiver_role', $receiver_role);
        $query->bindParam(':user_role', $user_role);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll();
        }
        return $data;
    }

    function show_files_campus_to_doctor($receiver_id, $sender_role, $user_role)
    {
        $sql = "SELECT f.*, CONCAT(a1.firstname, IF(a1.middlename IS NOT NULL AND a1.middlename != '', CONCAT(' ', a1.middlename), ''), 
        ' ', a1.lastname) AS doctor_name 
        FROM files f 
        INNER JOIN account a1 ON a1.account_id = :sender_role
        INNER JOIN account a2 ON a2.account_id = :receiver_id AND a2.user_role = :user_role
        WHERE sender_id = a1.account_id AND receiver_id = :receiver_id
        ORDER BY f.is_created DESC;";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':receiver_id', $receiver_id);
        $query->bindParam(':sender_role', $sender_role);
        $query->bindParam(':user_role', $user_role);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll();
        }
        return $data;
    }
}
