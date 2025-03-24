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
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <input type="text" id="search" class="form-control w-50 border-black" placeholder="Search by name">
                                <div>
                                    <i class='bx bx-list-ul toggle-view p-2 rounded border border-black' data-view="list" style="cursor: pointer; transition: background-color 0.3s;" onmouseover="this.style.backgroundColor='lightgray'" onmouseout="this.style.backgroundColor='transparent'"></i>
                                    <i class='bx bx-grid-alt toggle-view p-2 rounded border border-black' data-view="grid" style="cursor: pointer; transition: background-color 0.3s;" onmouseover="this.style.backgroundColor='lightgray'" onmouseout="this.style.backgroundColor='transparent'"></i>
                                </div>
                            </div>
                            <div id="patient-container" class="mt-4 mb-3 row row-cols-1 row-cols-md-2 row-cols-lg-3">
                                <?php
                                // $pateint = new Patient();
                                // $patientArray = $pateint->get_patients($_SESSION['doctor_id']);

                                // foreach ($patientArray as $item) {

                                ?>
                                    <div class="col patient-card p-1" data-name="<?= strtolower($name[0]) ?>">
                                        <div class="card">
                                            <a class="card-header fs-5" href="./patient-view?account_id=<?= $item['account_id']  ?>">
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
                                                    <a href="./patient-appointment?account_id=<?= $item['account_id']  ?>"><i class='bx bx-calendar'></i>Appointments</a>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                // }
                                ?>
                            </div>
                            </main>
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