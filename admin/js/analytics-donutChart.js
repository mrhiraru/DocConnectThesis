const doughnutData = {
  labels: ['Campus A', 'Campus B', 'Campus C'],
  datasets: [{
    label: 'Campus Dataset',
    data: [240, 400, 100],
    backgroundColor: [
      '#dc3545',
      '#36A2EB',
      '#21bf73'
    ],
    hoverOffset: 4
  }]
};

const doughnutCtx = document.getElementById('doughnutChart').getContext('2d');
const doughnutChart = new Chart(doughnutCtx, {
  type: 'doughnut',
  data: doughnutData,
  options: {
    legend: {
      display: true
    }
  }
});