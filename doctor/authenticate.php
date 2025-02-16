<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
    header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 1) {
    header('location: ../index.php');
} else if (isset($_SESSION['google_access_token'])) {
    header('location: ../doctor/appointment.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<?php
$title = 'Appointment';
$appointment = 'active';
include '../includes/head.php';

?>

<body>
    <?php
    require_once('../includes/header-doctor.php');
    ?>
    <div class="container-fluid h-100">
        <div class="row">
            <?php
            require_once('../includes/sidepanel-doctor.php');
            ?>
            <main class="h-100 col-md-9 ms-sm-auto col-lg-10 bg-light d-flex justify-content-center align-items-center">
                <div class="card my-4">
                    <div class="card-body d-flex justify-content-center align-items-center flex-column">
                        <p class="fs-6 text-dark">Google account authentication is required to manage your appointments.</p>
                        <button type="button" class="btn btn-primary text-light" id="authenticate" onclick="handleAuthClick()">Authenticate</button>
                        <a href="./appointment.php" type="button" class="btn btn-primary text-light" id="proceed" hidden>Go back</a>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>

<?php
require_once('../tools/calendar.php');
?>
<script>
    // add here
</script>