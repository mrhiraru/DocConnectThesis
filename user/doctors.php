<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
  header('location: ../user/verification.php');
  exit();
}

require_once('../tools/functions.php');
require_once('../classes/account.class.php');
require_once('../classes/doctorsPage.class.php');

$doctor = new Account();
$doctorsPage = new DoctorsPage();

$introContent = $doctorsPage->getSectionContent('intro');
$specializationsContent = $doctorsPage->getSectionContent('specializations');
$telehealthContent = $doctorsPage->getSectionContent('telehealth');
$communityContent = $doctorsPage->getSectionContent('community');
$accessibilityContent = $doctorsPage->getSectionContent('accessibility');

$allDoctors = $doctor->show_doc();
$today = date('Ymd');
mt_srand($today);
shuffle($allDoctors);
$doctorArray = array_slice($allDoctors, 0, 5);
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
      <h1 class="text-green"><?php echo htmlspecialchars($introContent['title']); ?></h1>
      <p class="fs-5 fw-light">
        <?php echo htmlspecialchars($introContent['content']); ?>
      </p>
    </div>
  </section>

  <!-- Doctors Carousel -->
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
      </div>
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

  <section class="text-center py-3">
    <a href="doctorsList.php" class="btn btn-lg btn-outline-primary hover-light p-2">View All Doctors</a>
  </section>

  <section class="specializations padding-medium py-3 text-center bg-light">
    <h2 class="text-green"><?php echo htmlspecialchars($specializationsContent['title']); ?></h2>
    <p class="fs-5 fw-light"><?php echo htmlspecialchars($specializationsContent['content']); ?></p>
    <div class="container py-2">
      <div class="row text-center">
        <div class="col-md-4 mb-4">
          <div class="card border-1 shadow-sm h-100">
            <div class="card-body">
              <h5 class="card-title text-primary"><?php echo htmlspecialchars($specializationsContent['spec1_title']); ?></h5>
              <p class="card-text">
                <?php echo htmlspecialchars($specializationsContent['spec1_content']); ?>
              </p>
            </div>
          </div>
        </div>

        <div class="col-md-4 mb-4">
          <div class="card border-1 shadow-sm h-100">
            <div class="card-body">
              <h5 class="card-title text-primary"><?php echo htmlspecialchars($specializationsContent['spec2_title']); ?></h5>
              <p class="card-text">
                <?php echo htmlspecialchars($specializationsContent['spec2_content']); ?>
              </p>
            </div>
          </div>
        </div>

        <div class="col-md-4 mb-4">
          <div class="card border-1 shadow-sm h-100">
            <div class="card-body">
              <h5 class="card-title text-primary"><?php echo htmlspecialchars($specializationsContent['spec3_title']); ?></h5>
              <p class="card-text">
                <?php echo htmlspecialchars($specializationsContent['spec3_content']); ?>
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
          <img src="../assets/images/<?php echo htmlspecialchars($telehealthContent['image_path']); ?>" alt="Telehealth Illustration" class="img-fluid rounded shadow">
        </div>
        <div class="col-lg-6 d-flex flex-column justify-content-center">
          <h2 class="text-green fw-bold mb-3"><?php echo htmlspecialchars($telehealthContent['title']); ?></h2>
          <p class="fs-5 fw-light mb-3">
            <?php echo htmlspecialchars($telehealthContent['content']); ?>
          </p>
          <?php if (!empty($telehealthContent['quote'])): ?>
            <!-- <blockquote class="blockquote bg-white p-3 rounded shadow">
              <p class="mb-3">"<?php echo htmlspecialchars($telehealthContent['quote']); ?>"</p>
              <footer class="blockquote-footer text-end"><?php echo htmlspecialchars($telehealthContent['quote_author']); ?></footer>
            </blockquote> -->
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>

  <!-- Community Impact -->
  <section class="community-impact padding-medium py-5 text-center bg-light">
    <div class="container">
      <h2 class="text-green fw-bold mb-4"><?php echo htmlspecialchars($communityContent['title']); ?></h2>
      <div class="row">
        <div class="col-md-6 d-flex flex-column justify-content-center">
          <p class="fs-5 fw-light">
            <?php echo htmlspecialchars($communityContent['content']); ?>
          </p>
        </div>
        <div class="col-md-6">
          <img src="../assets/images/<?php echo htmlspecialchars($communityContent['image_path']); ?>" alt="Community Impact" class="img-fluid rounded shadow">
        </div>
      </div>
    </div>
  </section>

  <!-- Accessibility Note -->
  <section class="accessibility padding-medium py-5 text-center">
    <div class="container">
      <h2 class="text-green fw-bold mb-4"><?php echo htmlspecialchars($accessibilityContent['title']); ?></h2>
      <p class="fs-5 fw-light mb-4">
        <?php echo htmlspecialchars($accessibilityContent['content']); ?>
      </p>
      <img src="../assets/images/<?php echo htmlspecialchars($accessibilityContent['image_path']); ?>" alt="Accessibility for All" class="img-fluid rounded shadow">
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