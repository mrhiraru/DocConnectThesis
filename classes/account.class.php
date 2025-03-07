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
    public $school_id;
    public $height;
    public $weight;
    public $role;



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
        $sql = "INSERT INTO account (email, password, user_role, verification_status) VALUES (:email, :password, :user_role, :verification_status);";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':email', $this->email);
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
        $query->bindParam(':password', $hashedPassword);
        $query->bindParam(':user_role', $this->user_role);
        $verification_status = 'Verified'; //set na agad "verified"
        $query->bindParam(':verification_status', $verification_status);

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

    function get_doctor_info_2($account_id)
    {
        $sql = "SELECT a.*, d.*, CONCAT(a.firstname, IF(a.middlename IS NOT NULL AND a.middlename != '', CONCAT(' ', a.middlename), ''), 
        ' ', a.lastname) AS doctor_name FROM account a INNER JOIN doctor_info d ON a.account_id = d.account_id WHERE a.account_id = :account_id AND a.user_role = 1 AND d.is_deleted = 0";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':account_id', $account_id);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetch();
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
                        address = :address,
                        role = :role,
                        campus_id = :campus_id
                    WHERE account_id = :account_id";

        $query = $connect->prepare($sql);
        $query->bindParam(':firstname', $this->firstname);
        $query->bindParam(':middlename', $this->middlename);
        $query->bindParam(':lastname', $this->lastname);
        $query->bindParam(':gender', $this->gender);
        $query->bindParam(':birthdate', $this->birthdate);
        $query->bindParam(':contact', $this->contact);
        $query->bindParam(':email', $this->email);
        $query->bindParam(':address', $this->address);
        $query->bindParam(':role', $this->role);
        $query->bindParam(':gender', $this->gender);
        $query->bindParam(':campus_id', $this->campus_id);
        $query->bindParam(':account_id', $this->account_id);

        if ($query->execute()) {

            $sec_sql = "UPDATE patient_info 
            SET height = :height, weight = :weight
            WHERE account_id = :account_id";

            $sec_query = $connect->prepare($sec_sql);
            $sec_query->bindParam(':account_id', $this->account_id);
            $sec_query->bindParam(':height', $this->height);
            $sec_query->bindParam(':weight', $this->weight);

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

        $sql = "INSERT INTO account (email, password, firstname, middlename, lastname, user_role, contact, gender, birthdate, campus_id, address, role) VALUES (:email, :password, :firstname, :middlename, :lastname, :user_role, :contact, :gender, :birthdate, :campus_id, :address, :role);";

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
        $query->bindParam(':address', $this->address);
        $query->bindParam(':role', $this->role);

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
                $this->school_id = $accountData['school_id'];
                $this->height = $accountData['height'];
                $this->weight = $accountData['weight'];
                $this->role = $accountData['role'];
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

    // ---DASHBOARD FUNCTION START---
    function fetch_user_summary()
    {
        // Fetch total users count
        $sqlTotal = "SELECT COUNT(*) as total FROM account";
        $queryTotal = $this->db->connect()->prepare($sqlTotal);
        $queryTotal->execute();
        $totalUsers = $queryTotal->fetch(PDO::FETCH_ASSOC)['total'];

        // Fetch total active users
        $sqlActive = "SELECT COUNT(*) as active FROM account WHERE last_login >= NOW() - INTERVAL 30 DAY";
        $queryActive = $this->db->connect()->prepare($sqlActive);
        $queryActive->execute();
        $activeUsers = $queryActive->fetch(PDO::FETCH_ASSOC)['active'];

        // Fetch total patients
        $sqlPatients = "SELECT COUNT(*) as total FROM account WHERE user_role IN (3, 4, 5, 6, 7)";
        $queryPatients = $this->db->connect()->prepare($sqlPatients);
        $queryPatients->execute();
        $totalPatients = $queryPatients->fetch(PDO::FETCH_ASSOC)['total'];

        // Fetch total doctors
        $sqlDoctors = "SELECT COUNT(*) as total FROM account WHERE user_role = '1'";
        $queryDoctors = $this->db->connect()->prepare($sqlDoctors);
        $queryDoctors->execute();
        $totalDoctors = $queryDoctors->fetch(PDO::FETCH_ASSOC)['total'];

        // Fetch patient names, doctor names, and appointment dates
        $sqlAppointments = "SELECT a2.firstname AS patient_firstname, 
                                   a2.lastname AS patient_lastname, 
                                   a1.firstname AS doctor_firstname, 
                                   a1.lastname AS doctor_lastname, 
                                   ap.appointment_date,
                                   ap.appointment_time
                            FROM appointment ap
                            LEFT JOIN doctor_info d ON ap.doctor_id = d.doctor_id
                            LEFT JOIN account a1 ON d.account_id = a1.account_id
                            LEFT JOIN patient_info p ON ap.patient_id = p.patient_id
                            LEFT JOIN account a2 ON p.account_id = a2.account_id
                            WHERE DATE(ap.appointment_date) = CURDATE()
                            ORDER BY ap.appointment_date DESC, ap.appointment_time ASC";

        $queryAppointments = $this->db->connect()->prepare($sqlAppointments);
        $queryAppointments->execute();
        $appointments = $queryAppointments->fetchAll(PDO::FETCH_ASSOC);

        return [
            'totalUsers' => $totalUsers,
            'totalActiveUsers' => $activeUsers,
            'totalPatients' => $totalPatients,
            'totalDoctors' => $totalDoctors,
            'appointments' => $appointments
        ];
    }

    function fetch_users_per_campus_per_year()
    {
        $sql = "SELECT c.campus_name, 
                       YEAR(a.is_created) AS year_created, 
                       COUNT(a.account_id) AS total_users
                FROM account a
                LEFT JOIN campus c ON a.campus_id = c.campus_id
                WHERE c.campus_name IS NOT NULL AND c.campus_name != 'Unknown'
                GROUP BY c.campus_name, year_created
                ORDER BY year_created ASC, total_users DESC";

        try {
            $query = $this->db->connect()->prepare($sql);
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "Database Error: " . $e->getMessage();
            return [];
        }
    }
    // ---DASHBOARD FUNCTION END---

    // ---APPOINMENTS FUNCTIONS START---
    function get_appointments_with_doctors_and_patients()
    {
        $sql = "SELECT d.doctor_id,
                       a1.account_id AS doctor_account_id,
                       CONCAT(a1.firstname, ' ', a1.lastname) AS doctor_name,
                       d.specialty,
                       a1.contact AS doctor_contact,
                       a1.email AS doctor_email,
                       a1.address AS doctor_address,
                       a1.campus_id AS doctor_campus_id,
                       
                       p.patient_id,
                       a2.account_id AS patient_account_id,
                       CONCAT(a2.firstname, ' ', a2.lastname) AS patient_name,
                       a2.contact AS patient_contact,
                       a2.email AS patient_email,
                       a2.address AS patient_address,
                       a2.campus_id AS patient_campus_id,
                       c2.campus_name AS patient_campus_name,
                       
                       ap.appointment_id,
                       ap.appointment_date,
                       ap.appointment_time,
                       ap.appointment_status,
                       ap.reason,
                       ap.result
                FROM appointment ap
                LEFT JOIN doctor_info d ON ap.doctor_id = d.doctor_id
                LEFT JOIN account a1 ON d.account_id = a1.account_id
                LEFT JOIN patient_info p ON ap.patient_id = p.patient_id
                LEFT JOIN account a2 ON p.account_id = a2.account_id
                LEFT JOIN campus c2 ON a2.campus_id = c2.campus_id
                ORDER BY ap.appointment_date DESC, ap.appointment_time ASC;";

        try {
            $query = $this->db->connect()->prepare($sql);
            if ($query->execute()) {
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                return $result;
            } else {
                echo "SQL Execution Error.";
                return [];
            }
        } catch (PDOException $e) {
            echo "Database Error: " . $e->getMessage();
            return [];
        }
    }


    // ---ANALYTICS FUNCTIONS START---
    // User Statistics Chart
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

    function fetch_appointment_statistics()
    {
        $db = $this->db->connect();

        // Fetch total appointments count
        $sqlTotal = "SELECT COUNT(*) as total FROM appointment";
        $queryTotal = $db->prepare($sqlTotal);
        $queryTotal->execute();
        $totalAppointments = $queryTotal->fetch(PDO::FETCH_ASSOC)['total'];

        // Fetch completed appointments
        $sqlCompleted = "SELECT COUNT(*) as completed FROM appointment WHERE appointment_status = 'Completed'";
        $queryCompleted = $db->prepare($sqlCompleted);
        $queryCompleted->execute();
        $completedAppointments = $queryCompleted->fetch(PDO::FETCH_ASSOC)['completed'];

        // Fetch cancelled appointments (also used for noShowRate)
        $sqlCancelled = "SELECT COUNT(*) as cancelled FROM appointment WHERE appointment_status = 'Cancelled'";
        $queryCancelled = $db->prepare($sqlCancelled);
        $queryCancelled->execute();
        $cancelledAppointments = $queryCancelled->fetch(PDO::FETCH_ASSOC)['cancelled'];

        // Fetch pending appointments
        $sqlPending = "SELECT COUNT(*) as pending FROM appointment WHERE appointment_status = 'Pending'";
        $queryPending = $db->prepare($sqlPending);
        $queryPending->execute();
        $pendingAppointments = $queryPending->fetch(PDO::FETCH_ASSOC)['pending'];

        // Fetch average duration of completed appointments
        $sqlAvgDuration = "SELECT AVG(TIMESTAMPDIFF(MINUTE, appointment_time, estimated_end)) as avgDuration FROM appointment WHERE appointment_status = 'Completed'";
        $queryAvgDuration = $db->prepare($sqlAvgDuration);
        $queryAvgDuration->execute();
        $avgDuration = $queryAvgDuration->fetch(PDO::FETCH_ASSOC)['avgDuration'];

        // Default to 0 if no data is found
        $avgDuration = $avgDuration ? round($avgDuration, 2) : 0;

        return [
            "totalAppointments" => $totalAppointments,
            "completedAppointments" => $completedAppointments,
            "cancelledAppointments" => $cancelledAppointments,
            "pendingAppointments" => $pendingAppointments,
            "avgDuration" => $avgDuration
        ];
    }


    function fetch_doctor_statistics()
    {
        $db = $this->db->connect();

        // Fetch total active doctors
        $sqlActiveDoctors = "SELECT COUNT(*) as activeDoctors FROM account WHERE user_role = '1' AND last_login >= NOW() - INTERVAL 30 DAY";
        $queryActiveDoctors = $db->prepare($sqlActiveDoctors);
        $queryActiveDoctors->execute();
        $activeDoctors = $queryActiveDoctors->fetch(PDO::FETCH_ASSOC)['activeDoctors'];

        // Fetch monthly active doctor trends for line graph
        $sqlDoctorTrends = "SELECT DATE_FORMAT(last_login, '%Y-%m') as month, COUNT(*) as count FROM account WHERE user_role = '1' AND last_login IS NOT NULL GROUP BY month ORDER BY month";
        $queryDoctorTrends = $db->prepare($sqlDoctorTrends);
        $queryDoctorTrends->execute();
        $doctorTrends = $queryDoctorTrends->fetchAll(PDO::FETCH_ASSOC);

        return [
            "activeDoctors" => $activeDoctors,
            "doctorTrends" => $doctorTrends
        ];
    }

    function fetch_health_concerns_and_trends()
    {
        $db = $this->db->connect();

        $sqlMedicalConditions = "SELECT medcon_name FROM medical_condition";
        $queryMedicalConditions = $db->prepare($sqlMedicalConditions);
        $queryMedicalConditions->execute();
        $medicalConditions = $queryMedicalConditions->fetchAll(PDO::FETCH_COLUMN);

        $healthConcernLabels = [];
        $healthConcernData = [];

        foreach ($medicalConditions as $condition) {
            $sqlConditionCount = "SELECT COUNT(*) as count 
                                  FROM appointment 
                                  WHERE diagnosis LIKE :condition";
            $queryConditionCount = $db->prepare($sqlConditionCount);
            $queryConditionCount->execute(['condition' => "%$condition%"]);
            $count = $queryConditionCount->fetch(PDO::FETCH_ASSOC)['count'];

            if ($count > 0) {
                $healthConcernLabels[] = $condition;
                $healthConcernData[] = $count;
            }
        }

        $sqlTopConcern = "SELECT diagnosis, COUNT(*) as count 
                          FROM appointment 
                          WHERE diagnosis IS NOT NULL AND diagnosis != '' 
                          GROUP BY diagnosis 
                          ORDER BY count DESC 
                          LIMIT 1";
        $queryTopConcern = $db->prepare($sqlTopConcern);
        $queryTopConcern->execute();
        $topConcern = $queryTopConcern->fetch(PDO::FETCH_ASSOC);

        $sqlSeasonalTrends = "SELECT DATE_FORMAT(appointment_date, '%Y-%m') as month, COUNT(*) as count 
                              FROM appointment 
                              GROUP BY month 
                              ORDER BY month";
        $querySeasonalTrends = $db->prepare($sqlSeasonalTrends);
        $querySeasonalTrends->execute();
        $seasonalTrends = $querySeasonalTrends->fetchAll(PDO::FETCH_ASSOC);

        return [
            'topConcern' => $topConcern['diagnosis'] ?? 'No data',
            'seasonalTrends' => $seasonalTrends,
            'healthConcernLabels' => $healthConcernLabels,
            'healthConcernData' => $healthConcernData
        ];
    }
}
