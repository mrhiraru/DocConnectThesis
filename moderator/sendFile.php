<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
    header('location: ../user/verification.php');
    exit();
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 2) {
    header('location: ./index.php');
    exit();
}

require_once '../classes/doctor.class.php';

$doctor = new Doctor();
$doctorArray = $doctor->get_doctors();
?>

<!DOCTYPE html>
<html lang="en">
<?php
$title = 'Send File to Doctors';
include './includes/admin_head.php';
function getCurrentPage()
{
    return basename($_SERVER['PHP_SELF']);
}
?>

<body>
    <?php
    require_once('./includes/admin_header.php');
    require_once('./includes/admin_sidepanel.php');
    ?>
    <div class="page-container">

        <!-- Page Header -->
        <section class="page-header padding-medium bg-white">
            <div class="container bg-white">
                <div class="row py-3 align-items-center">
                    <div class="col-md-6">
                        <h1 class="text-green mb-3">Send Files to Doctors</h1>
                        <p class="lead">Browse and choose a doctor to send necessary files to.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Search and View Toggle -->
        <section class="search-filter py-4 bg-white border-bottom">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-8 mb-3 mb-md-0">
                        <div class="input-group">
                            <input type="text" id="search" class="form-control" placeholder="Search by name...">
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <i class='bx bx-list-ul toggle-view p-2 rounded border border-dark' data-view="list" style="cursor: pointer;"></i>
                        <i class='bx bx-grid-alt toggle-view p-2 rounded border border-dark' data-view="grid" style="cursor: pointer;"></i>
                    </div>
                </div>
            </div>
        </section>

        <!-- Doctor List Section -->
        <section class="doctors-listing padding-medium py-5">
            <div class="container">
                <div class="row" id="doctor-container">
                    <?php foreach ($doctorArray as $item): ?>
                        <div class="col-lg-4 col-md-6 mb-4 doctor-card" data-name="<?= strtolower($item['doctor_name']) ?>">
                            <div class="card h-100 shadow-sm border-1">
                                <div class="card-header bg-white border-0 pt-3 text-center">
                                    <img src="<?= isset($item['account_image']) ? "../assets/images/" . $item['account_image'] : "../assets/images/default_profile.png" ?>"
                                        class="img-fluid rounded shadow" height="100" width="100" alt="Doctor Profile">
                                </div>
                                <div class="card-body text-center">
                                    <h5 class="card-title text-green mb-1"><?= htmlspecialchars($item['doctor_name']) ?></h5>
                                </div>
                                <div class="card-footer bg-white border-0 pb-3 text-center">
                                    <a href="./send-file-form.php?account_id=<?= $item['account_id'] ?>" class="btn btn-sm btn-primary text-light">
                                        <i class='bx bx-file'></i> Send File
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById("search");
            const doctorContainer = document.getElementById("doctor-container");
            const toggleButtons = document.querySelectorAll(".toggle-view");

            searchInput.addEventListener("input", function() {
                let filter = searchInput.value.toLowerCase();
                document.querySelectorAll(".doctor-card").forEach(function(card) {
                    let name = card.getAttribute("data-name");
                    card.style.display = name.includes(filter) ? "block" : "none";
                });
            });

            toggleButtons.forEach(button => {
                button.addEventListener("click", function() {
                    let view = this.getAttribute("data-view");
                    if (view === "list") {
                        doctorContainer.classList.remove("row-cols-md-2", "row-cols-lg-3");
                        doctorContainer.classList.add("row-cols-1");
                    } else {
                        doctorContainer.classList.remove("row-cols-1");
                        doctorContainer.classList.add("row-cols-md-2", "row-cols-lg-3");
                    }
                });
            });
        });
    </script>
</body>

</html>