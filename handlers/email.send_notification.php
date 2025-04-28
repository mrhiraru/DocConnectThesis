<?php
require_once('../tools/mailer.php');
require_once('../classes/appointment.class.php');

$appointment = new Appointment();

$appointment_details = $appointment->get_appointment_fulldetails($_POST['appointment_id']);
email_notification($appointment_details['appointment_date'], $appointment_details['appointment_time'], $appointment_details['doctor_email'], $appointment_details['patient_name'], $appointment_details['patient_email'], $appointment_details['doctor_name'], $appointment_details['appointment_link'], 'confirm');
