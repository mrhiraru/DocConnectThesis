<?php
require_once("../classes/message.class.php");

$message = new Message();
//$record = $message->load_botchatbox($_GET['account_id']);

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
      <img src="../assets/images/chatbot_profile.png" alt="Profile" class="rounded-circle me-3" height="40" width="40">
      <span> Chatbot </span>
    </div>
    <div>
      <i class='bx bx-dots-horizontal-rounded fs-4'></i>
    </div>
  </div>
</div>
<!-- Chat Messages -->
<div id="chatMessages" class="body flex-grow-1 d-flex flex-column p-3 bg-light overflow-auto">
  <!-- Messages will be dynamically loaded here -->

</div>

<!-- Chat Input -->

<form id="chatForm" action="" method="post" class="chat_input d-flex align-items-end p-3 border-top bg-light">
  <input type="hidden" name="account_id" id="account_id" value="<?= $_GET['account_id'] ?>">
  <textarea type="text" id="message" name="message" class="form-control border-2 text-dark me-3" placeholder="Type your message"></textarea>
  <button id="send" name="send" type="submit" class="btn btn-light d-flex justify-content-center">
    <i class='bx bx-send text-dark fs-4'></i>
  </button>
</form>

<script>
  $(document).ready(function() {

    $('#chatForm').on('submit', function(e) {
      e.preventDefault();

      const formData = {
        send: $('#send').val(),
        account_id: $('#account_id').val(),
        message: $("#message").val()
      }

      $.ajax({
        url: '../handlers/chatbot.send_message.php',
        type: 'POST',
        data: formData,
        success: function(response) {
          $('#message').val('');
        },
        error: function(xhr, status, error) {
          console.error('Error sending message:', error);
        }
      });
    });

  });
</script>