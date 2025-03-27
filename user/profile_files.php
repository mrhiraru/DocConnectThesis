<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
    header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 3) {
    header('location: ../index.php');
    exit();
}

require_once('../tools/functions.php');
require_once('../classes/account.class.php');
require_once('../classes/appointment.class.php');
?>

<!DOCTYPE html>
<html lang="en">
<?php
$title = 'DocConnect | Profile';
include '../includes/head.php';
?>

<style>
    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.05);
    }
    .file-link {
        color: #dc3545;
        text-decoration: none;
        transition: color 0.2s;
    }
    .file-link:hover {
        color: #a71d2a;
        text-decoration: underline;
    }
    .btn-view {
        background-color: #21bf73;
        color: white;
    }
    .btn-view:hover {
        background-color: #198754;
        color: white;
    }
    .search-box {
        max-width: 300px;
    }
    .file-details {
        line-height: 2;
    }
    .file-detail-label {
        font-weight: bold;
        color: #495057;
    }
</style>

<body>
    <?php
    require_once('../includes/header.php');
    ?>

    <section id="profile" class="page-container">
        <div class="container py-5">
            <div class="row">
                <?php include 'profile_left.php'; ?>

                <div class="col-lg-9">
                    <?php
                    $files = 'active';
                    $aFiles = 'page';
                    $cFiles = 'text-dark';

                    include 'profile_nav.php';
                    ?>

                    <div class="card bg-body-tertiary mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-4 text-green">My Files</h5>
                            <hr>

                            <!-- Doctor Uploads Table -->
                            <div class="mb-5">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="text-dark fw-semibold mb-0">Doctor Uploads</h6>
                                    <div class="search-box">
                                        <div class="input-group">
                                            <input type="text" class="form-control doctor-search" placeholder="Search doctor files...">
                                        </div>
                                    </div>
                                </div>
                                <table class="table table-hover doctor-files">
                                    <thead>
                                        <tr>
                                            <th>File Name</th>
                                            <th>Description</th>
                                            <th>Sender</th>
                                            <th>Date Sent</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><a href="#" class="file-link">Test_Result_April_2024.pdf</a></td>
                                            <td>Annual blood test results</td>
                                            <td>Dr. superman</td>
                                            <td>2024-04-15</td>
                                            <td>
                                                <button class="btn btn-sm btn-view" data-bs-toggle="modal" data-bs-target="#fileModal" 
                                                        data-filename="Test_Result_April_2024.pdf" 
                                                        data-description="Annual blood test results" 
                                                        data-sender="Dr. Smith" 
                                                        data-date="2024-04-15">
                                                    <i class='bx bx-show'></i> View
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><a href="#" class="file-link">Prescription_March_2024.pdf</a></td>
                                            <td>Medication prescription</td>
                                            <td>Dr. btamn</td>
                                            <td>2024-03-22</td>
                                            <td>
                                                <button class="btn btn-sm btn-view" data-bs-toggle="modal" data-bs-target="#fileModal" 
                                                        data-filename="Prescription_March_2024.pdf" 
                                                        data-description="Medication prescription" 
                                                        data-sender="Dr. Johnson" 
                                                        data-date="2024-03-22">
                                                    <i class='bx bx-show'></i> View
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Patient Uploads Table -->
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="text-dark fw-semibold mb-0">Patient Uploads</h6>
                                    <div class="search-box">
                                        <div class="input-group">
                                            <input type="text" class="form-control patient-search" placeholder="Search patient files...">
                                        </div>
                                    </div>
                                </div>
                                <table class="table table-hover patient-files">
                                    <thead>
                                        <tr>
                                            <th>File Name</th>
                                            <th>Description</th>
                                            <th>Sender</th>
                                            <th>Date Sent</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><a href="#" class="file-link">Patient_Record_Jane_Smith_April_2024.pdf</a></td>
                                            <td>Medical history update</td>
                                            <td>name</td>
                                            <td>2024-04-10</td>
                                            <td>
                                                <button class="btn btn-sm btn-view" data-bs-toggle="modal" data-bs-target="#fileModal" 
                                                        data-filename="Patient_Record_Jane_Smith_April_2024.pdf" 
                                                        data-description="Medical history update" 
                                                        data-sender="Jane Smith" 
                                                        data-date="2024-04-10">
                                                    <i class='bx bx-show'></i> View
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><a href="#" class="file-link">Jane_Smith_XRay_Chest_January_2024.pdf</a></td>
                                            <td>Chest X-ray results</td>
                                            <td>name</td>
                                            <td>2024-01-15</td>
                                            <td>
                                                <button class="btn btn-sm btn-view" data-bs-toggle="modal" data-bs-target="#fileModal" 
                                                        data-filename="Jane_Smith_XRay_Chest_January_2024.pdf" 
                                                        data-description="Chest X-ray results" 
                                                        data-sender="Jane Smith" 
                                                        data-date="2024-01-15">
                                                    <i class='bx bx-show'></i> View
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- File View Modal -->
    <div class="modal fade" id="fileModal" tabindex="-1" aria-labelledby="fileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fileModalLabel">File Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body file-details">
                    <div class="mb-2">
                        <span class="file-detail-label">File Name:</span><br>
                        <span id="modalFilename"></span>
                    </div>
                    <div class="mb-2">
                        <span class="file-detail-label">Description:</span><br>
                        <span id="modalDescription"></span>
                    </div>
                    <div class="mb-2">
                        <span class="file-detail-label">Sender:</span><br>
                        <span id="modalSender"></span>
                    </div>
                    <div class="mb-2">
                        <span class="file-detail-label">Date Sent:</span><br>
                        <span id="modalDate"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary text-light" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileModal = document.getElementById('fileModal');
            if (fileModal) {
                fileModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    document.getElementById('modalFilename').textContent = button.getAttribute('data-filename');
                    document.getElementById('modalDescription').textContent = button.getAttribute('data-description');
                    document.getElementById('modalSender').textContent = button.getAttribute('data-sender');
                    document.getElementById('modalDate').textContent = button.getAttribute('data-date');
                });
            }
            
            const doctorSearch = document.querySelector('.doctor-search');
            if (doctorSearch) {
                doctorSearch.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    const rows = document.querySelectorAll('.doctor-files tbody tr');
                    
                    rows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        row.style.display = text.includes(searchTerm) ? '' : 'none';
                    });
                });
            }
            
            const patientSearch = document.querySelector('.patient-search');
            if (patientSearch) {
                patientSearch.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    const rows = document.querySelectorAll('.patient-files tbody tr');
                    
                    rows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        row.style.display = text.includes(searchTerm) ? '' : 'none';
                    });
                });
            }
        });
    </script>

    <?php
    require_once('../includes/footer.php');
    ?>
</body>
</html>