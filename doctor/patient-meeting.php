<!DOCTYPE html>
<html lang="en">
<?php
$title = 'Patient Meeting';
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
                <div class="p-0 m-0 text-end">
                    <button class="btn btn-primary text-white mb-2">Create New Meeting</button>
                </div>
                <div class="p-0 m-0 row">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">Time</th>
                                <th scope="col">Date</th>
                                <th scope="col">Link</th>
                                <th scope="col">Status</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>01:45 PM</td>
                                <td>2024-04-07</td>
                                <td><a href="https://meet.google.com/mno-rst-uvw">https://meet.google.com/mno-rst-uvw</a></td>
                                <td>Upcoming</td>
                                <td><a href="../doctor/meeting-view" class="text-primary bg-white">View</a></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>10:30 AM</td>
                                <td>2024-04-06</td>
                                <td><a href="https://meet.google.com/pqr-stu-vwx">https://meet.google.com/pqr-stu-vwx</a></td>
                                <td>Completed</td>
                                <td><a href="../doctor/meeting-view" class="text-primary bg-white">View</a></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>08:00 AM</td>
                                <td>2024-04-05</td>
                                <td><a href="https://meet.google.com/xyz-abc-def">https://meet.google.com/xyz-abc-def</a></td>
                                <td>Completed</td>
                                <td><a href="../doctor/meeting-view" class="text-primary bg-white">View</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
</body>

</html>