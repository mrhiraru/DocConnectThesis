<?php
require_once('../classes/appointment.class.php');
require_once('../classes/message.class.php');
require_once('../tools/functions.php');

$appointment = new Appointment();

if (isset($_POST['confirm'])) {

    $appointment->appointment_id = htmlentities($_POST['appointment_id']);
    $appointment->appointment_date = htmlentities($_POST['appointment_date']);
    $appointment->appointment_time = htmlentities($_POST['appointment_time']);
    $appointment->reason = htmlentities($_POST['reason']);
    $appointment->appointment_link = htmlentities($_POST['link']);
    $appointment->event_id = htmlentities($_POST['event_id']);
    $appointment->appointment_status = "Incoming";


    if (
        validate_field($appointment->appointment_id &&
            $appointment->appointment_date &&
            $appointment->appointment_time &&
            $appointment->reason &&
            $appointment->appointment_link && $appointment->event_id &&
            $appointment->appointment_status)
    ) {
        if ($appointment->update_appointment()) {
            $message = new Message();

            $date_time = new DateTime($_POST['appointment_date'] . ' ' . $_POST['appointment_time']);
            $date_time = $date_time->format('F j, Y \a\t h:i A');
            $ids = $message->get_id_from_appointment($appointment->appointment_id);
            $id = $message->get_patient_account($ids['patient_id']);


            $message->sender_id = $_SESSION['account_id'];
            $message->receiver_id = $id['account_id'];
            $message->message = 'Your appointment has been confirmed on ' . $date_time;
            $message->message_type = 'System';

            if (
                validate_field($message->message) &&
                validate_field($message->sender_id) &&
                validate_field($message->receiver_id)
            ) {
                if ($message->send_message()) {
                    $success = 'success';
                } else {
                    echo 'An error occured while adding in the database.';
                }
            } else {
                $success = 'failed';
            }
        } else {
            $success = 'failed';
        }
    } else {
        $success = 'failed';
    }
} else if (isset($_POST['decline'])) {
    $appointment->appointment_id = htmlentities($_POST['appointment_id']);
    $appointment->appointment_status = "Cancelled";

    if (
        validate_field($appointment->appointment_id && $appointment->appointment_status)
    ) {
        if ($appointment->decline_appointment()) {
            $success = 'success';
        } else {
            $success = 'failed';
        }
    } else {
        $success = 'failed';
    }
} else if (isset($_POST['reschedule'])) {
    $appointment->appointment_id = htmlentities($_POST['appointment_id']);
    $appointment->appointment_date = htmlentities($_POST['appointment_date']);
    $appointment->appointment_time = htmlentities($_POST['appointment_time']);
    $appointment->reason = htmlentities($_POST['reason']);
    $appointment->appointment_status = "Incoming";

    if (
        validate_field($appointment->appointment_id &&
            $appointment->appointment_date &&
            $appointment->appointment_time &&
            $appointment->reason &&
            $appointment->appointment_status)
    ) {
        if ($appointment->reschedule_appointment()) {
            $success = 'success';
        } else {
            $success = 'failed';
        }
    } else {
        $success = 'failed';
    }
} else if (isset($_POST['cancel'])) {
    $appointment->appointment_id = htmlentities($_POST['appointment_id']);
    $appointment->event_id = '';
    $appointment->appointment_link = '';
    $appointment->appointment_status = "Cancelled";

    if (
        validate_field($appointment->appointment_id &&
            $appointment->appointment_status)
    ) {
        if ($appointment->cancel_appointment()) {
            $success = 'success';
        } else {
            $success = 'failed';
        }
    } else {
        $success = 'failed';
    }
} else if (isset($_POST['start'])) {
    $appointment->appointment_id = htmlentities($_POST['appointment_id']);
    $appointment->appointment_status = 'Ongoing';

    if (
        validate_field($appointment->appointment_id &&
            $appointment->appointment_status)
    ) {
        if ($appointment->update_appointment_status()) {
            $success = 'success';
        } else {
            $success = 'failed';
        }
    }
} else if (isset($_POST['end'])) {
    $appointment->result = htmlentities($_POST['result']);
    $appointment->comment = htmlentities($_POST['comment']);
    $appointment->appointment_id = htmlentities($_POST['appointment_id']);
    $appointment->appointment_status = 'Completed';

    if (
        validate_field($appointment->appointment_id && $appointment->result &&
            $appointment->appointment_status)
    ) {
        if ($appointment->complete_appointment()) {
            $success = 'success';
        } else {
            $success = 'failed';
        }
    }
}

echo $success;
