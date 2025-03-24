<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
    header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 3) {
    header('location: ../index.php');
    exit();
}

require_once('../tools/functions.php');
require_once('../classes/account.class.php');
require_once('../classes/appointment.class.php');
?>

<!DOCTYPE html>
<html lang="en">
<?php
$title = 'DocConnect | Profile';
include '../includes/head.php';
?>

<body>
    <?php
    require_once('../includes/header.php');
    ?>

    <section id="profile" class="page-container">
        <div class="container py-5">

            <div class="row">
                <?php include 'profile_left.php'; ?>

                <div class="col-lg-9">
                    <?php
                    $doctors = 'active';
                    $aDoctors = 'page';
                    $cDoctors = 'text-dark';

                    include 'profile_nav.php';
                    ?>

                    <div class="card bg-body-tertiary mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-2 text-green">Appointment List</h5>
                            <hr>
                            <div class="table-responsive">
                                <table class="table table-striped" id="eventsTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Doctor</th>
                                            <th>Date & Time</th>
                                            <th>Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $appointment_class = new Appointment();
                                        $appointmentArray = $appointment_class->get_patient_appointment_user($_SESSION['patient_id']);
                                        $counter = 1;
                                        if (empty(!$appointmentArray)) {
                                            foreach ($appointmentArray as $item) {
                                        ?>
                                                <tr>
                                                    <td><?= $counter ?></td>
                                                    <td><?= $item['doctor_name'] ?></td>
                                                    <td><?= date("l, M d, Y", strtotime($item['appointment_date'])) . " " . date("g:i A", strtotime($item['appointment_time'])) ?></td>
                                                    <td><?= $item['appointment_status'] ?></td>
                                                    <td class="text-center">
                                                        <?php
                                                        if ($item['appointment_status'] == 'Incoming') {
                                                        ?>
                                                            <a href="./appointment-view.php?account_id=<?= $_SESSION['account_id'] ?>&appointment_id=<?= $item['appointment_id'] ?>" class="btn btn-success btn-sm text-light"><i class='bx bx-play-circle me-1'></i>View</a>

                                                        <?php
                                                        } else if ($item['appointment_status'] == 'Ongoing') {
                                                        ?>
                                                            <a href="./appointment-view.php?account_id=<?= $_SESSION['account_id'] ?>&appointment_id=<?= $item['appointment_id'] ?>" class="btn btn-success btn-sm text-light"><i class='bx bx-play-circle me-1'></i>View</a>
                                                        <?php
                                                        } else if ($item['appointment_status'] == 'Pending') {
                                                        ?>

                                                        <?php
                                                        } else if ($item['appointment_status'] == 'Completed') {
                                                        ?>
                                                            <a href="./appointment-view.php?account_id=<?= $_SESSION['account_id'] ?>&appointment_id=<?= $item['appointment_id'] ?>" class="btn btn-info btn-sm text-light"><i class='bx bx-file-blank me-1'></i>Result</a>
                                                        <?php
                                                        } else if ($item['appointment_status'] == 'Cancelled') {
                                                        ?>

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
                                                <td colspan="5" class="text-center">No Appointments</td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php
    require_once('../includes/footer.php');
    ?>
</body>

</html>