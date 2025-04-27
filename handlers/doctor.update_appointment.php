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
    $appointment->decline_reason = htmlentities($_POST['decline_reason']);

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
    $appointment = new Appointment();

    // Required fields
    $appointment->complaint = htmlentities($_POST['complaint']);
    $appointment->his_illness = htmlentities($_POST['his_illness']);
    $appointment->medcon_history = htmlentities($_POST['medcon']);
    $appointment->ob_his = htmlentities($_POST['ob_his']);
    $appointment->fam_his = htmlentities($_POST['fam_his']);
    $appointment->soc_his = htmlentities($_POST['soc_his']);
    $appointment->rev_sys = htmlentities($_POST['rev_sys']);
    $appointment->medication = htmlentities($_POST['medication']);
    $appointment->allergy = htmlentities($_POST['allergy']);
    $appointment->immu = htmlentities($_POST['immu']);
    $appointment->assessment = htmlentities($_POST['assessment']);

    // Conditional fields
    if ($_POST['medcon_check'] === 'Yes') {
        $appointment->diagnosis = !empty($_POST['diagnosis']) ? implode(", ", $_POST['diagnosis']) : null;
    } else {
        $appointment->diagnosis = null;
    }

    if ($_POST['plan_check'] === 'Yes') {
        $appointment->plan = htmlentities($_POST['plan']);
    } else {
        $appointment->plan = null;
    }

    if ($_POST['prescription_check'] === 'Yes') {
        $appointment->prescription = htmlentities($_POST['prescription']);
    } else {
        $appointment->prescription = null;
    }

    // System fields
    $appointment->appointment_id = htmlentities($_POST['appointment_id']);
    $appointment->appointment_status = 'Completed';

    // Validate required fields
    if (
        validate_field($appointment->appointment_id) &&
        validate_field($appointment->complaint) &&
        validate_field($appointment->his_illness) &&
        validate_field($appointment->medcon_history) &&
        validate_field($appointment->ob_his) &&
        validate_field($appointment->fam_his) &&
        validate_field($appointment->soc_his) &&
        validate_field($appointment->rev_sys) &&
        validate_field($appointment->medication) &&
        validate_field($appointment->allergy) &&
        validate_field($appointment->immu) &&
        validate_field($appointment->assessment)
    ) {

        if ($appointment->complete_appointment()) {
            echo 'success';
        } else {
            echo 'failed';
        }
    } else {
        echo 'failed';
    }
    exit();
}

echo $success;
