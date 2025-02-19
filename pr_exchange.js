

function previewExchange(element, field, prId) {
    // Enable the submit button
    $('#submitExchange').prop('disabled', false);

    // Add a green border to the element
    $(element).removeClass('badge-warning').removeClass('badge-success').addClass('badge-success');
}

/* function toggleSelect(prID) {
    const select = $('#p_hands' + prID);
    const span = $('#p_hands_span' + prID);
    const button = $('#applyButton');

    if (select.is(':visible')) {
        // Replace select with span
        span.text(select.find("option:selected").text()).removeClass('d-none');
        select.addClass('d-none');
        button.text('Edit');
    } else {
        // Replace span with select
        select.removeClass('d-none').focus();
        span.addClass('d-none');
        button.text('Apply');
    }
} */


// Store updated values in hidden inputs
function updateSelect(element, field, productCode, prId) {
    const value = element.value;
    const hiddenInputsContainer = document.getElementById("hiddenInputsContainer");

    // ตรวจสอบว่า hidden input นี้มีอยู่แล้วหรือไม่
    let hiddenInput = document.getElementById(`hidden_${field}_${prId}`);
    if (!hiddenInput) {
        // ถ้าไม่มี สร้าง input hidden ใหม่
        hiddenInput = document.createElement("input");
        hiddenInput.type = "hidden";
        hiddenInput.id = `hidden_${field}_${prId}`;
        hiddenInput.name = `${field}[${prId}]`;
        hiddenInputsContainer.appendChild(hiddenInput);
    }
    // อัพเดทค่าของ hidden input
    hiddenInput.value = value;

    // เปิดใช้งานปุ่ม submit
    document.getElementById("submitExchange").disabled = false;
}


$('#formEdit').on('submit', function (e) {
    e.preventDefault(); // ป้องกันไม่ให้ฟอร์มทำการ submit แบบปกติ (โหลดหน้าใหม่)

    const formData = new FormData(this);  // เก็บข้อมูลทั้งหมดในฟอร์ม

    $.ajax({
        url: 'pr_submit_exchange.php',  // URL ที่จะส่งข้อมูล
        type: 'POST',  // วิธีการส่งข้อมูล (POST)
        data: formData,  // ข้อมูลที่ส่ง
        contentType: false,  // ไม่ตั้งค่า content-type เพราะกำลังส่งไฟล์
        processData: false,  // ไม่ต้องแปลงข้อมูลก่อนส่ง
        success: function (response) {
            alert('ข้อมูลถูกอัปเดตแล้ว!');
            // รีเฟรชข้อมูล หรือปิดฟอร์มตามต้องการ
        },
        error: function () {
            alert("เกิดข้อผิดพลาดในการอัปเดตข้อมูล");
        }
    });
});


function updateTotal() {
    let total = 0;
    // Loop through all qty-input fields and sum their values
    document.querySelectorAll('.qty-input').forEach(function (input) {
        total += parseFloat(input.value) || 0;
    });

    // Update the total in the total field
    document.querySelector('#totalQty').value = total;
}

function exchangeForm() {
    var selectedIds = [];
    $('input[name="selected_ids[]"]:checked').each(function () {
        selectedIds.push($(this).val());
    });

    if (selectedIds.length > 0) {
        // Fetch details for selected IDs
        $.ajax({
            url: 'ajax_GET/get_pr_exchange_details.php',
            type: 'POST',
            data: {
                ids: selectedIds
            },
            success: function (response) {
                let data = JSON.parse(response); // แปลง JSON response เป็น object

                $('#exchangeBtn').hide(); // ซ่อนปุ่ม Exchange
                $('#pr-table').hide(); // ซ่อนตาราง
                $('#backBtn').show(); // แสดงปุ่ม Back
                $('#editForm').show(); // แสดงฟอร์มแก้ไข

                // Populate the modal body with the HTML response
                $('#dataTable-selected').html(data.html);

            },
            error: function () {
                alert('Error fetching details. Please try again.');
            }
        });
    } else {
        Swal.fire({
            title: 'No Data',
            text: 'No items selected.',
            icon: 'info',
            confirmButtonText: 'OK'
        });
        //alert('No items selected.');
    }
}

function showTable() {
    $('#exchangeBtn').show(); // แสดงปุ่ม Exchange
    $('#pr-table').show(); // แสดงตาราง
    $('#backBtn').hide(); // ซ่อนปุ่ม Back
    $('#editForm').hide();

}

