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
require_once('../classes/doctor.class.php');

$appointment_class = new Appointment();
$refer = new Refer();

if (isset($_POST['refer'])) {

    $refer->appointment_id = htmlentities($_GET['appointment_id']);
    $refer->reason = ucfirst(strtolower(htmlentities($_POST['reason'])));
    if (isset($_POST['doctor_id'])) {
        $refer->doctor_id = htmlentities($_POST['doctor_id']);
    } else {
        $refer->doctor_id = '';
    }
    $refer->status = 'Pending';

    if (
        validate_field($refer->appointment_id) &&
        validate_field($refer->reason) &&
        validate_field($refer->doctor_id)
    ) {
        if ($refer->new_referral()) {
            $success = 'success';
            header('location: ./referral');
        } else {
            echo 'An error occured while adding in the database.';
        }
    } else {
        $success = 'failed';
    }
}

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
                        <h2>New Referral</h2>
                        <form action="" method="post">
                            <div class="col-12 col-md-4 mb-3">
                                <label for="reason">Referral reason:</label>
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
                                <label for="doctor_id">Refer to:</label>
                                <?php
                                $doctor = new Doctor();
                                $doctorArray = $doctor->get_doctors();
                                ?>
                                <select class="form-select" aria-label="doctor_id" name="doctor_id">
                                    <option selected disabled>Select Doctor</option>

                                    <?php
                                    foreach ($doctorArray as $item) {
                                        if ($item['doctor_id'] == $_SESSION['doctor_id']) {
                                            continue;
                                        }
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
                            <input type="hidden" name="appointment_id" value="<?= $_GET['appointment_id'] ?>">
                            <div class="col-12 col-md-4 mb-3">
                                <button type="submit" class="btn btn-primary text-light w-100" name="refer">Confirm</button>
                            </div>
                        </form>
                    </div>
                </div>

            </main>
        </div>
    </div>

</body>

</html>