<div style="height: 400px; width: 800px">
    <canvas id="longestBorrowedBooksChart"></canvas>
</div>

<script>
    // Fetch data from API
    fetch('http://127.0.0.1:8000/api/graph/longest-borrowed-books', {
        method: 'GET'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const books = data.data;

            // Extract book titles and average borrow durations
            const labels = books.map(book => book.title);
            const avgBorrowDurations = books.map(book => book.avg_borrow_duration);

            // Render the chart
            const ctx = document.getElementById('longestBorrowedBooksChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar', // Bar chart
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Average Borrow Duration (Days)',
                        data: avgBorrowDurations,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)', // Bar color
                        borderColor: 'rgba(54, 162, 235, 1)', // Border color
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Avg Borrow Duration (Days)'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Book Titles'
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                // Show additional data in tooltip
                                label: function(tooltipItem) {
                                    const book = books[tooltipItem.dataIndex];
                                    return `ISBN no.: ${book.isbn}`;
                                }
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
