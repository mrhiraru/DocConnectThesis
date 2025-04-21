<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
    header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 2) {
    header('location: ./index.php');
}

require_once '../classes/doctor.class.php';

?>

<!DOCTYPE html>
<html lang="en">
<?php
$title = 'Moderator | Send File';
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
        <div class="d-flex justify-content-between align-items-center mb-3">
            <input type="text" id="search" class="form-control w-50 border-black" placeholder="Search by name">
            <div>
                <i class='bx bx-list-ul toggle-view p-2 rounded border border-black' data-view="list" style="cursor: pointer; transition: background-color 0.3s;" onmouseover="this.style.backgroundColor='lightgray'" onmouseout="this.style.backgroundColor='transparent'"></i>
                <i class='bx bx-grid-alt toggle-view p-2 rounded border border-black' data-view="grid" style="cursor: pointer; transition: background-color 0.3s;" onmouseover="this.style.backgroundColor='lightgray'" onmouseout="this.style.backgroundColor='transparent'"></i>
            </div>
        </div>
        <div id="doctor-container" class="mt-4 mb-3 row row-cols-1 row-cols-md-2 row-cols-lg-3">
            <?php
            $doctor = new Doctor();
            $doctorArray = $doctor->get_doctors();

            foreach ($doctorArray as $item) {
                $name = explode(" ", $item['doctor_name']);
            ?>
                <div class="col doctor-card p-1" data-name="<?= strtolower($item['doctor_name']) ?>">
                    <div class="card">
                        <a class="card-header fs-5" href="./doctor-view.php?account_id=<?= $item['account_id']  ?>">
                            <p class="text-dark m-0">
                                <?= $item['doctor_name'] ?>
                                <img src="<?php if (isset($item['account_image'])) {
                                                echo "../assets/images/" . $item['account_image'];
                                            } else {
                                                echo "../assets/images/default_profile.png";
                                            } ?>" alt="" height="32" width="32" class="float-end rounded-circle border border-primary">
                            </p>
                        </a>
                        <div class="card-body d-flex flex-column">
                            <div>
                                <a href="./send-file-form.php?account_id=<?= $item['account_id']  ?>"><i class='bx bx-file'></i> Send File</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
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
                        doctorContainer.classList.add("row-cols-md-2", "row-cols-lg-3");
                    }
                });
            });
        });
    </script>
</body>

</html>