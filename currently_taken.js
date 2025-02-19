$(document).ready(function () {
    // เมื่อคลิกที่ checkbox "เลือกทั้งหมด"
    $('#checkAll').on('change', function () {
        $('input[name="selected_ids[]"]').prop('checked', $(this).prop('checked'));
    });

    // ตรวจสอบสถานะของ checkbox ย่อยเพื่อปรับ "เลือกทั้งหมด"
    $('input[name="selected_ids[]"]').on('change', function () {
        $('#checkAll').prop(
            'checked',
            $('input[name="selected_ids[]"]').length === $('input[name="selected_ids[]"]:checked').length
        );
    });

    $('#completedBtn').click(function () {
        // เก็บค่า ID ที่เลือก
        var selectedIds = [];
        $('input[name="selected_ids[]"]:checked').each(function () {
            selectedIds.push($(this).val());
        });

        // ตรวจสอบค่า selectedIds
        console.log('Selected IDs:', selectedIds);

        // ตรวจสอบว่าเลือก checkbox หรือไม่
        if (selectedIds.length === 0) {
            Swal.fire({
                title: 'Warning!',
                text: 'Please select at least one item.',
                icon: 'warning',
                confirmButtonColor: '#17a2b8'
            });
            return;
        }

        // ตรวจสอบรูปแบบของ mg_code
        var isValid = selectedIds.every(function (id) {
            const result = /^[a-zA-Z0-9_-]+$/.test(id);
            console.log(`Checking ID "${id}": ${result}`); // Log การตรวจสอบแต่ละ ID
            return result;
        });

        if (!isValid) {
            console.log('Invalid IDs found:', selectedIds.filter(id => !/^[a-zA-Z0-9_-]+$/.test(id)));
            Swal.fire({
                title: 'Error!',
                text: 'Some selected items have invalid codes. Please check again.',
                icon: 'error',
                confirmButtonColor: '#dc3545'
            });
            return;
        }

        // แสดง Loading
        Swal.fire({
            title: 'Processing...',
            html: 'Please wait a moment',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // ส่งข้อมูลไปยังเซิร์ฟเวอร์
        $.ajax({
            url: 'ajax_POST/completed_current.php',
            type: 'POST',
            data: {
                ids: selectedIds,
            },
            dataType: 'json', // Expect JSON response
            success: function (response) {
                console.log('Server Response:', response); // ตรวจสอบ response จากเซิร์ฟเวอร์
                try {
                    // ถ้า response เป็น string ให้แปลงเป็น JSON
                    if (typeof response === 'string') {
                        response = JSON.parse(response);
                    }

                    if (response.status === 'success') {
                        console.log('Operation Success:', response.message); // Log ข้อความสำเร็จ
                        Swal.fire({
                            title: 'Success',
                            text: response.message,
                            icon: 'success',
                        }).then(() => {
                            location.reload(); // Refresh หน้า
                        });
                    } else {
                        console.log('Operation Error:', response.message); // Log ข้อความข้อผิดพลาด
                        Swal.fire({
                            title: 'Error!',
                            text: response.message || 'An error occurred while processing your request.',
                            icon: 'error',
                            confirmButtonColor: '#dc3545'
                        });
                    }
                } catch (e) {
                    console.error('Error parsing response:', e); // Log ข้อผิดพลาด
                    Swal.fire({
                        title: 'Error!',
                        text: 'Invalid response from server.',
                        icon: 'error',
                        confirmButtonColor: '#dc3545'
                    });
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error:', { status, error }); // Log ข้อผิดพลาดจาก AJAX
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to communicate with the server. Please try again.',
                    icon: 'error',
                    confirmButtonColor: '#dc3545'
                });
            }
        });
    });

    /* start preview */
    // Other code...
    $('#previewReturnedSelectedBtn').click(function () {
        $('#makePR').prop('hidden', true);
        $('#returnButton').prop('hidden', false);
        // Collect selected IDs
        var selectedIds = [];
        $('input[name="selected_ids[]"]:checked').each(function () {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length > 0) {
            // Fetch details for selected IDs
            $.ajax({
                url: 'currently_selectd_details.php',
                type: 'POST',
                data: {
                    ids: selectedIds
                },
                success: function (response) {
                    $('#previewModalHeader').removeClass('text-info').addClass(
                        'text-warning');
                    // Populate the modal body with the response
                    $('#previewModalBody').html(response);

                    $('#previewModalLabel').html('Preview Selected Items (Returned)');

                    // Show the modal
                    var modal = new bootstrap.Modal(document.getElementById(
                        'previewModal'));
                    modal.show();
                },
                error: function () {
                    alert('Error fetching details. Please try again.');
                }
            });
        } else {
            Swal.fire({
                title: 'Warning',
                text: 'Please select the items to return',
                icon: 'warning',
                confirmButtonText: '<i class="fa-solid fa-check"></i> OK',
                confirmButtonColor: 'gray'
            });
            return;
        }
    });

    $('#previewPRSelectedBtn').click(function () {
        $('#makePR').prop('hidden', false);
        $('#returnButton').prop('hidden', true);
        // Collect selected IDs
        var selectedIds = [];
        $('input[name="selected_ids[]"]:checked').each(function () {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length > 0) {
            // Fetch details for selected IDs
            $.ajax({
                url: 'currently_selectd_details.php',
                type: 'POST',
                data: {
                    ids: selectedIds
                },
                success: function (response) {
                    $('#previewModalHeader').removeClass('text-warning').addClass(
                        'text-info');
                    // Populate the modal body with the response
                    $('#previewModalBody').html(response);
                    $('#previewModalLabel').html('Preview Selected Items (PR Create)');

                    // Show the modal
                    var modal = new bootstrap.Modal(document.getElementById(
                        'previewModal'));
                    modal.show();
                },
                error: function () {
                    /*  Swal.fire({
                         icon: 'error',
                         title: 'No items selected',
                         text: 'Please select at least one item to preview.'
                     }); */
                    alert('Error fetching details. Please try again.');
                }
            });
        } else {
            Swal.fire({
                title: 'Warning',
                text: 'Please select the items to create PR',
                icon: 'info',
                confirmButtonText: '<i class="fa-solid fa-check"></i> OK',
                confirmButtonColor: 'gray'
            });
            return;

            //alert('No items selected.');
            /* swal({
                title: "No items selected.",
                icon: "error"
            }); */
            //alert('No items selected.');
        }
    });
    /* END preview */

    $('#makePR').click(function () {
        var memo = $('#memo').val();
        var newDate = $('#newDate').val();

        // Collect selected IDs
        var selectedIds = [];
        $('input[name="selected_ids[]"]:checked').each(function () {
            selectedIds.push($(this).val());
        });

        // Validate if any checkboxes are selected
        if (selectedIds.length === 0) {
            Swal.fire({
                title: 'Warning!',
                text: 'Please select at least one item.',
                icon: 'warning',
                confirmButtonColor: '#17a2b8'
            });
            return;
        }

        // แสดง Loading
        Swal.fire({
            title: 'Processing...',
            html: 'Please wait a moment',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Send an AJAX request to insert data into the stockin table
        $.ajax({
            url: 'ajax_POST/currently_pr.php',
            type: 'POST',
            data: {
                ids: selectedIds,
                memo: memo,
                prDate: newDate
            },
            dataType: 'json',  // Expect JSON response
            success: function (response) {
                try {
                    // If response is string, parse it
                    if (typeof response === 'string') {
                        response = JSON.parse(response);
                    }

                    if (response.status === 'success') {
                        Swal.fire({
                            title: 'PR Create!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'Go to PR Page',
                            confirmButtonColor: '#17a2b8',
                            showCancelButton: true,
                            cancelButtonText: 'Stay on this page',
                            timer: 5000,
                            timerProgressBar: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'pr_management.php';
                            } else {
                                location.reload();
                            }
                        });
                    } else {
                        // Handle error response
                        Swal.fire({
                            title: 'Error!',
                            text: response.message || 'An error occurred while processing your request.',
                            icon: 'error',
                            confirmButtonColor: '#dc3545'
                        });
                    }
                } catch (e) {
                    console.error('Error parsing response:', e);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Invalid response from server.',
                        icon: 'error',
                        confirmButtonColor: '#dc3545'
                    });
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error:', status, error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to communicate with the server. Please try again.',
                    icon: 'error',
                    confirmButtonColor: '#dc3545'
                });
            }
        });
    });

    $('#returnButton').click(function () {

        // ตรวจสอบการเลือกรายการ
        const selectedIds = $('input[name="selected_ids[]"]:checked').map(function () {
            return $(this).val();
        }).get();


        const memo = $('#memo').val();
        const newDate = $('#newDate').val();

        // ตรวจสอบวันที่
        if (!newDate) {
            Swal.fire({
                title: 'Warning',
                text: 'Please specify the return date',
                icon: 'warning',
                confirmButtonText: '<i class="fa-solid fa-check"></i> OK',
                confirmButtonColor: 'gray'
            });
            return;
        }

        // แสดง Loading
        Swal.fire({
            title: 'Processing...',
            html: 'Please wait a moment',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // ส่ง AJAX request
        $.ajax({
            url: 'ajax_POST/currently_return.php',
            type: 'POST',
            data: {
                ids: selectedIds,
                memo: memo,
                returnDate: newDate
            },
            dataType: 'json', // ระบุว่าต้องการรับข้อมูลเป็น JSON
            success: function (response) {
                // ปิด Loading
                Swal.close();

                if (response.status === 'success') {
                    Swal.fire({
                        title: 'Success',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: '<i class="fa-solid fa-arrow-right-to-bracket"></i> Stock In History',
                        confirmButtonColor: "#ffc107", // สี warning
                        showCancelButton: true,
                        cancelButtonText: 'Stay on this page',
                        timer: 5000, // ตั้งเวลา 2 วินาที
                        timerProgressBar: true // แสดงแถบความคืบหน้า        
                    }).then((result) => {
                        if (!result.isConfirmed) {
                            location.reload();
                        } else {
                            window.location.href = 'stock_in_his.php';
                        }
                    });
                } else {
                    // กรณีเกิด error จาก server
                    Swal.fire({
                        title: 'เกิดข้อผิดพลาด',
                        text: response.message,
                        icon: 'error',
                        confirmButtonText: '<i class="fa-solid fa-check"></i> ตกลง',
                        confirmButtonColor: 'gray'
                    });
                }
            },
            error: function (xhr, status, error) {
                // ปิด Loading
                Swal.close();

                // กรณีเกิด error จาก AJAX
                Swal.fire({
                    title: 'เกิดข้อผิดพลาด',
                    text: 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้ กรุณาลองใหม่อีกครั้ง',
                    icon: 'error',
                    confirmButtonText: '<i class="fa-solid fa-check"></i> ตกลง',
                    confirmButtonColor: 'gray'
                });

                // Log error สำหรับ debug
                console.error('AJAX Error:', status, error);
                if (xhr.responseText) {
                    console.error('Server Response:', xhr.responseText);
                }
            }
        });
    });



    /* ---------------------------------------------------------------------------------- */


    // Load all data when the page loads
    loadData();
    // Function to activate the sale tab
    function activateSaleTab() {
        // Remove 'active' class from all tabs
        $('.tab').removeClass('active');

        // Add 'active' class to the clicked tab
        $('#saleTab').addClass('active');

        // Load the sale data
        loadData('sale');
    }
    // Event listener for all button
    $('#allTab').click(function () {
        // Remove 'active' class from all tabs
        $('.tab').removeClass('active');

        // Add 'active' class to the clicked tab
        $(this).addClass('active');

        loadData();
    });

    // Event listener for part sale button
    $('#saleTab').click(function () {
        // Remove 'active' class from all tabs
        $('.tab').removeClass('active');

        // Add 'active' class to the clicked tab
        $(this).addClass('active');

        loadData('sale');
    });

    // Check if the URL contains '#saleTab'
    if (window.location.hash === '#saleTab') {
        activateSaleTab();
    }
});

/* ------------------------- */

function updatePayment(mgCode, paymentStatus) {
    // Toggle payment status: if current status is 1, set to 2, else set to 1
    var new_payment_status = (paymentStatus == 1) ? 2 : 1;

    // AJAX request
    $.ajax({
        type: "POST",
        url: "ajax_POST/update_payment.php",
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
                // ตรวจสอบว่าแท็บ #saleTab กำลังเปิดใช้งานอยู่
                checkCurrentTab()
            });

            // Optionally, update UI or handle success response
            console.log("Payment status updated successfully.");
        },
        error: function (xhr, status, error) {
            console.error("Error updating payment status: " + error);
            // Handle error if needed
        }
    });
}

