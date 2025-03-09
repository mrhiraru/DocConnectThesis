<?php
session_start();

$temp_role = isset($_SESSION['user_role']) ?  $_SESSION['user_role'] : null;
$from = isset($_GET['from']) ? $_GET['from'] : null;


session_destroy();

if (!isset($from)) {
    if ($temp_role == 0) {
        header('location: ./admin/login.php');
    } else if ($temp_role == 1) {
        header('location: ./doctor/login.php');
    } else if ($temp_role == 2) {
        header('location: ./moderator/login.php');
    } else if ($temp_role == 3) {
        header('location: ./user/login.php');
    }
} else {
    if ($from == 0) {
        header('location: ./admin/login.php');
    } else if ($from == 1) {
        header('location: ./doctor/login.php');
    } else if ($from == 2) {
        header('location: ./moderator/login.php');
    } else if ($from == 3) {
        header('location: ./user/login.php');
    }
}
