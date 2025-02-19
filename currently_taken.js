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
            url: 'ajax_POST/currently_pr.php', // Update the URL to your PHP script
            type: 'POST',
            data: {
                ids: selectedIds,
                memo: memo,
                prDate: newDate
            }, // Send the selectedIds array with the key 'ids'
            success: function (response) {
                Swal.fire({
                    title: 'PR Create!',
                    text: 'PR has been create successfully.',
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonText: 'OK',
                    cancelButtonText: 'Go to PR page...',
                    confirmButtonColor: 'gray', // Custom color for confirm button
                    cancelButtonColor: '#8AD4D9' // Custom color for cancel button
                }).then((result) => {
                    // If user clicks "Move to Other Page" button
                    if (!result.isConfirmed) {
                        // Redirect to other page
                        window.location.href = 'pr_management.php';
                    } else {
                        location.reload();
                    }
                });
                // Handle the success response
                console.log(response); // Log the response
                // You can add further actions here if needed, such as displaying a success message or updating the UI
            },
            error: function () {
                // Handle the error
                alert('Error inserting data into stockin table. Please try again.');
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
                        showCancelButton: true,
                        confirmButtonText: '<i class="fa-solid fa-check"></i> ตกลง',
                        cancelButtonText: '<i class="fa-solid fa-arrow-right-to-bracket"></i> ประวัติการรับเข้า',
                        confirmButtonColor: 'gray',
                        cancelButtonColor: 'orange'
                    }).then((result) => {
                        if (!result.isConfirmed) {
                            window.location.href = 'stock_in_his.php';
                        } else {
                            location.reload();
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

    function showModal(productId, mgCode, payment, delivery) {
        const modal = new bootstrap.Modal(document.getElementById('updateModal'), {
            keyboard: false
        });

        console.log('ID:', productId);
        console.log('Mg Code:', mgCode);

        // Set the product ID in the modal form
        document.getElementById('product-id').value = productId;
        document.getElementById('mg-code').value = mgCode;

        if (payment === null) {
            payment === '2';
        }
        if (delivery == null) {
            delivery === '2';
        }

        console.log('Payment:', payment);
        console.log('Delivery:', delivery);
        // Set the checked state of the radio buttons
        document.getElementById('paymentSuccess').checked = (payment === '1');
        document.getElementById('paymentPending').checked = (payment === '2');
        document.getElementById('deliveryDelivered').checked = (delivery === '1');
        document.getElementById('deliveryNotDelivered').checked = (delivery === '2');

        // Show the modal
        modal.show();
    }


    function updateData() {
        // Get form values
        const productId = document.getElementById('product-id').value;
        let o_payment = 2;
        let o_delivery = 2;

        // Check if payment is checked
        const selectedPayment = document.querySelector('input[name="o_payment"]:checked');
        if (selectedPayment) {
            o_payment = selectedPayment.value;
        }

        // Check if delivery is checked
        const selectedDelivery = document.querySelector('input[name="o_delivery"]:checked');
        if (selectedDelivery) {
            o_delivery = selectedDelivery.value;
        }

        // Send POST request to update data
        fetch('currently_update.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: productId,
                o_payment: o_payment,
                o_delivery: o_delivery
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Display success message using SweetAlert
                    Swal.fire('Updated!', 'The payment and delivery have been updated.', 'success')
                        .then(() => {
                            // Reload the page to reflect the changes
                            location.reload();
                        });
                } else {
                    // Display error message using SweetAlert
                    Swal.fire('Error!', 'There was an error updating the data.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Display error message using SweetAlert
                Swal.fire('Error!', 'There was an error updating the data.', 'error');
            });
    }




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