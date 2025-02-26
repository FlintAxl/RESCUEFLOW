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
                    // ===== EXISTING BAR CHART =====
                    let dates = [];
                    let locations = {};
                    let locationColors = {};

                    // Assign random colors
                    const getRandomColor = () => '#' + Math.floor(Math.random()*16777215).toString(16);

                    data.incidents.forEach(row => {
                        if (!dates.includes(row.incident_date)) {
                            dates.push(row.incident_date);
                        }
                        if (!locations[row.location]) {
                            locations[row.location] = {};
                            locationColors[row.location] = getRandomColor();
                        }
                        locations[row.location][row.incident_date] = row.count;
                    });

                    let datasets = Object.keys(locations).map(location => ({
                        label: location,
                        backgroundColor: locationColors[location],
                        data: dates.map(date => locations[location][date] || 0)
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

                    // ===== NEW PIE CHART =====
                    let causes = [];
                    let counts = [];
                    let colors = [];

                    data.causes.forEach(row => {
                        causes.push(row.cause);
                        counts.push(row.count);
                        colors.push(getRandomColor());
                    });

                    new Chart(document.getElementById('causeChart'), {
                        type: 'pie',
                        data: {
                            labels: causes,
                            datasets: [{
                                data: counts,
                                backgroundColor: colors,
                                hoverOffset: 10
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: { display: true, position: 'right' }
                            }
                        }
                    });
                });
        });
    </script>

</body>
</html>
