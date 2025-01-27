<?php

require_once('../classes/message.class.php');
require_once('../tools/functions.php');
require_once('../tools/chatbot.php');

$message = new Message();

if (isset($_POST['send'])) {

    $message->message = htmlentities($_POST['message']);
    $message->account_id = $_POST['account_id'];
    $message->message_type = 'user';

    if (validate_field($message->message)) {
        if ($message->send_bot_message()) {
            $success = 'success';
            // call this loadChatBox(account_id) here but it is javascript is how is that possible
        } else {
            echo 'An error occured while adding in the database.';
        }
    } else {
        $success = 'failed';
    }
}
