<?php
require_once("../classes/message.class.php");

$message = new Message();

$timeout = 30;
$start_time = time();

while (time() - $start_time < $timeout) {
    $messageArray = $message->load_chatbot_messages($_GET['account_id'], $_GET['last_message_id']);

    if (count($messageArray) > 0) {
        foreach ($messageArray as $item) {
?>
            <div class="d-flex align-items-center justify-content-end mb-2" data-message-id="<?= $item['cb_message_id'] ?>">
                <div class="bg-secondary text-light p-2 rounded-3" style="max-width: 52%; white-space: pre-wrap;"><?= $item['user_message'] ?></div>
            </div>

            <div class="d-flex align-items-center mb-2" data-message-id="<?= $item['cb_message_id'] ?>">
                <div class="bg-primary text-light p-2 rounded-3" style="max-width: 52%; white-space: pre-wrap;"><?= $item['bot_response'] ?></div>
            </div>
<?php

        }
        break;
    }
    sleep(2);
}
?>