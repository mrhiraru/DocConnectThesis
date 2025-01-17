<?php
require_once("../classes/message.class.php");

$message = new Message();

if ($message->mark_messages_read($_GET['sender_id'], $_GET['receiver_id'])) {
    $success = 'success';
    echo "<script>console.log('Messages marked as read.');</script>";
} else {
    echo 'An error occured while adding in the database.';
}
