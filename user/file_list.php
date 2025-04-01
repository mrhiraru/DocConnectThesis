<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
    header('location: ../user/verification.php');
    exit();
}

require_once('../tools/functions.php');
require_once('../classes/account.class.php');
require_once('../classes/file.class.php');

$file = new File();

?>

<!DOCTYPE html>
<html lang="en">
<?php
$title = 'DocConnect | Files';
include '../includes/head.php';
?>

<body>
    <?php require_once('../includes/header.php'); ?>

    <section class="page-container padding-medium">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card border-0 shadow p-3">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="text-dark fw-semibold mb-0">Doctor Uploads</h6>
                                <a href="./file_upload?account_id=<?= $_GET['account_id'] ?>" class="btn btn-sm btn-primary text-light me-2">
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
                                    $file_sent_doctor = $file->show_files_doctor($_GET['account_id'], $_SESSION['account_id']);

                                    if (!empty($file_sent_doctor)) {
                                        foreach ($file_sent_doctor as $item) {
                                    ?>
                                            <tr>
                                                <td><a href="#" class="file-link"></a><?= $item['file_name'] ?></td>
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
                            <table class="table table-hover patient-files">
                                <thead>
                                    <tr>
                                        <th>File Name</th>
                                        <th>Description</th>
                                        <th>Uploaded By</th>
                                        <th>Date Uploaded</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><a href="#" class="file-link">Patient_Record_Jane_Smith_April_2024.pdf</a></td>
                                        <td>Medical history update</td>
                                        <td>name</td>
                                        <td>2024-04-10</td>
                                    </tr>
                                    <tr>
                                        <td><a href="#" class="file-link">Jane_Smith_XRay_Chest_January_2024.pdf</a></td>
                                        <td>Chest X-ray results</td>
                                        <td>name</td>
                                        <td>2024-01-15</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <?php require_once('../includes/footer.php'); ?>
</body>

</html>