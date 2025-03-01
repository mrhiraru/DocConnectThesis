<?php
require_once('../classes/appointment.class.php');
require_once('../tools/functions.php');

$appointment = new Appointment();

if (isset($_POST['confirm'])) {

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
        if ($appointment->update_appointment()) {

            $success = 'success';
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
    } else {
        $success = 'failed';
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
    } else {
        $success = 'failed';
    }
} else if (isset($_POST['update_link'])) {
    $appointment->appointment_link = htmlentities($_POST['appointment_link']);
    $appointment->event_id = htmlentities($_POST['event_id']);
    $appointment->appointment_id = htmlentities($_POST['appointment_id']);

    if (
        validate_field($$appointment->appointment_link && $appointment->event_id)
    ) {
        if ($appointment->update_link()) {

            $success = 'success';
        } else {
            $success = 'failed';
        }
    } else {
        $success = 'failed';
    }
}

echo $success;
