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
<style>
  body {
    margin: 0;
    padding: 0;
    height: 100vh;
    display: flex;
    flex-direction: column;
  }
  .top-red {
    background-color: #dc3545;
    height: 40%;
    width: 100%;
  }
  .main-content {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
  }
</style>
<body>
  <div class="top-red"></div>
  <div class="main-content">
    <div class="card bg-light shadow-sm p-4" style="max-width: 400px; width: 100%; margin-top: -200px;">
      <div class="text-center">
        <h4 class="mb-4">Forgot Password</h4>
        <p>Enter your email and we'll send you a link to reset your password.</p>
      </div>
      <form action="forgot_password_handler.php" method="POST">
        <div class="mb-3">
          <label for="email" class="form-label">Email Address</label>
          <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
        </div>
        <?php if (isset($_GET['error']) && $_GET['error'] == 'email_not_found'): ?>
          <div class="text-danger mb-3">We cannot find your email.</div>
        <?php endif; ?>
        <button type="submit" class="btn btn-outline-dark w-100">Submit</button>
      </form>
      <div class="text-center mt-3">
        <a href="../user/login" class="text-decoration-none">Back to Login</a>
      </div>
    </div>
  </div>
</body>
</html>
