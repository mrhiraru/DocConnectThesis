<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
    header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 1) {
    header('location: ../index.php');
    exit();
}


require_once('../classes/appointment.class.php');
?>

<!DOCTYPE html>
<html lang="en">
<?php
$title = 'Appointment View';
$patient = 'active';
include '../includes/head.php';
?>

<body>
    <?php
    require_once('../includes/header-doctor.php');
    ?>
    <div class="container-fluid">
        <div class="row">
            <?php
            require_once('../includes/sidepanel-doctor.php');
            ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mb-4">
                <?php
                require_once('../includes/breadcrumb-patient.php');
                ?>
                <div class="p-0 m-0 text-end">
                    <button class="btn btn-primary text-white mb-2">Create New Meeting</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped" id="eventsTable">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Date & Time</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $appointment_class = new Appointment();
                            $appointmentArray = $appointment_class->get_patient_appointment($_SESSION['doctor_id'], $_GET['account_id']);
                            $counter = 1;
                            if (empty(!$appointmentArray)) {
                                foreach ($appointmentArray as $item) {
                            ?>
                                    <tr>
                                        <td><?= $counter ?></td>
                                        <td><?= date("l, M d, Y", strtotime($item['appointment_date'])) . " " . date("g:i A", strtotime($item['appointment_time'])) ?></td>
                                        <td><?= $item['appointment_status'] ?></td>
                                        <td class="text-center">
                                        <?php
                            if ($item['appointment_status'] == 'Incoming') {
                            ?>
                                <a href="./appointment-view.php?account_id=<?= $_GET['account_id'] ?>&appointment_id=<?= $item['appointment_id'] ?>" class="btn btn-success btn-sm text-light"><i class='bx bx-play-circle me-1'></i>View</a>
                                <a href="./manage-appointment.php?appointment_id=<?= $item['appointment_id'] ?>" class="btn btn-warning btn-sm text-light"><i class='bx bxs-edit me-1'></i>Update</a>
                            <?php
                            } else if ($item['appointment_status'] == 'Ongoing') {
                            ?>
                                <a href="./appointment-view.php?account_id=<?= $_GET['account_id'] ?>&appointment_id=<?= $item['appointment_id'] ?>" class="btn btn-success btn-sm text-light"><i class='bx bx-play-circle me-1'></i>View</a>
                            <?php
                            } else if ($item['appointment_status'] == 'Pending') {
                            ?>
                                <a href="./manage-appointment.php?appointment_id=<?= $item['appointment_id'] ?>" class="btn btn-warning btn-sm text-light"><i class='bx bxs-edit me-1'></i>Update</a>
                            <?php
                            } else if ($item['appointment_status'] == 'Completed') {
                            ?>
                                <a href="./appointment-view.php?account_id=<?= $_GET['account_id'] ?>&appointment_id=<?= $item['appointment_id'] ?>" class="btn btn-info btn-sm text-light"><i class='bx bx-file-blank me-1'></i>Result</a>
                            <?php
                            } else if ($item['appointment_status'] == 'Cancelled') {
                            ?>
                                <a href="./manage-appointment.php?appointment_id=<?= $item['appointment_id'] ?>" class="btn btn-danger btn-sm text-light"><i class='bx bxs-edit me-1'></i>Reschedule</a>
                            <?php
                            }
                            ?>
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
            </main>
        </div>
    </div>
</body>

</html>