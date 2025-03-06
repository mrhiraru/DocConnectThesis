<?php
require_once('../classes/appointment.class.php');
$appointment = new Appointment();

$takenHours = $appointment->get_taken_hours($_GET['doctor_id'], $_GET['date']);

$takenHours = ["01:00:00", "02:00:00", "11:00:00"];

$formattedHours = array_map(function ($time) {
    return (int)explode(":", $time)[0]; // Extract and convert hour to integer
}, $takenHours);

echo "[" . implode(", ", $formattedHours) . "]";
