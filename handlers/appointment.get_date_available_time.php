<?php
require_once('../classes/appointment.class.php');
$appointment = new Appointment();

$doctorId = $_GET['doctor_id'];
$date = $_GET['date']; // selected date
$startTime = $_GET['startTime']; // Expected format: HH:MM:SS
$endTime = $_GET['endTime']; // Expected format: HH:MM:SS
$appointment_time = $_GET['appointment_time'];
$appointment_date = $_GET['appointment_date']; // default date

// Ensure taken hours are in HH:MM:SS format
$takenHours = array_map(function ($time) {
    return date("H:i:s", strtotime($time));
}, $appointment->get_taken_hours($doctorId, $date));


if (isset($appointment_time) && date('Y-m-d', strtotime($appointment_date)) == date('Y-m-d', strtotime($date))) {
    if (in_array(date('H:i:s', strtotime($appointment_time)), $takenHours)) {
        $key = array_search(date('H:i:s', strtotime($appointment_time)), $takenHours);
        unset($takenHours[$key]);
        $takenHours = array_values($takenHours);
    }
}

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

foreach ($availableTime as $index => $time) {
?>
    <input type="radio" class="btn-check" name="time" id="time<?= $index ?>" autocomplete="off" value="<?= $time ?>" <?= (isset($appointment_time) && $appointment_time == $time) ? 'checked' : '' ?> onload="set_value(this)" onchange="set_value(this)">
    <label class="btn btn-sm btn-outline-secondary m-0 shadow-sm" for="time<?= $index ?>"><?= date("h:i A", strtotime($time)) . " - " . date("h:i A", strtotime($time . ' +1 hour')) ?></label>
<?php
}
?>