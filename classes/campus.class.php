<?php
require_once("../classes/database.php");

class Campus
{
    public $campus_id;
    public $campus_name;
    public $campus_address;
    public $campus_contact;
    public $campus_email;
    public $campus_profile;

    protected $db;

    function __construct()
    {
        $this->db = new Database();
    }

    function add_campus()
    {
        $sql = "INSERT INTO campus (campus_profile, campus_name, campus_address, campus_contact, campus_email) VALUES (:campus_profile, :campus_name, :campus_address, :campus_contact, :campus_email);";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':campus_profile', $this->campus_profile);
        $query->bindParam(':campus_name', $this->campus_name);
        $query->bindParam(':campus_address', $this->campus_address);
        $query->bindParam(':campus_contact', $this->campus_contact);
        $query->bindParam(':campus_email', $this->campus_email);

        if ($query->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function show_campus()
    {
        $sql = "SELECT * FROM campus WHERE is_deleted != 1 ORDER BY campus_id ASC;";
        $query = $this->db->connect()->prepare($sql);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll();
        }
        return $data;
    }

    function view_campus($campus_id)
    {
        $sql = "SELECT * FROM campus WHERE campus_id = :campus_id and is_deleted != 1";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':campus_id', $campus_id);
        if ($query->execute()) {
            $data = $query->fetch();
        }
        return $data;
    }
}
