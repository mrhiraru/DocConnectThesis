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


// $message = new Message();
// if (isset($_POST['send'])) {
//   $message->sender_id = $_SESSION['account_id'];
//   $message->receiver_id = $_POST['receiver_id'];
//   $message->message = htmlentities($_POST['message']);

//   if (validate_field($message->message)) {
//     if ($message->send_message()) {
//       $success = 'success';
//       // call this loadChatBox(account_id) here but it is javascript is how is that possible
//     } else {
//       echo 'An error occured while adding in the database.';
//     }
//   } else {
//     $success = 'failed';
//   }
// }

?>

<!DOCTYPE html>
<html lang="en">
<?php
$title = 'Message';
include '../includes/head.php';
?>

<body class="bg-white">
  <input type="hidden" id="account_id" value="<?= $_SESSION['account_id'] ?>">
  <?php require_once('../includes/header.php'); ?>

  <section id="chat" class="padding-medium">
    <div class="d-flex h-100">
      <!-- Left Sidebar (Chats List) -->
      <div id="chat_sidepanel" class="d-flex flex-column bg-light border-end p-3 mt-3" style="min-width: 25%;">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <span class="fs-5 fw-bold">Chats</span>
        </div>
        <div class="input-group mb-3">
          <input type="text" class="form-control border-2" id="searchChat" placeholder="Search" oninput="update_chatlist(<?= $_SESSION['account_id'] ?>)">
        </div>
        <ul id="chatList" class="list-unstyled mb-0">
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
                        } ?>" alt="Profile" class="rounded-circle me-3" height="40" width="40">
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


      <!-- Chat Box -->
      <div id="chat_box" class="flex-grow-1 d-flex flex-column m-0 mt-3">
        <div class="d-flex flex-column justify-content-center align-items-center h-100">
          <p class="text-muted text-center">Select a user to start messaging.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- <script src="../js/chat.js"></script> -->
</body>

</html>


<script>
  var currentOpenedChat = null;
  var currentChatRequest = null;

  function loadChatBox(account_id, chatwith_account_id) {
    if (currentOpenedChat == chatwith_account_id) {
      return;
    } else {
      currentOpenedChat = chatwith_account_id;
    }

    if (currentChatRequest) {
      currentChatRequest.abort();
    }

    currentChatRequest = $.ajax({
      url: '../handlers/chat.load_chatbox.php?account_id=' + account_id + '&chatwith_account_id=' + chatwith_account_id,
      type: 'GET',
      success: function(response) {
        $('#chat_box').html(response);
      },
      error: function(xhr, status, error) {
        console.error('Error loading chatbox:', error);
      }
    });
  }

  function update_chatlist(account_id, user_role) {
    var search = $('#searchChat').val();
    $.ajax({
      url: '../handlers/chat.load_chatlist.php?search=' + search + '&account_id=' + account_id + '&user_role=' + user_role,
      type: 'GET',
      success: function(response) {
        $('#chatList').html(response);
      },
      error: function(xhr, status, error) {
        console.error('Error updating chatlist:', error);
      }
    });
  }
</script>