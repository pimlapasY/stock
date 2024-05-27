<?php include('navbar.php') ?>
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

<body>
    <!-- Bootstrap Modal -->
    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background-color: wheat;">
                    <h5 class="modal-title" id="updateModalLabel"><i class="fa-solid fa-pen-to-square fa-lg"></i>
                        Payment and
                        Delivery</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="update-form">
                    <div class="modal-body">
                        <div class="mb-3">
                            <input type="hidden" id="product-id" name="product-id">
                            <label for="mg-code" class="form-label"><i class="fa-solid fa-barcode"></i> MG CODE:</label>
                            <input class="form-control" type="text" id="mg-code" name="mg-code" disabled>
                        </div>
                        <!-- Hidden field to store product ID -->
                        <div class="mb-3">
                            <label for="o_payment" class="form-label"><i class="fa-solid fa-hand-holding-dollar"></i>
                                Payment:</label>
                            <select id="o_payment" name="o_payment" class="form-select">
                                <option></option>
                                <option value="1">Success</option>
                                <option value="2">Pending</option>
                                <!-- Add more options as needed -->
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="o_delivery" class="form-label"><i class="fa-solid fa-cart-flatbed"></i>
                                Delivery:</label>
                            <select id="o_delivery" name="o_delivery" class="form-select">
                                <option></option>
                                <option value="1">Delivered</option>
                                <option value="2">Not Delivered</option>
                                <!-- Add more options as needed -->
                            </select>
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


    <div class="container-fluid">
        <div class="mb-2">
            <h1><i class="fa-solid fa-database fa-xl"></i> Currently Taken</h1><br>
            <a href="#" class="btn btn-success" id="allBtn">All</a>
            <a href="#" class="btn btn-warning" id="partSaleBtn">Part Sale</a>
        </div>
        <table class="table table-hover mx-auto">
            <thead class="text-center table-secondary" style="text-transform: uppercase;">
                <th>#</th>
                <th>store</th>
                <th>CODE</th>
                <!-- <th>Stock out</th> -->
                <th>product</th>
                <!-- <th>Receipt date</th> -->
                <!-- <th>Supplier</th> -->
                <th>size</th>
                <th>color</th>
                <th>hand</th>
                <th>qty</th>
                <th>Sold date</th>
                <th>customer</th>
                <th>paid by</th>
                <th>payment</th>
                <th>Delivery</th>
                <th>PR/PO</th>
                <!-- <th>PO status</th> -->
                <th>Memo</th>
                <th>Update</th>
                <th>select</th>
            </thead>
            <tbody id="dataTable">


            </tbody>
        </table>
    </div>

</body>

</html>

<script>
function showModal(productId, mgCode) {
    const modal = new bootstrap.Modal(document.getElementById('updateModal'), {
        keyboard: false
    });
    // Set the product ID in the modal form
    document.getElementById('product-id').value = productId;
    document.getElementById('mg-code').value = mgCode;

    // Show the modal
    modal.show();
}

function updateData() {
    // Get form values
    const productId = document.getElementById('product-id').value;
    const o_payment = document.getElementById('o_payment').value || null;
    const o_delivery = document.getElementById('o_delivery').value || null;

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
    $('#allBtn').click(function(e) {
        e.preventDefault();
        loadData();
    });

    // Event listener for part sale button
    $('#partSaleBtn').click(function(e) {
        e.preventDefault();
        loadData('sale');
    });
});
</script>