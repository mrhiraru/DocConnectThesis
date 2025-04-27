<script type="module">
    import "../node_modules/pdfmake/build/pdfmake.js";
    import "../node_modules/pdfmake/build/vfs_fonts.js";

    // Function to calculate age from birthdate
    function calculateAge(birthdate) {
        const birthDate = new Date(birthdate);
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        return age;
    }

    function getbase64(loc) {
        const fs = require('fs');
        const path = require('path');

        const imagePath = path.resolve(__dirname, loc);
        const imageBase64 = fs.readFileSync(imagePath, {
            encoding: 'base64'
        });

        return imageBase64;
    }



    // Clinical History PDF
    var clinicalHistory = {
        content: [{
                columns: [{
                        image: 'data:image/png;base64,' + getbase64('assets/images/cliniclogo.png'),
                        width: 75,
                        height: 75
                    },
                    {
                        width: '*',
                        alignment: 'center',
                        stack: [{
                                text: 'Western Mindanao State University',
                                style: 'header',
                                fontSize: 20
                            },
                            {
                                text: 'W376+CGQ, Normal Rd, Zamboanga City',
                                style: 'subheader',
                                fontSize: 13,
                                bold: false
                            }
                        ]
                    },
                    {
                        image: 'data:image/png;base64,' + getbase64('assets/images/wmsulogo.png'),
                        width: 75,
                        height: 75
                    },
                ],
            }, {
                text: 'Clinical History',
                style: 'header',
                alignment: 'center'
            },
            {
                style: 'tableExample',
                color: '#444',
                table: {
                    widths: [225, 75, 75, 100],
                    headerRows: 1,
                    body: [
                        [{
                                text: "Patient's Name: " + (<?= isset($record['patient_name']) ? json_encode($record['patient_name']) : json_encode($_SESSION['fullname']) ?>),
                                style: 'tableExample'
                            },
                            {
                                text: "Age: " + calculateAge(<?= json_encode($record['birthdate']) ?>),
                                style: 'tableExample'
                            },
                            {
                                text: "Sex: " + (<?= json_encode($record['gender']) ?>),
                                style: 'tableExample'
                            },
                            {
                                text: "Civil Status:",
                                style: 'tableExample'
                            },
                        ],
                        [{
                                text: "Residence: " + (<?= json_encode($record['address']) ?>),
                                style: 'tableExample'
                            },
                            {
                                text: 'Religion:',
                                style: 'tableExample',
                                colSpan: 2
                            },
                            {},
                            {
                                text: 'Date & Time: ' + (<?= json_encode(date("l, M d, Y", strtotime($record['appointment_date'])) . " " . date("g:i A", strtotime($record['appointment_time']))) ?>),
                                style: 'tableExample'
                            }
                        ],
                        [{
                                text: 'Informant: ' + (<?= json_encode($_SESSION['fullname']) ?>),
                                style: 'tableExample',
                                colSpan: 4
                            },
                            {},
                            {},
                            {}
                        ],
                        [{
                                text: 'Chief Complaint:\n' + (<?= json_encode($record['complaint']) ?>),
                                style: 'tableExample',
                                colSpan: 4
                            },
                            {},
                            {},
                            {}
                        ],
                        [{
                                text: 'History of Present Illness:\n' + (<?= json_encode($record['his_illness']) ?>),
                                style: 'tableExample',
                                colSpan: 4
                            },
                            {},
                            {},
                            {}
                        ],
                        [{
                                text: 'Past Medical or Surgical History:\n' + (<?= json_encode($record['medcon_history']) ?>),
                                style: 'tableExample',
                                colSpan: 4
                            },
                            {},
                            {},
                            {}
                        ],
                        [{
                                text: 'OB History:\n' + (<?= json_encode($record['ob_his']) ?>),
                                style: 'tableExample',
                                colSpan: 4
                            },
                            {},
                            {},
                            {}
                        ],
                        [{
                                text: 'Family History:\n' + (<?= json_encode($record['fam_his']) ?>),
                                style: 'tableExample',
                                colSpan: 4
                            },
                            {},
                            {},
                            {}
                        ],
                        [{
                                text: 'Social History:\n' + (<?= json_encode($record['soc_his']) ?>),
                                style: 'tableExample',
                                colSpan: 4
                            },
                            {},
                            {},
                            {}
                        ],
                        [{
                                text: 'Review of System:\n' + (<?= json_encode($record['rev_sys']) ?>),
                                style: 'tableExample',
                                colSpan: 4
                            },
                            {},
                            {},
                            {}
                        ],
                        [{
                                text: 'Maintenance Medication:\n' + (<?= json_encode($record['medication']) ?>),
                                style: 'tableExample',
                                colSpan: 4
                            },
                            {},
                            {},
                            {}
                        ],
                        [{
                                text: 'Allergies & Medication Intolerance/s:\n' + (<?= json_encode($record['allergy']) ?>),
                                style: 'tableExample',
                                colSpan: 4
                            },
                            {},
                            {},
                            {}
                        ],
                        [{
                                text: 'Immunization & Preventive Care Services:\n' + (<?= json_encode($record['immu']) ?>),
                                style: 'tableExample',
                                colSpan: 4
                            },
                            {},
                            {},
                            {}
                        ],
                    ]
                }
            },
        ],
        styles: {
            header: {
                fontSize: 18,
                bold: true,
                margin: [0, 0, 0, 10]
            },
            tableExample: {
                margin: [0, 5, 0, 15],
                bold: true
            }
        }
    };

    // Consultation Result PDF
    var consultationResult = {
        content: [{
                text: 'Consultation Result',
                style: 'header',
                alignment: 'center'
            },
            {
                style: 'tableExample',
                color: '#444',
                table: {
                    widths: [225, 75, 75, 100],
                    body: [
                        [{
                                text: "Patient's Name: " + (<?= isset($record['patient_name']) ? json_encode($record['patient_name']) : json_encode($_SESSION['fullname']) ?>),
                                style: 'tableExample'
                            },
                            {
                                text: "Age: " + calculateAge(<?= json_encode($record['birthdate']) ?>),
                                style: 'tableExample'
                            },
                            {
                                text: "Sex: " + (<?= json_encode($record['gender']) ?>),
                                style: 'tableExample'
                            },
                            {
                                text: "Civil Status:",
                                style: 'tableExample'
                            }
                        ],
                        [{
                                text: "Residence: " + (<?= json_encode($record['address']) ?>),
                                style: 'tableExample'
                            },
                            {
                                text: 'Religion:',
                                style: 'tableExample',
                                colSpan: 2
                            },
                            {},
                            {
                                text: 'Date & Time: ' + (<?= json_encode(date("l, M d, Y", strtotime($record['appointment_date'])) . " " . date("g:i A", strtotime($record['appointment_time']))) ?>),
                                style: 'tableExample'
                            }
                        ],
                        [{
                                text: 'Informant: ' + (<?= json_encode($_SESSION['fullname']) ?>),
                                style: 'tableExample',
                                colSpan: 4
                            },
                            {},
                            {},
                            {}
                        ],
                        [{
                                text: 'Consultation Assessment:\n' + (<?= json_encode($record['assessment']) ?>),
                                style: 'tableExample',
                                colSpan: 4
                            },
                            {},
                            {},
                            {}
                        ],
                        [{
                                text: 'Diagnosis:\n' + (<?= json_encode($record['diagnosis']) ?>),
                                style: 'tableExample',
                                colSpan: 4
                            },
                            {},
                            {},
                            {}
                        ],
                        [{
                                text: 'Treatment Plan:\n' + (<?= json_encode($record['plan']) ?>),
                                style: 'tableExample',
                                colSpan: 4
                            },
                            {},
                            {},
                            {}
                        ],
                        [{
                                text: 'Prescription:\n' + (<?= json_encode($record['prescription']) ?>),
                                style: 'tableExample',
                                colSpan: 4
                            },
                            {},
                            {},
                            {}
                        ]
                    ]
                }
            }
        ],
        styles: {
            header: {
                fontSize: 18,
                bold: true,
                margin: [0, 0, 0, 10]
            },
            tableExample: {
                margin: [0, 5, 0, 15],
                bold: true
            }
        }
    };

    // Prescription PDF
    var prescription = {
        content: [{
                alignment: 'start',
                text: '<?= isset($record["doctor_name"]) ? $record["doctor_name"] : $_SESSION["fullname"] ?>',
                style: 'header',
                fontSize: 20
            },
            {
                alignment: 'start',
                text: '<?= isset($record["specialty"]) ? $record["specialty"] : $_SESSION["specialty"] ?>',
                style: 'subheader',
                fontSize: 13,
                bold: false
            },
            {
                columns: [{
                    alignment: 'start',
                    text: 'Western Mindanao State University\nW376+CGQ, Normal Rd, Zamboanga City',
                    style: 'address',
                    margin: [0, 15, 0, 20]
                }]
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
                text: "Patient's Name:  " + (<?= isset($record['patient_name']) ? json_encode($record['patient_name']) : json_encode($_SESSION['fullname']) ?>),
                margin: [0, 20, 0, -1]
            },
            {
                canvas: [{
                    type: 'line',
                    x1: 87,
                    y1: 0,
                    x2: 515,
                    y2: 0,
                    lineWidth: 0.5
                }]
            },
            {
                text: "Address:  " + (<?= json_encode($record['address']) ?>),
                margin: [0, 10, 0, -1]
            },
            {
                canvas: [{
                    type: 'line',
                    x1: 51,
                    y1: 0,
                    x2: 515,
                    y2: 0,
                    lineWidth: 0.5
                }]
            },
            {
                columns: [{
                        text: "Sex:  " + (<?= json_encode($record['gender']) ?>),
                        width: '33%'
                    },
                    {
                        text: "Age:  " + calculateAge(<?= json_encode($record['birthdate']) ?>),
                        width: '33%'
                    },
                    {
                        text: "Date:  " + (<?= json_encode(date("M d, Y", strtotime($record['appointment_date']))) ?>),
                        width: '33%'
                    }
                ],
                margin: [0, 10, 0, -1]
            },
            {
                columns: [{
                        canvas: [{
                            type: 'line',
                            x1: 29,
                            y1: 0,
                            x2: 155,
                            y2: 0,
                            lineWidth: 0.5
                        }]
                    },
                    {
                        canvas: [{
                            type: 'line',
                            x1: 25,
                            y1: 0,
                            x2: 155,
                            y2: 0,
                            lineWidth: 0.5
                        }]
                    },
                    {
                        canvas: [{
                            type: 'line',
                            x1: 26,
                            y1: 0,
                            x2: 170,
                            y2: 0,
                            lineWidth: 0.5
                        }]
                    },
                ],
                margin: [0, -1, 0, 20]
            },
            {
                image: 'data:image/png;base64,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',
                width: 100,
                height: 100
            },
            {
                text: <?= json_encode($record['prescription']) ?>,
                margin: [0, 30, 0, 20]
            },
            {
                canvas: [{
                    type: 'line',
                    x1: 500,
                    y1: 250,
                    x2: 215,
                    y2: 250,
                    lineWidth: 2
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

    // Add event listeners for each download button
    document.addEventListener('DOMContentLoaded', function() {
        var downloadClinicalHistory = document.getElementById("download_clinical_history");
        if (downloadClinicalHistory) {
            downloadClinicalHistory.addEventListener("click", () => {
                pdfMake.createPdf(clinicalHistory).download("clinical_history.pdf");
            });
        }

        var downloadConsultationReport = document.getElementById("download_consultation_report");
        if (downloadConsultationReport) {
            downloadConsultationReport.addEventListener("click", () => {
                pdfMake.createPdf(consultationResult).download("consultation_report.pdf");
            });
        }

        var downloadPrescription = document.getElementById("download_prescription");
        if (downloadPrescription) {
            downloadPrescription.addEventListener("click", () => {
                pdfMake.createPdf(prescription).download("prescription.pdf");
            });
        }
    });
</script>