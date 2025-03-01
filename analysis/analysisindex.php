<?php
include('../includes/check_admin.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Analysis</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f4f4f4;
            padding: 20px;
        }
        h2 {
            color: #333;
        }
        canvas {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        .chart-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 30px;
        }
        .chart-box {
            width: 45%;
            min-width: 400px;
        }
    </style>
</head>
<body>

    <h2>Incident Analysis</h2>
    
    <div class="chart-container">
        <!-- Bar Chart -->
        <div class="chart-box">
            <h3>Incident Trends Over Time</h3>
            <canvas id="incidentChart"></canvas>
        </div>

        <!-- Pie Chart -->
        <div class="chart-box">
            <h3>Causes of Incidents</h3>
            <canvas id="causeChart"></canvas>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetch('getdata.php')
                .then(response => response.json())
                .then(data => {
                    // ===== BAR CHART =====
                    let dates = [];
                    let barangays = {};
                    let barangayColors = {};

                    // Assign random colors
                    const getRandomColor = () => '#' + Math.floor(Math.random()*16777215).toString(16);

                    data.incidents.forEach(row => {
                        if (!dates.includes(row.incident_date)) {
                            dates.push(row.incident_date);
                        }
                        if (!barangays[row.barangay]) {
                            barangays[row.barangay] = {};
                            barangayColors[row.barangay] = getRandomColor();
                        }
                        barangays[row.barangay][row.incident_date] = row.count;
                    });

                    let datasets = Object.keys(barangays).map(barangay => ({
                        label: barangay,
                        backgroundColor: barangayColors[barangay],
                        data: dates.map(date => barangays[barangay][date] || 0)
                    }));

                    new Chart(document.getElementById('incidentChart'), {
                        type: 'bar',
                        data: {
                            labels: dates,
                            datasets: datasets
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: { display: true, position: 'top' },
                                tooltip: {
                                    enabled: true,
                                    callbacks: {
                                        label: tooltipItem => ` ${tooltipItem.dataset.label}: ${tooltipItem.raw} incidents`
                                    }
                                }
                            },
                            scales: {
                                x: { title: { display: true, text: 'Date of Incidents' } },
                                y: { beginAtZero: true, title: { display: true, text: 'Number of Incidents' } }
                            }
                        }
                    });

                    // ===== PIE CHART =====
                    let causes = [];
                    let counts = [];
                    let totalIncidents = 0;

                    let colors = [
                        "#FF6384", "#36A2EB", "#FFCE56", "#4BC0C0",
                        "#9966FF", "#FF9F40", "#C9CBCF", "#2A9D8F"
                    ];

                    data.causes.forEach((row, index) => {
                        causes.push(row.cause);
                        counts.push(row.count);
                        totalIncidents += row.count;
                    });

                    new Chart(document.getElementById('causeChart'), {
                        type: 'doughnut',
                        data: {
                            labels: causes,
                            datasets: [{
                                data: counts,
                                backgroundColor: colors.slice(0, causes.length),
                                hoverOffset: 10
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: { display: true, position: 'right' },
                                tooltip: {
                                    callbacks: {
                                        label: function(tooltipItem) {
                                            let value = tooltipItem.raw;
                                            let percentage = ((value / totalIncidents) * 100).toFixed(1);
                                            return ` ${tooltipItem.label}: ${value} (${percentage}%)`;
                                        }
                                    }
                                },
                                datalabels: {
                                    color: '#fff',
                                    formatter: (value, context) => {
                                        let percentage = ((value / totalIncidents) * 100).toFixed(1);
                                        return `${percentage}%`;
                                    }
                                }
                            },
                            animation: {
                                animateRotate: true,
                                animateScale: true
                            }
                        }
                    });
                });
        });
    </script>

</body>
</html>
