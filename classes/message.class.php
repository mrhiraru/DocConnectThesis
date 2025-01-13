<?php
require_once("../classes/database.php");

class Message
{
    public $message_id;
    public $sender_id;
    public $receiver_id;
    public $message;
    public $status;
    public $is_read;
    public $is_created;

    public $account_id;
    public $firstname;
    public $middlename;
    public $lastname;
    public $account_image;




    protected $db;

    function __construct()
    {
        $this->db = new Database();
    }

    function fetch_messages($account_id, $with_account_id)
    {
        $sql = "SELECT * FROM messages
          WHERE (sender_id = :account_id AND receiver_id = :with_account_id)
          OR (sender_id = :with_account_id AND receiver_id = :account_id)
          ORDER BY is_created";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':account_id', $account_id);
        $query->bindParam(':with_account_id', $with_account_id);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll();
        }
        return $data;
    }

    function get_chats($account_id, $opposite_role, $search)
    {
        if (isset($search) && $search != '') {
            $search = trim(htmlentities($search));
            $searches = explode(" ", $search);
        }

        $sql = "SELECT DISTINCT a.*,
            (SELECT COUNT(*) FROM messages m WHERE m.receiver_id = :account_id AND m.sender_id = a.account_id AND m.is_read = 0) AS unread_count
            FROM messages m 
            LEFT JOIN account a ON a.account_id = m.receiver_id
            WHERE m.sender_id = :account_id AND m.receiver_id != :account_id";

        // If a search term is provided, add it to the query
        // if (!empty($search)) {
        //     $sql .= " AND (a.firstname LIKE :search OR a.middlename LIKE :search OR a.lastname LIKE :search)";
        // }

        if (isset($search) && $search != '') {
            $first_counter = 0;
            foreach ($searches as $key => $word) {
                if ($first_counter == 0) {
                    $sql .= " AND ((a.firstname LIKE :search_$key";
                } else {
                    $sql .= " OR a.firstname LIKE :search_$key";
                }
                $first_counter++;
            }
            $sql .= ")";
            $second_counter = 0;
            foreach ($searches as $key => $word) {
                if ($second_counter == 0) {
                    $sql .= " OR (a.middlename LIKE :search_$key";
                } else {
                    $sql .= " OR a.middlename LIKE :search_$key";
                }
                $second_counter++;
            }
            $sql .= ")";
            $third_counter = 0;
            foreach ($searches as $key => $word) {
                if ($third_counter == 0) {
                    $sql .= " OR (a.lastname LIKE :search_$key";
                } else {
                    $sql .= " OR a.lastname LIKE :search_$key";
                }
                $third_counter++;
            }
            $sql .= "))";
        }

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':account_id', $account_id);
        $query->bindParam(':opposite_role', $opposite_role);

        // if (!empty($search)) {
        //     $query->bindValue(':search', '%' . $search . '%');
        // }

        if (isset($search) && $search != '') {
            foreach ($searches as $key => $word) {
                $query->bindValue(":search_$key", "%$word%");
            }
        }

        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll();
        }
        return $data;
    }

    function mark_messages_read($chat_with_id, $account_id)
    {
        $sql = "UPDATE messages SET is_read = 1 WHERE receiver_id = :receiver_id AND sender_id = :sender_id AND is_read = 0";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':sender_id', $chat_with_id);
        $query->bindParam(':receiver_id', $account_id);

        if ($query->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function send_message()
    {
        $sql = "INSERT INTO messages (sender_id, receiver_id, message, status, is_read) VALUES (:sender_id, :receiver_id, :message, 'sent', 0)";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':sender_id', $this->sender_id);
        $query->bindParam(':receiver_id', $this->receiver_id);
        $query->bindParam(':message', $this->message);

        if ($query->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function load_messages($account_id, $chatwith_account_id)
    {

        $sql = "SELECT * FROM messages 
        WHERE sender_id = :account_id AND receiver_id = :chatwith_account_id
        OR sender_id = :chatwith_account_id AND receiver_id = :account_id";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':account_id', $account_id);
        $query->bindParam(':chatwith_account_id', $chatwith_account_id);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll();
        }
        return $data;
    }

    function load_chatbox($account_id)
    {
        $sql = "SELECT * FROM account WHERE account_id = :account_id";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':account_id', $account_id);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetch();
        }

        return $data;
    }
}
