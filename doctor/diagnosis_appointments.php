<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
    header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 1) {
    header('location: ./login.php');
}

require_once('../tools/functions.php');
require_once('../classes/account.class.php');
require_once('../classes/doctorDashboard.class.php');

$diagnosis = isset($_GET['diagnosis']) ? urldecode($_GET['diagnosis']) : '';

$dashboard = new Dashboard();
$appointments = $dashboard->fetchAppointmentsByDiagnosis($diagnosis);

?>

<!DOCTYPE html>
<html lang="en">
<?php
$title = 'Diagnosis Appointments';
$dashboard = 'active';
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
            <main class="col-md-9 ms-sm-auto col-lg-10 bg-light">
                <div class="container my-4">
                    <h2>Appointments for Diagnosis: <?php echo htmlspecialchars($diagnosis); ?></h2>
                    <table id="diagnosisTable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Contact</th>
                                <th>Appointment Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($appointments)): ?>
                                <?php foreach ($appointments as $appointment): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($appointment['firstname'] . ' ' . $appointment['lastname']); ?></td>
                                        <td><?php echo htmlspecialchars($appointment['email']); ?></td>
                                        <td><?php echo htmlspecialchars($appointment['contact']); ?></td>
                                        <td><?php echo htmlspecialchars((new DateTime($appointment['appointment_date']))->format('F j, Y')); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center">No appointments found for this diagnosis.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>=

    <style>
        .dt-buttons{
            margin-bottom: 5px;
        }

        .dt-buttons .btn {
            background-color: #dc3545 !important;
            color: white !important;
            border: none !important;
        }

        .dt-buttons .btn:hover {
            background-color: #c82333 !important;
            color: white !important;
        }

        .dt-buttons .btn:focus,
        .dt-buttons .btn:active {
            background-color: #bd2130 !important;
            color: white !important;
            box-shadow: none !important;
        }
    </style>

    <script>
        $(document).ready(function() {
            $('#diagnosisTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                responsive: true,
                language: {
                    search: "Search:",
                    paginate: {
                        next: "Next",
                        previous: "Previous"
                    }
                }
            });
        });
    </script>
</body>

</html>