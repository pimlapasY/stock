<h1 class="mt-5" id="title"></h1>
<select class="form-select me-3 w-25" id="yearSelect">
</select>
<select class="form-select" id="dataRangeSelect" hidden>
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
    <button class="btn btn-success ms-3 d-flex align-self-center" id="exportCsv">Export CSV</button>
    <button class="btn btn-danger ms-3 d-flex align-self-center" id="exportPdf">Export PDF</button>
</div>
<table class="table table-bordered table-hover" id="dataTable">
    <thead>
        <tr class="table-primary">
            <th></th>
            <th>Description</th>
            <th>Amount (Exclude Vat)</th>
            <th>Amount (Include Vat)</th>
        </tr>
    </thead>
    <tbody id="tableBody">
        <!-- Data will be inserted here by jQuery -->
    </tbody>
    <tfoot>
        <tr class="table-secondary fw-bold" id="totalRow">
            <td colspan="2" class="text-center">Total</td>
            <td class="text-end" id="totalExcludeVat"></td>
            <td class="text-end" id="totalIncludeVat"></td>
        </tr>
    </tfoot>
</table>

<script>
    $(document).ready(function() {
        $('#exportCsv').click(function() {
            const rows = [];
            const table = $('#dataTable');

            // ดึงข้อมูลจากตาราง
            table.find('tr').each(function() {
                const row = [];
                $(this).find('th, td').each(function() {
                    let cellText = $(this).text().trim();

                    // แปลงตัวเลขให้เป็นเพียงตัวเลข (ลบ '฿', ',' และช่องว่าง)
                    if ($.isNumeric(cellText.replace(/[^0-9.-]+/g, ''))) {
                        cellText = cellText.replace(/[^0-9.-]+/g,
                            ''); // ลบสัญลักษณ์ที่ไม่ใช่ตัวเลข
                    }

                    row.push(cellText);
                });
                rows.push(row.join(',')); // ใช้ ',' คั่นข้อมูลในแต่ละเซลล์
            });

            // สร้างไฟล์ CSV
            const csvContent = rows.join('\n');
            const blob = new Blob([csvContent], {
                type: 'text/csv;charset=utf-8;'
            });
            const url = URL.createObjectURL(blob);

            // ดาวน์โหลดไฟล์ CSV
            const link = document.createElement('a');
            link.setAttribute('href', url);
            link.setAttribute('download', 'table_data.csv');
            link.style.display = 'none';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });

        // ฟังก์ชันสำหรับ Export เป็น PDF
        $('#exportPdf').click(function() {
            const table = document.getElementById('dataTable');
            const {
                jsPDF
            } = window.jspdf;

            const doc = new jsPDF('p', 'mm', 'a4');
            const margin = 10;
            const pageWidth = doc.internal.pageSize.getWidth();
            const pageHeight = doc.internal.pageSize.getHeight();

            // ใช้ html2canvas แปลงตารางเป็นรูปภาพ
            html2canvas(table).then((canvas) => {
                const imgData = canvas.toDataURL('image/png');
                const imgWidth = pageWidth - margin * 2; // ความกว้างของรูปภาพใน PDF
                const imgHeight = (canvas.height * imgWidth) / canvas.width;

                let position = margin;

                doc.addImage(imgData, 'PNG', margin, position, imgWidth, imgHeight);

                // หากรูปภาพสูงกว่าหน้ากระดาษ ให้เพิ่มหน้าใหม่
                if (imgHeight > pageHeight - margin * 2) {
                    let heightLeft = imgHeight - (pageHeight - margin * 2);
                    while (heightLeft > 0) {
                        position = 0;
                        doc.addPage();
                        doc.addImage(imgData, 'PNG', margin, position, imgWidth, Math.min(
                            heightLeft, pageHeight - margin * 2));
                        heightLeft -= pageHeight - margin * 2;
                    }
                }

                // ดาวน์โหลด PDF
                doc.save('table_data.pdf');
            });
        });
        // ตั้งค่า Dropdown ปี
        const currentYear = new Date().getFullYear();
        const startYear = currentYear - 10; // เริ่มจาก 10 ปีที่แล้ว
        const $yearSelect = $('#yearSelect');

        for (let year = startYear; year <= currentYear; year++) {
            const option = `<option value="${year}">${year}</option>`;
            $yearSelect.append(option);
        }
        $yearSelect.val(currentYear); // ตั้งค่าเริ่มต้นเป็นปีปัจจุบัน

        // เรียก fetchData เมื่อเปลี่ยนปี
        $yearSelect.change(function() {
            fetchData($('#dataRangeSelect').val());
        });

        // เรียก fetchData เมื่อเปลี่ยนช่วงเวลา
        $('#dataRangeSelect').change(function() {
            fetchData($(this).val());
        });

        // เรียก fetchData ครั้งแรก
        fetchData($('#dataRangeSelect').val());

        // ฟังก์ชันดึงข้อมูลจาก Backend
        function fetchData(selectedOption) {
            const selectedYear = $('#yearSelect').val();
            let title = '';
            let dataType = '';

            switch (selectedOption) {
                case '1':
                    title = `Yearly Report for ${selectedYear}`;
                    dataType = 'yearly';
                    break;
                case '2':
                    title = `Monthly Report for ${selectedYear}`;
                    dataType = 'monthly';
                    break;
                case '3':
                    title = `Weekly Report for ${selectedYear}`;
                    dataType = 'weekly';
                    break;
                default:
                    title = 'Report Overview';
                    dataType = 'monthly';
                    break;
            }

            $('#titleReport').text(title);

            $.ajax({
                url: 'ajax_GET/get_product_report.php',
                method: 'GET',
                data: {
                    type: dataType,
                    year: selectedYear
                },
                dataType: 'json',
                success: function(data) {
                    populateTable(data, dataType, selectedYear);
                    updateChart(data, dataType, selectedYear);
                },
                error: function(error) {
                    console.error('Error fetching data:', error);
                }
            });
        }

        // ฟังก์ชันแสดงข้อมูลในตาราง
        function populateTable(data, dataType, selectedYear) {
            const $tableBody = $('#tableBody');
            $tableBody.empty();

            let grandTotalExcludeVat = 0;
            let grandTotalIncludeVat = 0;

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

            function formatCurrency(value) {
                return '฿ ' + parseFloat(value || 0).toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            if (dataType === 'monthly') {
                // เตรียมข้อมูลรายเดือน (1-12)
                const dataMap = {};
                for (let month = 1; month <= 12; month++) {
                    const monthKey = month < 10 ? `0${month}` : `${month}`;
                    dataMap[`${selectedYear}-${monthKey}`] = [];
                }

                // เติมข้อมูลที่ได้จาก backend
                data.forEach(item => {
                    if (item.month) {
                        dataMap[item.month].push({
                            p_product_code: item.p_product_code,
                            p_product_name: item.p_product_name,
                            amount_exclude_vat: item.amount_exclude_vat,
                            amount_include_vat: item.amount_include_vat
                        });
                    }
                });

                // แสดงข้อมูลในตาราง
                Object.keys(dataMap).forEach(key => {
                    const monthData = dataMap[key];
                    const [year, month] = key.split('-');
                    const timePeriod = `${monthNames[month]} ${year}`;

                    let totalExcludeVat = 0;
                    let totalIncludeVat = 0;

                    monthData.forEach(item => {
                        totalExcludeVat += parseFloat(item.amount_exclude_vat || 0);
                        totalIncludeVat += parseFloat(item.amount_include_vat || 0);
                    });

                    // เพิ่มผลรวมรายเดือนเข้า grand total
                    grandTotalExcludeVat += totalExcludeVat;
                    grandTotalIncludeVat += totalIncludeVat;

                    const totalRow = `
            <tr class="fw-bold">
                <td class="text-primary">${timePeriod}</td>
                <td></td>
                <td class="text-end text-primary">${formatCurrency(totalExcludeVat)}</td>
                <td class="text-end text-primary">${formatCurrency(totalIncludeVat)}</td>
            </tr>
            `;
                    $tableBody.append(totalRow);

                    monthData.forEach(item => {
                        const row = `
                <tr>
                    <td></td>
                    <td>${item.p_product_name || 'N/A'} (${item.p_product_code || 'N/A'})</td>
                    <td class="text-end">${formatCurrency(item.amount_exclude_vat)}</td>
                    <td class="text-end">${formatCurrency(item.amount_include_vat)}</td>
                </tr>
                `;
                        $tableBody.append(row);
                    });
                });
            }

            // อัปเดตผลรวมใน tfoot
            $('#totalExcludeVat').text(formatCurrency(grandTotalExcludeVat));
            $('#totalIncludeVat').text(formatCurrency(grandTotalIncludeVat));
        }

        // ฟังก์ชันอัปเดตกราฟ
        let chartInstance = null;

        function updateChart(data, dataType, selectedYear) {
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

            const labels = [];
            const amountExcludeVat = [];
            const amountIncludeVat = [];

            if (dataType === 'monthly') {
                for (let month = 1; month <= 12; month++) {
                    const monthKey = month < 10 ? `0${month}` : `${month}`;
                    labels.push(`${monthNames[monthKey]} ${selectedYear}`);
                    amountExcludeVat.push(0);
                    amountIncludeVat.push(0);
                }

                data.forEach(item => {
                    const [year, month] = (item.month || '').split('-');
                    const index = labels.indexOf(`${monthNames[month]} ${year}`);
                    if (index > -1) {
                        amountExcludeVat[index] += parseFloat(item.amount_exclude_vat || 0);
                        amountIncludeVat[index] += parseFloat(item.amount_include_vat || 0);
                    }
                });
            }

            if (chartInstance) chartInstance.destroy();
            chartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'Amount (Exclude Vat)',
                            data: amountExcludeVat,
                            backgroundColor: 'rgba(75, 192, 192, 0.5)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Amount (Include Vat)',
                            data: amountIncludeVat,
                            backgroundColor: 'rgba(153, 102, 255, 0.5)',
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