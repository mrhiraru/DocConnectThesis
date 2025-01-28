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

            $new_message = chatbot_response($message->message);
            var_dump($new_message);
            $message->message = $new_message;
            $message->account_id = $_POST['account_id'];
            $message->message_type = 'bot';

            if (validate_field($message->message)) {
                if ($message->send_bot_message()) {
                    $success = 'success';
                } else {
                    echo 'An error occured while adding in the database.';
                }
            } else {
                $success = 'failed';
            }
        } else {
            echo 'An error occured while adding in the database.';
        }
    } else {
        $success = 'failed';
    }
}
