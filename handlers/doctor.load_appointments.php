<?php

require_once('../classes/appointment.class.php');

$appointment_class = new Appointment();

?>
<div class="d-flex justify-content-end mt-2">
    <button class="btn btn-green btn-sm me-2 text-light" onclick="exportTableToExcel('eventsTable')">Export to Excel</button>
    <button class="btn btn-danger btn-sm text-light" onclick="exportTableToPDF('eventsTable')">Export to PDF</button>
</div>

<div class="table-responsive">
    <table class="table table-striped" id="eventsTable">
        <thead>
            <tr>
                <th></th>
                <th>Date & Time</th>
                <th>Patient</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $appointmentArray = $appointment_class->doctor_appointments($_GET['doctor_id'], $_GET['status']);
            $counter = 1;
            if (!empty($appointmentArray)) {
                foreach ($appointmentArray as $item) {
            ?>
                    <tr>
                        <td><?= $counter ?></td>
                        <td><?= date("l, M d, Y", strtotime($item['appointment_date'])) . " " . date("g:i A", strtotime($item['appointment_time'])) ?></td>
                        <td><?= $item['patient_name'] ?></td>
                        <td class="text-center">
                            <?php
                            if ($item['appointment_status'] == 'Incoming') {
                            ?>
                                <a href="./appointment-view.php?account_id=<?= $item['account_id'] ?>&appointment_id=<?= $item['appointment_id'] ?>" class="btn btn-success btn-sm text-light"><i class='bx bx-play-circle me-1'></i>View</a>
                                <a href="./manage-appointment.php?appointment_id=<?= $item['appointment_id'] ?>" class="btn btn-warning btn-sm text-light"><i class='bx bxs-edit me-1'></i>Update</a>
                            <?php
                            } else if ($item['appointment_status'] == 'Ongoing') {
                            ?>
                                <a href="./appointment-view.php?account_id=<?= $item['account_id'] ?>&appointment_id=<?= $item['appointment_id'] ?>" class="btn btn-success btn-sm text-light"><i class='bx bx-play-circle me-1'></i>View</a>
                            <?php
                            } else if ($item['appointment_status'] == 'Pending') {
                            ?>
                                <a href="./manage-appointment.php?appointment_id=<?= $item['appointment_id'] ?>" class="btn btn-warning btn-sm text-light"><i class='bx bxs-edit me-1'></i>Update</a>
                            <?php
                            } else if ($item['appointment_status'] == 'Completed') {
                            ?>
                                <a href="./appointment-view.php?account_id=<?= $item['account_id'] ?>&appointment_id=<?= $item['appointment_id'] ?>" class="btn btn-info btn-sm text-light"><i class='bx bx-file-blank me-1'></i>Result</a>
                            <?php
                            } else if ($item['appointment_status'] == 'Cancelled') {
                            ?>
                                <a href="./manage-appointment.php?appointment_id=<?= $item['appointment_id'] ?>" class="btn btn-danger btn-sm text-light"><i class='bx bxs-edit me-1'></i>Reschedule</a>
                            <?php
                            }
                            ?>
                        </td>
                    </tr>
                <?php
                    $counter++;
                }
            } else {
                ?>
                <tr>
                    <td colspan="5" class="text-center">No <?= $_GET['status'] ?> Appointments</td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
    function exportTableToExcel(tableID) {
        setTimeout(() => {
            const table = document.getElementById(tableID);
            if (!table) return alert('Table not found!');

            const clonedTable = table.cloneNode(true);
            const rows = clonedTable.querySelectorAll("tr");

            rows.forEach(row => {
                const actionCell = row.querySelector("td:last-child, th:last-child");
                if (actionCell) actionCell.remove();
            });

            const wb = XLSX.utils.table_to_book(clonedTable, {
                sheet: "Appointments"
            });

            const ws = wb.Sheets["Appointments"];
            const range = XLSX.utils.decode_range(ws['!ref']);
            const colWidths = [];

            for (let C = range.s.c; C <= range.e.c; ++C) {
                let maxWidth = 10;
                for (let R = range.s.r; R <= range.e.r; ++R) {
                    const cellAddress = {
                        c: C,
                        r: R
                    };
                    const cellRef = XLSX.utils.encode_cell(cellAddress);
                    const cell = ws[cellRef];
                    if (cell && cell.v != null) {
                        const cellValue = cell.v.toString();
                        maxWidth = Math.max(maxWidth, cellValue.length + 5);
                    }
                }
                colWidths.push({
                    wch: maxWidth
                });
            }
            ws['!cols'] = colWidths;

            const status = new URLSearchParams(window.location.search).get('status') || 'appointments';
            const filename = `${capitalizeFirstLetter(status)}_Appointments.xlsx`;

            XLSX.writeFile(wb, filename);
        }, 100);
    }

    function exportTableToPDF(tableID) {
        setTimeout(() => {
            const table = document.getElementById(tableID);
            if (!table) return alert('Table not found!');

            const clonedTable = table.cloneNode(true);
            const rows = clonedTable.querySelectorAll("tr");

            rows.forEach(row => {
                const actionCell = row.querySelector("td:last-child, th:last-child");
                if (actionCell) actionCell.remove();
            });

            const status = new URLSearchParams(window.location.search).get('status') || 'appointments';
            const filename = `${capitalizeFirstLetter(status)}_Appointments.pdf`;

            const opt = {
                margin: 0.5,
                filename: filename,
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2
                },
                jsPDF: {
                    unit: 'in',
                    format: 'letter',
                    orientation: 'landscape'
                }
            };

            html2pdf().from(clonedTable).set(opt).save();
        }, 100);
    }

    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }
</script>