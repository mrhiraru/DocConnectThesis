<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
  header('location: ../user/verification.php');
}

require_once('../classes/aboutUs.class.php');

$aboutUs = new AboutUs();
$currentAboutUs = $aboutUs->getAboutUs();

?>

<!DOCTYPE html>
<html lang="en">
<?php
$title = 'About Us';
$about = 'active';
include '../includes/head.php';
?>

<body>
  <?php
  require_once('../includes/header.php');
  ?>

  <style>
    .about-header {
      background: url('../assets/images/bg-1.png') no-repeat center center/cover;
      color: white;
      padding: 125px 0;
      text-align: center;
    }

    .about-header h1 {
      font-size: 75px;
    }

    .icon-circle {
      width: 70px;
      height: 70px;
      display: flex;
      justify-content: center;
      align-items: center;
      border-radius: 50%;
    }
  </style>

  <!-- Header Section -->
  <section class="about-header mt-5">
    <h1>About Us</h1>
  </section>

  <section class="about-section px-3 px-md-5 mx-md-4">
    <div class="row my-5">
      <div class="col-12 col-md-8">
        <h2 class="text-green"><?= htmlspecialchars($currentAboutUs['heading'] ?? 'Your Health, Anytime, Anywhere') ?></h2>
        <hr class="my-3 c-red rounded-5" style="height: 5px;">
        <p><?= htmlspecialchars($currentAboutUs['subtext'] ?? 'Welcome to University Telecommunications Health Services! We are dedicated to enhancing student well-being through innovative, remote health solutions that ensure accessibility, privacy, and high-quality care.') ?></p>
        <div class="row row-cols-1 row-cols-md-2">
          <div class="col">
            <h3>Our Vision:</h3>
            <ul class="list-unstyled">
              <?php foreach (json_decode($currentAboutUs['visions'] ?? '[]') as $vision): ?>
                <li class="d-flex align-items-baseline mb-2">
                  <i class='bx bxs-check-circle text-green me-2'></i>
                  <p class="m-0"><?= htmlspecialchars($vision) ?></p>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
          <div class="col">
            <h3>Our Mission:</h3>
            <ul class="list-unstyled">
              <?php foreach (json_decode($currentAboutUs['missions'] ?? '[]') as $mission): ?>
                <li class="d-flex align-items-baseline mb-2">
                  <i class='bx bxs-check-circle text-green me-2'></i>
                  <p class="m-0"><?= htmlspecialchars($mission) ?></p>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      </div>

      <div class="col-4 d-none d-md-block">
        <div class="h-100 rounded-2 d-flex align-items-center justify-content-center overflow-hidden">
          <img src="<?= $currentAboutUs['image_path'] ?? '../assets/images/bg-1.png' ?>" alt="Image" class="img-fluid" style="height: 100%; width: 100%; object-fit: cover;">
        </div>
      </div>
    </div>

    <div class="mb-5">
      <h2 class="text-green"><?= htmlspecialchars($currentAboutUs['technology_heading'] ?? 'Technology and Innovation') ?></h2>
      <hr class="my-3 c-red rounded-5" style="height: 5px;">
      <p><?= htmlspecialchars($currentAboutUs['technology_subtext'] ?? 'Using cutting-edge telecommunication tools, we ensure secure, reliable, and seamless interactions between students and healthcare providers.') ?></p>
    </div>

    <div class="container mt-5">
      <div class="row text-center">
        <?php
        $technology_icons = json_decode($currentAboutUs['technology_icons'] ?? '[]');
        $technology_titles = json_decode($currentAboutUs['technology_titles'] ?? '[]');
        $technology_descriptions = json_decode($currentAboutUs['technology_descriptions'] ?? '[]');

        for ($i = 0; $i < 4; $i++): ?>
          <div class="col-md-3 mb-4 d-flex align-items-stretch">
            <div class="p-4 shadow-sm rounded-3 border bg-light w-100">
              <div class="icon-circle bg-primary text-white mx-auto mb-3">
                <i class='<?= htmlspecialchars($technology_icons[$i] ?? '') ?> display-6'></i>
              </div>
              <h5 class="fw-bold text-green"><?= htmlspecialchars($technology_titles[$i] ?? '') ?></h5>
              <p class="text-muted small"><?= htmlspecialchars($technology_descriptions[$i] ?? '') ?></p>
            </div>
          </div>
        <?php endfor; ?>
      </div>
    </div>

    <div class="mb-5 text-center">
      <h2 class="text-green">Discover More</h2>
      <hr class="my-3 c-red rounded-5" style="height: 5px;">
      <p>Discover how we can support your health journey. Schedule a virtual consultation or learn more about our services today!</p>
      <a href="./appointment.php" class="btn btn-primary text-light px-4 py-2 mt-3">Schedule a Consultation</a>
    </div>
  </section>

  <?php
  require_once('../includes/footer.php');
  ?>
</body>

</html>