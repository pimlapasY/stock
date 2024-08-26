<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Currently Taken</title>
</head>
<!-- <style>
td {
    padding: 15px;
    /* ปรับค่าตามที่ต้องการ */
}

th {
    font-size: 15px;
    height: 70px;
    background-color: #E5F9E5;
}
</style> -->
<style>
.modal-body {
    overflow-y: auto;
}
</style>

<body>
    <div class="d-flex flex-wrap">
        <?php include('navbar.php') ?>
        <div class="container pt-5 col-10">
            <h1><i class="fa-solid fa-database fa-xl"></i> Currently Taken</h1><br>
            <div class="d-flex justify-content-between">
                <div class="btn-group mb-2">
                    <input type="radio" class="btn-check" name="options" id="allBtn" autocomplete="off" checked />
                    <label class="btn btn-secondary" for="allBtn">
                        <i class="fa-solid fa-bars"></i> All
                    </label>

                    <input type="radio" class="btn-check" name="options" id="partSaleBtn" autocomplete="off" />
                    <label class="btn btn-secondary" for="partSaleBtn">
                        <i class="fa-solid fa-file-invoice-dollar"></i> Part Sale
                    </label>
                </div>

                <div class="mb-2">
                    <a href="#" id="previewPRSelectedBtn" class="btn btn-outline-info rounded-8"><i
                            class="fa-solid fa-file-lines"></i> PR
                        create</a>
                    <a href="#" id="previewReturnedSelectedBtn" class="btn btn-outline-warning rounded-8"><i
                            class="fa-solid fa-right-left"></i> Returned</a>
                    <a href="his_all.php" class="btn btn-success rounded-8"><i
                            class="fa-solid fa-file-circle-check"></i>
                        Completed</a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mx-auto table-sm">
                    <thead class="text-center table-secondary" style="text-transform: uppercase;">
                        <th><?php echo $select; ?></th>
                        <th>#</th>
                        <th><?php echo $store; ?></th>
                        <th><?php echo $code; ?></th>
                        <!-- <th>Stock out</th> -->
                        <th><?php echo $product; ?></th>
                        <!-- <th>Receipt date</th> -->
                        <!-- <th>Supplier</th> -->
                        <th><?php echo $size; ?></th>
                        <th><?php echo $color; ?></th>
                        <th><?php echo $hand; ?></th>
                        <th><?php echo $qty; ?></th>
                        <th><?php echo $soldDate; ?></th>
                        <th><?php echo $customer; ?></th>
                        <th><?php echo $paidBy; ?></th>
                        <th><?php echo $payment; ?></th>
                        <th><?php echo $delivery; ?></th>
                        <th><?php echo $prPo; ?></th>
                        <!-- <th>PO status</th> -->
                        <th><?php echo $update; ?></th>
                        <th><?php echo $memo; ?></th>
                    </thead>
                    <tbody id="dataTable" class="table-group-divider table-divider-color">
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <!------------------------ Preview Modal -------------------------------------------------------------------->
    <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
        <div class="modal-dialog mx-auto modal-xl">
            <div class="modal-content">
                <div class="modal-header text-center" id="previewModalHeader">
                    <h5 class="modal-title" id="previewModalLabel">Preview Selected Items</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <?php
                    // กำหนดวันที่ปัจจุบันในรูปแบบ 'Y-m-d'
                    $currentDate = date('Y-m-d');
                    ?>
                <div class="modal-body m-3">
                    <label for="newDate">Date: </label>
                    <input id="newDate" type="date" class="form-control w-50 ms-1"
                        value="<?php echo $currentDate; ?>"><br>
                    <label for="memo">Memo: </label>
                    <textarea id="memo" class="form-control w-50 ms-1" placeholder="memo"></textarea>
                </div>
                <div class="modal-body m-3" id="previewModalBody">
                    <!-- Details will be populated here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info" id="makePR" hidden>PR CREATE</button>
                    <button type="button" class="btn btn-warning" id="returnButton" hidden>RETURN</button>
                    <div class="spinner-border" role="status" hidden>
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ------------------------------------------------------------------------------- -->
    <!-- Bootstrap Modal -->
    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content">
                <div class="modal-header" style="background-color: wheat;">
                    <h5 class="modal-title" id="updateModalLabel"><i class="fa-solid fa-pen-to-square fa-lg"></i>
                        Payment and
                        Delivery</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="update-form">
                    <div class="modal-body">
                        <div class="mb-4 ms-2 me-2">
                            <input type="hidden" id="product-id" name="product-id">
                            <label for="mg-code" class="form-label"><i class="fa-solid fa-barcode"></i> MG CODE:</label>
                            <input class="form-control badge-warning" type="text" id="mg-code" name="mg-code">
                        </div>
                        <!-- Hidden field to store product ID -->
                        <div class="mb-4 ms-2 me-2">
                            <label for="o_payment" class="form-label"><i class="fa-solid fa-hand-holding-dollar"></i>
                                Payment Status:</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="o_payment" id="paymentSuccess"
                                    value="1">
                                <label class="form-check-label" for="paymentSuccess">Payment Successful</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="o_payment" id="paymentPending"
                                    value="2">
                                <label class="form-check-label" for="paymentPending">Payment Pending</label>
                            </div>
                        </div>
                        <!-- Add more options as needed -->
                        <div class="mb-1 ms-2 me-2">
                            <label for="o_delivery" class="form-label"><i class="fa-solid fa-cart-flatbed"></i> Delivery
                                Status:</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="o_delivery" id="deliveryDelivered"
                                    value="1">
                                <label class="form-check-label" for="deliveryDelivered">Successfully Delivered</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="o_delivery" id="deliveryNotDelivered"
                                    value="2">
                                <label class="form-check-label" for="deliveryNotDelivered">Delivery Pending</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" onclick="updateData()">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- ------------------------------------------------------------------------------------------------------------------------------- -->
    <script>
    $(document).ready(function() {
        // Other code...
        $('#previewReturnedSelectedBtn').click(function() {
            $('#makePR').prop('hidden', true);
            $('#returnButton').prop('hidden', false);
            // Collect selected IDs
            var selectedIds = [];
            $('input[name="selected_ids[]"]:checked').each(function() {
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
                    success: function(response) {
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
                    error: function() {
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
        });


        $('#makePR').click(function() {
            // Collect selected IDs
            var selectedIds = [];
            $('input[name="selected_ids[]"]:checked').each(function() {
                selectedIds.push($(this).val());
            });

            var memo = $('#memo').val();
            var newDate = $('#newDate').val();

            $('.spinner-border').removeAttr('hidden');
            $('#returnButton').hide();
            $('#makePR').hide();

            // Send an AJAX request to insert data into the stockin table
            $.ajax({
                url: 'currently_pr.php', // Update the URL to your PHP script
                type: 'POST',
                data: {
                    ids: selectedIds,
                    memo: memo,
                    prDate: newDate
                }, // Send the selectedIds array with the key 'ids'
                success: function(response) {
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
                        $('.spinner-border').attr('hidden');

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
                error: function() {
                    // Handle the error
                    alert('Error inserting data into stockin table. Please try again.');
                }
            });
        });

        $('#returnButton').click(function() {
            // Collect selected IDs
            var selectedIds = [];
            $('input[name="selected_ids[]"]:checked').each(function() {
                selectedIds.push($(this).val());
            });

            var memo = $('#memo').val();
            var newDate = $('#newDate').val();


            // Send an AJAX request to insert data into the stockin table
            $.ajax({
                url: 'currently_return.php', // Update the URL to your PHP script
                type: 'POST',
                data: {
                    ids: selectedIds,
                    memo: memo,
                    returnDate: newDate
                }, // Send the selectedIds array with the key 'ids'
                success: function(response) {
                    Swal.fire({
                        title: 'Return Updated!',
                        text: 'Stock has been updated successfully.',
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonText: '<i class="fa-solid fa-check"></i> OK',
                        cancelButtonText: '<i class="fa-solid fa-arrow-right-to-bracket"></i> History StockIn',
                        confirmButtonColor: 'gray', // Custom color for confirm button
                        cancelButtonColor: 'orange' // Custom color for cancel button
                    }).then((result) => {
                        // If user clicks "Move to Other Page" button
                        if (!result.isConfirmed) {
                            // Redirect to other page
                            window.location.href = 'stock_in_his.php';
                        } else {
                            location.reload();
                        }
                    });
                    // Handle the success response
                    console.log(response); // Log the response
                    // You can add further actions here if needed, such as displaying a success message or updating the UI
                },
                error: function() {
                    // Handle the error
                    alert('Error inserting data into stockin table. Please try again.');
                }
            });
        });




        $('#previewPRSelectedBtn').click(function() {
            $('#makePR').prop('hidden', false);
            $('#returnButton').prop('hidden', true);
            // Collect selected IDs
            var selectedIds = [];
            $('input[name="selected_ids[]"]:checked').each(function() {
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
                    success: function(response) {
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
                    error: function() {
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
                    title: 'No Data',
                    text: 'No items selected.',
                    icon: 'info',
                    confirmButtonText: 'OK'
                });
                //alert('No items selected.');
                /* swal({
                    title: "No items selected.",
                    icon: "error"
                }); */
                //alert('No items selected.');
            }
        });
    });
    </script>
    <!------------------------ Preview Modal -------------------------------------------------------------------->
</body>

</html>

<script>
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
</script>


<script>
$(document).ready(function() {
    // Function to load data based on button clicked
    function loadData(reasons = null) {
        // Define the URL for the AJAX request
        var url = "currently_fetch_data.php";
        // Define the data to be sent
        var data = {
            reasons: reasons
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

    // Load all data when the page loads
    loadData();

    // Event listener for all button
    $('#allBtn').click(function() {
        loadData();
    });

    // Event listener for part sale button
    $('#partSaleBtn').click(function() {
        loadData('sale');
    });
});
</script>