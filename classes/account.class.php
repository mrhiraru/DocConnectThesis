<?php
require_once("../classes/database.php");

class Account
{
    public $account_id;
    public $email;
    public $password;
    public $firstname;
    public $middlename;
    public $lastname;
    public $gender;
    public $birthdate;
    public $contact;
    public $account_image;
    public $address;
    public $user_role;
    public $verification_status;
    public $campus_id;
    public $bio;
    public $specialty;
    public $start_wt;
    public $end_wt;
    public $start_day;
    public $end_day;
    public $appointment_limits;
    public $patient_id;
    public $doctor_id;
    public $campus_name;


    protected $db;

    function __construct()
    {
        $this->db = new Database();
    }

    function sign_in_account()
    {
        $sql = "SELECT * FROM account WHERE email = :email LIMIT 1;";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':email', $this->email);

        if ($query->execute()) {
            $accountData = $query->fetch(PDO::FETCH_ASSOC);

            if ($accountData && password_verify($this->password, $accountData['password'])) {
                $this->account_id = $accountData['account_id'];
                $this->user_role = $accountData['user_role'];
                $this->firstname = $accountData['firstname'];
                $this->middlename = $accountData['middlename'];
                $this->lastname = $accountData['lastname'];
                $this->email = $accountData['email'];
                $this->verification_status = $accountData['verification_status'];
                $this->account_image = $accountData['account_image'];
                $this->address = $accountData['address'];

                return true;
            }
        }
    }

    // admin functions begin

    function add_admin()
    {
        $sql = "INSERT INTO account (email, password, firstname, middlename, lastname, user_role) VALUES (:email, :password, :firstname, :middlename, :lastname, :user_role);";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':email', $this->email);
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
        $query->bindParam(':password', $hashedPassword);
        $query->bindParam(':firstname', $this->firstname);
        $query->bindParam(':middlename', $this->middlename);
        $query->bindParam(':lastname', $this->lastname);
        $query->bindParam(':user_role', $this->user_role);

        if ($query->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function is_email_exist()
    {
        $sql = "SELECT * FROM account WHERE email = :email;";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':email', $this->email);
        if ($query->execute()) {
            if ($query->rowCount() > 0) {
                return true;
            }
        }
        return false;
    }

    function check_for_admin($user_role)
    {
        $sql = "SELECT * FROM account WHERE user_role = :user_role;";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':user_role', $user_role);
        if ($query->execute()) {
            if ($query->rowCount() > 0) {
                return true;
            }
        }
        return false;
    }


    // admin functions end

    function verify()
    {
        $sql = "UPDATE account SET verification_status = :verification_status WHERE account_id = :account_id";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':verification_status', $this->verification_status);
        $query->bindParam(':account_id', $this->account_id);

        if ($query->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // doctor functions start
    // fix laterr
    function add_doc()
    {
        $connect = $this->db->connect();
        $connect->beginTransaction();

        $sql = "INSERT INTO account (email, password, firstname, middlename, lastname, user_role, contact, birthdate, gender) VALUES (:email, :password, :firstname, :middlename, :lastname, :user_role, :contact, :birthdate, :gender);";

        $query = $connect->prepare($sql);
        $query->bindParam(':email', $this->email);
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
        $query->bindParam(':password', $hashedPassword);
        $query->bindParam(':firstname', $this->firstname);
        $query->bindParam(':middlename', $this->middlename);
        $query->bindParam(':lastname', $this->lastname);
        $query->bindParam(':user_role', $this->user_role);
        $query->bindParam(':contact', $this->contact);
        $query->bindParam(':birthdate', $this->birthdate);
        $query->bindParam(':gender', $this->gender);

        if ($query->execute()) {
            $last_product_id = $connect->lastInsertId();

            $sec_sql = "INSERT INTO doctor_info (account_id) VALUES (:account_id)";

            $sec_query = $connect->prepare($sec_sql);
            $sec_query->bindParam(':account_id', $last_product_id);

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


    function show_doc()
    {
        $sql = "SELECT a.*, d.* FROM account a INNER JOIN doctor_info d ON d.account_id = a.account_id WHERE a.user_role = 1 AND a.is_deleted != 1 ORDER BY a.account_id ASC;";
        $query = $this->db->connect()->prepare($sql);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll();
        }
        return $data;
    }

    function get_doctor()
    {
        $sql = "SELECT a.*, d.*, CONCAT(a.firstname, IF(a.middlename IS NOT NULL AND a.middlename != '', CONCAT(' ', a.middlename), ''), 
        ' ', a.lastname) AS doctor_name FROM account a INNER JOIN doctor_info d ON a.account_id = d.account_id WHERE a.user_role = 1 AND d.is_deleted = 0";
        $query = $this->db->connect()->prepare($sql);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll();
        }
        return $data;
    }


    function sign_in_doctor()
    {
        $sql = "SELECT a.*, d.* FROM account a INNER JOIN doctor_info d ON a.account_id = d.account_id WHERE email = :email LIMIT 1;";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':email', $this->email);

        if ($query->execute()) {
            $accountData = $query->fetch(PDO::FETCH_ASSOC);

            if ($accountData && password_verify($this->password, $accountData['password'])) {
                $this->account_id = $accountData['account_id'];
                $this->user_role = $accountData['user_role'];
                $this->firstname = $accountData['firstname'];
                $this->middlename = $accountData['middlename'];
                $this->lastname = $accountData['lastname'];
                $this->gender = $accountData['gender'];
                $this->birthdate = $accountData['birthdate'];
                $this->email = $accountData['email'];
                $this->contact = $accountData['contact'];
                $this->address = $accountData['address'];
                $this->verification_status = $accountData['verification_status'];
                $this->account_image = $accountData['account_image'];
                $this->start_wt = $accountData['start_wt'];
                $this->end_wt = $accountData['end_wt'];
                $this->start_day = $accountData['start_day'];
                $this->end_day = $accountData['end_day'];
                $this->specialty = $accountData['specialty'];
                $this->bio = $accountData['bio'];
                $this->doctor_id = $accountData['doctor_id'];

                return true;
            }
        }
    }

    function update_doctor_info()
    {
        $connect = $this->db->connect();
        $connect->beginTransaction();

        $sql = "UPDATE account 
        SET firstname = :firstname, middlename = :middlename, lastname = :lastname, gender = :gender, birthdate = :birthdate, contact = :contact, email = :email, address = :address
        WHERE account_id = :account_id";

        $query = $connect->prepare($sql);
        $query->bindParam(':email', $this->email);
        $query->bindParam(':firstname', $this->firstname);
        $query->bindParam(':middlename', $this->middlename);
        $query->bindParam(':lastname', $this->lastname);
        $query->bindParam(':contact', $this->contact);
        $query->bindParam(':birthdate', $this->birthdate);
        $query->bindParam(':gender', $this->gender);
        $query->bindParam(':address', $this->address);
        $query->bindParam(':account_id', $this->account_id);

        if ($query->execute()) {
            $sec_sql = "UPDATE doctor_info 
            SET specialty = :specialty, bio = :bio
            WHERE account_id = :account_id";

            $sec_query = $connect->prepare($sec_sql);
            $sec_query->bindParam(':account_id', $this->account_id);
            $sec_query->bindParam(':specialty', $this->specialty);
            $sec_query->bindParam(':bio', $this->bio);

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

    function update_user_info()
    {
        try {
            $connect = $this->db->connect();
            $connect->beginTransaction();

            $sql = "UPDATE account
                    SET firstname = :firstname, 
                        middlename = :middlename, 
                        lastname = :lastname, 
                        gender = :gender, 
                        birthdate = :birthdate, 
                        contact = :contact, 
                        email = :email, 
                        address = :address
                    WHERE account_id = :account_id";

            $stmt = $connect->prepare($sql);
            $stmt->bindParam(':firstname', $this->firstname);
            $stmt->bindParam(':middlename', $this->middlename);
            $stmt->bindParam(':lastname', $this->lastname);
            $stmt->bindParam(':gender', $this->gender);
            $stmt->bindParam(':birthdate', $this->birthdate);
            $stmt->bindParam(':contact', $this->contact);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':address', $this->address);
            $stmt->bindParam(':account_id', $this->account_id);

            if ($stmt->execute()) {
                $connect->commit();
                return true;
            } else {
                $connect->rollBack();
                return false;
            }
        } catch (PDOException $e) {
            $connect->rollBack();
            return false;
        }
    }

    function save_image()
    {
        $sql = "UPDATE account SET account_image = :account_image WHERE account_id = :account_id";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':account_image', $this->account_image);
        $query->bindParam(':account_id', $this->account_id);

        if ($query->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function update_working_time()
    {
        $sql = "UPDATE doctor_info SET start_wt = :start_wt, end_wt = :end_wt, start_day = :start_day, end_day = :end_day WHERE account_id = :account_id";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':start_wt', $this->start_wt);
        $query->bindParam(':end_wt', $this->end_wt);
        $query->bindParam(':start_day', $this->start_day);
        $query->bindParam(':end_day', $this->end_day);
        $query->bindParam(':account_id', $this->account_id);

        if ($query->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function get_doctor_info($doctor_id)
    {
        $sql = "SELECT * FROM doctor_info WHERE doctor_id = :doctor_id";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':doctor_id', $doctor_id);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetch();
        }
        return $data;
    }

    // doctor functions end

    // moderator functions start

    function add_mod()
    {
        $sql = "INSERT INTO account (email, password, firstname, middlename, lastname, user_role, contact, campus_id) VALUES (:email, :password, :firstname, :middlename, :lastname, :user_role, :contact, :campus_id);";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':email', $this->email);
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
        $query->bindParam(':password', $hashedPassword);
        $query->bindParam(':firstname', $this->firstname);
        $query->bindParam(':middlename', $this->middlename);
        $query->bindParam(':lastname', $this->lastname);
        $query->bindParam(':user_role', $this->user_role);
        $query->bindParam(':contact', $this->contact);
        $query->bindParam(':campus_id', $this->campus_id);

        if ($query->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function show_mod()
    {
        $sql = "SELECT a.*, c.campus_id, c.campus_name FROM account a INNER JOIN campus c ON a.campus_id = c.campus_id WHERE user_role = 2 AND a.is_deleted != 1 ORDER BY account_id ASC;";
        $query = $this->db->connect()->prepare($sql);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll();
        }
        return $data;
    }

    // moderator functions end


    // user functions start

    function add_user()
    {
        $connect = $this->db->connect();
        $connect->beginTransaction();

        $sql = "INSERT INTO account (email, password, firstname, middlename, lastname, user_role, contact, gender, birthdate, campus_id) VALUES (:email, :password, :firstname, :middlename, :lastname, :user_role, :contact, :gender, :birthdate, :campus_id);";

        $query = $connect->prepare($sql);
        $query->bindParam(':email', $this->email);
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
        $query->bindParam(':password', $hashedPassword);
        $query->bindParam(':firstname', $this->firstname);
        $query->bindParam(':middlename', $this->middlename);
        $query->bindParam(':lastname', $this->lastname);
        $query->bindParam(':user_role', $this->user_role);
        $query->bindParam(':contact', $this->contact);
        $query->bindParam(':gender', $this->gender);
        $query->bindParam(':birthdate', $this->birthdate);
        $query->bindParam(':campus_id', $this->campus_id);

        if ($query->execute()) {
            $last_product_id = $connect->lastInsertId();

            $sec_sql = "INSERT INTO patient_info (account_id) VALUES (:account_id)";

            $sec_query = $connect->prepare($sec_sql);
            $sec_query->bindParam(':account_id', $last_product_id);

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

    function add_user_old()
    {
        $sql = "INSERT INTO account (email, password, firstname, middlename, lastname, user_role, contact, gender, birthdate, campus_id) VALUES (:email, :password, :firstname, :middlename, :lastname, :user_role, :contact, :gender, :birthdate, :campus_id);";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':email', $this->email);
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
        $query->bindParam(':password', $hashedPassword);
        $query->bindParam(':firstname', $this->firstname);
        $query->bindParam(':middlename', $this->middlename);
        $query->bindParam(':lastname', $this->lastname);
        $query->bindParam(':user_role', $this->user_role);
        $query->bindParam(':contact', $this->contact);
        $query->bindParam(':gender', $this->gender);
        $query->bindParam(':birthdate', $this->birthdate);
        $query->bindParam(':campus_id', $this->campus_id);

        if ($query->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function show_user()
    {
        $sql = "SELECT a.*, c.campus_id, c.campus_name FROM account a INNER JOIN campus c ON a.campus_id = c.campus_id WHERE user_role = 3 AND a.is_deleted != 1 ORDER BY account_id ASC;";
        $query = $this->db->connect()->prepare($sql);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll();
        }
        return $data;
    }

    function sign_in_user()
    {
        $sql = "SELECT a.*, p.*, c.campus_name FROM account a INNER JOIN patient_info p ON a.account_id = p.account_id INNER JOIN campus c ON c.campus_id = a.campus_id WHERE email = :email LIMIT 1;";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':email', $this->email);

        if ($query->execute()) {
            $accountData = $query->fetch(PDO::FETCH_ASSOC);

            if ($accountData && password_verify($this->password, $accountData['password'])) {
                $this->account_id = $accountData['account_id'];
                $this->user_role = $accountData['user_role'];
                $this->firstname = $accountData['firstname'];
                $this->middlename = $accountData['middlename'];
                $this->lastname = $accountData['lastname'];
                $this->gender = $accountData['gender'];
                $this->birthdate = $accountData['birthdate'];
                $this->email = $accountData['email'];
                $this->contact = $accountData['contact'];
                $this->address = $accountData['address'];
                $this->verification_status = $accountData['verification_status'];
                $this->account_image = $accountData['account_image'];
                $this->patient_id = $accountData['patient_id'];
                $this->campus_name = $accountData['campus_name'];
                //add more data if needed for users

                return true;
            }
        }
    }

    function fetchUserData($account_id)
    {
        $sql = "SELECT account_id, email, firstname, middlename, lastname, gender, contact, address, account_image, verification_status, birthdate, is_created,
                CONCAT(firstname, IF(middlename IS NOT NULL AND middlename != '', CONCAT(' ', middlename), ''), ' ', lastname) AS fullname 
                FROM account WHERE account_id = :account_id";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':account_id', $account_id, PDO::PARAM_INT);

        if ($query->execute()) {
            return $query->fetch(PDO::FETCH_ASSOC);
        }

        return null;
    }

    function fetchDoctorData($account_id)
    {
        $sql = "SELECT a.account_id, a.email, a.firstname, a.middlename, a.lastname, a.gender, a.contact, a.address, 
                       a.account_image, a.verification_status, a.birthdate, a.is_created, 
                       CONCAT(a.firstname, IF(a.middlename IS NOT NULL AND a.middlename != '', CONCAT(' ', a.middlename), ''), ' ', a.lastname) AS fullname,
                       d.doctor_id, d.specialty, d.bio, d.start_wt, d.end_wt, d.start_day, d.end_day, d.appointment_limits
                FROM account a
                INNER JOIN doctor_info d ON a.account_id = d.account_id
                WHERE a.account_id = :account_id";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':account_id', $account_id, PDO::PARAM_INT);

        if ($query->execute()) {
            return $query->fetch(PDO::FETCH_ASSOC);
        }

        return null;
    }

    // user functions end

    // ---ANALYTICS FUNCTIONS START---
    function fetch_user_statistics()
    {
        $db = $this->db->connect();

        // Fetch user roles and their counts
        $sqlRoles = "SELECT user_role, COUNT(*) as count FROM account GROUP BY user_role";
        $queryRoles = $db->prepare($sqlRoles);
        $queryRoles->execute();
        $roleData = $queryRoles->fetchAll(PDO::FETCH_ASSOC);

        // Fetch total number of users
        $sqlTotal = "SELECT COUNT(*) as total FROM account";
        $queryTotal = $db->prepare($sqlTotal);
        $queryTotal->execute();
        $totalUsers = $queryTotal->fetch(PDO::FETCH_ASSOC)['total'];

        // Fetch active users (based on last login in the last 30 days)
        $sqlActive = "SELECT COUNT(*) as active FROM account WHERE last_login >= NOW() - INTERVAL 30 DAY";
        $queryActive = $db->prepare($sqlActive);
        $queryActive->execute();
        $activeUsers = $queryActive->fetch(PDO::FETCH_ASSOC)['active'];

        // Fetch new signups (assuming 'created_at' column and counting users registered in the last 30 days)
        $sqlNew = "SELECT COUNT(*) as new_signups FROM account WHERE is_created >= NOW() - INTERVAL 30 DAY";
        $queryNew = $db->prepare($sqlNew);
        $queryNew->execute();
        $newSignups = $queryNew->fetch(PDO::FETCH_ASSOC)['new_signups'];

        return [
            'roles' => $roleData,
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'newSignups' => $newSignups
        ];
    }

    
}
