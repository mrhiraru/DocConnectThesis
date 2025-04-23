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
    public $email;
    public $password;
    public $user_role;
    public $verification_status;

    protected $db;

    function __construct()
    {
        $this->db = new Database();
    }

    function add_campus()
    {
        $connect = $this->db->connect();
        $connect->beginTransaction();

        $sql = "INSERT INTO campus (campus_profile, campus_name, campus_address, campus_contact, campus_email) VALUES (:campus_profile, :campus_name, :campus_address, :campus_contact, :campus_email);";

        $query = $connect->prepare($sql);
        $query->bindParam(':campus_profile', $this->campus_profile);
        $query->bindParam(':campus_name', $this->campus_name);
        $query->bindParam(':campus_address', $this->campus_address);
        $query->bindParam(':campus_contact', $this->campus_contact);
        $query->bindParam(':campus_email', $this->campus_email);

        if ($query->execute()) {
            $last_product_id = $connect->lastInsertId();

            $sec_sql = "INSERT INTO account (email, password, user_role, campus_id, verification_status) VALUES (:email, :password, :user_role, :campus_id, :verification_status);";

            $sec_query = $connect->prepare($sec_sql);
            $sec_query->bindParam(':email', $this->email);
            $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
            $sec_query->bindParam(':password', $hashedPassword);
            $sec_query->bindParam(':user_role', $this->user_role);
            $sec_query->bindParam(':campus_id', $last_product_id);
            $sec_query->bindParam(':verification_status', $this->verification_status);

            if ($sec_query->execute()) {
                $connect->commit();
                return true;
            } else {
                $connect->rollBack();
                return false;
            }
        } else {
            $connect->rollBack();
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
        $sql = "SELECT * FROM campus c  WHERE campus_id = :campus_id and is_deleted != 1";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':campus_id', $campus_id);
        if ($query->execute()) {
            $data = $query->fetch();
        }
        return $data;
    }

    function show_campus_mod()
    {
        $sql = "SELECT c.*, a.account_id, FROM campus c
        INNER JOIN account a ON a.campus_id = c.campus_id
        WHERE a.user_role = 2 AND a.is_deleted != 1 AND c.is_deleted != 1
        ORDER BY campus_id ASC;";
        $query = $this->db->connect()->prepare($sql);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll();
        }
        return $data;
    }
}
