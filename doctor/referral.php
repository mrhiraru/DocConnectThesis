<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
    header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 1) {
    header('location: ./index.php');
}

require_once('../tools/functions.php');
require_once('../classes/appointment.class.php');
require_once('../classes/referral.class.php');

$appointment_class = new Appointment();
$refer = new Refer();

?>

<!DOCTYPE html>
<html lang="en">
<?php
$title = 'Patient Referral';
$referral = 'active';
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
                                        <th>Reason</th>
                                        <th>Date</th>
                                        <th>Doctor</th>
                                        <th>Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $referralArray = $refer->get_referral($_SESSION['doctor_id']);
                                    $counter = 1;
                                    if (!empty($referralArray)) {
                                        foreach ($referralArray as $item) {
                                    ?>
                                            <tr>
                                                <td><?= $counter ?></td>
                                                <td><?= $item['patient_name'] ?></td>
                                                <td><?= $item['reason'] ?></td>
                                                <td><?= date("M d, Y", strtotime($item['is_created'])) ?></td>

                                                <td><?= $item['doctor_name'] ?></td>
                                                <td><?= $item['status'] ?></td>

                                                <td class="text-center">

                                                    <a href="./appointment-view.php?account_id=<?= $_SESSION['account_id'] ?>&appointment_id=<?= $item['appointment_id'] ?>&referral=true&referral_id=<?= $item['referral_id'] ?>" class="btn btn-info btn-sm text-light"><i class='bx bx-file-blank me-1'></i>Appointment Result</a>

                                                </td>
                                            </tr>
                                        <?php
                                            $counter++;
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="7" class="text-center">No referred patients.</td>
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