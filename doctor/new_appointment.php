<!DOCTYPE html>
<html lang="en">
<?php
$title = 'Dashboard';
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
            <main class="col-md-9 ms-sm-auto col-lg-10">
            <div class="d-flex justify-content-start align-items-center mb-4">
                <a href="./dashboard" class="me-2" style="text-decoration: none;">
                    <i class='bx bxs-chevron-left mt-1' style="font-size: 30px;"></i>
                </a>
                <h4 class="text-start m-0">New Appointment</h4>
            </div>

            <label for="title" class="form-label">Title</label>
            <input type="text" id="title" class="form-control" placeholder="" style="width: 100%; max-width: 255px;">

            <div class="row">
                <div class="col-md-3">
                    <label for="datestart" class="form-label mt-3">Date Start</label>
                    <input type="text" id="datestart" class="form-control" placeholder="" style="width: 100%; max-width: 255px;">
                </div>
                <div class="col-md-3">
                    <label for="dateend" class="form-label mt-3">Date End</label>
                    <input type="text" id="dateend" class="form-control" placeholder="" style="width: 100%; max-width: 255px;">
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <label for="timestart" class="form-label mt-3">Time Start</label>
                    <input type="text" id="timestart" class="form-control" placeholder="" style="width: 100%; max-width: 255px;">
                </div>
                <div class="col-md-3">
                    <label for="dateend" class="form-label mt-3">Time End</label>
                    <input type="text" id="dateend" class="form-control" placeholder="" style="width: 100%; max-width: 255px;">
                </div>
            </div>
            
            <label for="select-patient" class="form-label mt-3">Patients</label>
            <div class="form-group mx-0 mx-md-0">
                <select id="select-patient" class="form-select" style="width: 100%; max-width: 255px;">
                    <option value="">Select Patient</option>
                    <option value="0">Franklin</option>
                    <option value="1">Farren</option>
                    <option value="2">Hilal</option>
                </select>
            </div>


            <button type="button" class="btn mt-4" style="background-color: #DC3545; color: white; padding-left: 30px; padding-right: 30px;">
                SAVE
            </button>
            </main>
        </div>
    </div>
</body>
</html>