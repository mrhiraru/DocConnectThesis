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

    public $cb_message_id;
    public $message_type;


    protected $db;

    function __construct()
    {
        $this->db = new Database();
    }

    // USER CHAT FUNCTIONS

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
            LEFT JOIN account a ON (a.account_id = m.receiver_id OR a.account_id = m.sender_id)
            WHERE a.account_id != :account_id 
            AND (SELECT COUNT(*) FROM messages m 
            WHERE ((m.receiver_id = :account_id AND m.sender_id = a.account_id) 
            OR (m.receiver_id = a.account_id AND m.sender_id = :account_id))) > 0";
        //WHERE m.sender_id = :account_id AND m.receiver_id != :account_id";

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
        $sql = "INSERT INTO messages (sender_id, receiver_id, message, message_type, status, is_read) VALUES (:sender_id, :receiver_id, :message, :message_type, 'sent', 0)";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':sender_id', $this->sender_id);
        $query->bindParam(':receiver_id', $this->receiver_id);
        $query->bindParam(':message', $this->message);
        $query->bindParam(':message_type', $this->message_type);

        if ($query->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function get_doctor_account($doctor_id)
    {
        $sql = "SELECT account_id FROM doctor_info WHERE doctor_id = :doctor_id";

        $query = $this->db->connect()->prepare($sql);
        $query->bindValue(':doctor_id', $doctor_id);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetch();
        }

        return $data;
    }

    function get_patient_account($patient_id)
    {
        $sql = "SELECT account_id FROM patient_info WHERE patient_id = :patient_id";

        $query = $this->db->connect()->prepare($sql);
        $query->bindValue(':patient_id', $patient_id);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetch();
        }

        return $data;
    }

    function get_ids_from_appointment($appointment_id)
    {
        $sql = "SELECT 
            a.doctor_id, 
            d.account_id AS doctor_account_id, 
            a.patient_id, 
            p.account_id AS patient_account_id
        FROM appointment a
        JOIN doctor_info d ON a.doctor_id = d.doctor_id
        JOIN patient_info p ON a.patient_id = p.patient_id
        WHERE a.appointment_id = :appointment_id";

        $query = $this->db->connect()->prepare($sql);
        $query->bindValue(':appointment_id', $appointment_id);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetch();
        }

        return $data;
    }

    function load_messages($account_id, $chatwith_account_id, $last_message_id = 0)
    {
        $sql = "SELECT * FROM messages 
        WHERE ((sender_id = :account_id AND receiver_id = :chatwith_account_id)
        OR (sender_id = :chatwith_account_id AND receiver_id = :account_id))";

        if ($last_message_id > 0) {
            $sql .= " AND message_id > :last_message_id";
        }

        $sql .= " ORDER BY message_id ASC";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':account_id', $account_id);
        $query->bindParam(':chatwith_account_id', $chatwith_account_id);

        if ($last_message_id > 0) {
            $query->bindParam(':last_message_id', $last_message_id);
        }

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

    // CHATBOT FUNCTIONS

    function load_chatbot_messages($account_id, $last_message_id = 0)
    {
        $sql = "SELECT * FROM chatbot_messages 
        WHERE account_id = :account_id";

        if ($last_message_id > 0) {
            $sql .= " AND cb_message_id > :last_message_id";
        }

        $sql .= " ORDER BY cb_message_id ASC";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':account_id', $account_id);

        if ($last_message_id > 0) {
            $query->bindParam(':last_message_id', $last_message_id);
        }

        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll();
        }
        return $data;
    }

    function send_bot_message()
    {
        $sql = "INSERT INTO chatbot_messages (account_id, message, message_type) VALUES (:account_id, :message, :message_type)";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':account_id', $this->account_id);
        $query->bindParam(':message', $this->message);
        $query->bindParam(':message_type', $this->message_type);

        if ($query->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
