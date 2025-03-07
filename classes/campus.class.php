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
    public $moderator_id;

    protected $db;

    function __construct()
    {
        $this->db = new Database();
    }

    function add_campus()
    {
        $sql = "INSERT INTO campus (campus_profile, campus_name, campus_address, campus_contact, campus_email, moderator_id) VALUES (:campus_profile, :campus_name, :campus_address, :campus_contact, :campus_email, :moderator_id);";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':campus_profile', $this->campus_profile);
        $query->bindParam(':campus_name', $this->campus_name);
        $query->bindParam(':campus_address', $this->campus_address);
        $query->bindParam(':campus_contact', $this->campus_contact);
        $query->bindParam(':campus_email', $this->campus_email);
        $query->bindParam(':moderator_id', $this->moderator_id);

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

    function get_moderators()
    {
        $sql = "SELECT account_id, firstname FROM account WHERE user_role = 2 AND account_id NOT IN (SELECT moderator_id FROM campus WHERE moderator_id IS NOT NULL)";
        $query = $this->db->connect()->prepare($sql);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll();
        }
        return $data;
    }

    function get_moderator_name($moderator_id)
    {
        $sql = "SELECT firstname FROM account WHERE account_id = :moderator_id";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':moderator_id', $moderator_id);
        if ($query->execute()) {
            $data = $query->fetch();
        }
        return $data ? $data['firstname'] : 'No Moderator Assigned';
    }
}
?>