<?php
require_once("../classes/message.class.php");

$message = new Message();
$record = $message->load_chatbox($_GET['chatwith_account_id']);

// if ($message->mark_messages_read($_GET['chatwith_account_id'], $_GET['account_id'])) {
//   $success = 'success';
// } else {
//   echo 'An error occured while adding in the database.';
// }

?>

<!-- Chat Header -->
<div class="head border-bottom bg-light py-3 px-3">
  <div class="d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center">
      <button id="backToChatList" class="btn p-0 pe-2 btn-light d-flex d-md-none">
        <i class='bx bx-left-arrow-alt fs-3'></i>
      </button>
      <img src="<?php if (isset($record['account_image'])) {
                  echo "../assets/images/" . $record['account_image'];
                } else {
                  echo "../assets/images/default_profile.png";
                } ?>" alt="Profile" class="rounded-circle me-3" height="40" width="40">
      <span><?= (isset($record['middlename'])) ? ucwords(strtolower($record['firstname'] . ' ' . $record['middlename'] . ' ' . $record['lastname'])) : ucwords(strtolower($record['firstname'] . ' ' . $record['lastname'])) ?></span>
    </div>
    <!-- <div>
      <a href="../user/doctorsView" class="d-flex">
        <i class='bx bx-dots-horizontal-rounded fs-4'></i>
      </a>
    </div> -->

    <!-- <?php
    $currentFile = basename($_SERVER['PHP_SELF']); // gets the filename like "chats.php"

    $link = "#"; // default or fallback link
    if ($currentFile === "chats.php") {
      $link = "../doctor/patient-view";
    } elseif ($currentFile === "chat_user.php") {
      $link = "../user/doctorsView";
    }
    ?>

    <div>
      <a href="<?= $link ?>" class="d-flex">
        <i class='bx bx-dots-horizontal-rounded fs-4'></i>
      </a>
    </div> -->
  </div>
</div>
<!-- Chat Messages -->
<div id="chatMessages" class="body flex-grow-1 d-flex flex-column p-3 bg-light overflow-auto">
  <!-- Messages will be dynamically loaded here -->

</div>

<!-- Chat Input -->

<form id="chatForm" action="" method="post" class="chat_input d-flex align-items-end p-3 border-top bg-light">
  <input type="hidden" name="sender_id" id="sender_id" value="<?= $_GET['account_id'] ?>">
  <input type="hidden" name="receiver_id" id="receiver_id" value="<?= $_GET['chatwith_account_id'] ?>">
  <textarea type="text" id="message" name="message" class="form-control border-2 text-dark me-3" placeholder="Type your message"></textarea>
  <button id="send" name="send" type="submit" class="btn btn-light d-flex justify-content-center">
    <i class='bx bx-send text-dark fs-4'></i>
  </button>
</form>

<script>
  $(document).ready(function() {
    const messageInput = $("#message");

    $('#chatForm').on('submit', function(e) {
      e.preventDefault();
      sendMessage();
    });

    messageInput.on("keydown", function(e) {
      if (e.key === "Enter" && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
      }
    });

    function sendMessage() {
      $('#send').prop('disabled', true);

      const formData = {
        send: $('#send').val(),
        sender_id: $('#sender_id').val(),
        receiver_id: $('#receiver_id').val(),
        message: messageInput.val()
      };

      $.ajax({
        url: '../handlers/chat.send_message.php',
        type: 'POST',
        data: formData,
        success: function(response) {
          messageInput.val('');
          $('#send').prop('disabled', false);
        },
        error: function(xhr, status, error) {
          console.error('Error sending message:', error);
        }
      });
    }
  });
</script>