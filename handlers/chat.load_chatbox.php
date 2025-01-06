<?php
require_once("../classes/message.class.php");

$message = new Message();
$record = $message->load_chatbox($_GET['account_id']);

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

<script>
  $(document).ready(function() {
    function loadMessages() {
      $.ajax({
        url: '../handlers/chat.load_messages.php',
        type: 'GET',
        success: function(response) {
          $('#chatMessages').html(response);
        },
        error: function(xhr, status, error) {
          console.error('Error loading messages:', error);
        }
      });
    }

    // Call the function to load the chatbox when needed
    loadMessages();
  });
</script>