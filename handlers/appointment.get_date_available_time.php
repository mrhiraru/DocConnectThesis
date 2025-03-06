<?php
require_once('../classes/appointment.class.php');
$appointment = new Appointment();

$takenHours = $appointment->get_taken_hours($_GET['doctor_id'], $_GET['date']);

$formattedHours = array_map(function ($time) {
    return (int)explode(":", $time)[0]; // Extract and convert hour to integer
}, $takenHours);

// Ensure a valid JSON response, even if empty
echo json_encode($formattedHours);
