<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
    header('location: ../user/verification.php');
    exit();
}

require_once('../tools/functions.php');
require_once('../classes/account.class.php');
?>

<!DOCTYPE html>
<html lang="en">
<?php
$title = 'DocConnect | Upload Documents';
include '../includes/head.php';
?>

<body>
    <?php require_once('../includes/header.php'); ?>
    
    <section class="page-container padding-medium">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card border-0 shadow">
                        <div class="card-header bg-white">
                            <div class="d-flex align-items-center">
                                <i class='bx bxs-cloud-upload text-danger fs-3 me-2'></i>
                                <h4 class="mb-0 text-dark">Upload Document</h4>
                            </div>
                            <hr class="my-2 border-secondary">
                        </div>
                        
                        <div class="card-body">
                            <form id="documentUploadForm" class="needs-validation" novalidate>
                                <!-- File Upload Input -->
                                <div class="mb-4">
                                    <label for="documentFile" class="form-label fw-semibold">Select Document (PDF or DOCX)</label>
                                    <div class="input-group">
                                        <input type="file" class="form-control d-none" id="documentFile" accept=".pdf,.docx" required>
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
                                    <textarea class="form-control" id="documentDescription" rows="4" placeholder="Enter a description for this document..." required></textarea>
                                    <div class="invalid-feedback">Please provide a description.</div>
                                </div>
                                
                                <!-- Submit Button -->
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="submit" class="btn btn-green px-4 text-light">
                                        <i class='bx bx-upload me-1'></i> Upload Document
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('documentFile');
            const fileNameDisplay = document.getElementById('fileName');
            
            fileInput.addEventListener('change', function() {
                if (this.files.length > 0) {
                    fileNameDisplay.textContent = this.files[0].name;
                } else {
                    fileNameDisplay.textContent = 'No file chosen';
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
                
                setTimeout(() => {
                    successDiv.remove();
                }, 3000);
            });
        });
    </script>

    <?php require_once('../includes/footer.php'); ?>
</body>
</html>