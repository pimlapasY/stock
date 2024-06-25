<?php include('navbar.php');
$currentDay = date('d');
$currentMonth = date('m');
$currentYear = date('Y');

// Check if the current month is December
if ($currentMonth == 12) {
    $nextMonth = 1;  // January
    $nextYear = $currentYear + 1;
} else {
    $nextMonth = $currentMonth + 1;
    $nextYear = $currentYear;
}

// Format next month to always be two digits
$nextMonth = str_pad($nextMonth, 2, '0', STR_PAD_LEFT);

 
    // Fetch product names from the database
    $stmt_code = $pdo->query("SELECT DISTINCT p_product_code FROM product");
    $productNames_code = $stmt_code->fetchAll(PDO::FETCH_COLUMN); 
    // Fetch product names from the database
    $stmt_color = $pdo->query("SELECT DISTINCT p_color FROM product");
    $productNames_color = $stmt_color->fetchAll(PDO::FETCH_COLUMN);       
    // Fetch product names from the database
    $stmt_size = $pdo->query("SELECT DISTINCT p_size FROM product");
    $productNames_size = $stmt_size->fetchAll(PDO::FETCH_COLUMN);    
    // Fetch product names from the database
    $stmt_hands = $pdo->query("SELECT DISTINCT p_hands FROM product");
    $productNames_hands = $stmt_hands->fetchAll(PDO::FETCH_COLUMN);  
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    <title>PR Management</title>
</head>

