<?php
require_once("../classes/message.class.php");

$message = new Message();

$timeout = 30;
$start_time = time();

while (time() - $start_time < $timeout) {
    $messageArray = $message->load_chatbot_messages($_GET['account_id'], $_GET['last_message_id']);

    if (count($messageArray) > 0) {
        foreach ($messageArray as $item) {
            if ($item['message_type'] == "user") {
?>
                <div class="d-flex align-items-center justify-content-end mb-2" data-message-id="<?= $item['cb_message_id'] ?>">
                    <div class="bg-primary text-light p-2 rounded-3 text-truncate" style="max-width: 70%; white-space: pre-wrap;"><?= $item['message'] ?></div>
                </div>
            <?php
            } else if ($item['message_type'] == "bot") {
            ?>
                <div class="d-flex align-items-center mb-2" data-message-id="<?= $item['cb_message_id'] ?>">
                    <div class="bg-green text-light p-2 rounded-3 text-truncate" style="max-width: 70%; white-space: pre-wrap;"><?= $item['message'] ?></div>
                </div>
<?php
            }
        }
        break;
    }
    sleep(2);
}
?>