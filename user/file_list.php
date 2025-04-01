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
                    <div class="card border-0 shadow">
                        <table class="table table-hover doctor-files">
                            <thead>
                                <tr>
                                    <th>File Name</th>
                                    <th>Description</th>
                                    <th>Sender</th>
                                    <th>Date Sent</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><a href="#" class="file-link">Test_Result_April_2024.pdf</a></td>
                                    <td>Annual blood test results</td>
                                    <td>Dr. superman</td>
                                    <td>2024-04-15</td>
                                    <td>
                                        <button class="btn btn-sm btn-view" data-bs-toggle="modal" data-bs-target="#fileModal"
                                            data-filename="Test_Result_April_2024.pdf"
                                            data-description="Annual blood test results"
                                            data-sender="Dr. Smith"
                                            data-date="2024-04-15">
                                            <i class='bx bx-show'></i> View
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><a href="#" class="file-link">Prescription_March_2024.pdf</a></td>
                                    <td>Medication prescription</td>
                                    <td>Dr. btamn</td>
                                    <td>2024-03-22</td>
                                    <td>
                                        <button class="btn btn-sm btn-view" data-bs-toggle="modal" data-bs-target="#fileModal"
                                            data-filename="Prescription_March_2024.pdf"
                                            data-description="Medication prescription"
                                            data-sender="Dr. Johnson"
                                            data-date="2024-03-22">
                                            <i class='bx bx-show'></i> View
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <?php require_once('../includes/footer.php'); ?>
</body>

</html>