<body>
    <div class="container-fluid">
        <div class="d-flex justify-content-start m-5">
            <h1 id="head_list">
                <?php
                echo '<i class="fa-solid fa-paste fa-xl"></i> ' . $pr_manage;
                ?>
            </h1>
        </div>
        <?php 
        $currentDayDemo = 20;
        if( $currentDay  == 20){
            echo '<div class="alert alert-primary alert-dismissible fade show" role="alert">
            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
            <strong>'.$currentDateText.$currentDay.'/'.$currentMonth.'/'.$currentYear.'</strong> '. $updatePayment_des. $nextMonth.'/'.$currentYear.'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>'; 
            /* echo '<div class="alert alert-primary alert-dismissible fade show" role="alert">
            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
            <strong>(Demo)'.$currentDateText.$currentDayDemo.'/'.$currentMonth.'/'.$currentYear.'</strong> '. $updatePayment_des. $nextMonth.'/'.$currentYear.'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>'; */ 
        }elseif($currentDay  <= 19 && $currentDays >= 15){
            echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:">
                <use xlink:href="#info-fill" />
            </svg>
            <strong>'.$coming.$currentDateText.$currentDay.'</strong> '.$coming_sub.'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';  
           /*  echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:">
                <use xlink:href="#info-fill" />
            </svg>
            <strong>(Demo)'.$coming.$currentDateText.$currentDayDemo.'</strong> '.$coming_sub.'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';  */ 
        }
           /*  echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:">
                <use xlink:href="#info-fill" />
            </svg>
            <strong>'.$coming.$currentDateText.$currentDay.'</strong> '.$coming_sub.'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';  
            echo '<div class="alert alert-primary alert-dismissible fade show" role="alert">
            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
            <strong>'.$currentDateText.$currentDay.'/'.$currentMonth.'/'.$currentYear.'</strong> '. $updatePayment_des. $nextMonth.'/'.$currentYear.'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>'; */      ?>
        <div class="d-flex align-self-center mb-2">
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
            <!--  <a class="btn btn-primary btn-rounded" id="exchange" style="white-space: nowrap;"><i
                    class="fa-solid fa-right-left"></i> PR Exchange</a>&nbsp; -->
            <a class="btn btn-info btn-rounded" style="white-space: nowrap;"><i class="fa-solid fa-circle-check"></i>
                Purchase</a>&nbsp;
            <a class="btn btn-success btn-rounded" style="white-space: nowrap;"><i
                    class="fa-solid fa-boxes-stacked"></i>
                Stockin</a>&nbsp;
            <a class="btn btn-warning btn-rounded" style="white-space: nowrap;"><i
                    class="fa-solid fa-truck-ramp-box"></i>
                Delivered</a>&nbsp;

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
            <select class="form-select" id="years" name="year">
                <option value="2023">2023</option>
                <option value="2024">2024</option>
                <option value="2025">2025</option>
                <option value="2026">2026</option>
                <option value="2027">2027</option>
            </select>&nbsp;
            <select class="form-select" id="paymentStatus" name="paymentStatus">
                <option value="all">All payment</option>
                <option value="1">- Pending Payment</option>
                <option value="2">- Payment Successful</option>
            </select>&nbsp;
            <select class="form-select" id="prStatusSelected" name="prStatusSelected">
                <option value="all">All status</option>
                <option value="99">- Pending</option>
                <option value="1">- Issue PR/PO</option>
                <option value="2">- Delivered</option>
            </select>
        </div>


        <table class="table table-hover mx-auto" id="pr-table">
            <thead class="text-center table-info" style="text-transform: uppercase;">
                <tr>
                    <th>PR CODE</th>
                    <th>store</th>
                    <th>mg CODE</th>
                    <th>product</th>
                    <th>size</th>
                    <th>color</th>
                    <th>hand</th>
                    <th>qty</th>
                    <th>Sold date</th>
                    <th>customer</th>
                    <th>paid by</th>
                    <th>payment</th>
                    <th>PR Status</th>
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
                            <div class="d-flex justify-content-lg-start">
                                <label for="prStatus" class="form-label">PR Status:&nbsp;</label>
                                <h5 id="prStatus"></h5>
                            </div>
                            <div class="d-flex justify-content-lg-end">
                                <button class="btn btn-outline-secondary" onclick="editProduct()"><i
                                        class="fa-solid fa-pen-to-square"></i>
                                    EDIT</button>
                                <input class="form-control" id="productID" hidden>
                            </div>
                        </div>

                        <div class="row ms-4 me-4">
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
                                        <input type="search" class="form-control" id="size" list="product_size"
                                            disabled>
                                        <datalist id="product_size">
                                            <?php foreach ($productNames_size as $productName_size): ?>
                                            <option value="<?php echo $productName_size; ?>">
                                                <?php endforeach; ?>
                                        </datalist>
                                    </div>
                                    <div class="mb-3">
                                        <label for="color" class="form-label">Color:</label>
                                        <input type="search" class="form-control" id="color" list="product_names_color"
                                            disabled>
                                        <datalist id="product_names_color">
                                            <?php foreach ($productNames_color as $productName_color): ?>
                                            <option value="<?php echo $productName_color; ?>">
                                                <?php endforeach; ?>
                                        </datalist>
                                    </div>
                                    <div class="mb-3">
                                        <label for="hand" class="form-label">Hand:</label>
                                        <input type="search" class="form-control" id="hand" list="product_hand"
                                            disabled>
                                        <datalist id="product_hand">
                                            <?php foreach ($productNames_hands as $productName_hand): ?>
                                            <option value="<?php echo $productName_hand; ?>">
                                                <?php endforeach; ?>
                                        </datalist>
                                    </div>
                                    <div class="mb-3">
                                        <label for="qty" class="form-label">Qty:</label>
                                        <input type="number" class="form-control" id="qty" disabled>
                                    </div>
                                    <div class="mb-3">
                                        <label for="soldDate" class="form-label">Sold Date:</label>
                                        <input type="text" class="form-control" id="soldDate" disabled>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="d-flex flex-column m-4">
                            <button type="button" class="btn  btn-primary" id="submitFormExchange"><i
                                    class="fa-solid fa-right-left">
                                </i>
                                Exchange</button>&nbsp;
                            <button type="button" class="btn  btn-warning" id="delivered">
                                <i class="fa-solid fa-truck-ramp-box"></i>
                                Delivered</button>&nbsp;

                            <button type="button" class="btn  btn-success" id="stockin">
                                <i class="fa-solid fa-box"></i>
                                Stockin</button>
                        </div>
                        <!-- Fetched data will be displayed here -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <!-- Add additional buttons as needed -->
                    </div>
                </div>
            </div>
        </div>
        <!-- EXCHANGE MODAL FOR PR MANAGE-->

    </div>
    <!-- jQuery -->

    <script>
    function loadData(store = null, month = formattedMonth, payment = 0, year = currentYear,
        prStatusSelected) {

        console.log('Payment: ' + ($('#paymentStatus').val()));
        console.log('PRStatus: ' + ($('#prStatusSelected').val()));
        // Define the URL for the AJAX request
        var url = "pr_mnm_fetch.php";
        // Define the data to be sent
        var data = {
            store: store,
            month: month,
            payment: payment,
            year: year,
            prStatusSelected: prStatusSelected
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

    // Function to get the active store based on the active tab
    function getActiveStore() {
        var activeTabId = $('.tab.active').attr('id');
        var store = null; // Default store value

        // Determine store based on active tab ID
        if (activeTabId === 'samtTab') {
            store = 'samt';
        } else if (activeTabId === 'sakabaTab') {
            store = 'sakaba';
        }

        console.log(activeTabId); // Output the active tab ID to console for debugging

        // Return the store based on the active tab
        return getStoreFromTabId(store);
    }

    // Function to map tab ID to store
    function getStoreFromTabId(tabId) {
        switch (tabId) {
            case 'samt':
                return 'samt';
            case 'sakaba':
                return 'sakaba';
            default:
                return null; // Return null for unknown or default case
        }
    }




    // Function to load data based on button clicked and selected month


    // Function to handle tab click events
    function handleTabClick(tabId, store) {
        $('.tab').removeClass('active');
        $(tabId).addClass('active');
        var month = $('#months').val();
        var paymentStatus = $('#paymentStatus').val();
        var prStatusSelected = $('#prStatusSelected').val();
        var year = $('#years').val();
        loadData(store, month, paymentStatus, year, prStatusSelected);
    }

    function updatePayment(mgCode, paymentStatus) {
        // Toggle payment status: if current status is 1, set to 2, else set to 1
        var new_payment_status = (paymentStatus == 1) ? 2 : 1;

        // AJAX request
        $.ajax({
            type: "POST",
            url: "pr_update_payment.php",
            data: {
                o_mg_code: mgCode,
                new_payment_status: new_payment_status
            },
            success: function(response) {

                // Show success notification
                Swal.fire({
                    icon: "success",
                    title: "Success!",
                    text: "Payment updated for " + mgCode,
                    showConfirmButton: true
                }).then(() => {
                    location.reload(); // Reload the page after the notification is closed
                });

                // Optionally, update UI or handle success response
                console.log("Payment status updated successfully.");

                // You can update the UI here if needed

                /* var selectedYear = $('#years').val();
                var paymentStatus = $('#paymentStatus').val();
                var prStatusSelected = $('#prStatusSelected').val();
                var selectedMonth = $('#months').val();

                var activeTabId = $('.tab.active').attr('id');
                var store = null; // Default store value

                // Determine store based on active tab ID
                if (activeTabId === 'samtTab') {
                    store = 'samt';
                } else if (activeTabId === 'sakabaTab') {
                    store = 'sakaba';
                }
                loadData(store, selectedMonth, paymentStatus, selectedYear, prStatusSelected); */
            },
            error: function(xhr, status, error) {
                console.error("Error updating payment status: " + error);
                // Handle error if needed
            }
        });
    }

    function editProduct() {
        var prCode = $('#prCodeInput').val();
        //$('#prCodeInput').prop('disabled', false);
        //$('#dateAddedInput').prop('disabled', false);
        //$('#mgCode').prop('disabled', false);
        //$('#productName').prop('disabled', false);
        $('#size').prop('disabled', false);
        $('#color').prop('disabled', false);
        $('#hand').prop('disabled', false);
        $('#qty').prop('disabled', false);
        //$('#productCode').prop('disabled', false);
        //$('#soldDate').prop('disabled', false);
        $('#prStatus').prop('disabled', false);
    }

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


    $(document).ready(function() {
        // เมื่อกดปิด modal ให้ทำการอัพเดทเพราะหากข้อมูลอัพเดท จะได้อัพเดทไปด้วยในตัว
        $('#editModal').on('hidden.bs.modal', function() {
            location.reload();
        });

        ///////////////////////////////////////////////////////



        var currentDate = new Date();

        ///////////////////////
        // ตั้งค่า default เดือนเป็นเดือนปัจจุบัน
        var currentMonth = new Date().getMonth() + 1; // getMonth() คืนค่าเป็น 0-11, เราจึงต้องบวก 1
        var formattedMonth = currentMonth < 10 ? '0' + currentMonth :
            currentMonth; // ถ้าเดือนน้อยกว่า 10 ให้เพิ่ม 0 ข้างหน้า



        var nextMonth = new Date().getMonth() + 2; // getMonth() คืนค่าเป็น 0-11, เราจึงต้องบวก 1
        var formattedNextMonth = nextMonth < 10 ? '0' + nextMonth :
            nextMonth; // ถ้าเดือนน้อยกว่า 10 ให้เพิ่ม 0 ข้างหน้า

        $('#months').val(formattedMonth);

        /////////////////////////////////
        var currentYear = new Date().getFullYear(); // Get current year
        console.log(currentYear);
        $('#years').val(currentYear); // Set the value of #years to the current year

        $('#years').change(function() {
            var selectedYear = $(this).val();
            var paymentStatus = $('#paymentStatus').val();
            var prStatusSelected = $('#prStatusSelected').val();
            var selectedMonth = $('#months').val();
            var activeStore = getActiveStore(); // Get the active store from the active tab
            loadData(activeStore, selectedMonth, paymentStatus, selectedYear, prStatusSelected);
        });

        $('#prStatusSelected').change(function() {
            var prStatusSelected = $(this).val();
            var selectedYear = $('#years').val();
            var paymentStatus = $('#paymentStatus').val();
            var selectedMonth = $('#months').val();
            var activeStore = getActiveStore(); // Get the active store from the active tab
            loadData(activeStore, selectedMonth, paymentStatus, selectedYear, prStatusSelected);
        });
        //////////////////////////////////
        // Load data with default values on page load
        var paymentStatus = $('#paymentStatus').val();
        var prStatusSelected = $('#prStatusSelected').val();

        /* 
                function alertMothChange() {
                    $('#months').val(formattedNextMonth);
                } */
        // Update data when the month is changed
        $('#months').change(function() {
            var selectedYear = $('#years').val();
            var selectedMonth = $(this).val();
            var paymentStatusSelected = $('#paymentStatus').val();
            var prStatus = $('#prStatusSelected').val();
            var activeStore = getActiveStore(); // Get the active store from the active tab
            loadData(activeStore, selectedMonth, paymentStatus, selectedYear, prStatusSelected);
        });

        // Update data when the payment status is changed
        $('#paymentStatus').change(function() {
            var selectedYear = $('#years').val();
            var paymentStatus = $(this).val();
            var prStatusSelected = $('#prStatusSelected').val();

            var selectedMonth = $('#months').val();
            var activeStore = getActiveStore(); // Get the active store from the active tab
            loadData(activeStore, selectedMonth, paymentStatus, selectedYear, prStatusSelected);
        });

        // Load all data when the page loads
        handleTabClick('#productTab');
        //////////////////////////////////////////////////////

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
        ///////////////////////////////////////



    });
    </script>
</body>

</html>

<script>
$(document).ready(function() {

    $('#delivered').click(function() {
        // Get data from the input field
        var prCode = $('#prCodeInput').val();

        // Send AJAX POST request
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to mark this item as delivered?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, mark as delivered!',
            cancelButtonText: 'No, cancel!'
        }).then((result) => {
            if (result.isConfirmed) {
                // User confirmed, proceed with the AJAX request
                $.ajax({
                    url: 'pr_update_status.php', // Server-side script URL
                    method: 'POST',
                    data: {
                        pr_code: prCode,
                        status: '2'
                    },
                    success: function(response) {
                        // Handle success response
                        console.log('Data inserted successfully');

                        // Display success message using SweetAlert
                        Swal.fire({
                            icon: "success",
                            title: "Success!",
                            showConfirmButton: true
                        }).then(function() {
                            // Call a function after closing the SweetAlert
                            openEditModal(prCode);
                            // Optionally reload the page or perform additional actions
                            // location.reload();
                        });
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        console.error('Error inserting data:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error inserting data'
                        });
                    }
                });
            } else {
                // User cancelled, do nothing
                console.log('User cancelled the action');
            }
        });
    });
    $('#stockin').click(function() {
        // Get data from the input field
        var prCode = $('#prCodeInput').val();

        // Send AJAX POST request
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to mark this for stock in? (this process move to PR History page)",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, mark as stock in',
            cancelButtonText: 'No, cancel!'
        }).then((result) => {
            if (result.isConfirmed) {
                // User confirmed, proceed with the AJAX request
                $.ajax({
                    url: 'pr_update_status.php', // Server-side script URL
                    method: 'POST',
                    data: {
                        pr_code: prCode,
                        status: '3'
                    },
                    success: function(response) {
                        // Handle success response
                        console.log('Data inserted successfully');

                        // Display success message using SweetAlert
                        Swal.fire({
                            icon: "success",
                            title: "Success!",
                            showConfirmButton: true
                        }).then(function() {
                            // Call a function after closing the SweetAlert
                            openEditModal(prCode);
                            // Optionally reload the page or perform additional actions
                            // location.reload();
                        });
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        console.error('Error inserting data:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error inserting data'
                        });
                    }
                });
            } else {
                // User cancelled, do nothing
                console.log('User cancelled the action');
            }
        });
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
                    openEditModal(prCode);
                    // Redirect or perform additional actions if needed
                    //location.reload();
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
    $('#editModalLabel').html('<i class="fa-solid fa-paste"></i> PR Management: ' + prCode);
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
            var prStatus = '';

            if (data.pr_status == '3') {
                prStatus = 'Stock In';
                $('#submitFormExchange').prop('disabled', true);
                $('#delivered').prop('disabled', true);
                $('#stockin').prop('disabled', true);
            } else if (data.pr_status == '1') {
                prStatus = 'Issue';
                $('#delivered').prop('disabled', false);
                $('#stockin').prop('disabled', true);
                $('#submitFormExchange').prop('disabled', true);
            } else if (data.pr_status == '2') {
                prStatus = 'Delivered';
                $('#delivered').prop('disabled', true);
                $('#stockin').prop('disabled', false);
                $('#submitFormExchange').prop('disabled', false);
            } else {
                prStatus = 'Pending';
                $('#submitFormExchange').prop('disabled', false);
                $('#delivered').prop('disabled', true);
                $('#stockin').prop('disabled', true);
            }
            // Clear loading message
            //$('#modal-body-content').html('');

            // Populate the modal with fetched data
            if (data) {
                $('#prCodeInput').val(data.pr_code);
                $('#dateAddedInput').val(data.pr_date);
                $('#mgCode').val(data.pr_mg_code);
                $('#productName').val(data.p_product_name);
                $('#size').val(data.p_size);
                $('#color').val(data.p_color);
                $('#hand').val(data.p_hands);
                $('#qty').val(data.pr_qty);
                $('#productCode').val(data.p_product_code);
                $('#soldDate').val(data.o_out_date);
                $('#prStatus').html(prStatus);
                $('#productID').val(data.pr_product_id);

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