<script type="module">
    import "../node_modules/pdfmake/build/pdfmake.js";
    import "../node_modules/pdfmake/build/vfs_fonts.js";

    document.getElementById("download_pdf").addEventListener("click", () => {
        var docDefinition = {
            content: [{
                    alignment: 'start',
                    text: '<?= $_SESSION['fullname'] ?>',
                    style: 'header',
                    fontSize: 20
                },
                {
                    alignment: 'start',
                    text: '<?= $_SESSION['specialty'] ?>',
                    style: 'subheader',
                    fontSize: 13,
                    bold: false
                },
                {
                    columns: [{
                            alignment: 'start',
                            text: 'Western Mindanao State University\nW376+CGQ, Normal Rd, Zamboanga City',
                            style: 'address',
                            width: '70%',
                            margin: [0, 15, 0, 15]
                        },
                        {
                            alignment: 'end',
                            text: 'Date: <?= date("l, M d, Y", strtotime($record['appointment_date'])) ?>',
                            style: 'address',
                            width: '30%',
                            margin: [0, 15, 0, 15]
                        }
                    ]
                },
                {
                    canvas: [{
                        type: 'line',
                        x1: 0,
                        y1: 0,
                        x2: 515,
                        y2: 0,
                        lineWidth: 1
                    }]
                },
                {
                    text: "Patient's Name: <?= $record['patient_name'] ?>",
                    margin: [0, 15, 0, 0]
                },
                {
                    columns: [{
                            text: "Birthdate: <?= date('M d, Y', strtotime($record['birthdate'])) ?>",
                            width: '65%'
                        },
                        {
                            text: "Gender: <?= $record['gender'] ?>",
                            width: '35%'
                        }
                    ],
                    margin: [0, 5, 0, 0]
                },
                {
                    columns: [{
                            text: "Email: <?= $record['email'] ?>",
                            width: '65%'
                        },
                        {
                            text: "Contact: <?= $record['contact'] ?>",
                            width: '35%'
                        }
                    ],
                    margin: [0, 5, 0, 15]
                },
                {
                    canvas: [{
                        type: 'line',
                        x1: 0,
                        y1: 0,
                        x2: 515,
                        y2: 0,
                        lineWidth: 1
                    }]
                },
                {

                    text: "Subjective Information",
                    margin: [0, 15, 0, 5],
                    fontSize: 13,
                },
                {

                    text: "Purpose of Appointment: <?= $record['purpose'] ?>",
                    margin: [0, 5, 0, 0],
                },
                {

                    text: "Reason: <?= $record['reason'] ?>",
                    margin: [0, 5, 0, 0],
                },
                {

                    text: "Chief Complaint:",
                    margin: [0, 5, 0, 0],
                },
                {

                    text: "<?= $record['complaint'] ?>",
                    margin: [30, 5, 0, 0],
                },
                <?php
                if ($record['medcon_history'] != null) {
                ?> {
                        text: "Medical History: ",
                        margin: [0, 5, 0, 0],
                    },
                    {

                        text: "<?= $record['medcon_history'] ?>",
                        margin: [30, 5, 0, 0],
                    },
                <?php
                }
                ?> {
                    text: "Allergy: ",
                    margin: [0, 5, 0, 0],
                },
                {

                    text: "<?= $record['allergy'] ?>",
                    margin: [30, 5, 0, 0],
                },
                {

                    text: "Medication: ",
                    margin: [0, 5, 0, 0],
                },
                {

                    text: "<?= $record['medication'] ?>",
                    margin: [30, 5, 0, 15],
                },
                {
                    canvas: [{
                        type: 'line',
                        x1: 0,
                        y1: 0,
                        x2: 515,
                        y2: 0,
                        lineWidth: 1
                    }]
                },
                {

                    text: "Objective Information",
                    margin: [0, 15, 0, 5],
                    fontSize: 13,
                },
                {

                    text: "Doctor's Observation: ",
                    margin: [0, 5, 0, 0],
                },
                {

                    text: "<?= $record['observation'] ?>",
                    margin: [30, 5, 0, 15],
                },

                {
                    canvas: [{
                        type: 'line',
                        x1: 0,
                        y1: 0,
                        x2: 515,
                        y2: 0,
                        lineWidth: 1
                    }]
                },
                {

                    text: "Assessment and Plan",
                    margin: [0, 15, 0, 5],
                    fontSize: 13,
                },
                {

                    text: "Medical Condition: ",
                    margin: [0, 5, 0, 0],
                },
                {

                    text: "<?= $record['diagnosis'] ?>",
                    margin: [30, 5, 0, 0],
                },
                {

                    text: "Assessment: ",
                    margin: [0, 5, 0, 0],
                },
                {

                    text: "<?= $record['assessment'] ?>",
                    margin: [30, 5, 0, 0],
                },
                {

                    text: "Treatment Plan and Recommendation: ",
                    margin: [0, 5, 0, 0],
                },
                {

                    text: "<?= $record['plan'] ?>",
                    margin: [30, 5, 0, 0],
                },
                {

                    text: "Prescription:",
                    margin: [0, 5, 0, 0],
                },
                {

                    text: "<?= $record['prescription'] ?>",
                    margin: [30, 5, 0, 0],
                },
                <?php if ($record['comment'] != null) { ?> {
                        text: "Addition Comment:",
                        margin: [0, 5, 0, 0],
                    },
                    {

                        text: "<?= $record['comment'] ?>",
                        margin: [30, 5, 0, 0],
                    },
                <?php } ?> {
                    canvas: [{
                            type: 'line',
                            x1: 0,
                            y1: 0,
                            x2: 515,
                            y2: 0,
                            lineWidth: 1

                        }

                    ],
                    margin: [0, 15, 0, 0]
                },
                {
                    canvas: [{
                        type: 'line',
                        x1: 500,
                        y1: 100,
                        x2: 215,
                        y2: 100,
                        lineWidth: 1
                    }]
                },
                {
                    text: 'Signature',
                    margin: [333, 5, 0, 0]
                }
            ],
            styles: {
                header: {
                    fontSize: 18,
                    bold: true,
                    alignment: 'center'
                },
                subheader: {
                    fontSize: 14,
                    bold: true,
                    alignment: 'center'
                },
                address: {
                    fontSize: 12,
                    alignment: 'center'
                },
                hoursTitle: {
                    fontSize: 12,
                    bold: true,
                    alignment: 'center'
                },
                hours: {
                    fontSize: 12,
                    alignment: 'center'
                }
            }
        };


        pdfMake.createPdf(docDefinition).download("consultation_result.pdf");
    });
</script>