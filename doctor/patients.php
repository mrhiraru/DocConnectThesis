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
            <main class="col-md-9 ms-sm-auto col-lg-10 p-md-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <input type="text" id="search" class="form-control w-50 border-black" placeholder="Search by name">
                    <div>
                        <i class='bx bx-list-ul toggle-view p-2 rounded border border-black' data-view="list" style="cursor: pointer; transition: background-color 0.3s;" onmouseover="this.style.backgroundColor='lightgray'" onmouseout="this.style.backgroundColor='transparent'"></i>
                        <i class='bx bx-grid-alt toggle-view p-2 rounded border border-black' data-view="grid" style="cursor: pointer; transition: background-color 0.3s;" onmouseover="this.style.backgroundColor='lightgray'" onmouseout="this.style.backgroundColor='transparent'"></i>
                    </div>
                </div>
                <div id="patient-container" class="mt-4 mb-3 row row-cols-1 row-cols-md-2 row-cols-lg-3">
                    <?php
                    $pateint = new Patient();
                    $patientArray = $pateint->get_patients($_SESSION['doctor_id']);

                    foreach ($patientArray as $item) {

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
                                        <a href=""><i class='bx bx-calendar'></i> Set Schedule</a>
                                        <span class="float-end">
                                            <a href=""><i class='bx bx-video'></i> Join Meeting:</a> <?= date("F j, Y", strtotime($name[3])) . " " . $name[1] ?>
                                        </span>
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

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById("search");
            const patientContainer = document.getElementById("patient-container");
            const toggleButtons = document.querySelectorAll(".toggle-view");

            searchInput.addEventListener("input", function() {
                let filter = searchInput.value.toLowerCase();
                document.querySelectorAll(".patient-card").forEach(function(card) {
                    let name = card.getAttribute("data-name");
                    card.style.display = name.includes(filter) ? "block" : "none";
                });
            });

            toggleButtons.forEach(button => {
                button.addEventListener("click", function() {
                    let view = this.getAttribute("data-view");
                    if (view === "list") {
                        patientContainer.classList.remove("row-cols-md-2", "row-cols-lg-3");
                        patientContainer.classList.add("row-cols-1");
                    } else {
                        patientContainer.classList.add("row-cols-md-2", "row-cols-lg-3");
                    }
                });
            });
        });
    </script>
</body>

</html>