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

$appointment_class = new Appointment();
$record = $appointment_class->get_appointment_details_user($_GET['appointment_id']);

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
                    $appointment = 'active';
                    $aAppointment = 'page';
                    $cAppointment = 'text-dark';

                    include 'profile_nav.php';
                    ?>

                    <div class="card bg-body-tertiary mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-2 text-green">Appointment View</h5>
                            <hr>
                            <div class="p-0 m-0 row">
                                <div class="col-12 mb-3 border-bottom">
                                    <p class="m-0 p-0 fs-5 text-dark fw-semibold text-wrap">
                                        <?= date("l, M d, Y", strtotime($record['appointment_date'])) . " " . date("g:i A", strtotime($record['appointment_time'])) ?>
                                    </p>
                                    <p class="m-0 p-0 fs-6 text-secondary">Doctor: <span class="text-dark"><?= $record['doctor_name'] ?></span></p>
                                    <p class="m-0 p-0 fs-6 text-secondary">Reason: <span class="text-dark"><?= $record['reason'] ?></span></p>
                                    <p class="m-0 p-0 fs-6 text-secondary mb">Status: <span class="text-dark"><?= $record['appointment_status'] ?></span></p>
                                    <p class="m-0 p-0 fs-6 text-secondary mb-3">Link: <a href="<?= $record['appointment_link'] ?>" class="text-primary"><?= $record['appointment_link'] ?></a></p>
                                </div>
                                <?php
                                if ($record['appointment_status'] == "Completed") {
                                ?>
                                    <div class="col-12 mb-3">
                                        <form action="" class="row" id="resultForm">
                                            <div class="col-12 mb-3">
                                                <label for="result" class="form-label">Consultation Result:</label>
                                                <textarea id="result" name="result" rows="2" cols="50" class="form-control bg-light" required readonly><?= $record['result'] ?></textarea>
                                            </div>
                                            <?php
                                            if (isset($record['diagnosis'])) {
                                            ?>
                                                <div class="col-12 mb-3">
                                                    <label for="result" class="form-label">Medical Condition/s:</label>
                                                    <textarea id="result" name="result" rows="2" cols="50" class="form-control bg-light" required readonly><?= $record['diagnosis'] ?></textarea>
                                                </div>
                                            <?php
                                            }
                                            ?>

                                            <div class="col-12">
                                                <label for="comment" class="form-label">Note:</label>
                                                <textarea id="comment" name="comment" rows="7" cols="50" class="form-control bg-light" readonly><?= $record['comment'] ?></textarea>
                                            </div>
                                        </form>
                                    </div>
                                <?php
                                }
                                ?>
                                <div class="col-12 d-flex justify-content-center">
                                    <?php
                                    if ($record['appointment_status'] == "Ongoing") {
                                    ?>
                                        <button class="btn btn-success text-white mb-3" onclick="join_meeting('<?= $record['appointment_link'] ?>')">
                                            <i class='bx bx-video me-2 align-middle fs-5'></i>
                                            Join Meeting
                                        </button>
                                    <?php
                                    } else if ($record['appointment_status'] == "Completed") {
                                    ?>
                                        <a href="" class="btn btn-danger text-white mb-3">
                                            <i class='bx bxs-edit align-middle fs-5 me-1'></i>
                                            New Appointment
                                        </a>
                                    <?php
                                    }
                                    ?>
                                </div>
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

    <script>
        function join_meeting(url) {
            window.open(url, '_blank', 'width=800,height=600,top=100,left=100,toolbar=no,menubar=no,scrollbars=yes,resizable=yes');
        }
    </script>
</body>

</html>