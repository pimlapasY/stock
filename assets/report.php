<h1 id="title"></h1>
<select class="form-select" id="dataRangeSelect">
    <option value="0">yearly / monthly / weekly</option>
    <option value="1">yearly</option>
    <option value="2">monthly</option>
    <option value="3">weekly</option>
    <!-- Add year options here if needed -->
</select>
<hr>

<canvas id="myChart" width="200" height="50"></canvas>
<h1 class="mt-5 text-primary text-center">New Products</h1>
<div class="d-flex">
    <h2 id="titleReport">Data Overview</h2>
    <a class="btn btn-primary mb-3 ms-3 d-flex align-self-center" id="printBtn"><i class="fa-solid fa-print"></i>
        Print</a>
</div>
<table class="table table-bordered" id="dataTable">
    <thead>
        <tr class="table-primary">
            <th></th>
            <th>Amount (Exclude Vat)</th>
            <th>Amount (Include Vat)</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody id="tableBody">
        <!-- Data will be inserted here by jQuery -->
    </tbody>
</table>

<script>
$(document).ready(function() {
    // Print function
    $('#printBtn').click(function() {
        const {
            jsPDF
        } = window.jspdf;
        const doc = new jsPDF();
        const element = document.getElementById('dataTable');

        html2canvas(element).then((canvas) => {
            const imgData = canvas.toDataURL('image/png');
            const imgWidth = 210; // A4 width in mm
            const pageHeight = 297; // A4 height in mm
            const imgHeight = canvas.height * imgWidth / canvas.width;
            let heightLeft = imgHeight;

            let position = 0;

            doc.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
            heightLeft -= pageHeight;

            while (heightLeft >= 0) {
                position = heightLeft - imgHeight;
                doc.addPage();
                doc.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                heightLeft -= pageHeight;
            }

            doc.save($('#titleReport').text() + '.pdf');
        });
    });
    // Set the default value to monthly
    $('#dataRangeSelect').val(2);

    // Function to fetch and display data based on the selected option
    function fetchData(selectedOption) {
        let title = '';
        let dataType = '';

        switch (selectedOption) {
            case '1':
                title = 'Yearly Report';
                dataType = 'yearly';
                break;
            case '2':
                title = 'Monthly Report';
                dataType = 'monthly';
                break;
            case '3':
                title =
                    'Weekly Report <h5 class="text-danger">*7 days (The data shows only the weeks with sales.)</h5>';
                dataType = 'weekly';
                break;
            default:
                title = 'Report Overview';
                dataType = 'monthly';
                break;
        }

        $('#titleReport').html(title);

        if (dataType) {
            $.ajax({
                url: 'ajax_GET/get_product_report.php',
                method: 'GET',
                data: {
                    type: dataType
                },
                dataType: 'json',
                success: function(data) {
                    populateTable(data, dataType);
                    updateChart(data, dataType);
                },
                error: function(error) {
                    console.error('Error fetching data:', error);
                }
            });
        }
    }

    // Trigger change event to load data when the select input is changed
    $('#dataRangeSelect').change(function() {
        const selectedOption = $(this).val();
        fetchData(selectedOption);
    });

    // Trigger the change event manually to load monthly data by default
    fetchData($('#dataRangeSelect').val());


    function populateTable(data, dataType) {
        const $tableBody = $('#tableBody');
        $tableBody.empty();

        // Month names mapping
        const monthNames = {
            '01': 'January',
            '02': 'February',
            '03': 'March',
            '04': 'April',
            '05': 'May',
            '06': 'June',
            '07': 'July',
            '08': 'August',
            '09': 'September',
            '10': 'October',
            '11': 'November',
            '12': 'December'
        };

        // Number formatter for currency
        const numberFormatter = new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'THB',
            minimumFractionDigits: 2
        });

        function formatCurrency(value) {
            return '฿ ' + parseFloat(value).toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }
        if (dataType === 'monthly') {
            const currentYear = new Date().getFullYear();
            let dataMap = {};

            // Initialize dataMap with all months
            for (let month = 1; month <= 12; month++) {
                const monthKey = month < 10 ? `0${month}` : `${month}`;
                dataMap[`${currentYear}-${monthKey}`] = [];
            }

            // Update dataMap with actual data
            data.forEach(item => {
                dataMap[item.month].push({
                    p_product_code: item.p_product_code,
                    p_product_name: item.p_product_name,
                    amount_exclude_vat: parseFloat(item.amount_exclude_vat),
                    amount_include_vat: parseFloat(item.amount_include_vat),
                    description: item.description
                });
            });

            Object.keys(dataMap).forEach(key => {
                const monthData = dataMap[key];
                const [year, month] = key.split('-');
                const timePeriod = `${monthNames[month]} ${year}`;

                // Aggregate amounts for the month
                let totalExcludeVat = 0;
                let totalIncludeVat = 0;
                monthData.forEach(item => {
                    totalExcludeVat += item.amount_exclude_vat;
                    totalIncludeVat += item.amount_include_vat;
                });


                const totalRow = `
                <tr class="fw-bold">
                    <td class="text-primary">${timePeriod}</td>
                    <td class="text-end text-primary">${formatCurrency(totalExcludeVat)}</td>
                    
                    <td class="text-end text-primary">${formatCurrency(totalIncludeVat)}</td>
                    <td></td>
                </tr>
            `;
                $tableBody.append(totalRow);
                // Add rows for each product
                monthData.forEach(item => {
                    const row = `
                    <tr>
                        <td></td>
                        <td class="text-end">${formatCurrency(item.amount_exclude_vat)}</td>
                        <td class="text-end">${formatCurrency(item.amount_include_vat)}</td>
                        <td>${item.p_product_name} (${item.p_product_code})</td>
                    </tr>
                `;
                    $tableBody.append(row);
                });
            });
        } else {
            // For yearly and weekly data
            data.forEach(item => {
                let timePeriod = '';
                if (dataType === 'yearly') {
                    timePeriod = item.year;
                } else if (dataType === 'weekly') {
                    timePeriod = `${item.week_start} - ${item.week_end}`;
                }
                const row = `
                <tr>
                    <td>${timePeriod}</td>
                    <td class="text-end">${formatCurrency(item.amount_exclude_vat)}</td>
                    <td class="text-end">${formatCurrency(item.amount_include_vat)}</td>
                    <td>${item.description}</td>
                </tr>
            `;
                $tableBody.append(row);
            });
        }
    }


    function updateChart(data, dataType) {
        const ctx = document.getElementById('myChart').getContext('2d');
        const monthNames = {
            '01': 'January',
            '02': 'February',
            '03': 'March',
            '04': 'April',
            '05': 'May',
            '06': 'June',
            '07': 'July',
            '08': 'August',
            '09': 'September',
            '10': 'October',
            '11': 'November',
            '12': 'December'
        };

        let labels = [];
        let amountExcludeVat = [];
        let amountIncludeVat = [];

        if (dataType === 'monthly') {
            const currentYear = new Date().getFullYear();
            for (let month = 1; month <= 12; month++) {
                const monthKey = month < 10 ? `0${month}` : `${month}`;
                labels.push(`${monthNames[monthKey]} ${currentYear}`);
                amountExcludeVat.push(0);
                amountIncludeVat.push(0);
            }
            data.forEach(item => {
                const [year, month] = item.month.split('-');
                const index = labels.indexOf(`${monthNames[month]} ${year}`);
                if (index > -1) {
                    amountExcludeVat[index] += parseFloat(item.amount_exclude_vat);
                    amountIncludeVat[index] += parseFloat(item.amount_include_vat);
                }
            });
        } else {
            // For yearly and weekly data
            labels = data.map(item => {
                if (dataType === 'yearly') {
                    return item.year;
                } else if (dataType === 'weekly') {
                    return item.week;
                }
            });
            amountExcludeVat = data.map(item => item.amount_exclude_vat);
            amountIncludeVat = data.map(item => item.amount_include_vat);
        }

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                        label: 'Amount (Exclude Vat)',
                        data: amountExcludeVat,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Amount (Include Vat)',
                        data: amountIncludeVat,
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

});
</script>