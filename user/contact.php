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
  $title = 'DocConnect | Contacts';
  $contact = 'active';
	include '../includes/head.php';
?>
<body>
  <style>
    body {
      font-family: Arial, sans-serif;
    }
    .contact-header {
      background: url('../assets/images/bg-1.png') no-repeat center center/cover;
      color: white;
      padding: 100px 0;
      text-align: center;
    }
    .contact-section {
      margin-top: -50px;
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      padding: 20px;
    }
    .contact-section h4 {
      font-weight: bold;
    }
    .form-control {
      border-radius: 0.3rem;
    }
    .map-container {
      margin-top: 30px;
    }
  </style>
  <?php 
    require_once ('../includes/header.php');
  ?>

  <!-- Header Section -->
  <section class="contact-header mt-5">
    <h1>Contact us</h1>
    <p>Connect with us, and we’re here to support your telehealth journey. Here’s how you can reach us</p>
  </section>

  <!-- Contact Section -->
  <div class="container">
    <div class="row contact-section mb-5">
      <!-- Left: Contact Details -->
      <div class="col-md-5 rounded-3 mb-4 mb-md-0" style="background-color: #eeeeee;">
        <div class="m-4">
          <h4>Get in touch</h4>
          <p>Want to get in touch? we’d love to hear from you. here’s how you can reach us</p>
          <div class="mb-3">
            <div class="d-flex align-items-start">
                <i class='bx bx-map-pin text-white bg-primary p-md-3 p-2 xx-large-font rounded-circle me-3' ></i>
              <div>
                <strong class="text-primary">Head Office</strong><br>
                <p class="text-break">Normal Road, Baliwasan, 7000 Zamboanga City</p>
              </div>
            </div>
          </div>
          <div class="mb-3">
            <div class="d-flex align-items-start">
              <i class='bx bx-envelope text-white bg-primary p-md-3 p-2 xx-large-font rounded-circle me-3' ></i>
              <div>
                <strong class="text-primary">Email Us</strong><br>
                <p class="text-break">wmsu.docconnect@gmail.com</p> 
              </div>
            </div>
          </div>
          <div class="mb-3">
            <div class="d-flex align-items-start">
              <i class='bx bx-phone text-white bg-primary p-md-3 p-2 xx-large-font rounded-circle me-3'></i>
              <div>
                <strong class="text-primary">Call Us</strong><br>
                Phone: +639 919 309<br>
                Fax: +63629 924 238
              </div>
            </div>
          </div>
          <hr>
          <div>
            <strong>Follow our social media</strong><br>
            <div class="social-icons mt-2">
              <a href="#"><i class='bx bxl-facebook text-white bg-primary p-2 me-1 rounded-circle' ></i></a>
              <a href="#"><i class='bx bxl-instagram text-white bg-primary p-2 me-1 rounded-circle' ></i></a>
            </div>
          </div>

        </div>
      </div>

      <!-- Right: Contact Form -->
      <div class="col-md-7">
        <div class="mx-2 mx-md-4">
          <h4 class="mb-4 text-primary">Send us a message</h4>
          <form>
            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label for="fname" class="form-label text-black-50 mb-0">First Name</label>
                <input type="text" class="form-control" id="fname" placeholder="First Name">
              </div>
              <div class="col-md-6">
                <label for="lname" class="form-label text-black-50 mb-0">Last Name*</label>
                <input type="text" class="form-control" id="lname" placeholder="First Name" required>
              </div>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label text-black-50 mb-0">Email</label>
              <input type="email" class="form-control bg-light border" id="email" name="email" placeholder="example@example.com" required>
            </div>
            <label for="message" class="form-label text-black-50 mb-0">What can we help you with?*</label>
            <textarea class="form-control" id="message" rows="4" placeholder="Message"></textarea>
            <button type="submit" class="btn btn-primary mt-3 w-100 text-light">Send</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Map Section -->
  <div class="map-container">
    <iframe
      src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7921.61518569353!2d122.05236037770997!3d6.913594200000012!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x325041dd7a24816f%3A0x51af215fb64cc81a!2sWestern%20Mindanao%20State%20University!5e0!3m2!1sen!2sph!4v1734515759093!5m2!1sen!2sph"
      width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy">
    </iframe>
  </div>

  <?php 
    require_once ('../includes/footer.php');
  ?>

</body>
</html>