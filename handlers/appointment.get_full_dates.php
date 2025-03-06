<?php
session_start();

require_once('../classes/appointment.class.php');
$appointment = new Appointment();

$appArray = $appointment->get_appointments($_SESSION['doctor_id']);
