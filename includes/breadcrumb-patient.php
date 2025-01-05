<?php
$current_page = basename($_SERVER['PHP_SELF'], ".php");
?>

<div class="my-3">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="../doctor/patient-view" class="<?= $current_page == 'patient-view' ? 'current' : '' ?>">Patient Information</a>
            </li>
            <li class="breadcrumb-item">
                <a href="../doctor/patient-results" class="<?= $current_page == 'patient-results' ? 'current' : '' ?>">Results</a>
            </li>
            <li class="breadcrumb-item">
                <a href="../doctor/patient-files" class="<?= $current_page == 'patient-files' ? 'current' : '' ?>">Files</a>
            </li>
            <li class="breadcrumb-item">
                <a href="../doctor/patient-meeting" class="<?= $current_page == 'patient-meeting' ? 'current' : '' ?>">Meeting Record</a>
            </li>
        </ol>
    </nav>
</div>
