<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
    header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 1) {
    header('location: ../index.php');
    exit();
}

require_once('../classes/patient.class.php');
require_once('../classes/immunization.class.php');
require_once('../classes/allergy.class.php');
require_once('../classes/medication.class.php');
require_once('../classes/medical_history.class.php');
require_once('../tools/functions.php');

$patient = new Patient();
$record = $patient->fetch_patient($_GET['account_id']);

?>

<!DOCTYPE html>
<html lang="en">
<?php
$title = 'Patient View';
$patient = 'active';
include '../includes/head.php';
?>

<body>
    <?php
    require_once('../includes/header-doctor.php');
    ?>
    <div class="container-fluid">
        <div class="row">
            <?php
            require_once('../includes/sidepanel-doctor.php');
            ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mb-4">
                <?php
                require_once('../includes/breadcrumb-patient.php');
                ?>
                <div class="p-0 m-0 row">
                    <div class="col-12 mb-3 border-bottom">
                        <p class="m-0 p-0 fs-5 text-dark fw-semibold"><?= $record['patient_name'] ?></p>
                        <p class="m-0 p-0 fs-6 text-secondary">Age: <span class="text-dark"><?= get_age($record['birthdate']) ?> </span></p>
                        <p class="m-0 p-0 fs-6 text-secondary">Date of Birth: <span class="text-dark"><?= date('F j, Y', strtotime($record['birthdate'])) ?></span></p>
                        <p class="m-0 p-0 fs-6 text-secondary">Address: <span class="text-dark"><?= $record['address'] ?></span></p>
                        <p class="m-0 p-0 fs-6 text-secondary">Contact: <span class="text-dark"><?= $record['contact'] ?></span></p>
                        <p class="m-0 p-0 fs-6 text-secondary mb-3">Email: <span class="text-dark"><?= $record['email'] ?></span></p>
                        <p class="m-0 p-0 fs-6 text-secondary">Height(cm): <span class="text-dark"><?= $record['height'] ?></span></p>
                        <p class="m-0 p-0 fs-6 text-secondary mb-3">Weight(kg): <span class="text-dark"><?= $record['weight'] ?></span></p>
                        <p class="m-0 p-0 fs-6 fw-semibold text-dark mb-2">Parent | Guardian</p>
                        <p class="m-0 p-0 fs-6 text-secondary">Name: <span class="text-dark"><?= $record['parent_name'] ?></span></p>
                        <p class="m-0 p-0 fs-6 text-secondary mb-3">Contact: <span class="text-dark"><?= $record['parent_contact'] ?></span></p>
                    </div>
                    <!-- <div class="col-12 mb-3 border-bottom">
                        <p class="m-0 p-0 fs-6 text-dark fw-semibold mb-2">Vitals <span class="fs-6 text-secondary fw-normal ">(April 1, 2024 12:00 PM)</span></p>
                        <p class="m-0 p-0 fs-6 text-secondary">Heart Rate (Pulse): <span class="text-dark">72 beats per minute</span></p>
                        <p class="m-0 p-0 fs-6 text-secondary">Respiratory Rate: <span class="text-dark">16 breaths per minute</span></p>
                        <p class="m-0 p-0 fs-6 text-secondary">Blood Pressure: <span class="text-dark">120/80 mmHg</span></p>
                        <p class="m-0 p-0 fs-6 text-secondary">Temperature: <span class="text-dark">98.6°F</span></p>
                        <p class="m-0 p-0 fs-6 text-secondary">Height: <span class="text-dark">1.65 m</span></p>
                        <p class="m-0 p-0 fs-6 text-secondary">Weight: <span class="text-dark">65 kg</span></p>
                        <p class="m-0 p-0 fs-6 text-secondary mb-3">BMI: <span class="text-dark">23.86 (Normal)</span></p>
                    </div> -->
                    <div class="col-6 mb-3">
                        <p class="m-0 p-0 fs-6 fw-semibold text-dark mb-2">Medical History:</p>
                        <?php
                        $medhis = new MedHis();
                        $hisArray = $medhis->get_medical_history($record['patient_id']);
                        if (!empty($hisArray)) {
                            foreach ($hisArray as $item) {
                        ?>
                                <p class="m-0 p-0 fs-6 text-dark">- <?= $item['his_condition'] ?> (<?= date('F j, Y', strtotime($record['diagnosis_date'])) ?>)</p>

                            <?php
                            }
                        } else {
                            ?>
                            <p class="m-0 p-0 fs-6 text-dark"> - No History - </p>
                        <?php
                        }
                        ?>
                    </div>
                    <div class="col-6 mb-3">
                        <p class="m-0 p-0 fs-6 fw-semibold text-dark mb-2">Allergies:</p>
                        <?php
                        $allergy = new Allergy();
                        $allergyArray = $allergy->get_allergy($record['patient_id']);
                        if (!empty($allergyArray)) {
                            foreach ($allergyArray as $item) {
                        ?>
                                <p class="m-0 p-0 fs-6 text-dark">- <?= $item['allergy_name'] ?> (<?= $item['description'] ?>)</p>
                            <?php
                            }
                        } else {
                            ?>
                            <p class="m-0 p-0 fs-6 text-dark"> - No Allergy - </p>
                        <?php
                        }
                        ?>
                    </div>
                    <div class=" col-6 mb-3">
                        <p class="m-0 p-0 fs-6 fw-semibold text-dark mb-2">Medication:</p>
                        <?php
                        $medication = new Medication();
                        $medArray = $medication->get_medication($record['patient_id']);
                        if (!empty($medArray)) {
                            foreach ($medArray as $item) {
                        ?>
                                <p class="m-0 p-0 fs-6 text-dark">- <?= $item['medication_name'] ?>: <?= $item['description'] ?></p>
                            <?php
                            }
                        } else {
                            ?>
                            <p class="m-0 p-0 fs-6 text-dark"> - No Medication - </p>
                        <?php
                        }
                        ?>
                    </div>
                    <div class=" col-6 mb-3">
                        <p class="m-0 p-0 fs-6 fw-semibold text-dark mb-2">Immunization:</p>
                        <?php
                        $immunization = new Immunization();
                        $immuArray = $immunization->get_immunization($record['patient_id']);
                        if (!empty($immuArray)) {
                            foreach ($immuArray as $item) {
                        ?>
                                <p class="m-0 p-0 fs-6 text-dark">- <?= $item['immunization_name'] ?></p>
                            <?php
                            }
                        } else {
                            ?>
                            <p class="m-0 p-0 fs-6 text-dark"> - No immunization - </p>
                        <?php
                        }
                        ?>

                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>