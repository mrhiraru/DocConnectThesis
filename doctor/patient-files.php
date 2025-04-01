<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
    header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 1) {
    header('location: ./index.php');
    exit();
}

require_once('../classes/file.class.php');

$file = new File();

?>

<!DOCTYPE html>
<html lang="en">
<?php
$title = 'Patient Files';
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
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mb-4">
                <?php
                require_once('../includes/breadcrumb-patient.php');
                ?>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="text-dark fw-semibold mb-0">Doctor Uploads</h6>
                        <a href="./patient-upload-files?account_id=<?= $_GET['account_id'] ?>" class="btn btn-sm btn-primary text-light me-2">
                            Upload FIles
                        </a>
                    </div>
                    <table class="table table-hover doctor-files">
                        <thead>
                            <tr>
                                <th>File Name</th>
                                <th>Description</th>
                                <th>Uploaded By</th>
                                <th>Date Uploaded</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $file_sent_doctor = $file->show_files_doctor($_SESSION['account_id'], $_GET['account_id']);

                            if (!empty($file_sent_doctor)) {
                                foreach ($file_sent_doctor as $item) {
                            ?>
                                    <tr>
                                        <td><a href="../assets/files/<?= $item['file_name'] ?>" class="file-link" download><?= $item['file_name'] ?></a></td>
                                        <td><?= $item['file_description'] ?></td>
                                        <td><?= $item['doctor_name'] ?></td>
                                        <td><?= date("F d, Y", strtotime($item['is_created'])) ?></td>
                                    </tr>
                                <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="4" style="text-align: center;">No files available.</td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Patient Uploads Table -->
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="text-dark fw-semibold mb-0">Patient Uploads</h6>
                    </div>
                    <table class="table table-hover doctor-files">
                        <thead>
                            <tr>
                                <th>File Name</th>
                                <th>Description</th>
                                <th>Uploaded By</th>
                                <th>Date Uploaded</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $file_sent_patient = $file->show_files_patient($_GET['account_id'], $_SESSION['account_id']);

                            if (!empty($file_sent_patient)) {
                                foreach ($file_sent_patient as $item) {
                            ?>
                                    <tr>
                                        <td><a href="../assets/files/<?= $item['file_name'] ?>" class="file-link" download><?= $item['file_name'] ?></a></td>
                                        <td><?= $item['file_description'] ?></td>
                                        <td><?= $item['patient_name'] ?></td>
                                        <td><?= date("F d, Y", strtotime($item['is_created'])) ?></td>
                                    </tr>
                                <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="4" style="text-align: center;">No files available.</td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
</body>

</html>