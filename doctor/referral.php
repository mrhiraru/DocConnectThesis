<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
    header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 1) {
    header('location: ./index.php');
}

require_once('../tools/functions.php');
require_once('../classes/appointment.class.php');

$appointment_class = new Appointment();

?>

<!DOCTYPE html>
<html lang="en">
<?php
$title = 'Patient Referral';
$appointment = 'active';
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
            <main class="col-md-9 ms-sm-auto col-lg-10 bg-light">
                <div class="card flex-fill my-4">
                    <div class="card-body">
                        <h2>Patient Referral</h2>
                        <div class="table-responsive">
                            <table class="table table-striped" id="eventsTable">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Patient</th>
                                        <th>Referral Reason</th>
                                        <th>Date of Referral</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $appointmentArray = $appointment_class->doctor_appointments($_SESSION['doctor_id'], 'Incoming');
                                    $counter = 1;
                                    if (!empty($appointmentArray)) {
                                        foreach ($appointmentArray as $item) {
                                    ?>
                                            <tr>
                                                <td><?= $counter ?></td>
                                                <td><?= $item['patient_name'] ?></td>
                                                <td><?= date("l, M d, Y", strtotime($item['appointment_date'])) . " " . date("g:i A", strtotime($item['appointment_time'])) ?></td>

                                                <td class="text-center">

                                                    <a href="./appointment-view.php?account_id=<?= $item['account_id'] ?>&appointment_id=<?= $item['appointment_id'] ?>" class="btn btn-success btn-sm text-light"><i class='bx bx-play-circle me-1'></i>Make Schedule</a>
                                                    <a href="./manage-appointment.php?appointment_id=<?= $item['appointment_id'] ?>" class="btn btn-warning btn-sm text-light"><i class='bx bxs-edit me-1'></i>Update</a>

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
                    </div>
                </div>

            </main>
        </div>
    </div>

</body>

</html>