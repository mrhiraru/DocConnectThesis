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
                        <h2>New Referral</h2>
                        <form action="" method="post">
                            <div class="col-12 col-md-4 mb-3">
                                <label for="reason">Referral Reason</label>
                                <textarea class="form-control" id="reason" name="reason" required><?= isset($_POST['reason']) ? $_POST['reason'] : '' ?></textarea>
                                <?php
                                if (isset($_POST['reason']) && !validate_field($_POST['reason'])) {
                                ?>
                                    <p class="text-dark m-0 ps-2">Referral reason is required.</p>
                                <?php
                                }
                                ?>
                            </div>
                            <div class="col-12 col-md-4 mb-3">
                                <label for="reason">Refer to:</label>
                                <input type="text" class="form-control" id="reason" name="reason" required placeholder="" value="<?= isset($_POST['reason']) ? $_POST['reason'] : '' ?>">
                                <?php
                                if (isset($_POST['reason']) && !validate_field($_POST['reason'])) {
                                ?>
                                    <p class="text-dark m-0 ps-2">Referral reason is required.</p>
                                <?php
                                }
                                ?>
                            </div>
                        </form>
                    </div>
                </div>

            </main>
        </div>
    </div>

</body>

</html>