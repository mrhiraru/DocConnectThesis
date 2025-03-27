<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
    header('location: ../user/verification.php');
}

require_once('../tools/functions.php');
require_once('../classes/account.class.php');

$doctor = new Account();
$allDoctors = $doctor->get_doctor();
$availableSpecialties = $doctor->get_available_specialties();
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
                        <button class="btn btn-primary text-light" type="button" id="searchButton">
                            <i class='bx bx-search'></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-4">
                    <select class="form-select" id="specialtyFilter">
                        <option value="">All Specialties</option>
                        <?php foreach ($availableSpecialties as $specialty): ?>
                            <option value="<?= htmlspecialchars($specialty['specialty']) ?>">
                                <?= htmlspecialchars($specialty['specialty']) ?>
                            </option>
                        <?php endforeach; ?>
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
                    <p class="text-muted" id="doctorCount">Showing <?= count($allDoctors) ?> doctors</p>
                </div>
            </div>

            <div class="row" id="doctorsContainer">
                <?php foreach ($allDoctors as $doctor): ?>
                    <div class="col-lg-4 col-md-6 mb-4 doctor-card" data-specialty="<?= htmlspecialchars($doctor['specialty']) ?>">
                        <div class="card h-100 shadow-sm border-1">
                            <a href="doctorsView.php?id=<?= $doctor['account_id'] ?>" class="text-decoration-none">
                                <div class="card-header bg-white border-0 pt-3">
                                    <div class="doctor-image text-center">
                                        <img src="<?php if (isset($doctor['account_image'])) {
                                                        echo "../assets/images/" . $doctor['account_image'];
                                                    } else {
                                                        echo "../assets/images/default_profile.png";
                                                    } ?>" alt="Doctor Profile Image" class="img-fluid rounded me-3 shadow" height="150" width="150">
                                    </div>
                                </div>
                                <div class="card-body text-center">
                                    <h5 class="card-title text-green mb-1"><?= htmlspecialchars($doctor['doctor_name']) ?></h5>
                                    <p class="text-primary mb-2"><?= htmlspecialchars($doctor['specialty']) ?></p>
                                    <p class="text-muted small mb-3">
                                        <i class="bi bi-calendar-week"></i> <?= htmlspecialchars($doctor['start_day']) ?> to <?= htmlspecialchars($doctor['end_day']) ?><br>
                                        <i class="bi bi-clock"></i> <?= date('h:i A', strtotime($doctor['start_wt'])) ?> - <?= date('h:i A', strtotime($doctor['end_wt'])) ?>
                                    </p>
                                </div>
                                <div class="card-footer bg-white border-0 pb-3">
                                    <div class="d-flex justify-content-center">
                                        <a href="appointment.php?doctor_id=<?= $doctor['account_id'] ?>" class="btn btn-sm btn-primary me-2 text-light">Book Appointment</a>
                                        <a href="chat_user.php?account_id=<?= $doctor['account_id'] ?>" class="btn btn-sm btn-success text-light">Chat Now</a>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
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