<?php

function validate_email($email)
{
    if (isset($email)) {
        $email = trim($email);

        if (empty($email)) {
            return "Email is required";
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Email you've entered is in an invalid format.";
        } else {
            if (preg_match('/@wmsu\.edu\.ph$/i', $email) || preg_match('/@gmail\.com$/i', $email)) {
                return 'success';
            } else {
                return "Only emails ending in @wmsu.edu.ph or @gmail.com are allowed.";
            }
        }
    } else {
        return 'Email is required';
    }
}

function validate_wmsu_email($email)
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $pattern = '/@wmsu\.edu\.ph$/i';

        // Check if the email matches the pattern
        if (preg_match($pattern, $email)) {
            return true;
        } else {
            return false;
        }
    } else {
        return false; // Invalid email format
    }
}


function validate_field($field)
{
    $field = htmlentities($field);
    if (strlen(trim($field)) < 1) {
        return false;
    } else {
        return true;
    }
}

function validate_password($password)
{
    $password = htmlentities($password);

    if (strlen(trim($password)) < 1) {
        return "Password is required.";
    } elseif (strlen($password) < 8) {
        return "Password must be at least 8 characters long.";
    } else {
        return "success"; // Indicates successful validation
    }
}

function validate_cpw($password, $cpassword)
{
    $pw = htmlentities($password);
    $cpw = htmlentities($cpassword);
    if (strcmp($pw, $cpw) == 0) {
        return true;
    } else {
        return false;
    }
}

function generate_code()
{
    $code = random_int(100000, 999999);
    return $code;
}

function validate_time($start, $end)
{
    $startTime = DateTime::createFromFormat('H:i', $start);
    $endTime = DateTime::createFromFormat('H:i', $end);

    if ($startTime === false || $endTime === false) {
        return false;
    }

    return $startTime < $endTime;
}

function check_time_between($start, $end, $time) {}

function get_age($birthdate)
{
    $birthDate = new DateTime($birthdate);
    $today = new DateTime('today');
    $age = $birthDate->diff($today)->y;
    return $age;
}

