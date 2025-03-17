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
$title = 'FAQs - DocConnect';
include '../includes/head.php';
?>

<body>
  <?php
  require_once('../includes/header.php');
  ?>

  <section class="faq-section padding-medium mt-xl-5 py-2">
    <div class="container">
      <div class="p-3 pb-md-4 mx-auto text-center">
        <p class="fs-5 text-muted text-uppercase mx-5">FAQs</p>
        <h1 class="display-6 fw-normal">Frequently Asked Questions</h1>
        <p class="fs-6 text-muted">Find answers to common questions about DocConnect.</p>
      </div>

      <div class="faq-container">
        <!-- FAQ Item 1 -->
        <div class="faq-item mb-3">
          <button class="faq-question btn btn-light w-100 text-start d-flex justify-content-between align-items-center p-3">
            <span>What is DocConnect?</span>
            <span class="icon fs-5">+</span>
          </button>
          <div class="faq-answer p-3 bg-light rounded-bottom">
            <p class="mb-0">DocConnect is a platform that offers free telecommunication health services to university students. It allows students to consult with healthcare professionals remotely.</p>
          </div>
        </div>

        <!-- FAQ Item 2 -->
        <div class="faq-item mb-3">
          <button class="faq-question btn btn-light w-100 text-start d-flex justify-content-between align-items-center p-3">
            <span>How do I sign up for DocConnect?</span>
            <span class="icon fs-5">+</span>
          </button>
          <div class="faq-answer p-3 bg-light rounded-bottom">
            <p class="mb-0">To sign up, visit our website and click on the "Sign Up" button. Follow the instructions to create your account.</p>
          </div>
        </div>

        <!-- FAQ Item 3 -->
        <div class="faq-item mb-3">
          <button class="faq-question btn btn-light w-100 text-start d-flex justify-content-between align-items-center p-3">
            <span>Is DocConnect free?</span>
            <span class="icon fs-5">+</span>
          </button>
          <div class="faq-answer p-3 bg-light rounded-bottom">
            <p class="mb-0">Yes, DocConnect is completely free for all university students.</p>
          </div>
        </div>

        <!-- Add more FAQ items as needed -->
      </div>
    </div>
  </section>

  <?php
  require_once('../includes/footer.php');
  ?>

  <script>
    // FAQ Toggle Script
    document.addEventListener("DOMContentLoaded", function () {
      const faqQuestions = document.querySelectorAll(".faq-question");

      faqQuestions.forEach((question) => {
        question.addEventListener("click", () => {
          const answer = question.nextElementSibling;
          const icon = question.querySelector(".icon");

          if (answer.style.maxHeight) {
            answer.style.maxHeight = null;
            icon.textContent = "+";
          } else {
            answer.style.maxHeight = answer.scrollHeight + "px";
            icon.textContent = "-";
          }
        });
      });
    });
  </script>
</body>

</html>