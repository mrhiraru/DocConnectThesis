<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
  header('location: ../user/verification.php');
  exit();
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 3) {
  header('location: ../index.php');
  exit();
}

require_once('../tools/functions.php');
require_once('../classes/account.class.php');
require_once('../classes/message.class.php');

?>

<!DOCTYPE html>
<html lang="en">
<?php
$title = 'Message';
include '../includes/head.php';
?>

<body class="bg-white" <?php if (isset($_GET['account_id']) && $_GET['account_id'] != "chatbot") {
                          echo "onload='loadChatBox(" . $_SESSION['account_id'] . ", " . $_GET['account_id'] . ")' ";
                        } else if (isset($_GET['account_id']) && $_GET['account_id'] == "chatbot") {
                          echo "onload='loadChatBotBox(" . $_GET['account_id'] . ")' ";
                        } ?>>
  <!-- // input hidden account id removed from here -->
  <?php require_once('../includes/header.php'); ?>

  <section id="chat" class="padding-medium">
    <div class="d-flex h-100 m-0">
      <!-- Left Sidebar (Chats List) -->
      <div id="chat_sidepanel" class="d-flex flex-column bg-light border-end p-3 m-0" style="min-width: 25%;">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <span class="fs-5 fw-bold">Chats</span>
        </div>
        <div class="input-group mb-3">
          <input type="text" class="form-control border-2" id="searchChat" placeholder="Search" oninput="update_chatlist(<?= $_SESSION['account_id'] ?>)">
        </div>
        <ul id="chatList" class="list-unstyled mb-0">
          <a href="javascript:void(0);" class="d-flex align-items-center text-dark text-decoration-none p-2 border-bottom" onclick="loadBotChatBox(<?= $_SESSION['account_id'] ?>)">
            <img src="../assets/images/chatbot_profile.png" alt="Profile" class="rounded-circle me-3 border" height="40" width="40">
            <div class="w-100">
              <strong>Chatbot</strong>
            </div>
          </a>
          <?php
          $message = new Message();
          $chatArray = $message->get_chats($_SESSION['account_id'], ($_SESSION['user_role'] == 3) ? 1 : 3, "");
          foreach ($chatArray as $item) {
          ?>
            <!-- display chat list using php -->
            <a href="javascript:void(0);" class="d-flex align-items-center text-dark text-decoration-none p-2 border-bottom" onclick="loadChatBox(<?= $_SESSION['account_id'] ?>,<?= $item['account_id'] ?>)">
              <img src="<?php if (isset($item['account_image'])) {
                          echo "../assets/images/" . $item['account_image'];
                        } else {
                          echo "../assets/images/default_profile.png";
                        } ?>" alt="Profile" class="rounded-circle me-3 border" height="40" width="40">
              <div class="w-100">
                <strong><?= (isset($item['middlename'])) ? ucwords(strtolower($item['firstname'] . ' ' . $item['middlename'] . ' ' . $item['lastname'])) : ucwords(strtolower($item['firstname'] . ' ' . $item['lastname'])) ?></strong>
              </div>
              <?php
              if ($item['unread_count'] > 0) {
              ?>
                <span id="unread<?= $item['account_id'] ?>" class="top-50 start-100 translate-middle-x badge rounded-pill bg-danger">
                  <?= $item['unread_count'] ?>
                  <span class="visually-hidden">unread messages</span>
                </span>
              <?php
              }
              ?>
            </a>
            <!-- display chat list using php -->
          <?php
          }
          ?>
        </ul>
      </div>


      <!-- Chat Box -->
      <div id="chat_box" class="flex-grow-1 d-flex flex-column m-0">
        <div class="d-flex flex-column justify-content-center align-items-center h-100">
          <p class="text-muted text-center">Select a user to start messaging.</p>
        </div>
      </div>
    </div>
  </section>
  <script src="../js/chat.js"></script>
  <script src="../js/chatbot.js"></script>
</body>

</html>