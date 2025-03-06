<?php
session_start();

require_once('../classes/privacyPolicy.class.php');

$privacyPolicy = new PrivacyPolicy();
$currentPolicy = $privacyPolicy->getPrivacyPolicy();

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

    <p class="date text-start text-muted fw-light bg-gray py-3 ps-4">Last Updated:  <?php echo date('F j, Y'); ?></p>

    <p>&nbsp;</p>
    
    <div class="mt-4">
      <?php echo $currentPolicy['content'] ?? 'No Privacy Poilicy available.'; ?>
    </div>
  </div>
</body>
</html>
