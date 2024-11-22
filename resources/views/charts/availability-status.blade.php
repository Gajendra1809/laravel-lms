
<div style="height: 400px; width: 800px">
    <canvas id="BooksAvailabilityChart" ></canvas>
</div>
<script>
    fetch('http://127.0.0.1:8000/api/graph/books-status', {
        method: 'GET'
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const books = data.data;

                // Extract labels (book titles) and data (borrows_count)
                const labels = ['Available', 'Not Available'];
                const bookCounts = [books.available, books.not_available];

                // Render the chart
                const ctx = document.getElementById('BooksAvailabilityChart').getContext('2d');
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Number of Books',
                            data: bookCounts,
                            backgroundColor: [
                                'blue',
                                'red'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        aspectRatio: 2,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            },
                            tooltip: {
                                enabled: true
                            },
                            datalabels: {
                                color: '#fff', // White color for better visibility
                                font: {
                                    weight: 'bold',
                                    size: 14
                                },
                                formatter: (value, context) => {
                                    const total = context.dataset.data.reduce((sum, val) => sum + val, 0);
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return `${percentage}%`; // Show percentage
                                },
                                anchor: 'center',
                                align: 'center'
                            }
                        }
                    },
                    plugins: [ChartDataLabels]
                });
            } else {
                console.error('Failed to fetch data:', data.message);
            }
        })
        .catch(error => console.error('Error:', error));
</script>
