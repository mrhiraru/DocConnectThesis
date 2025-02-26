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
    $ids = $message->get_ids_from_appointment($_POST['appointment_id']);
    $formatted_date = date('Y-m-d', strtotime($ids['appointment_date'])); // HTML date format
    $formatted_time = date('H:i', strtotime($ids['appointment_time']));  // HTML time format

    $date_time = date('F j, Y \a\t h:i A', strtotime("$formatted_date $formatted_time"));

    if ($_POST['action'] == 'decline') {
        $mess = "Appointment on " . $date_time . " has been declined.";
    } else if ($_POST['action'] == 'cancel') {
        $mess = "Appointment on " . $date_time . " has been cancelled.";
    } else if ($_POST['action'] == 'resched') {
        $mess = "Appointment has been rescheduled to " . $date_time . ".";
    } else if ($_POST['action'] == 'confirm') {
        $mess = "Appointment has been confirmed on " . $date_time . ".";
    } else if ($_POST['action'] == 'start') {
        $mess = "Appointment has started, You can now join the Meeting.";
    } else if ($_POST['action'] == 'end') {
        $mess = "Appointment has been completed.";
    }

    $message->sender_id = $ids['doctor_account_id'];
    $message->receiver_id = $ids['patient_account_id'];
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
