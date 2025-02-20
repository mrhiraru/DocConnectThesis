<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
    header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 1) {
    header('location: ../index.php');
}

require_once('../classes/patient.class.php');

?>

<!DOCTYPE html>
<html lang="en">
<?php
$title = 'Patients';
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
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="mt-4 mb-3 row row-cols-1 row-cols-md-2 row-cols-lg-3 ">
                    <?php
                    $pateint = new Patient();
                    $patientArray = $pateint->get_patients($_SESSION['doctor_id']);

                    foreach ($patientArray as $item) {

                    ?>
                        <div class="col p-1">
                            <div class="card">
                                <a class="card-header fs-5" href="./patient-view">
                                    <p class="text-dark m-0">
                                        <?= $item['patient_name'] ?>
                                        <img src="<?php if (isset($item['account_image'])) {
                                                        echo "../assets/images/" . $item['account_image'];
                                                    } else {
                                                        echo "../assets/images/default_profile.png";
                                                    } ?>" alt="" height="32" width="32" class="float-end rounded-circle border border-primary">
                                    </p>
                                </a>
                                <div class="card-body d-flex flex-column">
                                    <div>
                                        <a href="./chats.php?account_id=<?= $item['account_id']  ?>"><i class='bx bx-chat'></i> Chat</a>
                                    </div>
                                    <div>
                                        <a href=""><i class='bx bx-calendar'></i> Set Appointment</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </main>
        </div>
    </div>
</body>

</html>