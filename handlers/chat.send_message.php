<?php

require_once('../classes/message.class.php');
require_once('../tools/functions.php');

$message = new Message();

if (isset($_POST['send'])) {
    $message->sender_id = $_POST['sender_id'];
    $message->receiver_id = $_POST['receiver_id'];
    $message->message = htmlentities($_POST['message']);
    $message->message_type = 'User';

    if (validate_field($message->message)) {
        if ($message->send_message()) {
            $success = 'success';
        } else {
            echo 'An error occured while adding in the database.';
        }
    } else {
        $success = 'failed';
    }
} else if (isset($_POST['notif'])) {

    if ($_POST['action'] == 'decline') {
        $mess = "Your appointment has been declined.";
    } else if ($_POST['action'] == 'cancel') {
        $mess = "Your appointment has been cancelled.";
    } else if ($_POST['action'] == 'resched') {
        $mess = "Your appointment date and time has been rescheduled";
    } else if ($_POST['action'] == 'confirm') {
        $mess = "Your appointment has been confirmed.";
    }

    $message->sender_id = $message->get_doctor_account($_POST['sender_id']);
    $message->receiver_id = $message->get_patient_account($_POST['receiver_id']);
    $message->message = $mess;
    $message->message_type = 'System';

    if (validate_field($message->message)) {
        if ($message->send_message()) {
            $success = 'success';
        } else {
            echo 'An error occured while adding in the database.';
        }
    } else {
        $success = 'failed';
    }
}
