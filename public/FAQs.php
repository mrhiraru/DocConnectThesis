<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
  header('location: ../user/verification.php');
}

require_once('../classes/faqs.class.php');
$faq = new FAQ();
$faqs = $faq->getFAQs();
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
        <?php foreach ($faqs as $faq): ?>
          <div class="faq-item mb-3">
            <button class="btn btn-light w-100 text-start d-flex justify-content-between align-items-center p-3 border border-1 border-black" type="button" data-bs-toggle="collapse" data-bs-target="#faq<?php echo $faq['id']; ?>" aria-expanded="false" aria-controls="faq<?php echo $faq['id']; ?>">
              <span><?php echo htmlspecialchars($faq['question']); ?></span>
              <span class="icon fs-5">+</span>
            </button>
            <div class="collapse" id="faq<?php echo $faq['id']; ?>">
              <div class="p-3 bg-light rounded-bottom">
                <p class="mb-0"><?php echo htmlspecialchars($faq['answer']); ?></p>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
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