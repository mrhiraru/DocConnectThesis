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

$dashboard = new Dashboard();
$overviewData = $dashboard->fetchOverviewData();
$chartData = $dashboard->fetchPatientSummaryChartData();
$nextPatient = $dashboard->fetchNextPatientDetails();
$todayAppointments = $dashboard->fetchTodayAppointmentsDetails();
$appointmentRequests = $dashboard->fetchAppointmentRequests();
$calendarEvents = $dashboard->fetchCalendarEvents();


// Prepare labels and data for the chart
$chartLabels = [];
$chartValues = [];

if (!empty($chartData)) {
    foreach ($chartData as $item) {
        $chartLabels[] = $item['diagnosis']; // Labels for the chart (e.g., diagnosis names)
        $chartValues[] = $item['count']; // Data for the chart (e.g., number of patients)
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php
$title = 'Dashboard';
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
                    <!-- Overview Cards -->
                    <div class="row">
                        <div class="col-md-4 col-12 d-flex align-items-stretch mb-4">
                            <div class="card overview border-0 text-start d-flex justify-content-center p-3 w-100">
                                <div class="d-flex flex-sm-row flex-md-column flex-lg-row justify-content-center align-items-center">
                                    <div class="card-title">
                                        <div class="border border-5 rounded-5 border-primary me-3">
                                            <i class='bx bxs-user display-3 p-3 text-primary'></i>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        <p class="card-title mb-0">Total Patients</p>
                                        <p class="card-text display-6 mb-0"><?php echo $overviewData['total_patients']; ?></p>
                                        <p class="text-muted">Till Today</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-12 d-flex align-items-stretch mb-4">
                            <div class="card overview border-0 text-start d-flex justify-content-center p-3 w-100">
                                <div class="d-flex flex-sm-row flex-md-column flex-lg-row justify-content-center align-items-center">
                                    <div class="card-title">
                                        <div class="border border-5 rounded-5 border-primary me-3">
                                            <i class='bx bxs-user-plus display-3 p-3 text-primary'></i>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        <p class="card-title mb-0">Today Patients</p>
                                        <p class="card-text display-6 mb-0"><?php echo $overviewData['today_patients']; ?></p>
                                        <p class="text-muted"><?php echo date('M d Y'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-12 d-flex align-items-stretch mb-4">
                            <div class="card overview border-0 text-start d-flex justify-content-center p-3 w-100">
                                <div class="d-flex flex-sm-row flex-md-column flex-lg-row justify-content-center align-items-center">
                                    <div class="card-title">
                                        <div class="border border-5 rounded-5 border-primary me-3">
                                            <i class='bx bxs-calendar display-3 p-3 text-primary'></i>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        <p class="card-title mb-0">Today Appointments</p>
                                        <p class="card-text display-6 mb-0"><?php echo $overviewData['today_appointments']; ?></p>
                                        <p class="text-muted"><?php echo date('M d Y'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chart and Appointments -->
                    <div class="card chart_appointment border-0 mb-4">
                        <div class="row g-0">
                            <div class="col-lg-6 d-flex">
                                <div class="card bg-transparent border-0 flex-fill">
                                    <div class="card-body">
                                        <h5 class="card-title">Patients Summary <?php echo date('F Y'); ?></h5>
                                        <canvas id="patientSummaryChart" width="350" height="350"></canvas>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 d-flex">
                                <div class="card next_patient border-0 w-100 m-4">
                                    <div class="card-body">
                                        <div class="text-center mb-3">
                                            <h5 class="text-primary">Next Patient Details</h5>
                                        </div>
                                        <?php if ($nextPatient): ?>
                                            <div class="d-flex flex-column flex-md-row align-items-center">
                                                <img src="<?php echo !empty($nextPatient['account_image']) ? '../assets/images/' . $nextPatient['account_image'] : '../assets/images/profilenono.jpeg'; ?>" class="rounded-circle" alt="Patient Image" style="width: 80px; height: 80px; object-fit: cover;">
                                                <div class="ms-3">
                                                    <h6 class="mb-0"><?php echo $nextPatient['firstname'] . ' ' . $nextPatient['lastname']; ?></h6>
                                                    <p class="text-muted mb-0"><?php echo isset($nextPatient['purpose']) ? $nextPatient['purpose'] : 'No purpose specified'; ?></p>
                                                </div>
                                                <div class="ms-3">
                                                    <strong>Patient ID</strong>
                                                    <p class="text-muted mb-0"><?php echo $nextPatient['patient_id']; ?></p>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row row-cols-2 row-cols-md-3 mb-3">
                                                <div class="col">
                                                    <strong>Birthdate</strong>
                                                    <p class="text-muted"><?php echo (new DateTime($nextPatient['birthdate']))->format('F j, Y'); ?></p>
                                                </div>
                                                <div class="col">
                                                    <strong>Sex</strong>
                                                    <p class="text-muted"><?php echo $nextPatient['gender']; ?></p>
                                                </div>
                                                <div class="col">
                                                    <strong>Weight</strong>
                                                    <p class="text-muted"><?php echo $nextPatient['weight']; ?> Kg</p>
                                                </div>
                                                <div class="col">
                                                    <strong>Height</strong>
                                                    <p class="text-muted"><?php echo $nextPatient['height']; ?> cm</p>
                                                </div>
                                                <div class="col">
                                                    <strong>Last Appointment</strong>
                                                    <p class="text-muted"><?php echo (new DateTime($nextPatient['appointment_date']))->format('F j, Y'); ?></p>
                                                </div>
                                                <div class="col">
                                                    <strong>Reg. Date</strong>
                                                    <p class="text-muted"><?php echo (new DateTime($nextPatient['is_created']))->format('F j, Y'); ?></p>
                                                </div>
                                            </div>
                                            <hr>
                                            <h6>Patient History</h6>
                                            <div class="d-flex mb-3">
                                                <?php
                                                $diagnoses = explode(',', $nextPatient['diagnosis']);
                                                foreach ($diagnoses as $diagnosis):
                                                    echo '<span class="badge bg-warning text-dark me-2">' . trim($diagnosis) . '</span>';
                                                endforeach;
                                                ?>
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                <a href="./patient-files?account_id=<?php echo $nextPatient['account_id']; ?>" class="btn btn-primary me-3 d-flex align-items-center text-light">
                                                    <i class="bx bx-file me-2"></i>Document
                                                </a>
                                                <a href="./chats?account_id=<?php echo $nextPatient['account_id']; ?>" class="btn btn-outline-primary me-3 d-flex align-items-center">
                                                    <i class="bx bx-chat me-2"></i>Chat
                                                </a>
                                            </div>
                                        <?php else: ?>
                                            <p>No upcoming appointments.</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Next Patient and Prescriptions -->
                    <div class="row d-flex">
                        <div class="col-lg-6 mb-4 d-flex">
                            <div class="card border-primary flex-fill">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">Today Appointment</h5>
                                    <hr>
                                    <table class="table table-borderless">
                                        <thead>
                                            <tr>
                                                <th>Patient</th>
                                                <th>Name/ Purpose</th>
                                                <th>Time</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($todayAppointments)): ?>
                                                <?php foreach ($todayAppointments as $appointment): ?>
                                                    <tr>
                                                        <td>
                                                            <img src="<?php echo !empty($appointment['account_image']) ? '../assets/images/' . $appointment['account_image'] : '../assets/images/defualt_profile.png'; ?>"
                                                                class="rounded-circle" alt="Patient Image" height="40">
                                                        </td>
                                                        <td>
                                                            <strong class="text-primary"><?php echo $appointment['firstname'] . ' ' . $appointment['lastname']; ?></strong><br>
                                                            <small><?php echo $appointment['purpose']; ?></small>
                                                        </td>
                                                        <td>
                                                            <?php if ($appointment['appointment_status'] === 'On Going'): ?>
                                                                <span class="badge bg-info">On Going</span>
                                                            <?php else: ?>
                                                                <?php echo date('h:i A', strtotime($appointment['appointment_time'])); ?>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="3" class="text-center">No appointments today.</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                    <a href="#" class="text-primary">See All</a>
                                </div>
                            </div>
                        </div>

                        <!-- Appointment Request and Calendar -->
                        <div class="col-lg-6 mb-4 d-flex">
                            <div class="card w-100 h-100 border-primary">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">Appointment Request</h5>
                                    <hr>
                                    <table class="table table-borderless">
                                        <tbody>
                                            <?php if (!empty($appointmentRequests)): ?>
                                                <?php foreach ($appointmentRequests as $request): ?>
                                                    <tr>
                                                        <td>
                                                            <img src="<?php echo !empty($request['account_image']) ? '../assets/images/' . $request['account_image'] : 'https://via.placeholder.com/40'; ?>"
                                                                class="rounded-circle" alt="Patient Image" height="40">
                                                        </td>
                                                        <td>
                                                            <strong class="text-primary"><?php echo $request['firstname'] . ' ' . $request['lastname']; ?></strong><br>
                                                            <small><?php echo $request['diagnosis']; ?></small>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex justify-content-end">
                                                                <a href="./manage-appointment.php?appointment_id=<?php echo $request['appointment_id']; ?>">
                                                                    <i class='bx bx-edit text-light btn btn-warning py-1 px-2 mx-1'></i>
                                                                </a>
                                                                <a href="./chats?account_id=<?php echo $request['account_id']; ?>">
                                                                    <i class='bx bxs-message-dots text-light btn btn-info py-1 px-2 mx-1'></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="3" class="text-center">No appointment requests.</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                    <a href="#" class="text-primary">See All</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CALENDAR -->
                    <div class="row" id="calendar">
                        <div class="col-md-12 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Calendar - <?php echo date('F Y'); ?></h5>
                                    <div id="calendar"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </main>
        </div>
    </div>
    <!-- Chart.js Script -->
    <script>
        const chartLabels = <?php echo json_encode($chartLabels); ?>;
        const chartData = <?php echo json_encode($chartValues); ?>;

        const ctx = document.getElementById('patientSummaryChart').getContext('2d');
        const patientSummaryChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Number of Patients',
                    data: chartData,
                    backgroundColor: [
                        'rgba(220, 53, 69, 0.2)',
                        'rgba(33, 191, 115, 0.2)',
                        'rgba(0, 127, 79, 0.2)',
                        'rgba(255, 255, 15, 0.2)',
                        'rgba(220, 53, 69, 0.2)',
                        'rgba(33, 191, 115, 0.2)'
                    ],
                    borderColor: [
                        'rgba(220, 53, 69, 1)',
                        'rgba(33, 191, 115, 1)',
                        'rgba(0, 127, 79, 1)',
                        'rgb(255, 255, 15)',
                        'rgba(220, 53, 69, 1)',
                        'rgba(33, 191, 115, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: window.innerWidth < 768 ? 'listWeek' : 'dayGridMonth',
                themeSystem: 'Lux',
                editable: true,
                selectable: true,
                timeZone: 'UTC',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                events: <?php echo json_encode($calendarEvents); ?>,

                windowResize: function(view) {
                    if (window.innerWidth < 768) {
                        calendar.changeView('listWeek');
                    } else {
                        calendar.changeView('dayGridMonth');
                    }
                }
            });

            calendar.render();
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: window.innerWidth < 768 ? 'listWeek' : 'dayGridMonth',
                themeSystem: 'Lux',
                editable: true,
                selectable: true,
                timeZone: 'UTC',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                events: <?php echo json_encode($calendarEvents); ?>,

                windowResize: function(view) {
                    if (window.innerWidth < 768) {
                        calendar.changeView('listWeek');
                    } else {
                        calendar.changeView('dayGridMonth');
                    }
                }
            });

            calendar.render();
        });
    </script>

</body>

</html>