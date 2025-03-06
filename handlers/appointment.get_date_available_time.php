<?php
require_once('../classes/appointment.class.php');
$appointment = new Appointment();

$doctorId = $_GET['doctor_id'];
$date = $_GET['date'];
$startTime = $_GET['startTime']; // Expected format: HH:MM:SS
$endTime = $_GET['endTime']; // Expected format: HH:MM:SS

// Ensure taken hours are in HH:MM:SS format
$takenHours = array_map(function ($time) {
    return date("H:i:s", strtotime($time));
}, $appointment->get_taken_hours($doctorId, $date));

function getAvailableTimes($startTime, $endTime, $takenHours, $interval = 60)
{
    $availableTimes = [];
    $currentTime = strtotime($startTime);
    $endTimestamp = strtotime($endTime);

    while ($currentTime <= $endTimestamp) {
        $timeSlot = date("H:i:s", $currentTime); // Use HH:MM:SS format

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
    <button type="button" class="col-6 btn btm-sm btn-outline-secondary text-muted bg-light"><?= date("h:i A", strtotime($time)) . " - " . date("h:i A", strtotime($time . ' +1 hour')) ?></button>
<?php } ?>