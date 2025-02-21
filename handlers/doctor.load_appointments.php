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
                        <td class="text-center">
                            <?php
                            if ($item['appointment_status'] == 'Incoming') {
                            ?>
                                <a href="./manage-appointment.php?appointment_id=<?= $item['appointment_id'] ?>" class="btn btn-success btn-sm text-light"><i class='bx bx-play-circle me-1'></i>View</a>
                            <?php
                            } else if ($item['appointment_status'] == 'Ongoing') {
                            ?>
                                <a href="./manage-appointment.php?appointment_id=<?= $item['appointment_id'] ?>" class="btn btn-success btn-sm text-light"><i class='bx bx-play-circle me-1'></i>View</a>
                            <?php
                            } else if ($item['appointment_status'] == 'Pending') {
                            ?>
                                <a href="./manage-appointment.php?appointment_id=<?= $item['appointment_id'] ?>" class="btn btn-success btn-sm text-light"><i class='bx bx-play-circle me-1'></i>View</a>
                            <?php
                            } else if ($item['appointment_status'] == 'Completed') {
                            ?>
                                <a href="./manage-appointment.php?appointment_id=<?= $item['appointment_id'] ?>" class="btn btn-success btn-sm text-light"><i class='bx bx-play-circle me-1'></i>View</a>
                            <?php
                            } else if ($item['appointment_status'] == 'Cancelled') {
                            ?>
                                <a href="./manage-appointment.php?appointment_id=<?= $item['appointment_id'] ?>" class="btn btn-success btn-sm text-light"><i class='bx bx-play-circle me-1'></i>View</a>
                            <?php
                            }
                            ?>
                            <a href="./manage-appointment.php?appointment_id=<?= $item['appointment_id'] ?>" class="btn btn-warning btn-sm text-light"><i class='bx bxs-edit me-1'></i>Edit</a>
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