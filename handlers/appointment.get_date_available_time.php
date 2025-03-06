<?php
require_once('../classes/appointment.class.php');
$appointment = new Appointment();

$takenHours = $appointment->get_taken_hours($_GET['doctor_id'], $_GET['date']);

if (!$takenHours) {
    $takenHours = []; // Ensure it always returns an array
}

$formattedHours = array_map(function ($time) {
    return (int)explode(":", $time)[0]; // Extract and convert hour to integer
}, $takenHours);

header('Content-Type: application/json'); // Force JSON response
echo json_encode($formattedHours);
