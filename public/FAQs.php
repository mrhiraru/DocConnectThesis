<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
  header('location: ../user/verification.php');
}

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
          <button class="btn btn-light w-100 text-start d-flex justify-content-between align-items-center p-3" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" aria-expanded="false" aria-controls="faq1">
            <span>What is DocConnect?</span>
            <span class="icon fs-5">+</span>
          </button>
          <div class="collapse" id="faq1">
            <div class="p-3 bg-light rounded-bottom">
              <p class="mb-0">DocConnect is a platform that offers free telecommunication health services to university students. It allows students to consult with healthcare professionals remotely.</p>
            </div>
          </div>
        </div>

        <!-- FAQ Item 2 -->
        <div class="faq-item mb-3">
          <button class="btn btn-light w-100 text-start d-flex justify-content-between align-items-center p-3" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" aria-expanded="false" aria-controls="faq2">
            <span>How do I sign up for DocConnect?</span>
            <span class="icon fs-5">+</span>
          </button>
          <div class="collapse" id="faq2">
            <div class="p-3 bg-light rounded-bottom">
              <p class="mb-0">To sign up, visit our website and click on the "Sign Up" button. Follow the instructions to create your account.</p>
            </div>
          </div>
        </div>

        <!-- FAQ Item 3 -->
        <div class="faq-item mb-3">
          <button class="btn btn-light w-100 text-start d-flex justify-content-between align-items-center p-3" type="button" data-bs-toggle="collapse" data-bs-target="#faq3" aria-expanded="false" aria-controls="faq3">
            <span>Is DocConnect free?</span>
            <span class="icon fs-5">+</span>
          </button>
          <div class="collapse" id="faq3">
            <div class="p-3 bg-light rounded-bottom">
              <p class="mb-0">Yes, DocConnect is completely free for all university students.</p>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <?php
  require_once('../includes/footer.php');
  ?>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const faqButtons = document.querySelectorAll(".faq-item button");

      faqButtons.forEach((button) => {
        button.addEventListener("click", () => {
          const icon = button.querySelector(".icon");
          if (icon.textContent === "+") {
            icon.textContent = "-";
          } else {
            icon.textContent = "+";
          }
        });
      });
    });
  </script>
</body>

</html>