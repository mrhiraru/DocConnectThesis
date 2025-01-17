<!DOCTYPE html>
<html lang="en">
<?php
$title = 'Meeting View';
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
                        <p class="m-0 p-0 fs-5 text-dark fw-semibold">01:45 PM 2024-04-07 <button class="btn btn-primary text-white mb-2 float-end">Mark Meeting Completed</button></p>
                        <p class="m-0 p-0 fs-6 text-secondary">Link: <span class="text-primary"><a href="https://meet.google.com/mno-rst-uvw">https://meet.google.com/mno-rst-uvw</a></span></p>
                        <p class="m-0 p-0 fs-6 text-secondary mb-3">Status: <span class="text-dark">Upcoming</span></p>
                    </div>
                    <div class="col-12 mb-3 border-bottom">
                        <p class="m-0 p-0 fs-6 text-dark fw-semibold">Consultation Result</p>
                        <form action="" class="row">
                            <div class="col-12 my-3">
                                <label for="exampleFormControlInput1" class="form-label">Diagnosis:</label>
                                <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="">
                            </div>
                            <div class="col-12 mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Comment:</label>
                                <textarea id="w3review" name="w3review" rows="4" cols="50" class="form-control"></textarea>
                            </div>
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary text-white mb-3">Save Result</button>
                            </div>
                        </form>
                    </div>
                    <div class="col-12 mb-3">
                        <p class="m-0 p-0 fs-6 text-dark fw-semibold mb-2">Prescription <button type="submit" class="float-end bg-white text-primary" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class='bx bx-plus-circle me-2'></i>Add Medication</button></p>
                        <div class="mb-2">
                            <p class="m-0 p-0 fs-6 text-secondary">Drug: <span class="text-dark">Amoxicillin</span><button type="submit" class="mb-3 float-start bg-white text-primary float-end"><i class='bx bx-x-circle me-2'></i>Remove</button></p>
                            <p class="m-0 p-0 fs-6 text-secondary">Dosage: <span class="text-dark">10 mg</span></p>
                            <p class="m-0 p-0 fs-6 text-secondary">Instruction: <span class="text-dark">Take 1 capsule orally every 8 hours for 10 days.</span></p>
                        </div>
                        <div class="mb-2">
                            <p class="m-0 p-0 fs-6 text-secondary">Drug: <span class="text-dark">Loratadine</span><button type="submit" class="mb-3 float-start bg-white text-primary float-end"><i class='bx bx-x-circle me-2'></i>Remove</button></p>
                            <p class="m-0 p-0 fs-6 text-secondary">Dosage: <span class="text-dark">500 mg</span></p>
                            <p class="m-0 p-0 fs-6 text-secondary">Instruction: <span class="text-dark">Take 1 tablet orally once daily as needed for allergy symptoms.</span></p>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-6" id="exampleModalLabel">New Medication</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" class="row">
                        <div class="col-12 mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Drug:</label>
                            <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Dosage:</label>
                            <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Instruction:</label>
                            <textarea id="w3review" name="w3review" rows="4" cols="50" class="form-control"></textarea>
                        </div>
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary text-white mb-3">Save Medication</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>