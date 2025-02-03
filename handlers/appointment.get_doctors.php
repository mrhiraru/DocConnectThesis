<?php
require_once('../classes/account.class.php');
$account = new Account();

try {
  $doctors = $account->get_doctor();

  header('Content-Type: application/json');
  if ($doctors) {
    echo json_encode($doctors);
  } else {
    echo json_encode([]);
  }
} catch (PDOException $e) {
  echo json_encode(['error' => $e->getMessage()]);
}