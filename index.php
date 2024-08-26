<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARCANA STOCK</title>
    <?php include('connect.php') ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
</head>
<style>
.card-body {
    display: flex;
    /* Center horizontally */
    align-items: center;
    /* Center vertically */
}

.card-body h5 {
    margin: 0;
    /* Remove default margin for better centering */

}

.chart-container {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    /* Two columns */
    gap: 20px;
    /* Space between charts */
}

.chart-item {
    width: 100%;
    height: auto;
}
</style>

<body>
    <div class="d-flex flex-wrap">
        <?php include('navbar.php') ?>

        <div class="container pt-5 col-10">
            <?php include('assets/report.php') ?>

            <h1 class="mt-5"><i class="fa-solid fa-chart-line"></i> Sales Report</h1>
            <button id="saveTableButton" class="btn btn-outline-primary mb-3">
                <i class="fa-solid fa-file-csv"></i>
                Report
            </button>
            <button id="saveChartButton" class="btn btn-outline-primary  mb-3">
                <i class="fa-solid fa-file-image"></i>
                Chart
            </button>
            <hr>
            <div class="d-flex justify-content-between">
                <select class="form-select" id="months" name="months">
                    <option value="month">Months</option>
                    <option value="01">01 - Jan</option>
                    <option value="02">02 - Feb</option>
                    <option value="03">03 - Mar</option>
                    <option value="04">04 - Apr</option>
                    <option value="05">05 - May</option>
                    <option value="06">06 - Jun</option>
                    <option value="07">07 - Jul</option>
                    <option value="08">08 - Aug</option>
                    <option value="09">09 - Sep</option>
                    <option value="10">10 - Oct</option>
                    <option value="11">11 - Nov</option>
                    <option value="12">12 - Dec</option>
                    <!-- month options -->
                </select>&nbsp;
                <select class="form-select" id="years" name="years">
                    <option value="years">Years</option>
                    <option value="2023">2023</option>
                    <option value="2024">2024</option>
                    <option value="2025">2025</option>
                    <option value="2026">2026</option>
                    <option value="2027">2027</option>
                    <!-- year options -->
                </select>&nbsp;
                <div class="d-flex align-items-baseline mx-auto">
                    <label for="datepicker1" class="form-label">From: </label>&nbsp;
                    <input type="date" id="datepicker1" class="form-control" />
                    <label for="datepicker2" class="form-label ms-3">To: </label>&nbsp;
                    <input type="date" id="datepicker2" class="form-control" />
                </div>&nbsp;
                <button id="filterButton" class="btn btn-info">
                    Export
                </button>
                <script>
                document.addEventListener('DOMContentLoaded', () => {
                    setDefaultDates();
                    const fromDate = document.getElementById('datepicker1').value;
                    const toDate = document.getElementById('datepicker2').value;
                    $.ajax({
                        url: 'ajax_GET/get_product_sales.php',
                        method: 'GET',
                        data: {
                            from_date: fromDate,
                            to_date: toDate
                        },
                        success: function(response) {
                            const data = JSON.parse(response);
                            $('#data-table tbody').html(data.tableRows);
                            renderChart(data.chartData);
                            // Optionally display totals
                            $('#totalSale').text(data.totalSale + ' THB');
                            $('#totalQty').text(data.totalQty + ' Pcs');
                            $('#totalItems').text(data.totalItems + ' Items');

                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching data: ', error);
                        }
                    });
                });
                var currentYear = new Date().getFullYear();
                $('#years').val(currentYear);
                $('#title').html('<i class="fa-solid fa-money-bill-trend-up"></i> ' + 'New Business Income ' +
                    currentYear);

                function setDefaultDates(month, year) {
                    const currentDate = new Date();
                    const selectedYear = year || currentDate.getFullYear();
                    const selectedMonth = month || String(currentDate.getMonth() + 1).padStart(2, '0');

                    const fromDate = `${selectedYear}-${selectedMonth}-01`;
                    document.getElementById('datepicker1').value = fromDate;

                    const lastDay = new Date(selectedYear, parseInt(selectedMonth), 0).getDate();
                    const toDate = `${selectedYear}-${selectedMonth}-${String(lastDay).padStart(2, '0')}`;
                    document.getElementById('datepicker2').value = toDate;

                    document.getElementById('months').value = selectedMonth;
                    document.getElementById('years').value = selectedYear;
                    $('#title').html('<i class="fa-solid fa-money-bill-trend-up"></i> ' + 'New Business Income ' +
                        selectedYear);
                }
                document.addEventListener('DOMContentLoaded', () => {
                    setDefaultDates();
                });
                document.getElementById('months').addEventListener('change', (event) => {
                    const selectedYear = document.getElementById('years').value;
                    setDefaultDates(event.target.value, selectedYear);
                });
                document.getElementById('years').addEventListener('change', (event) => {
                    const selectedMonth = document.getElementById('months').value;
                    setDefaultDates(selectedMonth, event.target.value);
                });

                document.getElementById('filterButton').addEventListener('click', () => {
                    const fromDate = document.getElementById('datepicker1').value;
                    const toDate = document.getElementById('datepicker2').value;

                    $.ajax({
                        url: 'ajax_GET/get_product_sales.php',
                        method: 'GET',
                        data: {
                            from_date: fromDate,
                            to_date: toDate
                        },
                        success: function(response) {
                            const data = JSON.parse(response);
                            $('#data-table tbody').html(data.tableRows);
                            renderChart(data.chartData);
                            // Optionally display totals
                            $('#totalSale').text(data.totalSale + ' THB');
                            $('#totalQty').text(data.totalQty + ' Pcs');
                            $('#totalItems').text(data.totalItems + ' Items');
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching data: ', error);
                        }
                    });
                });
                let chartInstance;
                let chartInstance2;


                function renderChart(chartDataJson) {
                    const chartData = JSON.parse(chartDataJson);
                    const labels = chartData.map(item => item.product);
                    const totalQty = chartData.map(item => item.total_qty);
                    const totalPrice = chartData.map(item => item.total_price);

                    const ctx = document.getElementById('reportChart').getContext('2d');
                    const ctx2 = document.getElementById('reportChart2').getContext('2d');

                    if (chartInstance) {
                        chartInstance.destroy();
                    }
                    if (chartInstance2) {
                        chartInstance2.destroy();
                    }

                    chartInstance = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Total Price',
                                data: totalPrice,
                                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                                borderColor: 'rgba(153, 102, 255, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                    chartInstance2 = new Chart(ctx2, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Total Qty',
                                data: totalQty,
                                backgroundColor: 'rgba(138, 212, 217, 0.2)',
                                borderColor: 'rgba(138, 212, 217, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }
                </script>
            </div>
            <br>
            <script>
            // Save Table button functionality
            document.getElementById('saveTableButton').addEventListener('click', function() {
                var table = document.getElementById('data-table');
                var csv = [];
                var rows = table.querySelectorAll('tr');
                var titleFrom = document.getElementById('datepicker1').value;
                var titleTo = document.getElementById('datepicker2').value;

                for (var i = 0; i < rows.length; i++) {
                    var row = [];
                    var cols = rows[i].querySelectorAll('td, th');
                    var colIndex = 0;

                    for (var j = 0; j < cols.length; j++) {
                        var cellText = cols[j].innerText;
                        var colspan = parseInt(cols[j].getAttribute('colspan')) || 1;


                        // Add empty cells for colspan
                        for (var k = 1; k < colspan; k++) {
                            row.push('');
                        }
                        // Add the cell text to the row
                        row.push(cellText);
                        colIndex += colspan;
                    }

                    csv.push(row.join(','));
                }

                var csvFile = new Blob([csv.join('\n')], {
                    type: 'text/csv'
                });
                var downloadLink = document.createElement('a');
                downloadLink.download = 'reportDate' + titleFrom + '.' + titleTo +
                    '.csv';
                downloadLink.href = window.URL.createObjectURL(csvFile);
                downloadLink.click();
            });
            </script>
            <div class="d-flex justify-content-between mb-5">
                <div class="card w-25">
                    <div class="card-body">
                        <a class="btn btn-lg btn-secondary"><i class="fa-solid fa-dollar-sign "></i></a>&nbsp;
                        <h5 id="totalSale"></h5>
                    </div>
                </div>
                <div class="card w-25">
                    <div class="card-body">
                        <a class="btn btn-lg btn-secondary"><i class="fa-solid fa-inbox"></i></a>&nbsp;
                        <h5 id="totalQty"></h5>
                    </div>
                </div>
                <div class="card w-25">
                    <div class="card-body">
                        <a class="btn btn-lg btn-secondary"><i class="fa-solid fa-boxes-stacked"></i></a>&nbsp;
                        <h5 id="totalItems"> </h5>
                    </div>
                </div>
            </div>
            <div class="chart-container mb-5">
                <div class="chart-item">
                    <canvas id="reportChart" width="400" height="200">
                    </canvas>
                </div>
                <div class="chart-item">
                    <canvas id="reportChart2" width="400" height="200">
                    </canvas>
                </div>
                <!-- Add more chart items here -->
            </div>
            <table class="table table-bordered mb-5" id="data-table">
                <thead>
                    <tr class=" table-info">
                        <th>Product Code</th>
                        <th>Product Name</th>
                        <th class="text-end">Quantity</th>
                        <th class="text-end">Cost Price</th>
                        <th class="text-end">Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be inserted here by jQuery -->
                </tbody>
            </table>

            <script>
            document.getElementById('saveChartButton').addEventListener('click', function() {
                var canvas = document.getElementById('reportChart');
                var link = document.createElement('a');
                var titleFrom = document.getElementById('datepicker1').value;
                var titleTo = document.getElementById('datepicker2').value;
                link.href = canvas.toDataURL('image/png'); // Convert the canvas to a PNG image
                link.download = 'chartDate' + titleFrom + '.' + titleTo +
                    '.png'; // Set the name of the downloaded file
                link.click(); // Trigger the download
            });
            </script>


        </div>
    </div>
    <?php include('footer.php') ?>
</body>

</html>