<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
  header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 1) {
  header('location: ../index.php');
}

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
                    $appointments = array(
                        array("John Doe", "10:00 AM", "10:30 AM", "2024-04-01", "09123456789", "https://meet.google.com/abcdefgh", "Completed"),
                        array("Jane Smith", "02:30 PM", "03:00 PM", "2024-04-02", "09129876543", "https://meet.google.com/ijklmnop", "Completed"),
                        array("Michael Johnson", "09:15 AM", "09:45 AM", "2024-04-03", "09123456789", "https://meet.google.com/qrstuvwxyz", "Completed"),
                        array("Emily Brown", "11:45 AM", "12:15 PM", "2024-04-04", "09121234567", "https://meet.google.com/12345678", "Ongoing"),
                        array("David Lee", "03:00 PM", "03:30 PM", "2024-04-05", "09127654321", "https://meet.google.com/abcdefgh", "Upcoming"),
                        array("Sarah Wilson", "08:30 AM", "09:00 AM", "2024-04-06", "09123456789", "https://meet.google.com/ijklmnop", "Upcoming"),
                        array("Matthew Taylor", "01:15 PM", "01:45 PM", "2024-04-07", "09129876543", "https://meet.google.com/qrstuvwxyz", "Upcoming"),
                        array("Alice Anderson", "09:00 AM", "09:30 AM", "2024-04-08", "09122334455", "https://meet.google.com/abcdefgh", "Upcoming"),
                        array("Christopher Martinez", "04:30 PM", "05:00 PM", "2024-04-09", "09121122334", "https://meet.google.com/ijklmnop", "Upcoming"),
                        array("Olivia White", "10:45 AM", "11:15 AM", "2024-04-10", "09123456789", "https://meet.google.com/qrstuvwxyz", "Upcoming")
                    );
                    foreach ($appointments as $name) {
                    ?>
                        <div class="col p-1">
                            <div class="card">
                                <a class="card-header fs-5" href="./patient-view">
                                    <p class="text-dark m-0">
                                        <?= $name[0] ?>
                                        <img src="../assets/images/profile_img.jpg" alt="" height="32" width="32" class="float-end rounded-circle border border-primary">
                                    </p>
                                </a>
                                <div class="card-body d-flex flex-column">
                                    <div>
                                        <a href=""><i class='bx bx-chat'></i> Chat</a>
                                    </div>
                                    <div>
                                        <a href=""><i class='bx bx-calendar'></i> Set Schedule</a><span class="float-end"><a href=""><i class='bx bx-video'></i> Join Meeting:</a> <?= date("F j, Y", strtotime($name[3])) . " " . $name[1] ?></span>
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