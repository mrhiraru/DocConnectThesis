<?php

require_once('../classes/message.class.php');
require_once('../tools/functions.php');

$message = new Message();

if (isset($_POST['send'])) {
    $message->sender_id = $_POST['sender_id'];
    $message->receiver_id = $_POST['receiver_id'];
    $message->message = htmlentities($_POST['message']);
    $message->message_type = 'User';

    if (validate_field($message->message)) {
        if ($message->send_message()) {
            $success = 'success';
        } else {
            echo 'An error occured while adding in the database.';
        }
    } else {
        $success = 'failed';
    }
}
