<?php
session_start();
include_once '../classes/database.php';

$db = new Database();
$conn = $db->connect();

if (isset($_POST['fetch_messages'])) {
  $sender_id = $_SESSION['account_id'];
  $receiver_id = $_POST['receiver_id'];

  $sql = "SELECT * FROM messages WHERE (sender_id = :sender_id AND receiver_id = :receiver_id) 
          OR (sender_id = :receiver_id AND receiver_id = :sender_id) ORDER BY timestamp ASC";
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':sender_id', $sender_id, PDO::PARAM_INT);
  $stmt->bindParam(':receiver_id', $receiver_id, PDO::PARAM_INT);
  $stmt->execute();

  $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($messages);
}

if (isset($_POST['send_message'])) {
  $sender_id = $_SESSION['account_id'];
  $receiver_id = $_POST['receiver_id'];
  $message = $_POST['message'];

  $sql = "INSERT INTO messages (sender_id, receiver_id, message) VALUES (:sender_id, :receiver_id, :message)";
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':sender_id', $sender_id, PDO::PARAM_INT);
  $stmt->bindParam(':receiver_id', $receiver_id, PDO::PARAM_INT);
  $stmt->bindParam(':message', $message, PDO::PARAM_STR);

  if ($stmt->execute()) {
    echo "Message sent";
  } else {
    $errorInfo = $stmt->errorInfo();
    echo "Failed to send message. Error: " . $errorInfo[2];
  }
}
?>
