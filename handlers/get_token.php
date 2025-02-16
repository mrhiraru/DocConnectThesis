<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['google_access_token'])) {
    echo json_encode(['access_token' => $_SESSION['google_access_token']]);
} else {
    echo json_encode(['access_token' => null]);
}
?>