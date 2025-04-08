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
            <div class="col-md-6 text-md-start">
                <button onclick="history.back()" class="btn btn-outline-secondary hover-light d-fex align-items-center mb-3">
                    <i class='bx bx-chevron-left'></i>
                    Back
                </button>
            </div>
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="row gx-4">
                        <!-- Doctor Uploads Card -->
                        <div class="col-md-6 mb-4">
                            <div class="card border-0 shadow h-100">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="text-dark fw-semibold mb-0">Doctor Uploads</h6>
                                    </div>
                                    <div class="table-container" style="overflow-x: auto; max-width: 100%;">
                                        <table class="table table-hover doctor-files w-100" id="doctorFilesTable">
                                            <thead>
                                                <tr>
                                                    <th class="text-nowrap">File Name</th>
                                                    <th class="text-nowrap">Description</th>
                                                    <th class="text-nowrap">Uploaded By</th>
                                                    <th class="text-nowrap">Date Uploaded</th>
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
                                                            <td class="text-truncate" style="max-width: 150px;"><a href="../assets/files/<?= htmlspecialchars($item['file_name']) ?>" class="file-link" download><?= htmlspecialchars($item['file_name']) ?></a></td>
                                                            <td class="text-truncate" style="max-width: 150px;"><?= htmlspecialchars($item['file_description']) ?></td>
                                                            <td class="text-truncate" style="max-width: 120px;"><?= htmlspecialchars($item['doctor_name']) ?></td>
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
                        <div class="col-md-6 mb-4">
                            <div class="card border-0 shadow h-100">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="text-dark fw-semibold mb-0">Patient Uploads</h6>
                                        <a href="./file_upload?account_id=<?= $_GET['account_id'] ?>" class="btn btn-sm btn-primary text-light me-2">
                                            Upload new files
                                        </a>
                                    </div>
                                    <div class="table-container" style="overflow-x: auto; max-width: 100%;">
                                        <table class="table table-hover doctor-files w-100" id="patientFilesTable">
                                            <thead>
                                                <tr>
                                                    <th class="text-nowrap">File Name</th>
                                                    <th class="text-nowrap">Description</th>
                                                    <th class="text-nowrap">Uploaded By</th>
                                                    <th class="text-nowrap">Date Uploaded</th>
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
                                                            <td class="text-truncate" style="max-width: 150px;"><a href="../assets/files/<?= htmlspecialchars($item['file_name']) ?>" class="file-link" download><?= htmlspecialchars($item['file_name']) ?></a></td>
                                                            <td class="text-truncate" style="max-width: 150px;"><?= htmlspecialchars($item['file_description']) ?></td>
                                                            <td class="text-truncate" style="max-width: 120px;"><?= htmlspecialchars($item['patient_name']) ?></td>
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
    </section>

    <?php require_once('../includes/footer.php'); ?>

    <!-- DataTables Script -->
    <script>
        $(document).ready(function() {
            $('#doctorFilesTable').DataTable({
                "pageLength": 10,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "scrollX": false,
                "dom": '<"top"f>rt<"bottom"lip><"clear">',
                "language": {
                    "search": "_INPUT_",
                    "searchPlaceholder": "Search...",
                    "emptyTable": "No files available"
                }
            });

            $('#patientFilesTable').DataTable({
                "pageLength": 10,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "scrollX": false,
                "dom": '<"top"f>rt<"bottom"lip><"clear">',
                "language": {
                    "search": "_INPUT_",
                    "searchPlaceholder": "Search...",
                    "emptyTable": "No files available"
                }
            });
        });
    </script>

    <style>
        .table-container {
            width: 100%;
            overflow: hidden;
        }

        .table {
            table-layout: fixed;
            width: 100%;
            margin-bottom: 0;
        }

        .text-truncate {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .dataTables_wrapper .dataTables_filter {
            float: none;
            text-align: left;
            margin-bottom: 10px;
        }

        .dataTables_wrapper .dataTables_info {
            padding-top: 10px;
        }

        .dataTables_wrapper .dataTables_paginate {
            padding-top: 10px;
        }
    </style>
</body>

</html>