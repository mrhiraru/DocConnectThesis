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

    $appointment_date = new DateTime($ids['appointment_date'], new DateTimeZone('UTC')); // Assume it's stored in UTC

    // Convert time string separately
    $appointment_time = DateTime::createFromFormat('H:i:s', $ids['appointment_time'], new DateTimeZone('UTC'));

    // Merge date & time manually
    $appointment_date->setTime(
        (int) $appointment_time->format('H'),
        (int) $appointment_time->format('i'),
        (int) $appointment_time->format('s')
    );

    // Convert to Manila timezone
    $appointment_date->setTimezone(new DateTimeZone('Asia/Manila'));

    // Format final output
    $date_time = $appointment_date->format('F j, Y \a\t h:i A');


    if ($_POST['action'] == 'decline') {
        $mess = "Your appointment on " . $date_time . " has been declined.";
    } else if ($_POST['action'] == 'cancel') {
        $mess = "Your appointment on " . $date_time . " has been cancelled.";
    } else if ($_POST['action'] == 'resched') {
        $mess = "Your appointment has been reschedule to " . $date_time . ".";
    } else if ($_POST['action'] == 'confirm') {
        $mess = "Your appointment has been confirmed on " . $date_time . ".";
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
