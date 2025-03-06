<?php
header('Content-Type: application/json'); // Set JSON response

require_once('../classes/appointment.class.php');
$appointment = new Appointment();

// Ensure session is started
session_start();

// Validate input parameters
if (!isset($_SESSION['doctor_id'], $_GET['start_wt'], $_GET['end_wt'])) {
    echo json_encode(["error" => "Missing parameters"]);
    exit;
}

$appArray = $appointment->get_appointments($_SESSION['doctor_id']);
$fullyBookedDates = [];

// Convert time format to hour integers
$startHour = (int) date('H', strtotime($_GET['start_wt']));
$endHour = (int) date('H', strtotime($_GET['end_wt']));

$appointmentsByDate = [];

// Group appointments by date
foreach ($appArray as $appointment) {
    $date = $appointment['appointment_date'];
    $hour = (int) date('H', strtotime($appointment['appointment_time']));
    $appointmentsByDate[$date][] = $hour;
}

// Check each date if all hours are occupied
foreach ($appointmentsByDate as $date => $hours) {
    $allHours = range($startHour, $endHour - 1);

    if (array_diff($allHours, $hours) === []) {
        $fullyBookedDates[] = $date;
    }
}

// Ensure JSON output
echo json_encode($fullyBookedDates);
exit;
