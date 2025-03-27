<?php
require_once('../classes/appointment.class.php');
require_once('../classes/medical_condition.class.php');
require_once('../tools/functions.php');

$appointment = new Appointment();

if (isset($_POST['confirm'])) {

    $appointment->appointment_id = htmlentities($_POST['appointment_id']);
    $appointment->appointment_date = htmlentities($_POST['appointment_date']);
    $appointment->appointment_time = htmlentities($_POST['appointment_time']);
    $appointment->estimated_end = date('H:i', strtotime('+59 minutes', strtotime($appointment->appointment_time)));
    $appointment->appointment_link = htmlentities($_POST['link']);
    $appointment->event_id = htmlentities($_POST['event_id']);
    $appointment->appointment_status = "Incoming";


    if (
        validate_field($appointment->appointment_id &&
            $appointment->appointment_date &&
            $appointment->appointment_time &&
            $appointment->appointment_link && $appointment->event_id &&
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
    $appointment->estimated_end = date('H:i', strtotime('+59 minutes', strtotime($appointment->appointment_time)));
    $appointment->appointment_status = "Incoming";

    if (
        validate_field($appointment->appointment_id &&
            $appointment->appointment_date &&
            $appointment->appointment_time &&
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
    $appointment->complaint = htmlentities($_POST['complaint']);

    if ($_POST['exmedcon_check'] === 'Yes') {
        $appointment->medcon_history = htmlentities($_POST['medcon']);
    } else if ($_POST['exmedcon_check'] === 'No') {
        $appointment->medcon_history = "No past or existing medical condition history";
    }

    if ($_POST['allergy_check'] === 'Yes') {
        $appointment->allergy = htmlentities($_POST['allergy']);
    } else if ($_POST['allergy_check'] === 'No') {
        $appointment->allergy = "No allergies";
    }

    if ($_POST['medication_check'] === 'Yes') {
        $appointment->medication = htmlentities($_POST['medication']);
    } else if ($_POST['allergy_check'] === 'No') {
        $appointment->medication = "No medication";
    }

    $appointment->observation = htmlentities($_POST['observation']);

    if ($_POST['medcon_check'] === 'Yes') {
        $appointment->diagnosis = implode(", ", $_POST['diagnosis']);
    } else if ($_POST['medcon_check'] === 'No') {
        $appointment->diagnosis = null;
    }

    $appointment->assessment = htmlentities($_POST['assessment']);

    if ($_POST['plan_check'] === 'Yes') {
        $appointment->plan = htmlentities($_POST['plan']);
    } else if ($_POST['plan_check'] === 'No') {
        $appointment->plan = null;
    }

    if ($_POST['prescription_check'] === 'Yes') {
        $appointment->prescription = htmlentities($_POST['prescription']);
    } else if ($_POST['prescription_check'] === 'No') {
        $appointment->prescription = null;
    }

    $appointment->comment = htmlentities($_POST['comment']);
    $appointment->appointment_id = htmlentities($_POST['appointment_id']);
    $appointment->appointment_status = 'Completed';



    if (
        validate_field($appointment->appointment_id && $appointment->complaint &&
            $appointment->observation && $appointment->assessment &&
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
}

echo $success;
