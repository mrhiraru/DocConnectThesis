<?php
require_once('../classes/appointment.class.php');

$appointment = new Appointment();
$scheduleArray = $appointment->get_date_schedules($_GET['doctor_id'], $_GET['appointment_date'], $_GET['appointment_id']);

$day_map = [
    'Monday'    => 1,
    'Tuesday'   => 2,
    'Wednesday' => 3,
    'Thursday'  => 4,
    'Friday'    => 5,
    'Saturday'  => 6,
    'Sunday'    => 7
];

$today = date('Y-m-d');
$error_color = 'text-danger';
if ($_GET['appointment_date'] < $today) {
    $error_message = "Appointment date cannot be in the past.";
} else {
    $estimated_end = date('H:i', strtotime('+59 minutes', strtotime($_GET['appointment_time'])));

    $hasConflict = false;
    if (!empty($scheduleArray)) {
        foreach ($scheduleArray as $schedule) {
            if ($schedule['appointment_time'] < $estimated_end && $schedule['estimated_end'] > $_GET['appointment_time']) {
                $hasConflict = true;
                break;
            }
        }
    }

    $appointment_day_number = $day_map[date('l', strtotime($_GET['appointment_date']))];
    $doctor_start_day_number = $day_map[$_GET['start_day']];
    $doctor_end_day_number = $day_map[$_GET['end_day']];


    if ($appointment_day_number < $doctor_start_day_number || $appointment_day_number > $doctor_end_day_number) {
        $error_message = "Please select date within your working days: " . $_GET['start_day'] . " to " . $_GET['end_day'] . ".";
    } else {
        $temp_start_wt = date('H:i', strtotime('-1 minute', strtotime($_GET['start_wt'])));
        if ($_GET['appointment_time'] < $temp_start_wt || $estimated_end > $_GET['end_wt']) {
            $error_message = "Please select time within your working time: " . date("g:i A", strtotime($_GET['start_wt'])) . " to " . date("g:i A", strtotime($_GET['end_wt'])) . ".";
        } else {
            if ($hasConflict) {
                $error_message = "Time selected is not available due to an existing appointment.";
            } else {
                $error_color = 'text-success';
                $error_message = "Time and Date is available.";
            }
        }
    }
}

?>
<table class="table table-sm">
    <thead>
        <tr>
            <th colspan="4" class="<?= $error_color ?> text-center"><?= $error_message ?></th>
        </tr>
        <?php
        if (!empty($scheduleArray)) {
        ?>
            <tr>
                <th>Date: <?= date("l, M d, Y", strtotime($_GET['appointment_date'])) ?></th>
                <th>Time</th>
                <th class="text-end"></th>
            </tr>
        <?php
        } else {
        ?>
            <tr>
                <th colspan="4" class="text-center border-0">No other schedule for <?= date("l, M d, Y", strtotime($_GET['appointment_date'])) ?></th>
            </tr>
        <?php
        }
        ?>
    </thead>
    <tbody>
        <?php
        if (!empty($scheduleArray)) {
            foreach ($scheduleArray as $schedule) {
                if ($schedule['appointment_time'] < $estimated_end && $schedule['estimated_end'] > $_GET['appointment_time']) {
                    $row_color = 'table-danger';
                } else {
                    $row_color = 'table-light';
                }
        ?>
                <tr class="<?= $row_color ?>">
                    <td><?= $schedule['patient_name'] ?></td>
                    <td><?= date("g:i A", strtotime($schedule['appointment_time'])) . " - " . date("g:i A", strtotime($schedule['estimated_end'])) ?></td>
                    <td class="text-end"><i class="<?= ($row_color == 'table-danger') ?  'bx bx-error-circle' : '' ?> text-primary"></i></td>
                </tr>
        <?php
            }
        }
        ?>
    </tbody>
</table>