<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
    header('location: ../user/verification.php');
    exit();
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 2) {
    header('location: ./index.php');
    exit();
}

require_once '../classes/doctor.class.php';

$doctor = new Doctor();
$doctorArray = $doctor->get_doctors();


require_once('../classes/file.class.php');

$file = new File();
if (isset($_POST['upload_document'])) {

    $file->sender_id = $_SESSION['account_id'];
    $file->receiver_id = $_GET['doctor_id'];

    $uploaddir = '../assets/files/';
    $uploadname = $_FILES[htmlentities('documentname')]['name'];
    $uploadext = explode('.', $uploadname);
    $uploadnewext = strtolower(end($uploadext));
    $allowed = array('pdf', 'doc', 'xls', 'xlsx', 'docx', 'png', 'jpeg', 'jpg');

    if (in_array($uploadnewext, $allowed)) {

        $uploadenewname = reset($uploadext) . "_" . date('Ymd_His') . "." . $uploadnewext;
        $uploadfile = $uploaddir . $uploadenewname;

        if (move_uploaded_file($_FILES[htmlentities('documentname')]['tmp_name'], $uploadfile)) {

            $file->file_name = $uploadenewname;
            $file->purpose = htmlentities($_POST['purpose']);
            $file->file_description = htmlentities($_POST['documentDescription']);

            if ($file->add_file()) {
                header('Location: fileList?doctor_id=' . $_GET['doctor_id']);
                exit();
            } else {
                $success = 'failed';
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
$title = 'File Upload';
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

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <button onclick="history.back()" class="btn btn-outline-secondary hover-light d-fex align-items-center mb-3">
                    <i class='bx bx-chevron-left'></i>
                    Back
                </button>
                <div class="card border-0 shadow">
                    <div class="card-header bg-white">
                        <div class="d-flex align-items-center">
                            <i class='bx bxs-cloud-upload text-danger fs-3 me-2'></i>
                            <h4 class="mb-0 text-dark">Send File</h4>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <!-- Form Column -->
                            <div class="col-md-6">
                                <form id="documentUploadForm" method='post' class="needs-validation" enctype="multipart/form-data" novalidate>
                 
                                    <!-- File Upload Input -->
                                    <div class="mb-4">
                                        <label for="documentFile" class="form-label fw-semibold">Attach file</label>
                                        <div class="input-group">
                                            <input type="file" class="form-control d-none" id="documentFile" name="documentname" accept=".pdf,.doc,.xls,.xlsx,.docx,.png,.jpg,.jpeg" required>
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
                                            <i class='bx bx-upload me-1'></i> Send file
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('documentFile');
            const fileNameDisplay = document.getElementById('fileName');
            const pdfPreview = document.getElementById('pdfPreview');
            const noPreview = document.getElementById('noPreview');
            const unsupportedPreview = document.getElementById('unsupportedPreview');
            const form = document.getElementById('documentUploadForm');
            const previewContainer = document.querySelector('.border.rounded.p-3.h-100');

            // Create image preview element
            const imgPreview = document.createElement('img');
            imgPreview.id = 'imagePreview';
            imgPreview.className = 'w-100 d-none';
            imgPreview.style.maxHeight = '400px';
            imgPreview.style.objectFit = 'contain';
            previewContainer.insertBefore(imgPreview, noPreview);

            fileInput.addEventListener('change', function() {
                if (this.files.length > 0) {
                    const file = this.files[0];
                    fileNameDisplay.textContent = file.name;

                    // Hide all previews first
                    pdfPreview.classList.add('d-none');
                    imgPreview.classList.add('d-none');
                    noPreview.classList.add('d-none');
                    unsupportedPreview.classList.add('d-none');

                    // Show appropriate preview based on file type
                    if (file.type === 'application/pdf') {
                        const fileURL = URL.createObjectURL(file);
                        pdfPreview.src = fileURL;
                        pdfPreview.classList.remove('d-none');
                    } else if (file.type.match('image.*')) {
                        const fileURL = URL.createObjectURL(file);
                        imgPreview.src = fileURL;
                        imgPreview.classList.remove('d-none');
                    } else if (file.type === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' ||
                        file.type === 'application/msword' ||
                        file.type === 'application/vnd.ms-excel' ||
                        file.type === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
                        unsupportedPreview.classList.remove('d-none');
                    } else {
                        noPreview.classList.remove('d-none');
                    }
                } else {
                    // No file selected
                    fileNameDisplay.textContent = 'No file chosen';
                    pdfPreview.classList.add('d-none');
                    imgPreview.classList.add('d-none');
                    unsupportedPreview.classList.add('d-none');
                    noPreview.classList.remove('d-none');
                }
            });

            form.addEventListener('submit', function(e) {
                // Remove previous alerts
                const existingAlerts = document.querySelectorAll('.alert');
                existingAlerts.forEach(alert => alert.remove());

                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                    form.classList.add('was-validated');
                    return;
                }

                const file = fileInput.files[0];
                const validTypes = [
                    'application/pdf',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'application/msword',
                    'application/vnd.ms-excel',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'image/png',
                    'image/jpeg',
                    'image/jpg'
                ];

                if (!file || !validTypes.includes(file.type)) {
                    e.preventDefault();
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-danger alert-dismissible fade show mt-3';
                    alertDiv.innerHTML = `
                <strong>Error!</strong> Please upload only PDF, DOC, DOCX, XLS, XLSX, PNG, JPG, or JPEG files.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`;
                    form.parentNode.insertBefore(alertDiv, form.nextSibling);
                    return;
                }
            });

            <?php if (isset($success)): ?>
                const messageType = '<?php echo $success === 'success' ? 'success' : 'danger' ?>';
                const messageText = '<?php echo $success === 'success' ? 'Document uploaded successfully!' : 'Failed to upload document. Please try again.' ?>';

                const alertDiv = document.createElement('div');
                alertDiv.className = `alert alert-${messageType} alert-dismissible fade show mt-3`;
                alertDiv.innerHTML = `
            <strong>${messageType === 'success' ? 'Success!' : 'Error!'}</strong> ${messageText}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`;
                form.parentNode.insertBefore(alertDiv, form.nextSibling);

                <?php if ($success === 'success'): ?>
                    form.reset();
                    form.classList.remove('was-validated');
                    fileNameDisplay.textContent = 'No file chosen';
                    pdfPreview.classList.add('d-none');
                    imgPreview.classList.add('d-none');
                    unsupportedPreview.classList.add('d-none');
                    noPreview.classList.remove('d-none');
                    pdfPreview.src = '';
                    imgPreview.src = '';
                <?php endif; ?>
            <?php endif; ?>
        });
    </script>
</body>

</html>