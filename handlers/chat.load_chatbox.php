<?php
require_once("../classes/message.class.php");

$message = new Message();
$record = $message->load_chatbox($_GET['chatwith_account_id']);

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
      <span><?= (isset($record['middlename'])) ? ucwords(strtolower($record['firstname'] . ' ' . $record['middlename'] . ' ' . $record['lastname'])) : ucwords(strtolower($item['firstname'] . ' ' . $record['lastname'])) ?></span>
    </div>
    <div>
      <i class='bx bx-dots-horizontal-rounded fs-4'></i>
    </div>
  </div>
</div>

<input type="hidden" id="account_id" value="<?= $_GET['account_id'] ?>">
<input type="hidden" id="chatwith_account_id" value="<?= $_GET['chatwith_account_id'] ?>">
<!-- Chat Messages -->
<div id="chatMessages" class="body flex-grow-1 d-flex flex-column p-3 bg-light overflow-auto">
  <!-- Messages will be dynamically loaded here -->

</div>

<!-- Chat Input -->

<form id="chatForm" action="" method="post" class="chat_input d-flex align-items-end p-3 border-top bg-light">
  <input type="hidden" id="sender_id" name="sender_id" value="<?= $_GET['account_id'] ?>">
  <input type="hidden" id="receiver_id" name="receiver_id" value="<?= $_GET['chatwith_account_id'] ?>">
  <textarea type="text" id="message" name="message" class="form-control border-2 text-dark me-3" placeholder="Type your message"></textarea>
  <button id="send" name="send" type="submit" class="btn btn-light d-flex justify-content-center">
    <i class='bx bx-send text-dark fs-4'></i>
  </button>
</form>

<script>
  $(document).ready(function() {
    function loadMessages(account_id, chatwith_account_id) {
      $.ajax({
        url: '../handlers/chat.load_messages.php?account_id=' + account_id + '&chatwith_account_id=' + chatwith_account_id,
        type: 'GET',
        success: function(response) {
          $('#chatMessages').html(response);
          scrollToBottom();
        },
        error: function(xhr, status, error) {
          console.error('Error loading chatbox:', error);
        }
      });
    }

    function scrollToBottom() {
      var chatMessages = document.getElementById('chatMessages');
      chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Call the function to load messages when the chatbox is loaded
    var account_id = $('#account_id').val();
    var chatwith_account_id = $('#chatwith_account_id').val();
    loadMessages(account_id, chatwith_account_id);

    $('#chatForm').on('submit', function(e) {
      e.preventDefault();

      const formData = {
        send: $('#send').val(),
        sender_id: $("#sender_id").val(),
        receiver_id: $("#receiver_id").val(),
        message: $("#message").val()
      }

      $.ajax({
        url: '../handlers/chat.send_message.php',
        type: 'POST',
        data: formData,
        success: function(response) {
          $('#message').val('');
          loadMessages(account_id, chatwith_account_id);
        },
        error: function(xhr, status, error) {
          console.error('Error sending message:', error);
        }
      });
    });
  });
</script>