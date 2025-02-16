<?php
session_start();
header('Content-Type: application/json');

// Read the JSON input from the request
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['access_token'])) {
    $_SESSION['google_access_token'] = $data['access_token'];
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No token received']);
}
?>