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

    var download_pdf = document.getElementById("download_pdf");
    if (download_pdf) {
        download_pdf.addEventListener("click", () => {
            // Clinical History PDF
            var clinicalHistory = {
                content: [
                    {text: 'Clinical History', style: 'header', alignment: 'center'},
                    {
                        style: 'tableExample',
                        color: '#444',
                        table: {
                            widths: [225, 75, 75, 100],
                            headerRows: 1,
                            body: [
                                [
                                    {text: "Patient's Name: " + (<?= isset($record['patient_name']) ? json_encode($record['patient_name']) : json_encode($_SESSION['fullname']) ?>), style: 'tableExample'}, 
                                    {text: "Age: " + calculateAge(<?= json_encode($record['birthdate']) ?>), style: 'tableExample'}, 
                                    {text: "Sex: " + (<?= json_encode($record['gender']) ?>), style: 'tableExample'}, 
                                    {text: "Civil Status:", style: 'tableExample'}, 
                                ], 
                                [
                                    {text: "Residence: " + (<?= json_encode($record['address']) ?>), style: 'tableExample'}, 
                                    {text: 'Religion:', style: 'tableExample', colSpan: 2}, 
                                    {}, 
                                    {text: 'Date & Time: ' + (<?= json_encode(date("l, M d, Y", strtotime($record['appointment_date'])) . " " . date("g:i A", strtotime($record['appointment_time']))) ?>), style: 'tableExample'}
                                ],
                                [
                                    {text: 'Informant: ' + (<?= json_encode($_SESSION['fullname']) ?>), style: 'tableExample', colSpan: 4}, 
                                    {}, 
                                    {}, 
                                    {}
                                ],
                                [
                                    {text: 'Chief Complaint:\n' + (<?= json_encode($record['complaint']) ?>), style: 'tableExample', colSpan: 4}, 
                                    {}, 
                                    {}, 
                                    {}
                                ],
                                [
                                    {text: 'History of Present Illness:\n' + (<?= json_encode($record['his_illness']) ?>), style: 'tableExample', colSpan: 4}, 
                                    {}, 
                                    {}, 
                                    {}
                                ],
                                [
                                    {text: 'Past Medical or Surgical History:\n' + (<?= json_encode($record['medcon_history']) ?>), style: 'tableExample', colSpan: 4}, 
                                    {}, 
                                    {}, 
                                    {}
                                ],
                                [
                                    {text: 'OB History:\n' + (<?= json_encode($record['ob_his']) ?>), style: 'tableExample', colSpan: 4}, 
                                    {}, 
                                    {}, 
                                    {}
                                ],
                                [
                                    {text: 'Family History:\n' + (<?= json_encode($record['fam_his']) ?>), style: 'tableExample', colSpan: 4}, 
                                    {}, 
                                    {}, 
                                    {}
                                ],
                                [
                                    {text: 'Social History:\n' + (<?= json_encode($record['soc_his']) ?>), style: 'tableExample', colSpan: 4}, 
                                    {}, 
                                    {}, 
                                    {}
                                ],
                                [
                                    {text: 'Review of System:\n' + (<?= json_encode($record['rev_sys']) ?>), style: 'tableExample', colSpan: 4}, 
                                    {}, 
                                    {}, 
                                    {}
                                ],
                                [
                                    {text: 'Maintenance Medication:\n' + (<?= json_encode($record['medication']) ?>), style: 'tableExample', colSpan: 4}, 
                                    {}, 
                                    {}, 
                                    {}
                                ],
                                [
                                    {text: 'Allergies & Medication Intolerance/s:\n' + (<?= json_encode($record['allergy']) ?>), style: 'tableExample', colSpan: 4}, 
                                    {}, 
                                    {}, 
                                    {}
                                ],
                                [
                                    {text: 'Immunization & Preventive Care Services:\n' + (<?= json_encode($record['immu']) ?>), style: 'tableExample', colSpan: 4}, 
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
                content: [
                    {text: 'Consultation Result', style: 'header', alignment: 'center'},
                    {
                        style: 'tableExample',
                        color: '#444',
                        table: {
                            widths: ['*'],
                            body: [
                                [
                                    {text: "Patient's Name: " + (<?= isset($record['patient_name']) ? json_encode($record['patient_name']) : json_encode($_SESSION['fullname']) ?>), style: 'tableExample'}, 
                                ],
                                [
                                    {text: 'Informant: ' + (<?= json_encode($_SESSION['fullname']) ?>), style: 'tableExample'}, 
                                ],
                                [
                                    {text: 'Consultation Assessment:\n' + (<?= json_encode($record['assessment']) ?>), style: 'tableExample'}, 
                                ],
                                [
                                    {text: 'Diagnosis:\n' + (<?= json_encode($record['diagnosis']) ?>), style: 'tableExample'}, 
                                ],
                                [
                                    {text: 'Treatment Plan:\n' + (<?= json_encode($record['plan']) ?>), style: 'tableExample'}, 
                                ],
                                [
                                    {text: 'Prescription:\n' + (<?= json_encode($record['prescription']) ?>), style: 'tableExample'}, 
                                ],
                                [
                                    {text: 'Additional Comments:\n' + (<?= json_encode($record['comment']) ?>), style: 'tableExample'}, 
                                ]
                            ]
                        }
                    },
                    {
                        canvas: [{ type: 'line', x1: 0, y1: 0, x2: 515, y2: 0, lineWidth: 1 }],
                        margin: [0, 20, 0, 0]
                    },
                    {
                        text: 'Signature',
                        alignment: 'right',
                        margin: [0, 20, 0, 0]
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
                content: [
                    {text: 'Prescription', style: 'header', alignment: 'center'},
                    {
                        style: 'tableExample',
                        color: '#444',
                        table: {
                            widths: ['*', '*', '*'],
                            body: [
                                [
                                    {text: "Patient's Name: " + (<?= isset($record['patient_name']) ? json_encode($record['patient_name']) : json_encode($_SESSION['fullname']) ?>), style: 'tableExample', colSpan: 3}, 
                                    {}, 
                                    {}
                                ],
                                [
                                    {text: "Sex: " + (<?= json_encode($record['gender']) ?>), style: 'tableExample'}, 
                                    {text: "Age: " + calculateAge(<?= json_encode($record['birthdate']) ?>), style: 'tableExample'}, 
                                    {text: "Consultation Date: " + (<?= json_encode(date("M d, Y", strtotime($record['appointment_date']))) ?>), style: 'tableExample'}
                                ]
                            ]
                        }
                    },
                    {
                        canvas: [{ type: 'line', x1: 0, y1: 0, x2: 515, y2: 0, lineWidth: 1 }],
                        margin: [0, 10, 0, 10]
                    },
                    {
                        text: 'Prescription:',
                        style: 'subheader',
                        margin: [0, 0, 0, 10]
                    },
                    {
                        text: <?= json_encode($record['prescription']) ?>,
                        margin: [0, 0, 0, 20]
                    },
                    {
                        canvas: [{ type: 'line', x1: 500, y1: 0, x2: 215, y2: 0, lineWidth: 2 }],
                        margin: [0, 0, 0, 5]
                    },
                    {
                        text: 'Signature',
                        alignment: 'right',
                        margin: [0, 5, 0, 0]
                    }
                ],
                styles: {
                    header: {
                        fontSize: 18,
                        bold: true,
                        margin: [0, 0, 0, 10]
                    },
                    subheader: {
                        fontSize: 14,
                        bold: true,
                        margin: [0, 0, 0, 5]
                    },
                    tableExample: {
                        margin: [0, 5, 0, 15],
                        bold: true
                    }
                }
            };

            // Generate all three PDFs
            pdfMake.createPdf(clinicalHistory).download("clinical_history.pdf");
            pdfMake.createPdf(consultationResult).download("consultation_report.pdf");
            pdfMake.createPdf(prescription).download("prescription.pdf");
        });
    }
</script>