<?php ?>

<canvas id="chart"></canvas>
                <script>
                    const ctx = document.getElementById('chart');


                    const myChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: [<?= $chartLabels ?>],
                            datasets: [
                                {
                                    pointRadius: 3,
                                    borderWidth: 4,
                                    pointHoverBorderWidth: 10,
                                    fill: 0,
                                    label: 'Min',
                                    data: [<?=$chartData["min"] ?>],
                                    backgroundColor: '#2c5282',
                                    borderColor: "#2c5282"
                                },
                                {
                                    pointRadius: 3,
                                    pointHoverBorderWidth: 10,
                                    borderWidth: 4,
                                    fill: 0,
                                    label: 'Max',
                                    data: [<?=$chartData["max"] ?>],
                                    backgroundColor: '#9b2c2c',
                                    borderColor: "#9b2c2c"
                                }
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
                                            display: false
                                        },
                                        gridLines: {
                                            drawBorder: false,
                                            display: false
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