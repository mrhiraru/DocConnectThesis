var ctx = document.getElementById('patientSummaryChart').getContext('2d');
var patientSummaryChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['New Patients', 'Old Patients', 'Total Patients'],
        datasets: [{
            data: [30, 70, 100],
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
        }]
    },
    options: {
        responsive: true
    }
});