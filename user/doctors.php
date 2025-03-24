<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
  header('location: ../user/verification.php');
}

require_once('../tools/functions.php');
require_once('../classes/account.class.php');
?>

<!DOCTYPE html>
<html lang="en">
<?php
$title = 'Our Doctors';
$doctors = 'active';
include '../includes/head.php';
?>

<body>
  <?php require_once('../includes/header.php'); ?>

  <!-- Introduction Section -->
  <section class="page-container padding-medium pt-3 p-3">
    <div class="border-primary border-bottom text-center mx-4 mb-3">
      <h1 class="text-green">Our Doctors</h1>
      <p class="fs-5 fw-light">
        At Western Mindanao State University, our telehealth platform combines cutting-edge technology
        with compassionate care. Our team of dedicated doctors is here to serve not just our university
        community but also the broader Zamboanga Peninsula, fostering a culture of wellness and health
        awareness. Whether you need routine care, specialized advice, or preventive consultation,
        we are committed to delivering accessible and high-quality healthcare tailored to your needs.
      </p>
    </div>
  </section>

  <!-- Doctors Carousel -->
  <?php
  $doctor = new Account();
  $allDoctors = $doctor->show_doc();

  $today = date('Ymd');

  if (!empty($allDoctors)) {
    mt_srand($today);
    shuffle($allDoctors);

    $doctorArray = array_slice($allDoctors, 0, 5);
  } else {
    $doctorArray = array();
  }
  ?>
  <section id="carousel">
    <div id="doctorsCarousel" class="carousel carousel-dark slide" data-bs-ride="carousel" data-bs-touch="true">
      <div class="carousel-indicators">
        <?php
        if (!empty($doctorArray)) {
          foreach ($doctorArray as $index => $item) {
            $activeClass = ($index === 0) ? 'active' : '';
        ?>
            <button type="button" data-bs-target="#doctorsCarousel" data-bs-slide-to="<?= $index ?>" class="<?= $activeClass ?>" aria-label="Slide <?= $index + 1 ?>"></button>
        <?php
          }
        }
        ?>
      </div>
      <div class="carousel-inner">
        <?php
        if (!empty($doctorArray)) {
          foreach ($doctorArray as $index => $item) {
            $activeClass = ($index === 0) ? 'active' : '';
        ?>
            <div class="carousel-item <?= $activeClass ?>" data-bs-interval="5000">
              <div class="row mx-5 mb-4 align-items-stretch">
                <div class="col-12 col-lg-5 mb-3 mb-lg-0">
                  <div class="profile-card h-100 me-4">
                    <div class="profile-image">
                      <img src="<?php if (isset($item['account_image'])) {
                                  echo "../assets/images/" . $item['account_image'];
                                } else {
                                  echo "../assets/images/default_profile.png";
                                } ?>" alt="...">
                    </div>
                  </div>
                </div>
                <div class="col-12 col-lg-7">
                  <div class="details h-100">
                    <h4 class="text-green mb-3 fs-2 text-center text-lg-start"><?= (isset($item['middlename'])) ? ucwords(strtolower($item['firstname'] . ' ' . $item['middlename'] . ' ' . $item['lastname'])) : ucwords(strtolower($item['firstname'] . ' ' . $item['lastname'])); ?></h4>
                    <div class="d-flex flex-column">
                      <div class="row mb-3 align-items-stretch">
                        <div class="col-12 col-md-6 mb-3 mb-md-0">
                          <div class="card px-4 py-2 bg-light shadow-lg h-100">
                            <h6 class="text-primary">Medical Specialty:</h6>
                            <p class="fw-light"><?= $item['specialty'] ?></p>
                          </div>
                        </div>
                        <div class="col-12 col-md-6">
                          <div class="card px-4 py-2 bg-light shadow-lg h-100">
                            <h6 class="text-primary">Working Schedule:</h6>
                            <p class="fw-light"><?= $item['start_day'] . ' to ' . $item['end_day'] . ", " . date('h:i A', strtotime($item['start_wt'])) . ' - ' . date('h:i A', strtotime($item['end_wt'])) ?></p>
                          </div>
                        </div>
                      </div>
                      <div class="card px-4 py-2 bg-light shadow-lg mb-3">
                        <h6 class="text-primary">About:</h6>
                        <p class="fw-light fs-6">
                          <?= $item['bio'] ?>
                        </p>
                      </div>
                      <div class="d-flex justify-content-end">
                        <a href="./appointment.php" class="btn btn-primary text-light me-2">Book an Appointment</a>
                        <a href="./chat_user?account_id=<?= $item['account_id'] ?>" class="btn btn-success text-light">Chat Me</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        <?php
          }
        }
        ?>

        <button class="carousel-control-prev" type="button" data-bs-target="#doctorsCarousel" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#doctorsCarousel" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>
  </section>

  <section class="specializations padding-medium py-3 text-center bg-light">
    <h2 class="text-green">Our Specializations</h2>
    <p class="fs-5 fw-light">Our team of expert doctors provides care in the following areas:</p>
    <div class="container py-2">
      <div class="row text-center">
        <div class="col-md-4 mb-4">
          <div class="card border-1 shadow-sm h-100">
            <div class="card-body">
              <h5 class="card-title text-primary">General Medicine</h5>
              <p class="card-text">
                We provide comprehensive primary care to address a wide range of health concerns, ensuring the overall well-being of our diverse university community.
              </p>
            </div>
          </div>
        </div>

        <div class="col-md-4 mb-4">
          <div class="card border-1 shadow-sm h-100">
            <div class="card-body">
              <h5 class="card-title text-primary">Mental Health</h5>
              <p class="card-text">
                We support mental wellness to help students and staff manage stress, thrive academically, and foster a healthier, more supportive campus environment.
              </p>
            </div>
          </div>
        </div>

        <div class="col-md-4 mb-4">
          <div class="card border-1 shadow-sm h-100">
            <div class="card-body">
              <h5 class="card-title text-primary">Dentistry</h5>
              <p class="card-text">
                We promote good oral health through preventive and restorative care, helping everyone maintain confident smiles and overall well-being.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="telehealth padding-medium py-5 text-center">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 mb-4 mb-lg-0">
          <img src="../assets/images/doctors_telehealth.png" alt="Telehealth Illustration" class="img-fluid rounded shadow">
        </div>
        <div class="col-lg-6 d-flex flex-column justify-content-center">
          <h2 class="text-green fw-bold mb-3">Our Telehealth Advantage</h2>
          <p class="fs-5 fw-light mb-3">
            Leveraging cutting-edge technology, we offer secure and accessible teleconsultations that bring quality care to the comfort of your home.
          </p>
          <blockquote class="blockquote bg-white p-3 rounded shadow">
            <p class="mb-3">"The doctors were so attentive and helpful. Telehealth made it easy to consult from home."</p>
            <footer class="blockquote-footer text-end">Satisfied User</footer>
          </blockquote>
        </div>
      </div>
    </div>
  </section>

  <!-- Community Impact -->
  <section class="community-impact padding-medium py-5 text-center bg-light">
    <div class="container">
      <h2 class="text-green fw-bold mb-4">Making an Impact</h2>
      <div class="row">
        <div class="col-md-6 d-flex flex-column justify-content-center">
          <p class="fs-5 fw-light">
            Our doctors are committed to giving back through outreach programs, health education seminars, and volunteer initiatives that promote wellness across Zamboanga Peninsula.
          </p>
        </div>
        <div class="col-md-6">
          <img src="../assets/images/doctors_community-impact.png" alt="Community Impact" class="img-fluid rounded shadow">
        </div>
      </div>
    </div>
  </section>

  <!-- Accessibility Note -->
  <section class="accessibility padding-medium py-5 text-center">
    <div class="container">
      <h2 class="text-green fw-bold mb-4">Accessible to All</h2>
      <p class="fs-5 fw-light mb-4">
        We strive to ensure that everyone can access our services, regardless of ability. Alternative formats and tools are available for users with disabilities.
      </p>
      <img src="../assets/images/doctors_accessibility.png" alt="Accessibility for All" class="img-fluid rounded shadow">
    </div>
  </section>

  <?php require_once('../includes/footer.php'); ?>

  <script>
    const carousel = document.querySelector('#doctorsCarousel');
    let startX = 0;

    carousel.addEventListener('touchstart', (e) => {
      startX = e.touches[0].clientX;
    });

    carousel.addEventListener('touchend', (e) => {
      const endX = e.changedTouches[0].clientX;
      if (endX < startX - 50) {
        const nextButton = carousel.querySelector('.carousel-control-next');
        nextButton.click();
      } else if (endX > startX + 50) {
        const prevButton = carousel.querySelector('.carousel-control-prev');
        prevButton.click();
      }
    });
  </script>
</body>

</html>