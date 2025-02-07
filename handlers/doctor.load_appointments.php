<?php

require_once('../classes/appointment.class.php');

$appointment_class = new Appointment();

?>
<div class="table-responsive">
    <table class="table table-striped" id="eventsTable">
        <thead>
            <tr>
                <th></th>
                <th>Date & Time</th>
                <th>Patient</th>
                <th>Meeting Link</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $appointmentArray = $appointment_class->doctor_appointments($_GET['doctor_id'], $_GET['status']);
            $counter = 1;
            if (empty(!$appointmentArray)) {
                foreach ($appointmentArray as $item) {
            ?>
                    <tr>
                        <td><?= $counter ?></td>
                        <td><?= date("l, M d, Y", strtotime($item['appointment_date'])) . " " . date("g:i A", strtotime($item['appointment_time'])) ?></td>
                        <td><?= $item['patient_name'] ?></td>
                        <td><?= $item['appointment_link'] ?></td>
                        <td class="text-center">
                            <a href="./update-appointment.php?appointment_id=<?= $item['appointment_id'] ?>" class="btn btn-warning btn-sm"><i class='bx bxs-edit text-light'></i></a>
                            <button class="btn btn-danger btn-sm ms-2"><i class='bx bxs-trash text-light'></i></button>
                        </td>
                    </tr>
                <?php
                    $counter++;
                }
            } else {
                ?>
                <tr>
                    <td colspan="5" class="text-center">No <?= $_GET['status'] ?> Appointments</td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>