<?php
require_once('../classes/appointment.class.php');
require_once('../tools/functions.php');

$appointment = new Appointment();

if (isset($_POST['confirm'])) {

    $appointment->appointment_id = htmlentities($_POST['appointment_id']);
    $appointment->appointment_date = htmlentities($_POST['appointment_date']);
    $appointment->appointment_time = htmlentities($_POST['appointment_time']);
    $appointment->reason = htmlentities($_POST['reason']);
    $appointment->appointment_link = htmlentities($_POST['link']);
    $appointment->appointment_status = "Incoming";


    if (
        validate_field($appointment->appointment_id &&
            $appointment->appointment_date &&
            $appointment->appointment_time &&
            $appointment->reason &&
            $appointment->appointment_link &&
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
}

echo $success;
