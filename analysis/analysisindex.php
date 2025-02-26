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
    </style>
</head>
<body>

    <h2>Incident Analysis</h2>
    <canvas id="incidentChart"></canvas>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetch('getdata.php')
                .then(response => response.json())
                .then(data => {
                    let dates = [];
                    let locations = {};
                    let locationColors = {};

                    // Assign random colors for each location
                    const getRandomColor = () => '#' + Math.floor(Math.random()*16777215).toString(16);

                    // Process data
                    data.forEach(row => {
                        if (!dates.includes(row.incident_date)) {
                            dates.push(row.incident_date);
                        }
                        if (!locations[row.location]) {
                            locations[row.location] = {};
                            locationColors[row.location] = getRandomColor(); // Assign color
                        }
                        locations[row.location][row.incident_date] = row.count;
                    });

                    // Convert data for Chart.js
                    let datasets = Object.keys(locations).map(location => ({
                        label: location,
                        backgroundColor: locationColors[location], // Unique color
                        data: dates.map(date => locations[location][date] || 0)
                    }));

                    // Create bar chart
                    new Chart(document.getElementById('incidentChart'), {
                        type: 'bar',
                        data: {
                            labels: dates,
                            datasets: datasets
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top',
                                    labels: {
                                        font: {
                                            size: 14
                                        }
                                    }
                                },
                                tooltip: {
                                    enabled: true,
                                    callbacks: {
                                        label: function(tooltipItem) {
                                            return ` ${tooltipItem.dataset.label}: ${tooltipItem.raw} incidents`;
                                        }
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Date of Incidents',
                                        font: {
                                            size: 14
                                        }
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Number of Incidents',
                                        font: {
                                            size: 14
                                        }
                                    }
                                }
                            }
                        }
                    });
                });
        });
    </script>

</body>
</html>
