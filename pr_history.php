<?php include('navbar.php');
 $currentMonth = date('m'); 
 $currentYear = date('Y'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    <title>PR History</title>
</head>

<body>
    <div class="container-fluid">
        <div class="d-flex justify-content-start m-5">
            <h1 id="head_list">
                <?php
                echo '<i class="fa-solid fa-file-medical fa-xl"></i> ' . 'PR History';
                ?>
            </h1>
        </div>
        <div class="d-flex justify-content-between">
            <div class="d-flex align-self-start mb-4">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link tab" href="#" id="productTab" style="font-size: 20px;">
                            <i class="fa-solid fa-box fa-lg"></i> All Store
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link tab" href="#" id="samtTab" style="font-size: 20px;">
                            <i class="fa-solid fa-store fa-lg"></i> SAMT Store
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link tab" href="#" id="sakabaTab" style="font-size: 20px;">
                            <i class="fa-solid fa-store fa-lg"></i> SAKABA Store
                        </a>
                    </li>
                </ul>
            </div>
            <div class="d-flex align-self-end  align-items-center mb-2">
                <div class="d-flex align-items-baseline mx-auto">
                    <select class="form-select" id="months" name="months">
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
                    </select>&nbsp;
                    <div class="d-flex align-items-baseline mx-auto">
                        <label for="datepicker1" class="form-label">From: </label>&nbsp;
                        <input type="date" id="datepicker1" class="form-control" />
                        <label for="datepicker2" class="form-label ms-3">To: </label>&nbsp;
                        <input type="date" id="datepicker2" class="form-control" />
                    </div>&nbsp;
                    <script>
                    function setDefaultDates(month) {
                        const currentDate = new Date();
                        const year = currentDate.getFullYear();
                        const selectedMonth = month || String(currentDate.getMonth() + 1).padStart(2, '0');

                        // Set the "From" date to the 1st of the selected month
                        const fromDate = `${year}-${selectedMonth}-01`;
                        document.getElementById('datepicker1').value = fromDate;

                        // Calculate the last day of the selected month
                        const lastDay = new Date(year, parseInt(selectedMonth), 0).getDate();
                        const toDate = `${year}-${selectedMonth}-${String(lastDay).padStart(2, '0')}`;
                        document.getElementById('datepicker2').value = toDate;

                        // Set the dropdown to the selected month
                        document.getElementById('months').value = selectedMonth;
                    }

                    // Initialize default dates on page load
                    document.addEventListener('DOMContentLoaded', () => {
                        setDefaultDates();
                    });

                    // Update dates when the month selection changes
                    document.getElementById('months').addEventListener('change', (event) => {
                        setDefaultDates(event.target.value);
                    });
                    </script>
                </div>
                <!-- <a class="btn btn-primary btn-rounded" id="exchange" style="white-space: nowrap;"><i
                        class="fa-solid fa-right-left"></i> Stock Exchange</a>&nbsp; -->
                <a class="btn btn-info btn-rounded"><i class="fa-solid fa-check-to-slot"></i> Good Request</a>&nbsp;
            </div>
        </div>

        <table class="table table-hover mx-auto">
            <thead class="text-center table-secondary" style="text-transform: uppercase;">
                <tr>
                    <th>PR CODE</th>
                    <th>Store</th>
                    <th>mg CODE</th>
                    <th>product</th>
                    <th>size</th>
                    <th>color</th>
                    <th>hand</th>
                    <th>qty</th>
                    <th>PR date</th>
                    <th>customer</th>
                    <th>paid by</th>
                    <th>payment</th>
                    <th>PR/PO</th>
                    <!-- <th>Delivery</th> -->
                    <th><input class="form-check-input" type="checkbox" id="select-all"></th>
                    <th>Memo</th>
                </tr>
            </thead>
            <tbody id="dataTable" class="table-group-divider table-divider-color">
                <!-- Data will be loaded here via AJAX -->
            </tbody>
        </table>

        <!-- Modal for Editing Data -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header text-bg-light">
                        <h5 class="modal-title" id="editModalLabel">Edit Modal Title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modal-body-content">
                        <div class="row">
                            <div class="col-md-6">
                                <form id="editForm">
                                    <div class="mb-3">
                                        <label for="prCodeInput" class="form-label">PR Code:</label>
                                        <input type="text" class="form-control" id="prCodeInput" disabled>
                                    </div>
                                    <div class="mb-3">
                                        <label for="dateAddedInput" class="form-label">PR Date:</label>
                                        <input type="text" class="form-control" id="dateAddedInput" disabled>
                                    </div>
                                    <div class="mb-3">
                                        <label for="mgCode" class="form-label">MG Code:</label>
                                        <input type="text" class="form-control" id="mgCode" disabled>
                                    </div>
                                    <div class="mb-3">
                                        <label for="productCode" class="form-label">Product Code:</label>
                                        <input type="text" class="form-control" id="productCode" disabled>
                                    </div>
                                    <div class="mb-3">
                                        <label for="productName" class="form-label">Product Name:</label>
                                        <input type="text" class="form-control" id="productName" disabled>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-6">
                                <form id="editForm">
                                    <div class="mb-3">
                                        <label for="size" class="form-label">Size:</label>
                                        <input type="text" class="form-control" id="size">
                                    </div>
                                    <div class="mb-3">
                                        <label for="color" class="form-label">Color:</label>
                                        <input type="text" class="form-control" id="color">
                                    </div>
                                    <div class="mb-3">
                                        <label for="hand" class="form-label">Hand:</label>
                                        <input type="text" class="form-control" id="hand">
                                    </div>
                                    <div class="mb-3">
                                        <label for="qty" class="form-label">Qty:</label>
                                        <input type="number" class="form-control" id="qty">
                                    </div>
                                    <div class="mb-3">
                                        <label for="soldDate" class="form-label">Sold Date:</label>
                                        <input type="text" class="form-control" id="soldDate" disabled>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- Fetched data will be displayed here -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="submitFormExchange"><i
                                class="fa-solid fa-right-left">
                            </i>
                            Exchange</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <!-- Add additional buttons as needed -->
                    </div>
                </div>
            </div>
        </div>
        <!-- EXCHANGE MODAL FOR PR MANAGE-->
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('select-all');
            const dataTable = document.getElementById('dataTable');

            selectAllCheckbox.addEventListener('change', function() {
                const checkboxes = dataTable.querySelectorAll('.select-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked;
                });
            });

        });
        /*  // Get the current date
        const currentDate = new Date();

        // Set the day to the 1st
        currentDate.setDate(1);

        // Get the year, month, and day (formatted to YYYY-MM-DD)
        const year = currentDate.getFullYear();
        const month = String(currentDate.getMonth() + 1).padStart(2, '0');
        const day = String(currentDate.getDate()).padStart(2, '0');

        // Create the formatted date string
        const formattedDatePicker = `${year}-${month}-${day}`;

        // Set the value of the date input
        document.getElementById('datepicker1').value = formattedDatePicker; */

        $(document).ready(function() {
            // ตั้งค่า default เดือนเป็นเดือนปัจจุบัน
            var currentMonth = new Date().getMonth() +
                1; // getMonth() คืนค่าเป็น 0-11, เราจึงต้องบวก 1
            var formattedMonth = currentMonth < 10 ? '0' + currentMonth :
                currentMonth; // ถ้าเดือนน้อยกว่า 10 ให้เพิ่ม 0 ข้างหน้า

            $('#months').val(formattedMonth);


            $('#months').change(function() {
                var selectedValue = $(this).val();
                loadData(null, selectedValue);
            });



            // Function to load data based on button clicked and selected month
            function loadData(store = null, month = formattedMonth) {
                // Define the URL for the AJAX request
                var url = "pr_his_fetch.php";
                // Define the data to be sent
                var data = {
                    store: store,
                    month: month,
                    page: 'history'
                };
                // Perform an AJAX request
                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    success: function(response) {
                        // Replace the content of dataTable with the new data
                        $('#dataTable').html(response);
                    }
                });
            }

            // Function to handle tab click events
            function handleTabClick(tabId, store) {
                $('.tab').removeClass('active');
                $(tabId).addClass('active');
                $('#months').val();
                loadData(store);
            }

            // Load all data when the page loads
            handleTabClick('#productTab');

            // Set up click event handlers for each tab
            $('#productTab').click(function(e) {
                e.preventDefault();
                handleTabClick('#productTab');
            });

            $('#samtTab').click(function(e) {
                e.preventDefault();
                handleTabClick('#samtTab', 'samt');
            });

            $('#sakabaTab').click(function(e) {
                e.preventDefault();
                handleTabClick('#sakabaTab', 'sakaba');
            });
        });
        </script>
