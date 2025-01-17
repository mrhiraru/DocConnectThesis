<!DOCTYPE html>
<html lang="en">
<?php
$title = 'Patient Results';
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
                    <p class="m-0 p-0 fs-5 text-dark fw-semibold mb-2">Latest Consultation Result</p>
                    <div class="col-12 pt-2">
                        <p class="m-0 p-0 fs-5 text-secondary mb-2">Date: <span class="text-dark">2024-04-07</span></p>
                        <div class="mb-2">
                            <p class="m-0 p-0 fs-6 text-secondary">Diagnosis: <span class="text-dark">Suspected bacterial infection requiring antibiotic treatment.</span></p>
                            <p class="m-0 p-0 fs-6 text-secondary">Comment: <span class="text-dark">After our discussion and examining your symptoms, it looks like you might have a bacterial infection going on. We're going to start you on some Amoxicillin to help clear that up. Remember to take one capsule every 8 hours for the next 10 days, okay? Additionally, I'm prescribing Loratadine for your allergy symptoms. You can take one tablet once daily if you need it for those pesky allergies. If you have any questions or concerns, don't hesitate to give us a call. We'll touch base again in 10 days to see how you're doing. Take care!</span></p>
                        </div>
                        <p class="m-0 p-0 fs-6 text-dark fw-semibold mb-2">Prescriptions </p>
                        <div class="mb-2">
                            <p class="m-0 p-0 fs-6 text-secondary">Drug: <span class="text-dark">Amoxicillin</span></p>
                            <p class="m-0 p-0 fs-6 text-secondary">Dosage: <span class="text-dark">10 mg</span></p>
                            <p class="m-0 p-0 fs-6 text-secondary">Instruction: <span class="text-dark">Take 1 capsule orally every 8 hours for 10 days.</span></p>
                        </div>
                        <div class="mb-2">
                            <p class="m-0 p-0 fs-6 text-secondary">Drug: <span class="text-dark">Loratadine</span></p>
                            <p class="m-0 p-0 fs-6 text-secondary">Dosage: <span class="text-dark">500 mg</span></p>
                            <p class="m-0 p-0 fs-6 text-secondary">Instruction: <span class="text-dark">Take 1 tablet orally once daily as needed for allergy symptoms.</span></p>
                        </div>
                    </div>
                    <div class="col-12 pt-2 border-bottom"></div>
                    <div class="col-12 pt-2">
                        <p class="m-0 p-0 fs-5 text-secondary mb-2">Date: <span class="text-dark">2024-04-07</span></p>
                        <div class="mb-2">
                            <p class="m-0 p-0 fs-6 text-secondary">Diagnosis: <span class="text-dark">Acute sinusitis with allergic rhinitis exacerbation.</span></p>
                            <p class="m-0 p-0 fs-6 text-secondary">Comment: <span class="text-dark">It seems like you're dealing with a bit of a sinus issue along with some allergies flaring up. Based on your symptoms and examination, we're going to tackle this with a two-pronged approach. First off, I'm prescribing you Amoxicillin to help clear up that sinus infection. You'll want to take one capsule orally every 8 hours for the next 10 days. Additionally, to ease those allergy symptoms, I'm recommending Loratadine. Just pop one tablet orally once daily whenever you feel those allergies acting up. Remember to stay hydrated and get plenty of rest. If you have any questions, feel free to reach out. Take care!</span></p>
                        </div>
                        <p class="m-0 p-0 fs-6 text-dark fw-semibold mb-2">Prescriptions </p>
                        <div class="mb-2">
                            <p class="m-0 p-0 fs-6 text-secondary">Drug: <span class="text-dark">Amoxicillin</span></p>
                            <p class="m-0 p-0 fs-6 text-secondary">Dosage: <span class="text-dark">10 mg</span></p>
                            <p class="m-0 p-0 fs-6 text-secondary">Instruction: <span class="text-dark">Take 1 capsule orally every 8 hours for 10 days.</span></p>
                        </div>
                        <div class="mb-2">
                            <p class="m-0 p-0 fs-6 text-secondary">Drug: <span class="text-dark">Loratadine</span></p>
                            <p class="m-0 p-0 fs-6 text-secondary">Dosage: <span class="text-dark">500 mg</span></p>
                            <p class="m-0 p-0 fs-6 text-secondary">Instruction: <span class="text-dark">Take 1 tablet orally once daily as needed for allergy symptoms.</span></p>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>