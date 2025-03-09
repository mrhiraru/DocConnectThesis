const totalUsers = 10000; // Set number ng users sa type, para ito yung 100%

function calculatePercentages(lineData) {
  const onlineTotal = lineData.yCampus2.reduce((acc, val) => acc + val, 0);
  const faceToFaceTotal = lineData.yCampus1.reduce((acc, val) => acc + val, 0);

  const onlinePercentage = (onlineTotal / totalUsers) * 100;
  const faceToFacePercentage = (faceToFaceTotal / totalUsers) * 100;

  return [onlinePercentage.toFixed(2), faceToFacePercentage.toFixed(2)];
}

function updateBarChart(barChart, percentages) {
  barChart.data.datasets[0].data = percentages;
  barChart.update();
}

document.getElementById('yearSelect').addEventListener('change', function() {
  const selectedYear = this.value === '1' ? "2021-2022" : this.value === '2' ? "2022-2023" : "2023-2024";
  const percentages = calculatePercentages(data[selectedYear].type);
  updateBarChart(barChart, percentages);
});

const initialPercentages = calculatePercentages(data["2021-2022"].type);

const barData = {
  labels: ['Online', 'Face-to-Face'],
  datasets: [{
    label: 'User Types',
    data: initialPercentages,
    backgroundColor: ['#21bf73', '#dc3545'],
  }]
};

const barConfig = {
  type: 'bar',
  data: barData,
  options: {
    indexAxis: 'y',
    scales: {
      x: {
        beginAtZero: true,
        max: 100
      }
    }
  }
};

const barCtx = document.getElementById('barGraph').getContext('2d');
const barChart = new Chart(barCtx, barConfig);
