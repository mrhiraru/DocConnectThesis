<?php
require_once('../classes/account.class.php');
$account = new Account();

$record = $account->get_doctor_info_2($_GET['account_id']);

?>
<div class="d-flex justify-content-center col-12 col-md-auto mb-3 mb-md-0">
    <img id="account_image" src="../assets/images/default_profile.png" alt="Doctor Profile" width="125" height="125" class="rounded-circle border border-2 shadow-sm">
</div>
<div class="col-12 col-md-7">
    <p class="fs-6 fw-semibold text-dark mb-1 text-black-50">Name: <span class="text-black" id="doctor_name"><?= $record['doctor_name'] ?></span> </p>
    <p class="fs-6 fw-semibold text-dark mb-1 text-black-50">Specialty: <span class="text-black" id="specialty"><?= $record['specialty'] ?></span> </p>
    <p class="fs-6 fw-semibold text-dark mb-1 text-black-50">Contact: <span class="text-black" id="contact"><?= $record['contact'] ?></span> </p>
    <p class="fs-6 fw-semibold text-dark mb-1 text-black-50">Email: <span class="text-black" id="email"><?= $record['email'] ?></span> </p>
    <p class="fs-6 fw-semibold text-dark mb-1 text-black-50">Working Days: <span class="text-black" id="working_day"><?= $record['start_day'] . ' to ' . $record['end_day'] ?></span> </p>
    <p class="fs-6 fw-semibold text-dark mb-1 text-black-50">Working Time: <span class="text-black" id="working_time"><?= date("g:i A", strtotime($record['start_wt'])) . " to " . date("g:i A", strtotime($record['end_wt'])) ?></span> </p>
</div>