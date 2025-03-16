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
$title = 'DocConnect';
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
          <h2 class="display-1 text-uppercase text-light text-center text-md-start">Connectivity that Heals</h2>
          <p class="fs-4 my-4 pb-2 text-white text-center text-md-start">Revolutionizing Healthcare Through Telecommunications</p>
          <div class="d-flex justify-content-center justify-content-md-start">
            <a class="btn btn-outline-light fs-5 text-capitalize me-3" href="./appointment">
              Book an Appointment
            </a>
            <a class="btn btn-outline-light fs-5 text-capitalize" href="./chat_user?chatbot=true">
              chat with our Bot
            </a>
          </div>
        </div>
        <!-- <div class="img col-md-6 mt-5">
          <img src="" alt="img" class="img-fluid rounded-circle">
        </div> -->
      </div>
    </div>
  </section>

  <section id="features" class="mt-xl-5 mx-5">
    <div class="p-3 pb-md-4 mx-auto text-center">
      <p class="fs-5 text-muted text-uppercase mx-5">Key Features</p>
      <h1 class="fs-3 fw-normal">Experience the Benefits of <br> Our Telecommunication Health Services</h1>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4">
      <div class="col p-0 mb-3">
        <div class="card mx-3 mb-sm-3 rounded-3 shadow-sm h-100">
          <div class="card-body d-flex flex-column justify-content-between shadow-sm">
            <div class="align-content-center text-center">
              <i class='bx bxs-heart p-3 mb-3 border-green text-green rounded-circle shadow-sm fs-3'></i>
              <h4 class="card-title pricing-card-title">Enhanced Patient Engagement and Satisfaction</h4>
              <p class="fs-6 text-muted">Empower patients to participate in their healthcare journey through telecommunication health services.</p>
            </div>
          </div>
        </div>
      </div>
      <div class="col p-0 mb-3">
        <div class="card mx-3 mb-sm-3 rounded-3 shadow-sm h-100">
          <div class="card-body d-flex flex-column justify-content-between shadow-sm">
            <div class="align-content-center text-center">
              <i class='bx bx-phone-call p-3 mb-3 border border-2 border-primary text-primary rounded-circle shadow-sm fs-3'></i>
              <h4 class="card-title pricing-card-title">Remote Consultations</h4>
              <p class="fs-6 text-muted">Access healthcare professionals from anywhere, eliminating the need for in-person visits.</p>
            </div>
          </div>
        </div>
      </div>
      <div class="col p-0 mb-3">
        <div class="card mx-3 mb-sm-3 rounded-3 shadow-sm h-100">
          <div class="card-body d-flex flex-column justify-content-between shadow-sm">
            <div class="align-content-center text-center">
              <i class='bx bxs-user-voice p-3 mb-3 border-green text-green rounded-circle shadow-sm fs-3'></i>
              <h4 class="card-title pricing-card-title">Specialized Telehealth Services</h4>
              <p class="fs-6 text-muted">Collaborative care coordination between your primary care provider and specialists for comprehensive treatment plans.</p>
            </div>
          </div>
        </div>
      </div>
      <div class="col p-0 mb-3">
        <div class="card mx-3 mb-sm-3 rounded-3 shadow-sm h-100">
          <div class="card-body d-flex flex-column justify-content-between shadow-sm">
            <div class="align-content-center text-center">
              <i class='bx bxs-buildings p-3 mb-3 border border-2 border-primary text-primary rounded-circle shadow-sm fs-3'></i>
              <h4 class="card-title pricing-card-title">Scalable Telehealth Solutions for Healthcare Providers</h4>
              <p class="fs-6 text-muted">Customizable telehealth platforms tailored to the needs of individual healthcare practices.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="services" class="padding-medium mt-xl-5 py-2">
    <div class="container mb-4 pt-2">
      <div class="p-3 pb-md-4 mx-auto text-center">
        <p class="fs-5 text-muted text-uppercase mx-5">Our Services</p>
        <h1 class="display-6 fw-normal">Telecommunication Health Services <br> for Your Convenience</h1>
      </div>

      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
        <div class="col mb-4">
          <div class="d-flex flex-column h-100">
            <img src="../assets/images/services/expert_medical_advice.png" alt="Virtual Consultations" class="rounded-2 shadow-lg w-100 object-fit-cover" style="height: 250px;">
            <div class="card mx-3 mb-sm-3 rounded-3 shadow-sm flex-grow-1">
              <div class="card-body d-flex flex-column justify-content-between">
                <div>
                  <h3 class="card-title pricing-card-title">Virtual Consultations</h3>
                  <p class="fs-6 text-muted">
                    Skip the waiting room, consult with healthcare professionals online! Receive expert medical advice, book convenient appointments, and get follow-up care for effective health management.
                  </p>
                </div>
                <a href="./services#one" class="w-100 btn btn-outline-primary hover-light">Read More</a>
              </div>
            </div>
          </div>
        </div>

        <div class="col mb-4">
          <div class="d-flex flex-column h-100">
            <img src="../assets/images/services/health_metrics_tracking.png" alt="Remote Monitoring" class="rounded-2 shadow-lg w-100 object-fit-cover" style="height: 250px;">
            <div class="card mx-3 mb-sm-3 rounded-3 shadow-sm flex-grow-1">
              <div class="card-body d-flex flex-column justify-content-between">
                <div>
                  <h3 class="card-title pricing-card-title">Remote Monitoring</h3>
                  <p class="fs-6 text-muted">
                    Stay in control of your health with cutting-edge tools. Track health metrics, receive instant alerts, and share data securely with your doctor for accurate treatment plans.
                  </p>
                </div>
                <a href="./services#two" class="w-100 btn btn-outline-primary hover-light">Read More</a>
              </div>
            </div>
          </div>
        </div>

        <div class="col mb-4">
          <div class="d-flex flex-column h-100">
            <img src="../assets/images/services/counseling_services.png" alt="Mental Health Support" class="rounded-2 shadow-lg w-100 object-fit-cover" style="height: 250px;">
            <div class="card mx-3 mb-sm-3 rounded-3 shadow-sm flex-grow-1">
              <div class="card-body d-flex flex-column justify-content-between">
                <div>
                  <h3 class="card-title pricing-card-title">Mental Health Support</h3>
                  <p class="fs-6 text-muted">
                    Access mental health resources anytime, anywhere. Speak with professional counselors, join therapy sessions, and get 24/7 crisis support.
                  </p>
                </div>
                <a href="./services#three" class="w-100 btn btn-outline-primary hover-light">Read More</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


  <section id="info" class="padding-medium mt-xl-5 mx-5">
    <div class="p-3 pb-md-4 mx-auto text-center">
      <h1 class="display-4 fw-normal">Telemedicine</h1>
      <p class="fs-5 text-muted">Bridging the gap between distance and healthcare accessibility.</p>
    </div>

    <div class="row mb-3 d-flex justify-content-center">
      <div class="col-10">
        <div class="row row-cols-1 row-cols-md-2">
          <div class="col mb-4">
            <div class="border border-success shadow-sm rounded-2 p-3 h-100 d-flex flex-column">
              <div class="row">
                <div class="col-4 d-flex align-items-start justify-content-center">
                  <i class='bx bxs-phone-call p-3 bg-green text-white rounded-1 fs-3'></i>
                </div>
                <div class="col-8">
                  <h3 class="fw-normal">Doctor on Call</h3>
                  <p class="fs-6 text-muted">Get instant access to licensed doctors anytime, anywhere. Consult via phone or video calls for expert medical advice and treatment recommendations.</p>
                </div>
              </div>
            </div>
          </div>

          <div class="col mb-4">
            <div class="border border-success shadow-sm rounded-2 p-3 h-100 d-flex flex-column">
              <div class="row">
                <div class="col-4 d-flex align-items-start justify-content-center">
                  <i class='bx bxs-book-reader p-3 border-green text-green rounded-1 shadow-sm fs-3'></i>
                </div>
                <div class="col-8">
                  <h3 class="fw-normal">Health Education</h3>
                  <p class="fs-6 text-muted">Access reliable health resources, wellness tips, and disease prevention guides to make informed decisions about your well-being.</p>
                </div>
              </div>
            </div>
          </div>

          <div class="col mb-4">
            <div class="border border-success shadow-sm rounded-2 p-3 h-100 d-flex flex-column">
              <div class="row">
                <div class="col-4 d-flex align-items-start justify-content-center">
                  <i class='bx bxs-first-aid p-3 border-green text-green rounded-1 shadow-sm fs-3'></i>
                </div>
                <div class="col-8">
                  <h3 class="fw-normal">Emergency Assistance</h3>
                  <p class="fs-6 text-muted">Quickly connect with medical professionals in urgent situations and receive immediate guidance on first-aid measures and next steps.</p>
                </div>
              </div>
            </div>
          </div>

          <div class="col mb-4">
            <div class="border border-success shadow-sm rounded-2 p-3 h-100 d-flex flex-column">
              <div class="row">
                <div class="col-4 d-flex align-items-start justify-content-center">
                  <i class='bx bxs-file-plus p-3 bg-green text-white rounded-1 fs-3'></i>
                </div>
                <div class="col-8">
                  <h3 class="fw-normal">E-Prescriptions</h3>
                  <p class="fs-6 text-muted">Receive digital prescriptions directly from doctors, ensuring safe and convenient access to medications without visiting a clinic.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
    <div class="mb-5 text-center">
      <hr class="my-3 c-red rounded-5" style="height: 5px;">
      <p>Our telemedicine services are available throughout the week to ensure you receive timely medical care.</p>
      <a href="./appointment" class="btn btn-primary text-light px-4 py-2 mt-3">Schedule a Appointment</a>
    </div>
  </section>

  <section id="about" class="padding-medium mt-xl-5 py-2">
    <div class="container mb-4 pt-2">
      <div class="row align-items-stretch justify-content-center mt-xl-5">
        <div class="col-md-6 mt-3 my-md-5 px-4 me-0 m-md-4 px-lg-0 d-flex flex-column">
          <div class="mb-3">
            <p class="text-secondary">Learn more about us</p>
            <h2 class="display-6 fw-semibold">Your Health, Anytime, Anywhere</h2>
          </div>
          <p>
            Welcome to University Telecommunications Health Services! We are dedicated to enhancing student well-being
            through innovative, remote health solutions that ensure accessibility, privacy, and high-quality care.
          </p>
          <div class="d-flex align-items-center mb-4">
            <i class='bx bxs-check-circle c-red'></i>
            <p class="ps-3 m-0">Revolutionizing healthcare accessibility through telecommunication.</p>
          </div>
          <div class="d-flex align-items-center mb-4">
            <i class='bx bxs-check-circle c-red'></i>
            <p class="ps-3 m-0">Ensuring students receive quality healthcare, regardless of location.</p>
          </div>
          <div class="d-flex align-items-center mb-4">
            <i class='bx bxs-check-circle c-red'></i>
            <p class="ps-3 m-0">Empowering students with tools and resources for better health management.</p>
          </div>
          <a href="./about_us" class="btn bg-green px-3 py-1 mt-4 link-light button" style="width: fit-content;">Learn more</a>
        </div>
        <div class="col-md-4 d-flex align-items-stretch">
          <img src="../assets/images/billboard-img3.jpg" alt="img" class="img-thumbnail img-fluid rounded-3 shadow-lg w-100" style="object-fit: cover;">
        </div>
      </div>
    </div>
  </section>

  <?php
  require_once('../includes/footer.php');
  ?>

</body>

</html>