function deletePr() {
    // Get data from the input field
    var prCode = $('#prCodeInput').val();

    // Send AJAX POST request
    Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to Delete?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No, cancel!'
    }).then((result) => {
        if (result.isConfirmed) {
            // User confirmed, proceed with the AJAX request
            $.ajax({
                url: 'pr_update_status.php', // Server-side script URL
                method: 'POST',
                data: {
                    pr_code: prCode,
                    status: '990'
                },
                success: function (response) {
                    // Handle success response
                    console.log('Data inserted successfully');

                    // Display success message using SweetAlert
                    Swal.fire({
                        icon: "success",
                        title: "Success!",
                        showConfirmButton: true
                    }).then(function () {
                        // Call a function after closing the SweetAlert
                        location.reload()
                        // Optionally reload the page or perform additional actions
                        // location.reload();

                    });
                },
                error: function (xhr, status, error) {
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
}

function openExchange(prCode) {
    var element = $('#exchangeID' + prCode);
    var isHidden = element.prop('hidden');

    // สลับสถานะการซ่อนแสดง
    element.prop('hidden', !isHidden);
}


function loadData(store = null, month = formattedMonth, payment = 0, year = currentYear,
    prStatusSelected) {

    console.log('Payment: ' + ($('#paymentStatus').val()));
    console.log('PRStatus: ' + ($('#prStatusSelected').val()));
    // Define the URL for the AJAX request
    var url = "pr_exchange_fetch.php";
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
        success: function (response) {
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
        success: function (response) {

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
        error: function (xhr, status, error) {
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

    var fields = [
        '#color', '#hand', '#qty', '#size'
    ];

    fields.forEach(function (field) {
        var isDisabled = $(field).prop('disabled');
        $(field).prop('disabled', !isDisabled);
    });
}

document.addEventListener('DOMContentLoaded', function () {
    const selectAllCheckbox = document.getElementById('select-all');
    const dataTable = document.getElementById('dataTable');

    selectAllCheckbox.addEventListener('change', function () {
        const checkboxes = dataTable.querySelectorAll('.select-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = selectAllCheckbox.checked;
        });
    });

});


$(document).ready(function () {
    $('#closeModalButton').on('click', function () {
        // Your code to close the modal
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

    $('#years').change(function () {
        var selectedYear = $(this).val();
        var paymentStatus = $('#paymentStatus').val();
        var prStatusSelected = $('#prStatusSelected').val();
        var selectedMonth = $('#months').val();
        var activeStore = getActiveStore(); // Get the active store from the active tab
        loadData(activeStore, selectedMonth, paymentStatus, selectedYear, prStatusSelected);
    });

    $('#prStatusSelected').change(function () {
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
    $('#months').change(function () {
        var selectedYear = $('#years').val();
        var selectedMonth = $(this).val();
        var paymentStatusSelected = $('#paymentStatus').val();
        var prStatus = $('#prStatusSelected').val();
        var activeStore = getActiveStore(); // Get the active store from the active tab
        loadData(activeStore, selectedMonth, paymentStatus, selectedYear, prStatusSelected);
    });

    // Update data when the payment status is changed
    $('#paymentStatus').change(function () {
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
    $('#productTab').click(function (e) {
        e.preventDefault();
        handleTabClick('#productTab');
    });

    $('#samtTab').click(function (e) {
        e.preventDefault();
        handleTabClick('#samtTab', 'samt');
    });

    $('#sakabaTab').click(function (e) {
        e.preventDefault();
        handleTabClick('#sakabaTab', 'sakaba');
    });
    ///////////////////////////////////////

});


$(document).ready(function () {

    function handleSelectedUpdate(action, status, successMessage, errorMessage) {
        var selectedIds = [];
        $('input[name="selected_ids[]"]:checked').each(function () {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length > 0) {
            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to update the status for ${selectedIds.length} selected items.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, update it!',
                cancelButtonText: 'No, cancel!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'pr_update_status.php',
                        type: 'POST',
                        data: {
                            ids: selectedIds,
                            status: status
                        },
                        success: function (response) {
                            Swal.fire({
                                title: 'Success',
                                text: successMessage,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function () {
                            Swal.fire({
                                title: 'Error',
                                text: errorMessage,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        } else {
            Swal.fire({
                title: 'No Data',
                text: 'No items selected.',
                icon: 'info',
                confirmButtonText: 'OK'
            });
        }
    }

    $('#purchaseSelected').click(function () {
        handleSelectedUpdate(
            'purchaseSelected',
            1,
            'Purchase request status updated successfully.',
            'Error updating purchase request status. Please try again.'
        );
    });

    $('#deliveredSelected').click(function () {
        handleSelectedUpdate(
            'deliveredSelected',
            2,
            'Delivered request status updated successfully.',
            'Error updating delivery request status. Please try again.'
        );
    });

    $('#stockinSelected').click(function () {
        handleSelectedUpdate(
            'stockinSelected',
            3,
            'StockIn request status updated successfully.',
            'Error updating StockIn request status. Please try again.'
        );
    });


    $('#delivered').click(function () {
        // Get data from the input field
        var prID = $('#prID').val();

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
                        pr_id: prID,
                        status: '2'
                    },
                    success: function (response) {
                        // Handle success response
                        console.log('Data inserted successfully');

                        // Display success message using SweetAlert
                        Swal.fire({
                            icon: "success",
                            title: "Success!",
                            showConfirmButton: true
                        }).then(function () {
                            // Call a function after closing the SweetAlert
                            openEditModal(prID);
                            $('#closeModalButton').prop('disabled', false)
                            // Optionally reload the page or perform additional actions
                            // location.reload();

                        });
                    },
                    error: function (xhr, status, error) {
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

    $('#stockin').click(function () {
        // Get data from the input field
        //var prCode = $('#prCodeInput').val();
        var prID = $('#prID').val();

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
                        pr_id: prID,
                        status: '3'
                    },
                    success: function (response) {
                        // Handle success response
                        console.log('Data inserted successfully');

                        // Display success message using SweetAlert
                        Swal.fire({
                            icon: "success",
                            title: "Success!",
                            showConfirmButton: true
                        }).then(function () {
                            window.location.href = 'pr_history.php';
                            // Call a function after closing the SweetAlert
                            //openEditModal(prCode);
                            // Optionally reload the page or perform additional actions
                            // location.reload();
                        });
                    },
                    error: function (xhr, status, error) {
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
    $('#submitFormExchange').click(function () {

        // Get data from both forms
        var prCode = $('#prCodeInput').val(); // Example: Fetch PR Code from first form
        var pdCode = $('#productCode').val(); // Example: Fetch PR Code from first form
        var pdName = $('#productName').val();
        var size = $('#size').val();
        var color = $('#color').val();
        var hand = $('#hand').val();
        var qty = $('#qty').val();
        var prMgcode = $('#mgCode').val();
        var prDate = $('#dateAddedInput').val();
        var prID = $('#prID').val();
        var prStatusID = $('#prStatusID').val();


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
                pdName: pdName,
                prMgcode,
                prDate,
                prID,
                prStatusID
            },
            success: function (response) {
                // Handle success response (if needed)
                console.log('Data inserted successfully');
                var jsonResponse = JSON.parse(response);

                // Check if the response indicates success or error
                if (jsonResponse.hasOwnProperty('success')) {
                    // Handle success case
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: jsonResponse.success,
                        showConfirmButton: true
                    }).then(function () {
                        // Additional actions if needed
                        console.log(response);
                        openEditModal(prID);

                        $('#alertExchange').removeClass('alert-danger').addClass(
                            'alert-success').html(
                                'Successfully Exchange please <a id="closeModalButton" data-bs-dismiss="modal" style="cursor: pointer;"><i class="fa-solid fa-rotate"></i> Refresh</a>'
                            ).prop('hidden', false);

                        // Attach a click event to the refresh button
                        $('#closeModalButton').click(function () {
                            location.reload(); // Refresh the page
                        });

                        $('#closeModalButton').prop('disabled', false);
                    });
                } else if (jsonResponse.hasOwnProperty('error')) {
                    // Handle error case
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: jsonResponse.error,
                        showConfirmButton: true
                    }).then(function () {
                        $('#alertExchange').removeClass('alert-success').addClass(
                            'alert-danger').prop('hidden', false).html(
                                ' Product not found, please check your fill or <a href="register.php">Register Here</a>'
                            ); // Show the alert
                        return; // Exit the function
                    });
                }
                //alert(response);
                // Optionally, clear form fields or reset form state
            },
            error: function (xhr, status, error) {
                // Handle error response
                console.error('Error inserting data:', error);
                alert('Error inserting data');
            }
        });
    });
});

function openEditModal(prID) {
    console.log(prID); // Log prCode to verify it's received correctly
    // Clear previous content and show loading state
    //$('#modal-body-content').html('<p>Loading...</p>');

    // AJAX request to fetch data based on pr_code
    $.ajax({
        url: 'ajax_POST/pr_modal_exchange.php',
        method: 'POST',
        dataType: 'json',
        data: {
            pr_id: prID
        },
        success: function (data) {
            var prStatus = '';
            $('#editModalLabel').html('<i class="fa-solid fa-paste"></i> PR Management: ' + data.pr_code);

            /*   if (data.pr_memo === 'Exchange' && data.pr_status == '2') {
                  prStatus = 'Delivered';
                  $('#submitFormExchange').prop('disabled', true);
                  $('#delivered').prop('disabled', true);
                  $('#stockin').prop('disabled', false);
              } else if (data.pr_memo === 'Exchange' && (data.pr_status === null || data.pr_status === '')) {
                  prStatus = 'Pending';
                  $('#submitFormExchange').prop('disabled', true);
                  $('#delivered').prop('disabled', true);
                  $('#stockin').prop('disabled', true); */
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
                $('#prStatusID').val(data.pr_status);
                $('#productID').val(data.pr_product_id);
                $('#prID').val(data.pr_id);

            } else {
                $('#modal-body-content').html('<p>No data found</p>');
            }
        },
        error: function (xhr, status, error) {
            console.error('Failed to fetch data:', status, error);
            $('#modal-body-content').html('<p>Error fetching data</p>');
        },
        complete: function () {
            $('#editModal').modal('show');
        }

    });
}
