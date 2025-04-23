<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
    header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 1) {
    header('location: ./index.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<?php
$title = 'Profile';
$profile = 'active';
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
                <div class="container">
                    <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                        <button onclick="history.back()" class="btn btn-outline-secondary hover-light d-flex align-items-center">
                            <i class='bx bx-chevron-left'></i>
                            Back
                        </button>
                        <a href="./fileUpload.php" class="btn btn-outline-primary hover-light">
                            Send Files
                        </a>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-12">
                            <div class="row gx-4">

                                <!-- Doctor Uploads Card -->
                                <div class="col-12 mb-4">
                                    <div class="card border-0 shadow h-100">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6 class="text-dark fw-semibold mb-0">Dr. (last name) Files</h6>
                                            </div>
                                            <div class="table-container" style="overflow-x: auto; max-width: 100%;">
                                                <table class="table table-hover doctor-files w-100" id="doctorFilesTable">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-nowrap">File Attachment</th>
                                                            <th class="text-nowrap">Description</th>
                                                            <!-- <th class="text-nowrap">Uploaded By</th> -->
                                                            <th class="text-nowrap">Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $file_sent_doctor = $file->show_files_doctor($_GET['account_id'], $_SESSION['account_id']);
                                                        $doctor_count = 0;

                                                        if (!empty($file_sent_doctor)) {
                                                            foreach ($file_sent_doctor as $item) {
                                                                if ($doctor_count++ >= 10) break;
                                                        ?>
                                                                <tr>
                                                                    <td class="text-truncate" style="max-width: 150px;">
                                                                        <a href="../assets/files/<?= htmlspecialchars($item['file_name']) ?>"
                                                                            class="file-link"
                                                                            target="_blank"
                                                                            onclick="return previewFile(event, '<?= htmlspecialchars($item['file_name']) ?>')">
                                                                            <?= htmlspecialchars($item['file_name']) ?>
                                                                        </a>
                                                                    </td>
                                                                    <td class="" style="max-width: 150px;"><?= htmlspecialchars($item['file_description']) ?></td>
                                                                    <!-- <td class="text-truncate" style="max-width: 120px;"><?php // htmlspecialchars($item['doctor_name']) 
                                                                                                                                ?></td> -->
                                                                    <td class="text-nowrap"><?= date("F d, Y", strtotime($item['is_created'])) ?></td>
                                                                </tr>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Patient Uploads Card -->
                                <div class="col-md-12 mb-4">
                                    <div class="card border-0 shadow h-100">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6 class="text-dark fw-semibold mb-0">(campus name) clinic Files</h6>
                                            </div>
                                            <div class="table-container" style="overflow-x: auto; max-width: 100%;">
                                                <table class="table table-hover doctor-files w-100" id="patientFilesTable">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-nowrap">File Attachment</th>
                                                            <th class="text-nowrap">Description</th>
                                                            <!-- <th class="text-nowrap">Uploaded By</th> -->
                                                            <th class="text-nowrap">Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $file_sent_patient = $file->show_files_patient($_SESSION['account_id'], $_GET['account_id']);
                                                        $patient_count = 0;

                                                        if (!empty($file_sent_patient)) {
                                                            foreach ($file_sent_patient as $item) {
                                                                if ($patient_count++ >= 10) break;
                                                        ?>
                                                                <tr>
                                                                    <td class="text-truncate" style="max-width: 150px;">
                                                                        <a href="../assets/files/<?= htmlspecialchars($item['file_name']) ?>"
                                                                            class="file-link"
                                                                            target="_blank"
                                                                            onclick="return previewFile(event, '<?= htmlspecialchars($item['file_name']) ?>')">
                                                                            <?= htmlspecialchars($item['file_name']) ?>
                                                                        </a>
                                                                    </td>
                                                                    <td class="" style="max-width: 150px;"><?= htmlspecialchars($item['file_description']) ?></td>
                                                                    <!-- <td class="text-truncate" style="max-width: 120px;"><?php //htmlspecialchars($item['patient_name']) 
                                                                                                                                ?></td> -->
                                                                    <td class="text-nowrap"><?= date("F d, Y", strtotime($item['is_created'])) ?></td>
                                                                </tr>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>