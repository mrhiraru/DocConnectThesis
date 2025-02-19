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
  $title = 'Services';
  $services = 'active';
	include '../includes/head.php';
?>
<body>
  <?php 
    require_once ('../includes/header.php');
  ?>
  <section class="services hero vh-50 d-flex justify-content-center align-items-center mt-5">
    <h1 class="text-center mb-4 text-light">Our Services</h1>
  </section>

  <div class="container mt-3">
    <div class="p-3 pb-md-4 mx-auto text-center">
      <h1 class="display-6 fw-normal">Telecommunication Health Services</h1>
      <p class="fs-5 text-muted mx-5">
        Welcome to DocConnect's Telecommunication Health Services! Our goal is 
        to provide you with seamless access to quality healthcare and wellness resources 
        from the comfort of your home or office. Our comprehensive suite of 
        telecommunication health services ensures that you receive the care you need when 
        you need it, without the hassle of traveling to a clinic. Here's what we offer:
     </p>
    </div>
    <!-- Virtual Consultations -->
    <div class="mb-5">
      <h2 class="text-primary">Virtual Consultations</h2>
      <p>Skip the waiting room—consult with healthcare professionals online!</p>
      <div class="row">
        <div class="col-md-4 mb-4">
          <div class="card service-card shadow-sm">
            <img src="../assets/images/services/expert_medical_advice.png" class="card-img-top service-img" alt="Virtual Consultations">
            <div class="card-body">
              <h5 class="card-title text-green">Expert Medical Advice</h5>
              <p class="card-text">Receive personalized guidance and treatment plans from licensed healthcare providers.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="card service-card shadow-sm">
            <img src="../assets/images/services/convenient_appointments.png" class="card-img-top service-img" alt="Convenient Appointments">
            <div class="card-body">
              <h5 class="card-title text-green">Convenient Appointments</h5>
              <p class="card-text">Book consultations at your preferred time and attend from any device.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="card service-card shadow-sm">
            <img src="../assets/images/services/follow-up_care.png" class="card-img-top service-img" alt="Follow-Up Care">
            <div class="card-body">
              <h5 class="card-title text-green">Follow-Up Care</h5>
              <p class="card-text">Get continuous support to ensure your health management is effective.</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Online Doctor Appointments -->
    <div class="mb-5">
      <h2 class="text-primary">Online Doctor Appointments</h2>
      <p>Connect directly with doctors via video calls.</p>
      <div class="row">
        <div class="col-md-4 mb-4">
          <div class="card service-card">
            <img src="../assets/images/services/easy_access.png" class="card-img-top service-img" alt="Easy Access">
            <div class="card-body">
              <h5 class="card-title text-green">Easy Access</h5>
              <p class="card-text">Talk to general practitioners and specialists without leaving your space.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="card service-card">
            <img src="../assets/images/services/wide_availability.png" class="card-img-top service-img" alt="Wide Availability">
            <div class="card-body">
              <h5 class="card-title text-green">Wide Availability</h5>
              <p class="card-text">Choose the doctor that best fits your needs and preferences.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="card service-card">
            <img src="../assets/images/services/secure_and_confidential.png" class="card-img-top service-img" alt="Secure Video Calls">
            <div class="card-body">
              <h5 class="card-title text-green">Secure and Confidential</h5>
              <p class="card-text">All consultations prioritize your privacy and confidentiality.</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Remote Monitoring -->
    <div class="mb-5">
      <h2 class="text-primary">Remote Monitoring</h2>
      <p>Stay in control of your health with cutting-edge tools.</p>
      <div class="row">
        <div class="col-md-4 mb-4">
          <div class="card service-card">
            <img src="../assets/images/services/health_metrics_tracking.png" class="card-img-top service-img" alt="Health Metrics">
            <div class="card-body">
              <h5 class="card-title text-green">Health Metrics Tracking</h5>
              <p class="card-text">Monitor your vital signs in real-time with state-of-the-art devices.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="card service-card">
            <img src="../assets/images/services/instant_alerts.png" class="card-img-top service-img" alt="Instant Alerts">
            <div class="card-body">
              <h5 class="card-title text-green">Instant Alerts</h5>
              <p class="card-text">Receive notifications if any readings fall outside normal ranges.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="card service-card">
            <img src="../assets/images/services/data-driven_care.png" class="card-img-top service-img" alt="Data Sharing">
            <div class="card-body">
              <h5 class="card-title text-green">Data-Driven Care</h5>
              <p class="card-text">Securely share health data with your doctor for accurate treatment plans.</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Mental Health Support -->
    <div class="mb-5">
      <h2 class="text-primary">Mental Health Support</h2>
      <p>Access mental health resources anytime, anywhere.</p>
      <div class="row">
        <div class="col-md-4 mb-4">
          <div class="card service-card">
            <img src="../assets/images/services/counseling_services.png" class="card-img-top service-img" alt="Counseling Services">
            <div class="card-body">
              <h5 class="card-title text-green">Counseling Services</h5>
              <p class="card-text">Speak with professional counselors for support and guidance.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="card service-card">
            <img src="../assets/images/services/therapy_sessions.png" class="card-img-top service-img" alt="Therapy Sessions">
            <div class="card-body">
              <h5 class="card-title text-green">Therapy Sessions</h5>
              <p class="card-text">Join individual or group sessions remotely to improve mental health.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="card service-card">
            <img src="../assets/images/services/247_crisis_support.png" class="card-img-top service-img" alt="Crisis Support">
            <div class="card-body">
              <h5 class="card-title text-green">24/7 Crisis Support</h5>
              <p class="card-text">Immediate support during challenging times with trained professionals.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php 
    require_once ('../includes/footer.php');
  ?>

</body>
</html>