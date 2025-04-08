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
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="text-dark fw-semibold mb-0">Patient Request</h6>
                        <a href="./patient-upload-files?account_id=<?= $_GET['account_id'] ?>" class="btn btn-sm btn-primary text-light me-2">
                            Send Response
                        </a>
                    </div>
                    <table class="table table-hover doctor-files">
                        <thead>
                            <tr>
                                <th>Patient Request</th>
                                <th>File Attachment</th>
                                <th>Description</th>
                                <!-- <th>Uploaded By</th> -->
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $file_sent_patient = $file->show_files_patient($_GET['account_id'], $_SESSION['account_id']);

                            if (!empty($file_sent_patient)) {
                                foreach ($file_sent_patient as $item) {
                            ?>
                                    <tr>
                                        <td><?= $item['purpose'] ?></td>
                                        <td>
                                            <a class="text-truncate"
                                                href="../assets/files/<?= htmlspecialchars($item['file_name']) ?>"
                                                class="file-link"
                                                target="_blank"
                                                onclick="return previewFile(event, '<?= htmlspecialchars($item['file_name']) ?>')">
                                                <?= htmlspecialchars($item['file_name']) ?>
                                            </a>
                                        </td>
                                        <td><?= htmlspecialchars($item['file_description']) ?></td>
                                        <!-- <td><?php // $item['patient_name'] 
                                                    ?></td> -->
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

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="text-dark fw-semibold mb-0">Response</h6>
                    </div>
                    <table class="table table-hover doctor-files">
                        <thead>
                            <tr>
                                <th>Response Purpose</th>
                                <th>File Attachment</th>
                                <th>Description</th>
                                <!-- <th>Uploaded By</th> -->
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $file_sent_doctor = $file->show_files_doctor($_SESSION['account_id'], $_GET['account_id']);

                            if (!empty($file_sent_doctor)) {
                                foreach ($file_sent_doctor as $item) {
                            ?>
                                    <tr>
                                        <td><?= $item['purpose'] ?></td>
                                        <td>
                                            <a class="text-truncate"
                                                href="../assets/files/<?= htmlspecialchars($item['file_name']) ?>"
                                                class="file-link"
                                                target="_blank"
                                                onclick="return previewFile(event, '<?= htmlspecialchars($item['file_name']) ?>')">
                                                <?= htmlspecialchars($item['file_name']) ?>
                                            </a>
                                        </td>
                                        <td><?= htmlspecialchars($item['file_description']) ?></td>
                                        <!-- <td><?php // $item['doctor_name'] 
                                                    ?></td> -->
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

    <script>
        function previewFile(event, fileName) {
            event.preventDefault();
            const fileExt = fileName.split('.').pop().toLowerCase();
            const fileUrl = event.target.href;

            // For PDF files, open in new tab
            if (fileExt === 'pdf') {
                window.open(fileUrl, '_blank');
                return false;
            }

            // For other supported file types (images), open in new tab
            const imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            if (imageExtensions.includes(fileExt)) {
                window.open(fileUrl, '_blank');
                return false;
            }

            // For unsupported file types, initiate download
            window.location.href = fileUrl + '?download=true';
            return false;
        }
    </script>

</body>

</html>