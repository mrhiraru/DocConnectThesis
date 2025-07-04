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
    public $suffix;
    public $religion;
    public $civil_status;

    public $e_signature;


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
                $this->suffix = $accountData['suffix'];
                $this->email = $accountData['email'];
                $this->verification_status = $accountData['verification_status'];
                $this->account_image = $accountData['account_image'];
                $this->address = $accountData['address'];
                $this->campus_id = $accountData['campus_id'];
                $this->gender = $accountData['gender'];
                $this->birthdate = $accountData['birthdate'];
                $this->contact = $accountData['contact'];
                $this->height = $accountData['height'];
                $this->weight = $accountData['weight'];
                $this->role = $accountData['role'];
                $this->religion = $accountData['religion'];
                $this->civil_status = $accountData['civil_status'];
                return true;
            }
        }
        return false;
    }

    function createRememberMeToken($account_id)
    {
        $token = bin2hex(random_bytes(32)); // Generate a secure token
        $expires_at = date('Y-m-d H:i:s', strtotime('+30 days')); // Token expires in 30 days

        $sql = "INSERT INTO remember_me_tokens (account_id, token, expires_at) VALUES (:account_id, :token, :expires_at);";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':account_id', $account_id);
        $query->bindParam(':token', $token);
        $query->bindParam(':expires_at', $expires_at);

        if ($query->execute()) {
            return $token;
        } else {
            return false;
        }
    }

    function validateRememberMeToken($token)
    {
        $sql = "SELECT * FROM remember_me_tokens WHERE token = :token AND expires_at > NOW();";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':token', $token);

        if ($query->execute()) {
            $tokenData = $query->fetch(PDO::FETCH_ASSOC);
            if ($tokenData) {
                return $tokenData['account_id'];
            }
        }
        return false;
    }

    function deleteRememberMeToken($token)
    {
        $sql = "DELETE FROM remember_me_tokens WHERE token = :token;";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':token', $token);

        return $query->execute();
    }

    function change_password($old_password, $new_password, $confirm_password)
    {
        $sql = "SELECT password FROM account WHERE account_id = :account_id LIMIT 1;";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':account_id', $this->account_id);

        if ($query->execute()) {
            $accountData = $query->fetch(PDO::FETCH_ASSOC);

            if ($accountData && password_verify($old_password, $accountData['password'])) {
                if ($new_password === $confirm_password) {
                    $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);

                    $update_sql = "UPDATE account SET password = :password WHERE account_id = :account_id;";
                    $update_query = $this->db->connect()->prepare($update_sql);
                    $update_query->bindParam(':password', $hashedPassword);
                    $update_query->bindParam(':account_id', $this->account_id);

                    if ($update_query->execute()) {
                        return "success";
                    } else {
                        return "error_db";
                    }
                } else {
                    return "error_mismatch";
                }
            } else {
                return "error_old_password";
            }
        } else {
            return "error_db";
        }
    }

    function sign_in_mod()
    {
        $sql = "SELECT a.*, c.* FROM account a INNER JOIN campus c ON a.campus_id = c.campus_id WHERE a.email = :email LIMIT 1;";
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
                $this->suffix = $accountData['suffix'];
                $this->email = $accountData['email'];
                $this->verification_status = $accountData['verification_status'];
                $this->account_image = $accountData['account_image'];
                $this->address = $accountData['address'];
                $this->campus_id = $accountData['campus_id'];
                $this->campus_name = $accountData['campus_name'];
                $this->gender = $accountData['gender'];
                $this->birthdate = $accountData['birthdate'];
                $this->contact = $accountData['contact'];
                $this->height = $accountData['height'];
                $this->weight = $accountData['weight'];
                $this->role = $accountData['role'];
                $this->religion = $accountData['religion'];
                $this->civil_status = $accountData['civil_status'];
                return true;
            }
        }
        return false;
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

    function get_full_name()
    {
        $name = $this->firstname;
        if (!empty($this->middlename)) {
            $name .= ' ' . $this->middlename;
        }
        $name .= ' ' . $this->lastname;
        if (!empty($this->suffix)) {
            $name .= ' ' . $this->suffix;
        }
        return $name;
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

    function get_available_specialties()
    {
        $sql = "SELECT DISTINCT specialty FROM doctor_info WHERE specialty IS NOT NULL AND specialty != '' AND is_deleted = 0";
        $query = $this->db->connect()->prepare($sql);
        $data = [];
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
                $this->e_signature = $accountData['e_signature'];

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
                    suffix = :suffix,
                    gender = :gender, 
                    birthdate = :birthdate, 
                    contact = :contact, 
                    email = :email, 
                    address = :address,
                    role = :role,
                    religion = :religion,
                    civil_status = :civil_status,
                    campus_id = :campus_id
                WHERE account_id = :account_id";

        $query = $connect->prepare($sql);
        $query->bindParam(':firstname', $this->firstname);
        $query->bindParam(':middlename', $this->middlename);
        $query->bindParam(':lastname', $this->lastname);
        $query->bindParam(':suffix', $this->suffix);
        $query->bindParam(':gender', $this->gender);
        $query->bindParam(':birthdate', $this->birthdate);
        $query->bindParam(':contact', $this->contact);
        $query->bindParam(':email', $this->email);
        $query->bindParam(':address', $this->address);
        $query->bindParam(':role', $this->role);
        $query->bindParam(':campus_id', $this->campus_id);
        $query->bindParam(':religion', $this->religion);
        $query->bindParam(':civil_status', $this->civil_status);
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

    function save_e_signature()
    {
        $sql = "UPDATE doctor_info SET e_signature = :e_signature WHERE account_id = :account_id";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':e_signature', $this->e_signature);
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

    function get_mydoctors($patient_id)
    {
        $sql = "SELECT a.*, di.*, COUNT(ap.appointment_id) AS appointment_count, 
            CONCAT(a.firstname, IF(a.middlename IS NOT NULL AND a.middlename != '', CONCAT(' ', a.middlename), ''), ' ', a.lastname) AS doctor_name,
            MAX(ap.appointment_date) AS latest_appointment_date, 
            MAX(ap.appointment_time) AS latest_appointment_time
            FROM account a
            INNER JOIN doctor_info di ON di.account_id = a.account_id
            INNER JOIN appointment ap ON di.doctor_id = ap.doctor_id
            WHERE ap.patient_id = :patient_id
            GROUP BY di.doctor_id
            ORDER BY latest_appointment_date ASC, latest_appointment_time ASC";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':patient_id', $patient_id);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll();
        }
        return $data;
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
        $sql = "INSERT INTO account (email, password, user_role, campus_id, verification_status) VALUES (:email, :password, :user_role, :campus_id, :verification_status);";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':email', $this->email);
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
        $query->bindParam(':password', $hashedPassword);
        $query->bindParam(':user_role', $this->user_role);
        $query->bindParam(':campus_id', $this->campus_id);
        $query->bindParam(':verification_status', $this->verification_status);

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

    function show_user_campus($campus_id)
    {
        $sql = "SELECT a.*, c.campus_id, c.campus_name FROM account a INNER JOIN campus c ON a.campus_id = c.campus_id WHERE user_role = 3 AND a.campus_id = :campus_id AND a.is_deleted != 1 ORDER BY account_id ASC;";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':campus_id', $campus_id);
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
                $this->suffix = $accountData['suffix'];
                $this->religion = $accountData['religion'];
                $this->civil_status = $accountData['civil_status'];

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
                       ap.*
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

        $sqlRoles = "SELECT user_role, COUNT(*) as count FROM account WHERE user_role NOT IN (0, 2, 7) GROUP BY user_role";
        $queryRoles = $db->prepare($sqlRoles);
        $queryRoles->execute();
        $roleData = $queryRoles->fetchAll(PDO::FETCH_ASSOC);

        $sqlRoleSpecific = "SELECT role, COUNT(*) as count FROM account WHERE user_role = 3 AND role IN ('Student', 'Alumni', 'Employee', 'Faculty') GROUP BY role";
        $queryRoleSpecific = $db->prepare($sqlRoleSpecific);
        $queryRoleSpecific->execute();
        $roleSpecificData = $queryRoleSpecific->fetchAll(PDO::FETCH_ASSOC);

        $sqlTotal = "SELECT COUNT(*) as total FROM account WHERE user_role NOT IN (0, 2, 7)";
        $queryTotal = $db->prepare($sqlTotal);
        $queryTotal->execute();
        $totalUsers = $queryTotal->fetch(PDO::FETCH_ASSOC)['total'];

        $sqlActive = "SELECT COUNT(*) as active FROM account WHERE last_login >= NOW() - INTERVAL 30 DAY AND user_role NOT IN (0, 2, 7)";
        $queryActive = $db->prepare($sqlActive);
        $queryActive->execute();
        $activeUsers = $queryActive->fetch(PDO::FETCH_ASSOC)['active'];

        $sqlNew = "SELECT COUNT(*) as new_signups FROM account WHERE is_created >= NOW() - INTERVAL 30 DAY AND user_role NOT IN (0, 2, 7)";
        $queryNew = $db->prepare($sqlNew);
        $queryNew->execute();
        $newSignups = $queryNew->fetch(PDO::FETCH_ASSOC)['new_signups'];

        return [
            'roles' => $roleData,
            'roleSpecific' => $roleSpecificData,
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

        $conditionCounts = array_fill_keys($medicalConditions, 0);

        $sqlDiagnoses = "SELECT diagnosis, appointment_date FROM appointment WHERE diagnosis IS NOT NULL AND diagnosis != ''";
        $queryDiagnoses = $db->prepare($sqlDiagnoses);
        $queryDiagnoses->execute();
        $diagnoses = $queryDiagnoses->fetchAll(PDO::FETCH_ASSOC);

        $currentMonthConditionCounts = array_fill_keys($medicalConditions, 0);
        $currentMonth = date('Y-m');
        // count ng diagnosis each
        foreach ($diagnoses as $row) {
            $diagnosis = $row['diagnosis'];
            $appointmentDate = $row['appointment_date'];

            //pang separate
            $conditions = array_map('trim', explode(',', $diagnosis));
            // total lng 
            foreach ($conditions as $condition) {
                if (isset($conditionCounts[$condition])) {
                    $conditionCounts[$condition]++;
                }
            }

            // total sa current month
            if (date('Y-m', strtotime($appointmentDate)) === $currentMonth) {
                foreach ($conditions as $condition) {
                    if (isset($currentMonthConditionCounts[$condition])) {
                        $currentMonthConditionCounts[$condition]++;
                    }
                }
            }
        }

        $healthConcernLabels = [];
        $healthConcernData = [];
        foreach ($conditionCounts as $condition => $count) {
            if ($count > 0) {
                $healthConcernLabels[] = $condition;
                $healthConcernData[] = $count;
            }
        }

        arsort($conditionCounts);
        $topConcern = key($conditionCounts) ?? 'No data';

        arsort($currentMonthConditionCounts);
        $topConcernMonth = key($currentMonthConditionCounts) ?? 'No data';

        $sqlSeasonalTrends = "SELECT DATE_FORMAT(appointment_date, '%Y-%m') as month, COUNT(*) as count 
                              FROM appointment 
                              GROUP BY month 
                              ORDER BY month";
        $querySeasonalTrends = $db->prepare($sqlSeasonalTrends);
        $querySeasonalTrends->execute();
        $seasonalTrends = $querySeasonalTrends->fetchAll(PDO::FETCH_ASSOC);

        return [
            'topConcern' => $topConcern,
            'topConcernMonth' => $topConcernMonth,
            'seasonalTrends' => $seasonalTrends,
            'healthConcernLabels' => $healthConcernLabels,
            'healthConcernData' => $healthConcernData
        ];
    }

    function fetch_diagnosis_trends($month = null, $year = null)
    {
        $db = $this->db->connect();

        $sql = "SELECT DATE_FORMAT(appointment_date, '%Y-%m') as month, diagnosis, COUNT(*) as count 
                FROM appointment 
                WHERE diagnosis IS NOT NULL AND diagnosis != ''";

        if ($month && $year) {
            $sql .= " AND DATE_FORMAT(appointment_date, '%Y-%m') = :month_year";
            $params = ['month_year' => "$year-$month"];
        } elseif ($year) {
            $sql .= " AND YEAR(appointment_date) = :year";
            $params = ['year' => $year];
        } else {
            $params = [];
        }

        $sql .= " GROUP BY month, diagnosis ORDER BY month";

        $query = $db->prepare($sql);
        $query->execute($params);
        $diagnosisTrends = $query->fetchAll(PDO::FETCH_ASSOC);

        $sqlConditions = "SELECT medcon_name FROM medical_condition";
        $queryConditions = $db->prepare($sqlConditions);
        $queryConditions->execute();
        $medicalConditions = $queryConditions->fetchAll(PDO::FETCH_COLUMN);

        $conditionCounts = array_fill_keys($medicalConditions, 0);

        foreach ($diagnosisTrends as $row) {
            $diagnosis = $row['diagnosis'];
            $conditions = array_map('trim', explode(',', $diagnosis));
            foreach ($conditions as $condition) {
                if (isset($conditionCounts[$condition])) {
                    $conditionCounts[$condition] += $row['count'];
                }
            }
        }

        return [
            'diagnosisTrends' => $diagnosisTrends,
            'conditionCounts' => $conditionCounts
        ];
    }
}
