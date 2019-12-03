<?php ?>

<canvas id="chart" class="" style="z-index:-1"></canvas>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.min.js"></script>
<script>
const ctx = document.getElementById('chart');

const myChart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: [<?= $chartLabels ?>],
    datasets: [
      {
        pointRadius:0,
        fill: 1,  
        label: 'Min',
        data: [<?=$chartData["min"] ?>],
        backgroundColor: "blue",
        borderColor: "blue"
      },
      {
        pointRadius:0,
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
      xAxes: [{ stacked: true,
      gridLines:{
        //drawBorder:false,
        //display:false
      }, ticks:{
        //display:false
      }}],
      yAxes: [
        { stacked: false,
        ticks:{
         // display:false 
        },
          gridLines: {
            //drawBorder:false,
          //display: false
        } }
        ]
    },
    legend:{
      display:false
    }
    
  }
});

</script>