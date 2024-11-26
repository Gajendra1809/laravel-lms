
    <div style="height: 400px; width: 800px">
        <label for="limit">Get top : </label>
        <select name="limit" id="limit">
            <option value="2">2</option>
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="15">15</option>
        </select>
        <div id="chartContainer">
            <canvas id="mostBorrowedBooksChart"></canvas>
        </div>
    </div>
    <script>
        document.getElementById('limit').addEventListener('change', function() {
            const limit = this.value;
            document.getElementById('mostBorrowedBooksChart').remove();
            const chartContainer = document.getElementById('chartContainer');
            const newCanvas = document.createElement('canvas');
            newCanvas.id = 'mostBorrowedBooksChart';
            chartContainer.appendChild(newCanvas);
            apiCall(limit);
        })
        const apiCall = async (limit) => {
            fetch('http://127.0.0.1:8000/api/graph/most-borrowed-books/'+limit, {
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
        }
        document.addEventListener('DOMContentLoaded', apiCall(2));
    </script>
