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

function email_notification($email, $fullname)
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
        $mail->Subject = 'WMSU DocConnect Appointment';
        $mail->Body    = '<p> Hi ' . ucwords($fullname) . ',Verification Code: <strong>';
        $mail->AltBody = '';

        if ($mail->send()) {
            //echo 'Message has been sent';
        };
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}