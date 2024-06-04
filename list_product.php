<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product_list</title>
</head>
<?php include('connect.php'); ?>
<style>
.modal-header {
    background-color: #cfe2f3;
    /* Optionally change the text color */
}
</style>


<body>
    <div class="d-flex justify-content-end m-3">
        <a href="register.php" class="btn btn-success"><i class="fa-solid fa-plus"></i> NEW</a>&nbsp;
        <button type="button" class="btn btn-primary" onclick="openPreviewModal()"><i class="fa-solid fa-inbox"></i>
            Stock in</button>&nbsp;
        <button class="btn btn-info"><i class="fa-solid fa-clipboard-list"></i> add PR</button>
    </div>

    <table class="table table-bordered table-hover" style="width: 100%;">
        <!-- Table content -->
        <thead class="table-primary text-center">
            <tr style="vertical-align: middle;">
                <th rowspan="2">No</th>
                <th rowspan="2">Product ID</th>
                <th rowspan="2">Collection</th>
                <th rowspan="2">Name</th>
                <th rowspan="2">Hands</th>
                <th rowspan="2">Color</th>
                <th rowspan="2">Size</th>
                <th rowspan="2">Cost price</th>
                <th rowspan="2">Sale price</th>
                <th rowspan="2">All Qty</th>
                <th class="text-center" colspan="1">Store</th>
            </tr>
            <tr>
                <th class="text-center">SAMT</th>
            </tr>
        </thead>
        <tbody>
            <?php

                    // Fetch individual product rows
                    $stmt = $pdo->prepare("SELECT p.*, IFNULL(s.s_qty, 0) AS stock_qty
                    FROM product p
                    LEFT JOIN stock s ON s.s_product_id = p.p_product_id
                    GROUP BY p.p_product_code, p.p_product_name, p.p_hands, p.p_color,FIELD(p.p_size, 'SS', 'S', 'M', 'L', 'XL', 'XXL'), p.p_unit, p.p_collection, p.p_cost_price, p.p_sale_price
                    ORDER BY p.p_date_add DESC, p.p_color ,FIELD(p.p_size, 'SS', 'S', 'M', 'L', 'XL', 'XXL')
                    ");

                    // Execute the statement
                    $stmt->execute();
                    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    // Loop through products and output each row
                    foreach ($products as $index => $product) {
                    echo "<tr data-id='" . htmlspecialchars($product['p_product_id']) . "'>";
                    echo "<td>" . ($index + 1) . "</td>"; // Display No starting from 1
                    echo "<td>".htmlspecialchars($product['p_product_id'])."</td>";
                    echo "<td>" . htmlspecialchars($product['p_collection']) . "</td>";
                    echo "<td>" . htmlspecialchars($product['p_product_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($product['p_hands']) . "</td>";
                    echo "<td>" . htmlspecialchars($product['p_color']) . "</td>";
                    echo "<td>" . htmlspecialchars($product['p_size']) . "</td>";
                    echo "<td class='text-end'>" . number_format($product['p_cost_price']) . "</td>";
                    echo "<td class='text-end'>" . number_format($product['p_sale_price']) . "</td>";
                    echo "<td class='text-end' style='color: " . ($product['stock_qty'] == 0 ? "red ;background:#FCF3CF;" : "green;") . "'>" . htmlspecialchars($product['stock_qty']) . "</td>";
                    echo "<td class='text-center' style='vertical-align: middle;'>";
                    echo '<div class="input-group"><div class="input-group-text">';
                    echo "<input class='form-check-input' type='checkbox' value='' id='checkbox_" . htmlspecialchars($product['p_product_id']) . "' onchange='toggleInput(this)' /> <br>";
                    echo "</div><input class='form-control' min='1' type='number' id='input_" . htmlspecialchars($product['p_product_id']) . "' value='' style='display: none;' /> </div>";
                    echo "</td>";
                    echo "</tr>";
                    }

                
                     ?>
        </tbody>
    </table> <!-- Preview Modal -->
    <div id="previewModal" class="modal modal-xl modal fade" style="display:none; width:100%;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-inbox fa-lg"></i> Preview Changes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="margin: 50px;">
                    <!--  <div class="d-flex justify-content-start w-50">
                        <p>PR CODE : </p>&nbsp;
                        <p><?php echo date('Ymd'); ?></p>
                    </div> -->
                    <div class="d-flex justify-content-start w-50">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1"><b>Date</b></span>
                            <input class="form-control" type="date" value="" name="date_create" id="currentDate"
                                aria-describedby="basic-addon1" />
                        </div>
                        <script>
                        document.getElementById('currentDate').valueAsDate = new Date();
                        </script>

                    </div>
                    <div class="d-flex justify-content-end">
                        <h2 id="total">Total</h2>
                    </div>
                    <div class="d-flex justify-content-center">
                        <table class="table table-hover table-bordered" style="width: 100%;">
                            <!-- Table content -->
                            <thead class="text-center">
                                <tr class="table-light" style="vertical-align: middle;">
                                    <th rowspan="2">No</th>
                                    <th rowspan="2">Collection</th>
                                    <th rowspan="2">Name</th>
                                    <th rowspan="2">Hands</th>
                                    <th rowspan="2">Color</th>
                                    <th rowspan="2">Size</th>
                                    <th rowspan="2">Cost price</th>
                                    <th rowspan="2">Sale price</th>
                                    <th rowspan="2">All Qty</th>
                                    <th class="text-center" colspan="1">Store</th>
                                    <th rowspan="2">Total Price</th>
                                </tr>
                            </thead>
                            <tbody class=" modal-body" id="previewBody">
                                <!-- Preview content will be inserted here -->
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end">
                        <textarea class="form-control" name="memo" placeholder="memo" style="width: 300px;"></textarea>
                    </div>
                    <div class="d-flex justify-content-end">
                        <div class="form-check m-3">
                            <input class="form-check-input" type="radio" name="reason" id="flexRadioDefault1" value="1"
                                checked>
                            <label class="form-check-label" for="flexRadioDefault1">
                                Purchased
                            </label>
                        </div>
                        <div class="form-check m-3">
                            <input class="form-check-input" type="radio" name="reason" id="flexRadioDefault2" value="2">
                            <label class="form-check-label" for="flexRadioDefault2">
                                Returned
                            </label>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary modal-footer" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="confirmButton">Confirm</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<script>
var previewData = []; // Array to store preview data

// Function to toggle input field visibility and update preview
function toggleInput(checkbox, reason) {
    var inputId = checkbox.id.replace('checkbox_', 'input_'); // Get the corresponding input field ID
    var inputField = document.getElementById(inputId);

    if (checkbox.checked) {
        inputField.type = 'number'; // Change input type to number
        inputField.style.display = 'block'; // Show the input field

        // Remove any existing event listener to avoid duplicates
        inputField.removeEventListener('input', handleInputChange);
        inputField.addEventListener('input', handleInputChange);

        updatePreview(inputId); // Update the preview with the reason
    } else {
        inputField.style.display = 'none'; // Hide the input field
        inputField.value = ''; // Clear input value

        // Remove the product from previewData
        var productId = inputId.replace('input_', ''); // Get the product ID from inputId
        previewData = previewData.filter(item => item.p_product_id !== productId);

        // Update the preview display
        updatePreviewDisplay();
    }
}
// Function to handle input change
function handleInputChange(event) {
    var inputId = event.target.id;
    updatePreview(inputId);
}

// Function to update the preview
function updatePreview(inputId) {
    if (inputId) {
        var productId = inputId.replace('input_', ''); // Get the product ID from inputId
        var inputField = document.getElementById(inputId);
        var qty = inputField.value; // Get the quantity from input field

        // Validate quantity input
        /* if (!qty || isNaN(qty) || qty < 0) {
            console.error('Invalid quantity input');
            return;
        } */

        // Send AJAX request to fetch product data
        $.ajax({
            type: 'POST',
            url: 'list_get_product_info.php', // URL to handle AJAX request
            data: {
                productId: productId
            }, // Send product ID as data
            success: function(response) {
                try {
                    var productData = JSON.parse(response);

                    // Update previewData or create new entry
                    var productIndex = previewData.findIndex(item => item.p_product_id === productId);
                    if (productIndex !== -1) {
                        previewData[productIndex].s_qty = qty;
                    } else {

                        // Create a new entry in previewData
                        previewData.push({
                            p_product_id: productId,
                            p_product_code: productData.p_product_code,
                            p_qty: productData
                                .stock_qty, // Ensure this field exists in your fetched data
                            s_qty: qty,
                            p_collection: productData.p_collection,
                            p_product_name: productData.p_product_name,
                            p_hands: productData.p_hands,
                            p_color: productData.p_color,
                            p_size: productData.p_size,
                            p_cost_price: productData.p_cost_price,
                            p_sale_price: productData.p_sale_price,
                            // Add more properties as needed
                            //p_reason: reason // Add reason to the new entry
                        });
                    }

                    // Update the preview display
                    updatePreviewDisplay();
                } catch (e) {
                    console.error('Error parsing response:', e);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX request error:', error);
            }
        });
    }
}

// Function to update the preview display
function updatePreviewDisplay() {
    var previewBody = document.getElementById('previewBody');
    previewBody.innerHTML = ''; // Clear existing content

    previewData.forEach(function(product, index) {
        var row = document.createElement('tr');
        row.innerHTML = `
            <td style="display: none;">${product.p_product_id}</td> <!-- Hidden to send ID for update -->
            <td>${index + 1}</td>
            <td>${product.p_collection}</td>
            <td>${product.p_product_name}</td>
            ${product.p_hands ? `<td>${product.p_hands}</td>` : `<td></td>`}
            ${product.p_color ? `<td>${product.p_color}</td>` : `<td></td>`}
            ${product.p_size ? `<td>${product.p_size}</td>` : `<td></td>`}
            <td>${Number(product.p_cost_price).toLocaleString()}</td>
            <td>${Number(product.p_sale_price).toLocaleString()}</td>
            <td style="width:200px; color: red; text-align: center;">${product.p_qty}</td>    
            <td style="width:200px; color: green; text-align: center;">
                +${product.s_qty} (${parseInt(product.p_qty) + parseInt(product.s_qty)})
            </td>
            <td class='text-end'>${Number(parseInt(product.s_qty) * parseInt(product.p_cost_price)).toLocaleString()}</td>
        `;
        previewBody.appendChild(row);
    });
}



function openPreviewModal() {
    // Call the updatePreview function to update the preview data
    updatePreview();
    // Show the preview modal
    $('#previewModal').modal('show');

    // Get the total number of preview rows
    var totalRows = previewData.length;

    // Update the total count display in the modal
    document.getElementById('total').innerText = 'Total: ' + totalRows;

    // Check if totalRows is 0
    if (totalRows === 0) {
        // If total is 0, show an alert
        Swal.fire({
            title: 'No Data',
            text: 'There is no data to update.',
            icon: 'info',
            confirmButtonText: 'OK'
        });
    }


    /* // Event listener for radio button change inside the modal
    document.querySelectorAll('#previewModal input[name="reason"]').forEach(function(radio) {
        radio.addEventListener('change', function(event) {
            // Call toggleInput with the corresponding checkbox inputId and reason value
            var checkboxId = event.target.id.replace('flexRadioDefault', 'checkbox');
            var checkbox = document.getElementById(checkboxId);
            var reason = event.target.value;
            toggleInput(checkbox, reason);
        });
    }); */
}

// เพิ่มการจัดการเหตุการณ์เมื่อปุ่ม "Confirm" ถูกคลิก
document.querySelector('#confirmButton').addEventListener('click', function() {
    // แสดงข้อความยืนยันด้วย Sweet Alert
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to update stock?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, update it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            var selectedReason = document.querySelector('input[name="reason"]:checked').value;
            // ค่าวันที่
            var dateCreate = document.querySelector('#currentDate').value;
            var memo = document.querySelector('textarea[name="memo"]').value;

            // ถ้าผู้ใช้กด OK ให้ดำเนินการส่งข้อมูลไปยังสคริปต์ PHP ด้วย AJAX
            $.ajax({
                type: 'POST',
                url: 'list_insert_stock_qty.php', // ตั้งค่า URL ของสคริปต์ PHP ที่จะปรับปรุงข้อมูลในฐานข้อมูล
                data: {
                    previewData: previewData, // ส่งข้อมูลทั้งหมดใน previewData
                    reason: selectedReason, // ส่งค่า reason
                    date_create: dateCreate, // ส่งค่าวันที่
                    memo: memo // ส่งค่า memo

                },
                success: function(response) {
                    console.log(response);

                    // Show success message
                    Swal.fire({
                        title: 'Updated!',
                        text: 'Stock has been updated successfully.',
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonText: '<i class="fa-solid fa-folder-plus"></i> on this page',
                        cancelButtonText: '<i class="fa-solid fa-arrow-right-to-bracket"></i> History StockIn',
                        confirmButtonColor: '#28a745', // Custom color for confirm button
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
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: 'Error',
                        icon: 'error',
                    });
                    console.error(error);
                    //alert('Error occurred while updating stock.');
                }
            });
        }
    });
});
</script>