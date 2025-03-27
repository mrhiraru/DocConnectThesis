<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
    header('location: ../user/verification.php');
}

require_once('../tools/functions.php');
require_once('../tools/functions.php');
require_once('../classes/account.class.php');

$doctor_id = isset($_GET['id']) ? intval($_GET['id']) : '';

$doctor = new Account();
$doctorDetails = $doctor->get_doctor_info_2($doctor_id);
?>

<!DOCTYPE html>
<html lang="en">
<?php
$title = 'All Doctors';
$doctors = 'active';
include '../includes/head.php';
?>

<body>
    <?php require_once('../includes/header.php'); ?>

    <!-- Page Header -->
    <section class="page-container page-header padding-medium bg-light">
        <div class="container">
            <div class="row py-3 align-items-center">
                <div class="col-md-6">
                    <h1 class="text-green mb-3">Our Medical Team</h1>
                    <p class="lead">Browse our complete list of healthcare professionals ready to serve you.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="doctors.php" class="btn btn-outline-secondary hover-light">Back to Featured Doctors</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Search and Filter Section -->
    <section class="search-filter py-4 bg-light border-bottom">
        <div class="container">
            <div class="row">
                <div class="col-md-8 mb-3 mb-md-0">
                    <div class="input-group">
                        <input type="text" class="form-control" id="doctorSearch" placeholder="Search by name or specialty...">
                        <button class="btn btn-primary" type="button" id="searchButton">
                            <i class="bi bi-search"></i> Search
                        </button>
                    </div>
                </div>
                <div class="col-md-4">
                    <select class="form-select" id="specialtyFilter">
                        <option value="">All Specialties</option>
                        <option value="Cardiology">Cardiology</option>
                        <option value="Dermatology">Dermatology</option>
                        <option value="Pediatrics">Pediatrics</option>
                        <option value="General Medicine">General Medicine</option>
                        <option value="Mental Health">Mental Health</option>
                        <option value="Dentistry">Dentistry</option>
                    </select>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Doctors Listing -->
    <section class="doctors-listing padding-medium py-5">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <h3 class="text-primary">Available Doctors</h3>
                    <p class="text-muted" id="doctorCount">Showing 6 doctors</p>
                </div>
            </div>

            <div class="row" id="doctorsContainer">
                <!-- Doctor Card 1 -->
                <div class="col-lg-4 col-md-6 mb-4 doctor-card" data-specialty="Cardiology">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-header bg-white border-0 pt-3">
                            <div class="doctor-image text-center">
                                <img src="../assets/images/default_profile.png" class="rounded-circle img-thumbnail" alt="Dr. John Smith" style="width: 150px; height: 150px; object-fit: cover;">
                            </div>
                        </div>
                        <div class="card-body text-center">
                            <h5 class="card-title text-green mb-1">Dr. John Smith</h5>
                            <p class="text-primary mb-2">Cardiology</p>
                            <p class="text-muted small mb-3">
                                <i class="bi bi-calendar-week"></i> Monday to Friday<br>
                                <i class="bi bi-clock"></i> 08:00 AM - 05:00 PM
                            </p>
                            <p class="card-text doctor-bio">Specialized in heart-related conditions with over 10 years of experience in cardiovascular treatments and preventive care.</p>
                        </div>
                        <div class="card-footer bg-white border-0 pb-3">
                            <div class="d-flex justify-content-center">
                                <a href="appointment.php" class="btn btn-sm btn-primary me-2">Book Appointment</a>
                                <a href="chat_user.php" class="btn btn-sm btn-success">Chat Now</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Doctor Card 2 -->
                <div class="col-lg-4 col-md-6 mb-4 doctor-card" data-specialty="Dermatology">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-header bg-white border-0 pt-3">
                            <div class="doctor-image text-center">
                                <img src="../assets/images/default_profile.png" class="rounded-circle img-thumbnail" alt="Dr. Sarah Johnson" style="width: 150px; height: 150px; object-fit: cover;">
                            </div>
                        </div>
                        <div class="card-body text-center">
                            <h5 class="card-title text-green mb-1">Dr. Sarah Johnson</h5>
                            <p class="text-primary mb-2">Dermatology</p>
                            <p class="text-muted small mb-3">
                                <i class="bi bi-calendar-week"></i> Tuesday to Saturday<br>
                                <i class="bi bi-clock"></i> 09:00 AM - 06:00 PM
                            </p>
                            <p class="card-text doctor-bio">Skin specialist focusing on acne treatment, eczema, psoriasis, and cosmetic dermatology procedures.</p>
                        </div>
                        <div class="card-footer bg-white border-0 pb-3">
                            <div class="d-flex justify-content-center">
                                <a href="appointment.php" class="btn btn-sm btn-primary me-2">Book Appointment</a>
                                <a href="chat_user.php" class="btn btn-sm btn-success">Chat Now</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Doctor Card 3 -->
                <div class="col-lg-4 col-md-6 mb-4 doctor-card" data-specialty="Pediatrics">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-header bg-white border-0 pt-3">
                            <div class="doctor-image text-center">
                                <img src="../assets/images/default_profile.png" class="rounded-circle img-thumbnail" alt="Dr. Michael Brown" style="width: 150px; height: 150px; object-fit: cover;">
                            </div>
                        </div>
                        <div class="card-body text-center">
                            <h5 class="card-title text-green mb-1">Dr. Michael Brown</h5>
                            <p class="text-primary mb-2">Pediatrics</p>
                            <p class="text-muted small mb-3">
                                <i class="bi bi-calendar-week"></i> Monday to Thursday<br>
                                <i class="bi bi-clock"></i> 08:30 AM - 04:30 PM
                            </p>
                            <p class="card-text doctor-bio">Pediatrician specializing in newborn care, childhood vaccinations, and treatment of common childhood illnesses.</p>
                        </div>
                        <div class="card-footer bg-white border-0 pb-3">
                            <div class="d-flex justify-content-center">
                                <a href="appointment.php" class="btn btn-sm btn-primary me-2">Book Appointment</a>
                                <a href="chat_user.php" class="btn btn-sm btn-success">Chat Now</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Doctor Card 4 -->
                <div class="col-lg-4 col-md-6 mb-4 doctor-card" data-specialty="General Medicine">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-header bg-white border-0 pt-3">
                            <div class="doctor-image text-center">
                                <img src="../assets/images/default_profile.png" class="rounded-circle img-thumbnail" alt="Dr. Emily Wilson" style="width: 150px; height: 150px; object-fit: cover;">
                            </div>
                        </div>
                        <div class="card-body text-center">
                            <h5 class="card-title text-green mb-1">Dr. Emily Wilson</h5>
                            <p class="text-primary mb-2">General Medicine</p>
                            <p class="text-muted small mb-3">
                                <i class="bi bi-calendar-week"></i> Monday to Friday<br>
                                <i class="bi bi-clock"></i> 08:00 AM - 05:00 PM
                            </p>
                            <p class="card-text doctor-bio">Primary care physician with expertise in diagnosing and treating a wide range of adult health conditions.</p>
                        </div>
                        <div class="card-footer bg-white border-0 pb-3">
                            <div class="d-flex justify-content-center">
                                <a href="appointment.php" class="btn btn-sm btn-primary me-2">Book Appointment</a>
                                <a href="chat_user.php" class="btn btn-sm btn-success">Chat Now</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Doctor Card 5 -->
                <div class="col-lg-4 col-md-6 mb-4 doctor-card" data-specialty="Mental Health">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-header bg-white border-0 pt-3">
                            <div class="doctor-image text-center">
                                <img src="../assets/images/default_profile.png" class="rounded-circle img-thumbnail" alt="Dr. David Lee" style="width: 150px; height: 150px; object-fit: cover;">
                            </div>
                        </div>
                        <div class="card-body text-center">
                            <h5 class="card-title text-green mb-1">Dr. David Lee</h5>
                            <p class="text-primary mb-2">Mental Health</p>
                            <p class="text-muted small mb-3">
                                <i class="bi bi-calendar-week"></i> Wednesday to Sunday<br>
                                <i class="bi bi-clock"></i> 10:00 AM - 07:00 PM
                            </p>
                            <p class="card-text doctor-bio">Psychiatrist specializing in anxiety disorders, depression, and stress management techniques.</p>
                        </div>
                        <div class="card-footer bg-white border-0 pb-3">
                            <div class="d-flex justify-content-center">
                                <a href="appointment.php" class="btn btn-sm btn-primary me-2">Book Appointment</a>
                                <a href="chat_user.php" class="btn btn-sm btn-success">Chat Now</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Doctor Card 6 -->
                <div class="col-lg-4 col-md-6 mb-4 doctor-card" data-specialty="Dentistry">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-header bg-white border-0 pt-3">
                            <div class="doctor-image text-center">
                                <img src="../assets/images/default_profile.png" class="rounded-circle img-thumbnail" alt="Dr. Jessica Garcia" style="width: 150px; height: 150px; object-fit: cover;">
                            </div>
                        </div>
                        <div class="card-body text-center">
                            <h5 class="card-title text-green mb-1">Dr. Jessica Garcia</h5>
                            <p class="text-primary mb-2">Dentistry</p>
                            <p class="text-muted small mb-3">
                                <i class="bi bi-calendar-week"></i> Monday to Saturday<br>
                                <i class="bi bi-clock"></i> 09:00 AM - 06:00 PM
                            </p>
                            <p class="card-text doctor-bio">Dental surgeon specializing in cosmetic dentistry, orthodontics, and preventive oral care.</p>
                        </div>
                        <div class="card-footer bg-white border-0 pb-3">
                            <div class="d-flex justify-content-center">
                                <a href="appointment.php" class="btn btn-sm btn-primary me-2">Book Appointment</a>
                                <a href="chat_user.php" class="btn btn-sm btn-success text-light">Chat Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php require_once('../includes/footer.php'); ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const doctorCards = document.querySelectorAll('.doctor-card');
            document.getElementById('doctorCount').textContent = `Showing ${doctorCards.length} doctors`;

            document.getElementById('searchButton').addEventListener('click', filterDoctors);
            document.getElementById('doctorSearch').addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    filterDoctors();
                }
            });

            document.getElementById('specialtyFilter').addEventListener('change', filterDoctors);

            function filterDoctors() {
                const searchTerm = document.getElementById('doctorSearch').value.toLowerCase();
                const specialtyFilter = document.getElementById('specialtyFilter').value.toLowerCase();
                let visibleCount = 0;

                doctorCards.forEach(card => {
                    const name = card.querySelector('.card-title').textContent.toLowerCase();
                    const specialty = card.getAttribute('data-specialty').toLowerCase();
                    const bio = card.querySelector('.doctor-bio').textContent.toLowerCase();

                    const matchesSearch = name.includes(searchTerm) || specialty.includes(searchTerm) || bio.includes(searchTerm);
                    const matchesSpecialty = !specialtyFilter || specialty === specialtyFilter;

                    if (matchesSearch && matchesSpecialty) {
                        card.style.display = 'block';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                document.getElementById('doctorCount').textContent = `Showing ${visibleCount} doctors`;
            }
        });
    </script>
</body>

</html>