<?php
require_once('../tools/functions.php');

if (isset($_POST['update'])) {

    $data = array("doctor_id" => $_POST['doctor_id'], "appointment_date" => $_POST['appointment_date'], "appointment_time" => $_POST['appointment_time']);

    $new_schedule = chatbot_response($data,);

    $schedule_parts = explode(", ", $new_schedule);

    $appointment_date = $schedule_parts[0];
    $appointment_time = $schedule_parts[1];

    echo json_encode(array(
        "appointment_date" => $appointment_date,
        "appointment_time" => $appointment_time
    ));
}
