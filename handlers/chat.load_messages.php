<?php
require_once("../classes/message.class.php");

$message = new Message();

$timeout = 30;
$start_time = time();

while (time() - $start_time < $timeout) {
    $messageArray = $message->load_messages($_GET['account_id'], $_GET['chatwith_account_id'], $_GET['last_message_id']);

    if (count($messageArray) > 0) {
        foreach ($messageArray as $item) {
            if ($item['sender_id'] == $_GET['account_id'] && $item['receiver_id'] == $_GET['chatwith_account_id']) {
?>
                <div class="d-flex align-items-center mb-2" data-message-id="<?= $item['message_id'] ?>">
                    <div class="bg-primary text-light p-2 rounded-3" style="max-width: 52%; white-space: pre-wrap;"><?= $item['message'] ?></div>
                </div>
            <?php
            } else if ($item['sender_id'] == $_GET['chatwith_account_id'] && $item['receiver_id'] == $_GET['account_id']) {
            ?>
                <div class="d-flex align-items-center justify-content-end mb-2" data-message-id="<?= $item['message_id'] ?>">
                    <div class="bg-secondary text-light p-2 rounded-3" style="max-width: 52%; white-space: pre-wrap;"><?= $item['message'] ?></div>
                </div>
<?php
            }
        }
        break;
    }
    sleep(2);
}
?>