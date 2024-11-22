
    <div style="height: 400px; width: 800px">
        <canvas id="mostBorrowedBooksChart" ></canvas>
    </div>
    <script>
        fetch('http://127.0.0.1:8000/api/graph/most-borrowed-books', {
            method: 'GET'
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const books = data.data;
    
                    // Extract labels (book titles) and data (borrows_count)
                    const labels = books.map(book => book.title);
                    const borrowCounts = books.map(book => book.borrows_count);
    
                    // Render the chart
                    const ctx = document.getElementById('mostBorrowedBooksChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Number of Borrows',
                                data: borrowCounts,
                                backgroundColor: 'rgba(54, 162, 235, 0.5)', // Bar color
                                borderColor: 'rgba(54, 162, 235, 1)', // Border color
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
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Number of Borrows'
                                    }
                                },
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Book Titles'
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
