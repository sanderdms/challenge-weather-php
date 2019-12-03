<?php

?>

<canvas id="chart"></canvas>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.min.js"></script>
        <script>
var ctx = document.getElementById('chart');

var myChart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: [<?= $chartLabels ?>],
    datasets: [
      {
        fill: 1,  
        label: 'Min',
        data: [<?=$chartData["min"] ?>],
        backgroundColor: "blue",
        borderColor: "blue"
      },
      {
        fill: 1,  
        label: 'Max',
        data: [<?=$chartData["max"] ?>],
        backgroundColor: 'red',
        borderColor: "red"
      }
    ]
  },
  options: {
    scales: {
      xAxes: [{ stacked: true }],
      yAxes: [{ stacked: false }]
    }
  }
});

</script>