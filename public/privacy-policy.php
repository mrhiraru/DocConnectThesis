<?php
session_start();

require_once('../tools/functions.php');
require_once('../classes/account.class.php');

?>

<!DOCTYPE html>
<html lang="en">
<?php 
  $title = 'DocConnect - Privacy Policy';
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
    <h1 class="text-center fw-bolder">Privacy Policy for DocConnect</h1>
    
    <p>&nbsp;</p>

    <p class="date text-start text-muted fw-light bg-gray py-3 ps-4">Last Updated: January 31, 2025</p>

    <p>&nbsp;</p>
    
    <div class="mt-4">
      <p class="fw-light">Welcome to DocConnect, a free university Telehealth website. Your privacy is important to us, and we are committed to protecting your personal information. This Privacy Policy outlines how we collect, use, and safeguard your data.</p>
    </div>

    <p>&nbsp;</p>

    <ol>
    <div class="mt-4">
      <h3><li>Information We Collect</li></h3>
      <p class="fw-light">We may collect the following types of information:</p>
      <ul>
        <li class="fw-light">Personal Information: Name, university ID, email address, and contact details (if provided).</li>
        <li class="fw-light">Health Information: Medical history, symptoms, and appointment details submitted for consultations.</li>
        <li class="fw-light">Usage Data: Browser type, IP address, device information, and interaction logs for website analytics.</li>
      </ul>
    </div>

    <p>&nbsp;</p>

    <div class="mt-4">
      <h3><li>How We Use Your Information</li></h3>
      <p class="fw-light">We use your information for the following purposes:</p>
      <ul>
        <li class="fw-light">To provide Telehealth consultations and connect you with healthcare providers.</li>
        <li class="fw-light">To schedule and manage doctor appointments.</li>
        <li class="fw-light">To improve our platform’s functionality and user experience.</li>
        <li class="fw-light">To comply with legal or university health regulations.</li>
      </ul>
    </div>

    <p>&nbsp;</p>

    <div class="mt-4">
      <h3><li>Data Sharing and Security</li></h3>
      <ul>
        <li class="fw-light"><span class="fw-bold">Confidentiality:</span> We do not sell, trade, or share your personal data with third parties, except as required by law or with your consent.</li>
        <li class="fw-light"><span class="fw-bold">Third-Party Services: :</span> We may integrate third-party tools (e.g., Google Calendar) for scheduling but ensure compliance with privacy standards.</li>
        <li class="fw-light"><span class="fw-bold">Security Measures:</span> We implement encryption, access controls, and secure storage to protect your data.</li>
      </ul>
   </div>

    <p>&nbsp;</p>

    <div class="mt-4">
      <h3><li>Your Rights and Choices</li></h3>
      <ul>
        <li class="fw-light">You have the right to access, update, or request deletion of your personal data.</li>
        <li class="fw-light">You may opt out of non-essential communications at any time.</li>
        <li class="fw-light">You can request a copy of the data we store about you.</li>
      </ul>
    </div>

    <p>&nbsp;</p>

    <div class="mt-4">
      <h3><li>Cookies and Tracking Technologies</li></h3>
      <p class="fw-light">We may use cookies and analytics tools to enhance your browsing experience. You can manage cookie preferences through your browser settings.</p>
    </div>

    <p>&nbsp;</p>

    <div class="mt-4">
      <h3><li>Changes to This Policy</li></h3>
      <p class="fw-light">We may update this Privacy Policy periodically. Any changes will be posted on this page with an updated revision date.</p>
    </div>

    <p>&nbsp;</p>

    <div class="mt-4">
      <h3><li>Contact Us</li></h3>
      <p class="fw-light">If you have any questions or concerns about this Privacy Policy, please contact us at:</p>
      <p class="fw-light">Email: <a href="#">wmsu.itso@wmsu.edu.ph </a>(Chat) / <a href="#">http://www.wmsu.edu.ph</a> (Platform)</p>
      <p class="fw-light">Address: Normal Road, Baliwasan, 7000 Zamboanga City</p>
    </div>

    </ol>
  </div>
</body>
</html>
