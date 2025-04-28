<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


//Load Composer's autoloader
require_once('../vendor/autoload.php');

//Create an instance; passing `true` enables exceptions


function send_code($email, $fullname, $code)
{
    $mail = new PHPMailer(true);
    try {
        //Server settings
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'wmsu.docconnect@gmail.com';                     //SMTP username
        $mail->Password   = 'knoy xtai qnvf lfbg';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
        $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('wmsu.docconnect@gmail.com', 'DocConnect');
        $mail->addAddress($email, $fullname);     //Add a recipient
        $mail->addReplyTo('wmsu.docconnect@gmail.com', 'DocConnect');

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Verify Your WMSU DocConnect Account';
        $mail->Body    = '<p> Hi ' . ucwords($fullname) . ',Verification Code: <strong>' . $code;
        $mail->AltBody = '';

        if ($mail->send()) {
            //echo 'Message has been sent';
        };
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

function formatAppointmentSchedule($date, $time)
{
    // Set the timezone to GMT+8
    date_default_timezone_set('Asia/Manila'); // GMT+8

    // Combine date and time into a full datetime
    $datetime = strtotime($date . ' ' . $time);

    // Format the starting time
    $start = date('D j M Y g', $datetime); // D = day short, j = day number, M = month short, Y = year, g = 12-hour format without leading zero

    // Get the hour part for the ending time
    $startHour = (int)date('G', $datetime); // G = 24-hour format without leading zero
    $endHour = $startHour; // Same hour for ":59" end

    // Determine AM/PM
    $meridiem = date('a', $datetime); // am or pm

    // Build the final string
    return "@ {$start}{$meridiem} - {$endHour}:59{$meridiem} (GMT+8)";
}

function formatAppointmentSchedule2($date, $time)
{
    // Set the timezone to GMT+8
    date_default_timezone_set('Asia/Manila'); // GMT+8

    // Combine date and time into a full datetime
    $datetime = strtotime($date . ' ' . $time);

    // Format as "April 9, 2025 at 07:00 PM"
    return date('F j, Y \a\t h:i A', $datetime);
}

function email_notification($date, $time, $doctor_email, $doctor_fullname, $patient_email, $patient_fullname, $link, $action)
{
    if ($action == 'confirm') {
        $message = 'Appointment has been confirmed on ' . formatAppointmentSchedule2($date, $time) . '. <br> Meeting Link: <a href="' . $link . '"> ' . $link . ' </a>';
    } else if ($action == 'decline') {
        $message = '';
    } else if ($action == 'reschedule') {
        $message = 'Appoinment has been rescheduled to ' . formatAppointmentSchedule2($date, $time) . '. <br> Meeting Link: <a href="' . $link . '"> ' . $link . ' </a>';
    } else if ($action == 'cancel') {
        $message = '';
    }

    $mail = new PHPMailer(true);
    try {
        //Server settings
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'wmsu.docconnect@gmail.com';                     //SMTP username
        $mail->Password   = 'knoy xtai qnvf lfbg';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
        $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('wmsu.docconnect@gmail.com', 'DocConnect');
        $mail->addAddress($doctor_email, $doctor_fullname);     //Add a recipient
        $mail->addAddress($patient_email, $patient_fullname);     //Add a recipient
        $mail->addReplyTo('wmsu.docconnect@gmail.com', 'DocConnect');

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Notification: Docconnect Consultation: ' . formatAppointmentSchedule($date, $time) . ' (Hilal Abdulajid)';
        $mail->Body    = $message;
        $mail->AltBody = '';

        if ($mail->send()) {
            //echo 'Message has been sent';
        };
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
