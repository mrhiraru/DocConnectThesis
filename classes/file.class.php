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
}
