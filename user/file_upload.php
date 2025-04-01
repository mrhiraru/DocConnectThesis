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
if (isset($_POST['upload_document'])) {

    $account_class->account_id = $_SESSION['account_id'];

    $uploaddir = '../assets/files/';
    $uploadname = $_FILES[htmlentities('documentname')]['name'];
    $uploadext = explode('.', $uploadname);
    $uploadnewext = strtolower(end($uploadext));
    $allowed = array('pdf', 'doc', 'xls', 'xlsx', 'docx');

    if (in_array($uploadnewext, $allowed)) {

        $uploadenewname = reset($uploadext) . date('Ymd_His') . "." . $uploadnewext;
        $uploadfile = $uploaddir . $uploadenewname;

        if (move_uploaded_file($_FILES[htmlentities('documentname')]['tmp_name'], $uploadfile)) {

            $file->file_name = $uploadenewname;
            $file->file_description = htmlentities($_POST['documentDescription']);
            $file->sender_id = $_SESSION['account_id'];
            $file->receiver_id = $_GET['account_id'];
            if ($file->add_file()) {
                $success = 'success';
            } else {
                echo 'An error occured while adding in the database.';
            }
        } else {
            $success = 'failed';
        }
    } else {
        $success = 'failed';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php
$title = 'DocConnect | File Upload';
include '../includes/head.php';
?>

<body>
    <?php require_once('../includes/header.php'); ?>

    <section class="page-container padding-medium">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card border-0 shadow">
                        <div class="card-header bg-white">
                            <div class="d-flex align-items-center">
                                <i class='bx bxs-cloud-upload text-danger fs-3 me-2'></i>
                                <h4 class="mb-0 text-dark">Upload Document</h4>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <!-- Form Column -->
                                <div class="col-md-6">
                                    <form id="documentUploadForm" method='post' class="needs-validation" enctype="multipart/form-data" novalidate>
                                        <!-- File Upload Input -->
                                        <div class="mb-4">
                                            <label for="documentFile" class="form-label fw-semibold">Select Document (PDF or DOCX)</label>
                                            <div class="input-group">
                                                <input type="file" class="form-control d-none" id="documentFile" name="documentname" accept=".pdf,.doc,.xls,.xlsx,.docx" required>
                                                <button class="btn btn-danger text-light" type="button" onclick="document.getElementById('documentFile').click()">
                                                    <i class='bx bx-file me-1'></i> Choose File
                                                </button>
                                                <span id="fileName" class="input-group-text bg-light">No file chosen</span>
                                            </div>
                                            <div class="invalid-feedback">Please select a file to upload.</div>
                                        </div>

                                        <!-- Description Input -->
                                        <div class="mb-4">
                                            <label for="documentDescription" class="form-label fw-semibold">Description</label>
                                            <textarea class="form-control bg-light" id="documentDescription" name="documentDescription" rows="4" placeholder="Enter a description for this document..." required></textarea>
                                            <div class="invalid-feedback">Please provide a description.</div>
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                            <button type="submit" class="btn btn-green px-4 text-light" name="upload_document">
                                                <i class='bx bx-upload me-1'></i> Upload Document
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <div class="col-md-6 mt-4 mt-md-0">
                                    <div class="border rounded p-3 h-100">
                                        <h5 class="text-center mb-3">Document Preview</h5>
                                        <div id="noPreview" class="text-center py-5">
                                            <i class='bx bx-file-blank fs-1 text-muted'></i>
                                            <p class="mt-2 text-muted">No document selected</p>
                                        </div>
                                        <iframe id="pdfPreview" frameborder="0" scrolling="no" class="w-100 d-none" style="height: 400px;"></iframe>
                                        <div id="unsupportedPreview" class="text-center py-5 d-none">
                                            <i class='bx bx-error-alt fs-1 text-warning'></i>
                                            <p class="mt-2 text-muted">Preview not available for this file type</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('documentFile');
            const fileNameDisplay = document.getElementById('fileName');
            const pdfPreview = document.getElementById('pdfPreview');
            const noPreview = document.getElementById('noPreview');
            const unsupportedPreview = document.getElementById('unsupportedPreview');

            fileInput.addEventListener('change', function() {
                if (this.files.length > 0) {
                    const file = this.files[0];
                    fileNameDisplay.textContent = file.name;

                    pdfPreview.classList.add('d-none');
                    noPreview.classList.add('d-none');
                    unsupportedPreview.classList.add('d-none');

                    if (file.type === 'application/pdf') {
                        const fileURL = URL.createObjectURL(file);
                        pdfPreview.src = fileURL;
                        pdfPreview.classList.remove('d-none');
                    } else if (file.type === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
                        unsupportedPreview.classList.remove('d-none');
                    } else {
                        noPreview.classList.remove('d-none');
                    }
                } else {
                    fileNameDisplay.textContent = 'No file chosen';
                    pdfPreview.classList.add('d-none');
                    unsupportedPreview.classList.add('d-none');
                    noPreview.classList.remove('d-none');
                }
            });

            const form = document.getElementById('documentUploadForm');

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                if (!form.checkValidity()) {
                    e.stopPropagation();
                    form.classList.add('was-validated');
                    return;
                }

                const file = fileInput.files[0];
                const description = document.getElementById('documentDescription').value;

                const validTypes = ['application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                if (!validTypes.includes(file.type)) {
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-danger alert-dismissible fade show mt-3';
                    alertDiv.innerHTML = `
                        <strong>Error!</strong> Please upload only PDF or DOCX files.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    `;
                    form.parentNode.insertBefore(alertDiv, form.nextSibling);
                    return;
                }

                const successDiv = document.createElement('div');
                successDiv.className = 'alert alert-success alert-dismissible fade show mt-3';
                successDiv.innerHTML = `
                    <strong>Success!</strong> Document uploaded successfully!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;
                form.parentNode.insertBefore(successDiv, form.nextSibling);

                form.reset();
                form.classList.remove('was-validated');
                fileNameDisplay.textContent = 'No file chosen';

                pdfPreview.classList.add('d-none');
                unsupportedPreview.classList.add('d-none');
                noPreview.classList.remove('d-none');
                pdfPreview.src = '';

                setTimeout(() => {
                    successDiv.remove();
                }, 3000);
            });
        });
    </script>

    <?php require_once('../includes/footer.php'); ?>
</body>

</html>