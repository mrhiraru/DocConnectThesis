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

$db = new Database();
$pdo = $db->connect();

$account_id = isset($_SESSION['account_id']) ? $_SESSION['account_id'] : 0;
?>

<!DOCTYPE html>
<html lang="en">
<?php
$title = 'Message';
include '../includes/head.php';
?>

<body class="bg-white">
  <input type="hidden" id="account_id" value="<?php echo htmlspecialchars($account_id); ?>">
  <?php require_once('../includes/header.php'); ?>

  <section id="chat" class="padding-medium">
    <div class="d-flex h-100">
      <!-- Left Sidebar (Chats List) -->
      <div id="chat_sidepanel" class="d-flex flex-column bg-light border-end p-3" style="min-width: 25%;">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <span class="fs-5 fw-bold">Chats</span>
        </div>
        <div class="input-group mb-3">
          <input type="text" class="form-control border-2" id="searchChat" placeholder="Search">
        </div>
        <ul id="chatList" class="list-unstyled mb-0">
          <?php
          $message = new Message();
          $chatArray = $message->get_chats($_SESSION['account_id'], ($_SESSION['user_role'] == 3) ? 1 : 3, "");
          foreach ($chatArray as $item) {
          ?>
            <!-- display chat list using php -->
            <a href="#" class="d-flex align-items-center text-dark text-decoration-none p-2" onclick="loadChat(<?= $item['account_id'] ?>, 'Doc Fei', '', this)">
              <img src="../assets/images/default_profile.png" alt="Profile" class="rounded-circle me-3" height="40" width="40">
              <div>
                <strong><?= (isset($item['middlename'])) ? ucwords(strtolower($item['firstname'] . ' ' . $item['middlename'] . ' ' . $item['lastname'])) : ucwords(strtolower($item['firstname'] . ' ' . $item['lastname'])) ?></strong>
              </div>
            </a>
            <!-- display chat list using php -->
          <?php
          }
          ?>
        </ul>
      </div>

      <!--  transfer to ajax -->
      <!-- Chat Box -->
      <div id="chat_box" class="flex-grow-1 d-flex flex-column">
        <!-- Chat Header -->
        <div class="head border-bottom bg-light py-3 px-3">
          <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
              <button id="backToChatList" class="btn p-0 pe-2 btn-light d-flex d-md-none">
                <i class='bx bx-left-arrow-alt fs-3'></i>
              </button>
              <img src="../assets/images/defualt_profile.png" alt="Profile" class="rounded-circle me-3" height="40" width="40">
              <span id="chatUser">Select a user to start chatting</span>
            </div>
            <div>
              <i class='bx bx-dots-horizontal-rounded fs-4'></i>
            </div>
          </div>
        </div>

        <!-- Chat Messages -->
        <div id="chatMessages" class="body flex-grow-1 d-flex flex-column p-3 bg-light">
          <!-- Messages will be dynamically loaded here -->
        </div>

        <!-- Chat Input -->
        <div class="chat_input d-flex align-items-end p-3 border-top bg-light">
          <textarea type="text" id="messageInput" class="form-control border-2 text-dark me-3" placeholder="Type your message"></textarea>
          <button id="sendMessage" type="button" class="btn btn-light d-flex justify-content-center">
            <i class='bx bx-send text-dark fs-4'></i>
          </button>
        </div>

      </div>

      <!--  transfer to ajax -->

      
    </div>
  </section>

  <!-- <script src="../js/chat.js"></script> -->
</body>

</html>