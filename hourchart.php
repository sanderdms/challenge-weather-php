<?php
$dayID = strtolower($day);
$output = [
    "min"=>"",
    "max"=>"",
    "temp"=>"",
    "labels"=>"",
];

foreach($weather as $item => $time){
    $output["min"] .= $time->main->temp_min.",";
    $output["max"] .= $time->main->temp_max.",";
    $output["temp"] .= $time->main->temp.","; 
    $time = date("H", $time->dt)."h";
    $output["labels"] .= "'".$time."',";
}
?>

<div class="h-24 mt-10">
    <canvas id="c-<?=$dayID?>"></canvas>
</div>

<script>
    const <?=$dayID?> = document.getElementById('c-<?=$dayID?>');
    const <?=$dayID?>Chart = new Chart(<?=$dayID?>, {
        type: 'line',
        data: {
            labels: [<?=$output["labels"]?>],
            datasets: [
                {
                    pointRadius: 3,
                    borderWidth: 4,
                    pointHoverBorderWidth: 10,
                    fill: true,
                    label: 'temperature °C',
                    data: [<?=$output["temp"]?>],
                    borderColor: "#2c5282"
                },
            ]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                xAxes: [{
                    stacked: true,
                    gridLines: {
                        drawBorder: false,
                        display: false
                    }, ticks: {
                        display: true
                    }
                }],
                yAxes: [
                    {
                        stacked: true,
                        ticks: {
                            display: true
                        },
                        gridLines: {
                            drawBorder: false,
                            display: true
                        }
                    }
                ]
            },
            legend: {
                display: false
            }
        }
    });
</script>