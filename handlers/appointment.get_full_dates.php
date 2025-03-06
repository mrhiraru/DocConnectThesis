<?php
session_start();

require_once('../classes/appointment.class.php');
$appointment = new Appointment();

$appArray = $appointment->get_full_dates($_SESSION['doctor_id'], $_GET['start_wt'], $_GET['end_wt']);
