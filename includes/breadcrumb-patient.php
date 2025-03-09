<?php
$current_page = basename($_SERVER['PHP_SELF'], ".php");
?>

<div class="my-3">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="../doctor/patient-view?account_id=<?= $_GET['account_id'] ?>" class="<?= $current_page == 'patient-view' ? 'current' : '' ?>">Patient Information</a>
            </li>
            <li class="breadcrumb-item">
                <a href="../doctor/patient-results?account_id=<?= $_GET['account_id'] ?>" class="<?= $current_page == 'patient-results' ? 'current' : '' ?>">Results</a>
            </li>
            <li class="breadcrumb-item d-none">
                <a href="../doctor/patient-files?account_id=<?= $_GET['account_id'] ?>" class="<?= $current_page == 'patient-files' ? 'current' : '' ?>">Files</a>
            </li>
            <li class="breadcrumb-item">
                <a href="../doctor/patient-appointment?account_id=<?= $_GET['account_id'] ?>" class="<?= $current_page == 'patient-meeting' ? 'current' : '' ?>">Appointment Record</a>
            </li>
        </ol>
    </nav>
</div>