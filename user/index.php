<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
  header('location: ../user/verification.php');
}

require_once('../tools/functions.php');
require_once('../classes/account.class.php');
require_once('../classes/homePage.class.php');

$homePageContent = new HomePageContent();

$mainContent = $homePageContent->getSectionContent('main');
$featuresContent = $homePageContent->getSectionContent('features');
$servicesContent = $homePageContent->getSectionContent('services');
$telemedicineContent = $homePageContent->getSectionContent('telemedicine');
$aboutUsContent = $homePageContent->getSectionContent('about_us');

$features = $homePageContent->getFeatures();
$services = $homePageContent->getServices();
$telemedicineItems = $homePageContent->getTelemedicineItems();

$keyPoints = explode("\n", $aboutUsContent['content']);
?>

<!DOCTYPE html>
<html lang="en">
<?php
$title = 'DocConnect';
$home = 'active';
include '../includes/head.php';
?>

<body>
  <?php
  require_once('../includes/header.php');
  ?>
  <section class="main">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-6 pe-3 pe-md-5 mt-5 mt-md-0">
          <h2 class="display-1 text-uppercase text-light text-center text-md-start"><?php echo htmlspecialchars($mainContent['title']); ?></h2>
          <p class="fs-4 my-4 pb-2 text-white text-center text-md-start"><?php echo htmlspecialchars($mainContent['subtitle']); ?></p>
          <div class="d-flex justify-content-center justify-content-md-start">
            <a class="btn btn-outline-light fs-5 text-capitalize me-3" href="./appointment">
              Book an Appointment
            </a>
            <a class="btn btn-outline-light fs-5 text-capitalize" href="./chat_user?chatbot=true">
              chat with our Bot
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="features" class="mt-xl-5 mx-2 mx-mg-5">
    <div class="p-3 pb-md-4 mx-auto text-center">
      <p class="fs-5 text-muted text-uppercase mx-5"><?php echo htmlspecialchars($featuresContent['subtitle']); ?></p>
      <h1 class="fs-3 fw-normal"><?php echo htmlspecialchars($featuresContent['title']); ?></h1>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4">
      <?php foreach ($features as $feature) { ?>
        <div class="col p-0 mb-3">
          <div class="card mx-3 mb-sm-3 rounded-3 shadow-sm h-100">
            <div class="card-body d-flex flex-column justify-content-between shadow-sm">
              <div class="align-content-center text-center">
                <i class='bx <?php echo htmlspecialchars($feature['icon']); ?> p-3 mb-3 border-green text-green rounded-circle shadow-sm fs-3'></i>
                <h4 class="card-title pricing-card-title"><?php echo htmlspecialchars($feature['title']); ?></h4>
                <p class="fs-6 text-muted"><?php echo htmlspecialchars($feature['description']); ?></p>
              </div>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
  </section>

  <section id="services" class="padding-medium mt-xl-5 py-2">
    <div class="container mb-4 pt-2">
      <div class="p-3 pb-md-4 mx-auto text-center">
        <p class="fs-5 text-muted text-uppercase mx-5"><?php echo htmlspecialchars($servicesContent['subtitle']); ?></p>
        <h1 class="display-6 fw-normal"><?php echo htmlspecialchars($servicesContent['title']); ?></h1>
      </div>

      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
        <?php foreach ($services as $service) { ?>
          <div class="col mb-4">
            <div class="d-flex flex-column h-100">
              <img src="<?php echo htmlspecialchars($service['image_path']); ?>" alt="<?php echo htmlspecialchars($service['title']); ?>" class="rounded-2 shadow-lg w-100 object-fit-cover" style="height: 250px;">
              <div class="card mx-3 mb-sm-3 rounded-3 shadow-sm flex-grow-1">
                <div class="card-body d-flex flex-column justify-content-between">
                  <div>
                    <h3 class="card-title pricing-card-title"><?php echo htmlspecialchars($service['title']); ?></h3>
                    <p class="fs-6 text-muted">
                      <?php echo htmlspecialchars($service['description']); ?>
                    </p>
                  </div>
                  <a href="./services#one" class="w-100 btn btn-outline-primary hover-light">Read More</a>
                </div>
              </div>
            </div>
          </div>
        <?php } ?>
      </div>
    </div>
  </section>

  <section id="info" class="padding-medium mt-xl-5 mx-2 mx-mg-5">
    <div class="p-3 pb-md-4 mx-auto text-center">
      <h1 class="display-4 fw-normal"><?php echo htmlspecialchars($telemedicineContent['title']); ?></h1>
      <p class="fs-5 text-muted"><?php echo htmlspecialchars($telemedicineContent['subtitle']); ?></p>
    </div>

    <div class="row mb-3 d-flex justify-content-center">
      <div class="col-10">
        <div class="row row-cols-1 row-cols-md-2">
          <?php foreach ($telemedicineItems as $item) { ?>
            <div class="col mb-4">
              <div class="border border-success shadow-sm rounded-2 p-3 h-100 d-flex flex-column">
                <div class="row">
                  <div class="col-12 col-lg-4 mb-3 mb-lg-0 d-flex align-items-start justify-content-center">
                    <i class='bx <?php echo htmlspecialchars($item['icon']); ?> p-3 bg-green text-white rounded-1 fs-3'></i>
                  </div>
                  <div class="col-12 col-lg-8">
                    <h3 class="fw-normal text-center"><?php echo htmlspecialchars($item['title']); ?></h3>
                    <p class="fs-6 text-muted text-center"><?php echo htmlspecialchars($item['description']); ?></p>
                  </div>
                </div>
              </div>
            </div>
          <?php } ?>
        </div>
      </div>
    </div>
    <div class="mb-5 text-center">
      <hr class="my-3 c-red rounded-5" style="height: 5px;">
      <p><?php echo htmlspecialchars($telemedicineContent['content']); ?></p>
      <a href="./appointment" class="btn btn-primary text-light px-4 py-2 mt-3">Schedule a Appointment</a>
    </div>
  </section>

  <section id="about" class="padding-medium mt-xl-5 py-2">
    <div class="container mb-4 pt-2">
      <?php
      $aboutUsContent = $homePageContent->getAboutUsContent();
      $keyPoints = explode("\n", $aboutUsContent['key_points'] ?? '');
      ?>
      <div class="row align-items-stretch justify-content-center mt-xl-5">
        <div class="col-md-6 mt-3 my-md-5 px-4 me-0 m-md-4 px-lg-0 d-flex flex-column">
          <div class="mb-3">
            <p class="text-secondary"><?= htmlspecialchars($aboutUsContent['subtitle'] ?? '') ?></p>
            <h2 class="display-6 fw-semibold"><?= htmlspecialchars($aboutUsContent['title'] ?? '') ?></h2>
          </div>
          <p>
            <?= htmlspecialchars($aboutUsContent['description'] ?? '') ?>
          </p>
          <?php
          foreach ($keyPoints as $point) {
            if (!empty(trim($point))) {
              echo '<div class="d-flex align-items-center mb-4">
                            <i class="bx bxs-check-circle c-red"></i>
                            <p class="ps-3 m-0">' . htmlspecialchars($point) . '</p>
                        </div>';
            }
          }
          ?>
          <a href="./about_us" class="btn bg-green px-3 py-1 mt-4 link-light button" style="width: fit-content;">Learn more</a>
        </div>
        <div class="col-md-4 d-flex align-items-stretch">
          <img src="<?= htmlspecialchars($aboutUsContent['image_path'] ?? '') ?>" alt="About Us" class="img-thumbnail img-fluid rounded-3 shadow-lg w-100" style="object-fit: cover;">
        </div>
      </div>
    </div>
  </section>

  <?php
  require_once('../includes/footer.php');
  ?>

</body>

</html>