function updateDelivery(mgCode, deliveryStatus) {
    // Toggle payment status: if current status is 1, set to 2, else set to 1
    var new_deliver_status = (deliveryStatus == 1) ? 2 : 1;

    // AJAX request
    $.ajax({
        type: "POST",
        url: "ajax_POST/update_deliver.php",
        data: {
            o_mg_code: mgCode,
            new_deliver_status: new_deliver_status
        },
        success: function (response) {

            // Show success notification
            Swal.fire({
                icon: "success",
                title: "Success!",
                text: "Delivery updated for " + mgCode,
                showConfirmButton: true
            }).then(() => {
                // ตรวจสอบว่าแท็บ #saleTab กำลังเปิดใช้งานอยู่
                checkCurrentTab(); // Reload the page after the notification is closed
            });
            console.log(response);
            // Optionally, update UI or handle success response
            console.log("Delivery status updated successfully.");
        },
        error: function (xhr, status, error) {
            console.error("Error updating payment status: " + error);
            // Handle error if needed
        }
    });
}


function checkCurrentTab() {
    // ตรวจสอบว่าแท็บ #saleTab กำลังเปิดใช้งานอยู่
    if ($('#saleTab').hasClass('active')) {
        loadData('sale'); // Reload the page after the notification is closed
    } else {
        loadData();
    }
}