<?php
require_once('../classes/appointment.class.php');
$appointment = new Appointment();

$takenHours = $appointment->get_taken_hours($_GET['doctor_id'], $_GET['date']);
$startTime = $_GET['startTime'];
$endTime = $_GET['endTime'];

function getAvailableTimes($startTime, $endTime, $takenHours, $interval = 30)
{
    $availableTimes = [];
    $currentTime = strtotime($startTime);
    $endTimestamp = strtotime($endTime);

    while ($currentTime <= $endTimestamp) {
        $timeSlot = date("H:i:s", $currentTime);

        // Check if the time is not in taken hours
        if (!in_array($timeSlot, $takenHours)) {
            $availableTimes[] = $timeSlot;
        }

        // Move to the next time slot
        $currentTime = strtotime("+$interval minutes", $currentTime);
    }

    return $availableTimes;
}

$availableTime = getAvailableTimes($startTime, $endTime, $takenHours, 60);


foreach ($availableTime as $time) {
?>
    <p class="text-muted"><?= date("h:i A", strtotime($time)) ?></p>
<?
}
