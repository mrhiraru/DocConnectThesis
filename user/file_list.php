<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
    header('location: ../user/verification.php');
    exit();
}

require_once('../tools/functions.php');
require_once('../classes/account.class.php');
require_once('../classes/file.class.php');

$file = new File();
if (isset($_POST['upload_document'])) {

    $account_class->account_id = $_SESSION['account_id'];

    $uploaddir = '../assets/files/';
    $uploadname = $_FILES[htmlentities('documentname')]['name'];
    $uploadext = explode('.', $uploadname);
    $uploadnewext = strtolower(end($uploadext));
    $allowed = array('pdf', 'doc', 'xls', 'xlsx', 'docx');

    if (in_array($uploadnewext, $allowed)) {

        $uploadenewname = reset($uploadext) . date('Ymd_His') . "." . $uploadnewext;
        $uploadfile = $uploaddir . $uploadenewname;

        if (move_uploaded_file($_FILES[htmlentities('documentname')]['tmp_name'], $uploadfile)) {

            $file->file_name = $uploadenewname;
            $file->file_description = htmlentities($_POST['documentDescription']);
            $file->sender_id = $_SESSION['account_id'];
            $file->receiver_id = $_GET['account_id'];
            if ($file->add_file()) {
                $success = 'success';
            } else {
                echo 'An error occured while adding in the database.';
            }
        } else {
            $success = 'failed';
        }
    } else {
        $success = 'failed';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php
$title = 'DocConnect | File Upload';
include '../includes/head.php';
?>

<body>
    <?php require_once('../includes/header.php'); ?>

    <section class="page-container padding-medium">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card border-0 shadow p-3">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="text-dark fw-semibold mb-0">Doctor Uploads</h6>
                            </div>
                            <table class="table table-hover doctor-files">
                                <thead>
                                    <tr>
                                        <th>File Name</th>
                                        <th>Description</th>
                                        <th>Uploaded By</th>
                                        <th>Date Uploaded</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><a href="#" class="file-link">Test_Result_April_2024.pdf</a></td>
                                        <td>Annual blood test results</td>
                                        <td>Dr. superman</td>
                                        <td>2024-04-15</td>
                                    </tr>
                                    <tr>
                                        <td><a href="#" class="file-link">Prescription_March_2024.pdf</a></td>
                                        <td>Medication prescription</td>
                                        <td>Dr. btamn</td>
                                        <td>2024-03-22</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Patient Uploads Table -->
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="text-dark fw-semibold mb-0">Patient Uploads</h6>
                            </div>
                            <table class="table table-hover patient-files">
                                <thead>
                                    <tr>
                                        <th>File Name</th>
                                        <th>Description</th>
                                        <th>Uploaded By</th>
                                        <th>Date Uploaded</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><a href="#" class="file-link">Patient_Record_Jane_Smith_April_2024.pdf</a></td>
                                        <td>Medical history update</td>
                                        <td>name</td>
                                        <td>2024-04-10</td>
                                    </tr>
                                    <tr>
                                        <td><a href="#" class="file-link">Jane_Smith_XRay_Chest_January_2024.pdf</a></td>
                                        <td>Chest X-ray results</td>
                                        <td>name</td>
                                        <td>2024-01-15</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <?php require_once('../includes/footer.php'); ?>
</body>

</html>