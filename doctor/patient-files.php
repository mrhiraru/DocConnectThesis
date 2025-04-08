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

                <!-- Patient Request Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 text-dark fw-semibold">Patient Request</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="patientRequestTable">
                                <thead>
                                    <tr>
                                        <th>Purpose</th>
                                        <th>File Attachment</th>
                                        <th>Description</th>
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
                                                <td><?= htmlspecialchars($item['purpose']) ?></td>
                                                <td>
                                                    <a class="text-truncate d-inline-block" style="max-width: 150px;"
                                                        href="../assets/files/<?= htmlspecialchars($item['file_name']) ?>"
                                                        class="file-link"
                                                        target="_blank"
                                                        onclick="return previewFile(event, '<?= htmlspecialchars($item['file_name']) ?>')">
                                                        <?= htmlspecialchars($item['file_name']) ?>
                                                    </a>
                                                </td>
                                                <td class="text-truncate" style="max-width: 200px;"><?= htmlspecialchars($item['file_description']) ?></td>
                                                <td><?= date("M d, Y", strtotime($item['is_created'])) ?></td>
                                            </tr>
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="4" class="text-center">No files available.</td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Doctor Response Card -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 text-dark fw-semibold">Your Responses</h6>
                        <a href="./patient-upload-files?account_id=<?= $_GET['account_id'] ?>" class="btn btn-sm btn-primary text-light">
                            <i class='bx bx-plus'></i> Send Response
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="doctorResponseTable">
                                <thead>
                                    <tr>
                                        <th>Purpose</th>
                                        <th>File Attachment</th>
                                        <th>Description</th>
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
                                                <td><?= htmlspecialchars($item['purpose']) ?></td>
                                                <td>
                                                    <a class="text-truncate d-inline-block" style="max-width: 150px;"
                                                        href="../assets/files/<?= htmlspecialchars($item['file_name']) ?>"
                                                        class="file-link"
                                                        target="_blank"
                                                        onclick="return previewFile(event, '<?= htmlspecialchars($item['file_name']) ?>')">
                                                        <?= htmlspecialchars($item['file_name']) ?>
                                                    </a>
                                                </td>
                                                <td class="text-truncate" style="max-width: 200px;"><?= htmlspecialchars($item['file_description']) ?></td>
                                                <td><?= date("M d, Y", strtotime($item['is_created'])) ?></td>
                                            </tr>
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="4" class="text-center">No files available.</td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <script>
        function previewFile(event, fileName) {
            event.preventDefault();
            const fileExt = fileName.split('.').pop().toLowerCase();
            const fileUrl = event.target.href;

            if (fileExt === 'pdf') {
                window.open(fileUrl, '_blank');
                return false;
            }

            const imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            if (imageExtensions.includes(fileExt)) {
                window.open(fileUrl, '_blank');
                return false;
            }

            window.location.href = fileUrl + '?download=true';
            return false;
        }

        $(document).ready(function() {
            const purposeOptions = [{
                    value: '',
                    text: 'All Purposes'
                },
                {
                    value: 'Medical Certificate',
                    text: 'Medical Certificate'
                },
                {
                    value: 'Prescription',
                    text: 'Prescription'
                }
            ];

            function setupTable(tableId) {
                $(`#${tableId}`).DataTable({
                    "pageLength": 5,
                    "lengthChange": false,
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "autoWidth": false,
                    // Remove top pagination controls
                    "dom": '<"table-tools d-flex justify-content-between align-items-center mb-3"<"search-filter-container">>rt<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    "language": {
                        "search": "",
                        "searchPlaceholder": "Search files...",
                        "emptyTable": "No files available",
                        "info": "Showing _START_ to _END_ of _TOTAL_ files",
                        "infoEmpty": "Showing 0 to 0 of 0 files",
                        "paginate": {
                            "previous": "<i class='bx bx-chevron-left'></i>",
                            "next": "<i class='bx bx-chevron-right'></i>"
                        }
                    },
                    "initComplete": function() {
                        var tableWrapper = $(`#${tableId}_wrapper .search-filter-container`);

                        // Create and append the search input
                        var searchDiv = $('<div class="dataTables_filter d-flex align-items-center me-3"></div>')
                            .appendTo(tableWrapper);
                        var searchInput = $(`#${tableId}_filter`).children().detach();
                        searchDiv.append(searchInput);

                        // Create and append the purpose filter dropdown
                        var select = $('<select class="form-select form-select-sm ms-2" style="width: 200px;"></select>');
                        purposeOptions.forEach(function(option) {
                            select.append($('<option>', {
                                value: option.value,
                                text: option.text
                            }));
                        });

                        // Filter column 0 (Purpose)
                        select.on('change', function() {
                            var val = $.fn.dataTable.util.escapeRegex($(this).val());
                            $(`#${tableId}`).DataTable()
                                .column(0)
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });

                        tableWrapper.append(select);
                    }
                });
            }

            setupTable('patientRequestTable');
            setupTable('doctorResponseTable');

            // Style search input consistently
            $('.dataTables_filter input').addClass('form-control form-control-sm').attr('placeholder', 'Search...');
        });
    </script>

</body>

</html>