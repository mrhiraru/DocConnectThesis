<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
    header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 1) {
    header('location: ./index.php');
}

require_once('../tools/functions.php');
require_once('../classes/appointment.class.php');
require_once('../classes/doctor.class.php');

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
                                <label for="gender">Refer to:</label>
                                <?php
                                $doctor = new Doctor();
                                $doctorArray = $doctor->get_doctors();
                                ?>
                                <select class="form-select" aria-label="doctor_id" name="doctor_id">
                                    <option selected disabled>Select Doctor</option>

                                    <?php
                                    foreach ($doctorArray as $item) {
                                    ?>
                                        <option value="<?= $item['doctor_id'] ?>" <?= (isset($_POST['doctor_id']) && $_POST['doctor_id'] == $item['doctor_id']) ? 'selected' : '' ?>><?= $item['doctor_name'] . ' - ' . $item['specialty'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                                <?php
                                if (isset($_POST['doctor_id']) && !validate_field($_POST['doctor_id'])) {
                                ?>
                                    <p class="text-dark m-0 ps-2">Doctor is required.</p>
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