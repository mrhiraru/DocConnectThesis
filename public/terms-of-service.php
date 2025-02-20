<?php
session_start();

require_once('../tools/functions.php');
require_once('../classes/account.class.php');

?>

<!DOCTYPE html>
<html lang="en">
<?php 
  $title = 'DocConnect - Terms of Service';
	include '../includes/head.php';
?>
<body>
  <style>
    .container {
      max-width: 860px;
    }

    .date {
      border-left: 7.5px solid gray;
      border-radius: 2.5px;
    }

    .bg-gray {
      background-color: lightgray;
    }
  </style>

  <div class="container px-3 py-4 p-md-5">
    <h1 class="text-center fw-bolder">DocConnect Terms of Service</h1>
    
    <p>&nbsp;</p>

    <p class="date text-start text-muted fw-light bg-gray py-3 ps-4">Last Updated: January 31, 2025</p>

    <p>&nbsp;</p>
    
    <div class="mt-4">
      <p>Dear Users, Welcome to DocConnect!</p>
      <p>&nbsp;</p>
      <p class="fw-light">These Terms of Service ("Terms") govern your use of DocConnect, a free telehealth platform provided by [University Name] ("we", "us", or "our"). By accessing or using our website, applications, and services (collectively, the "Service"), you agree to comply with these Terms. If you do not agree to these Terms, you may not use the Service.</p>
    </div>

    <p>&nbsp;</p>

    <ol>
    <div class="mt-4">
      <h3><li>Eligibility</li></h3>
      <p class="fw-light">By using DocConnect, you confirm that you are at least 18 years old or have obtained consent from a parent or legal guardian if you are between 14 and 18 years old. The Service is not intended for individuals under the age of 14.</p>
    </div>

    <p>&nbsp;</p>

    <div class="mt-4">
      <h3><li>Use of Service</li></h3>
      <ul>
        <li class="fw-light">DocConnect is provided solely for educational and informational telehealth purposes.</li>
        <li class="fw-light">The Service is not a substitute for professional medical diagnosis, treatment, or emergency care.</li>
        <li class="fw-light">Users are prohibited from using the Service for unlawful, fraudulent, or malicious purposes.</li>
        <li class="fw-light">You agree not to misuse, modify, disrupt, or interfere with the Service’s operations.</li>
      </ul>
    </div>

    <p>&nbsp;</p>

    <div class="mt-4">
      <h3><li>User Accounts</li></h3>
      <p class="fw-light">You may be required to create an account to access certain features of DocConnect. You are responsible for maintaining the confidentiality of your account credentials and for all activities that occur under your account. We reserve the right to suspend or terminate accounts that violate these Terms.</p>
    </div>

    <p>&nbsp;</p>

    <div class="mt-4">
      <h3><li>Privacy Policy</li></h3>
      <p class="fw-light">Your use of the Service is subject to our Privacy Policy, which explains how we collect, use, and protect your personal data. By using DocConnect, you consent to the practices described in our Privacy Policy.</p>
    </div>

    <p>&nbsp;</p>

    <div class="mt-4">
      <h3><li>Medical Disclaimer</li></h3>
      <p class="fw-light">DocConnect does not provide medical diagnoses, prescriptions, or emergency medical services. Any health-related information provided through the Service is for informational purposes only and should not be relied upon as medical advice. You should always seek professional medical guidance for any health concerns.</p>
    </div>

    <p>&nbsp;</p>

    <div class="mt-4">
      <h3><li>Intellectual Property</li></h3>
      <p class="fw-light">All content, trademarks, logos, and intellectual property associated with DocConnect are owned by us or licensed to us. Users may not reproduce, distribute, or modify any part of the Service without prior written consent.</p>
    </div>

    <p>&nbsp;</p>

    <div class="mt-4">
      <h3><li>Limitation of Liability</li></h3>
      <p class="fw-light">We provide DocConnect "as is" without any warranties, express or implied. We are not liable for any damages arising from the use of or inability to use the Service, including but not limited to health-related consequences, data loss, or service interruptions. We do not guarantee the accuracy, completeness, or availability of information provided through the Service.</p>
    </div>

    <p>&nbsp;</p>

    <div class="mt-4">
      <h3><li>Third-Party Services</li></h3>
      <p class="fw-light">DocConnect may contain links to third-party websites or services. We do not endorse or assume responsibility for any third-party content, products, or services.</p>
    </div>

    <p>&nbsp;</p>

    <div class="mt-4">
      <h3><li>Termination</li></h3>
      <p class="fw-light">We reserve the right to suspend or terminate access to the Service for any user who violates these Terms or engages in any conduct that we determine to be harmful or inappropriate.</p>
    </div>

    <p>&nbsp;</p>

    <div class="mt-4">
      <h3><li>Changes to Terms</li></h3>
      <p class="fw-light">We may update these Terms from time to time. Any changes will be posted on our website, and continued use of the Service after such updates constitutes acceptance of the revised Terms.</p>
    </div>

    <p>&nbsp;</p>

    <div class="mt-4">
      <h3><li>Governing Law</li></h3>
      <p class="fw-light">These Terms shall be governed by and interpreted in accordance with the laws of [Jurisdiction], without regard to its conflict of law principles.</p>
    </div>

    <p>&nbsp;</p>

    </ol>

    <div class="mt-4">
      <h3>Online Complaints and Feedback Portal</h3>
      <p class="fw-light">For any issues or feedback, please click the "Contact Us" button on the product interface after logging in.</p>
      <ul>
        <li><h5>Contact Email:</h5></li>
        <ul>
          <li><p class="fw-light">Chat: wmsu.itso@wmsu.edu.ph</p></li>
          <li><p class="fw-light">Platform: http://www.wmsu.edu.ph</p></li>
        </ul>
        <li><h5>Contact Address:</h5></li>
        <p class="fw-light">Normal Road, Baliwasan, 7000 Zamboanga City</p>
      </ul>
    </div>
</body>
</html>
