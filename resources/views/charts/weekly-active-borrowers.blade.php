<!-- resources/views/charts/weekly-active-borrowers.blade.php -->
<div style="height: 400px; width: 800px">
    <canvas id="weeklyActiveBorrowersChart"></canvas>
</div>

<script>
    // Fetch data from API
    fetch('http://127.0.0.1:8000/api/graph/weekly-active-borrowers', {
        method: 'GET'
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const weeklyData = data.data;

                // Extract labels (weeks) and data (borrower counts)
                const labels = Object.keys(weeklyData);
                const borrowerCounts = Object.values(weeklyData);

                // Create the Line Chart
                const ctx = document.getElementById('weeklyActiveBorrowersChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Weekly Active Borrowers',
                            data: borrowerCounts,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 2,
                            pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                            pointBorderColor: '#fff',
                            pointHoverBackgroundColor: '#fff',
                            pointHoverBorderColor: 'rgba(54, 162, 235, 1)'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            },
                            tooltip: {
                                enabled: true
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Number of Borrowers'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Weeks of Current Month'
                                }
                            }
                        }
                    }
                });
            } else {
                console.error('Failed to fetch data:', data.message);
            }
        })
        .catch(error => console.error('Error:', error));
</script>