</body>

</html>


<script>
$(document).ready(function() {
    // Handler for Exchange button click
    $('#exchange').click(function() {
        // Toggle visibility of all edit buttons associated with each row
        $('.edit-button').toggle();
    });

    //////////////////////////////////
    $('#submitFormExchange').click(function() {
        // Get data from both forms
        var prCode = $('#prCodeInput').val(); // Example: Fetch PR Code from first form
        var pdCode = $('#productCode').val(); // Example: Fetch PR Code from first form
        var pdName = $('#productName').val();
        var size = $('#size').val();
        var color = $('#color').val();
        var hand = $('#hand').val();
        var qty = $('#qty').val();


        // AJAX request to send data to server-side script for insertion
        $.ajax({
            url: 'pr_submit_exchange.php', // Replace with your server-side script URL
            method: 'POST',
            data: {
                pr_code: prCode,
                size: size,
                color: color,
                hand: hand,
                qty: qty,
                pdCode: pdCode,
                pdName: pdName
            },
            success: function(response) {
                // Handle success response (if needed)
                console.log('Data inserted successfully');

                Swal.fire({
                    icon: "success",
                    title: "Success!",
                    //text: response,
                    showConfirmButton: true
                    //timer: 1500
                }).then(function() {
                    // Redirect or perform additional actions if needed
                    location.reload();
                });
                //alert(response);
                // Optionally, clear form fields or reset form state
            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error('Error inserting data:', error);
                alert('Error inserting data');
            }
        });
    });
});

function openEditModal(prCode) {
    console.log(prCode); // Log prCode to verify it's received correctly
    $('#editModalLabel').html('<i class = "fa-solid fa-right-left"> </i> Exchange ' + prCode);
    // Clear previous content and show loading state
    //$('#modal-body-content').html('<p>Loading...</p>');

    // AJAX request to fetch data based on pr_code
    $.ajax({
        url: 'pr_fetch_exchange.php',
        method: 'POST',
        dataType: 'json',
        data: {
            pr_code: prCode
        },
        success: function(data) {
            // Clear loading message
            //$('#modal-body-content').html('');

            // Populate the modal with fetched data
            if (data) {
                $('#prCodeInput').val(data.pr_code);
                $('#dateAddedInput').val(data.pr_date_add);
                $('#mgCode').val(data.pr_mg_code);
                $('#productName').val(data.p_product_name);
                $('#size').val(data.p_size);
                $('#color').val(data.p_color);
                $('#hand').val(data.p_hands);
                $('#qty').val(data.pr_qty);
                $('#productCode').val(data.p_product_code);
                $('#soldDate').val(data.o_out_date);

            } else {
                $('#modal-body-content').html('<p>No data found</p>');
            }
        },
        error: function(xhr, status, error) {
            console.error('Failed to fetch data:', status, error);
            $('#modal-body-content').html('<p>Error fetching data</p>');
        },
        complete: function() {
            $('#editModal').modal('show');
        }

    });
}
</script>