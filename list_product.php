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
        <a href="register.php" class="btn btn-success">+ NEW</a>&nbsp;
        <button type="button" class="btn btn-primary" onclick="openPreviewModal()">Stock in</button>&nbsp;
        <button class="btn btn-info">PR</button>
    </div>

    <table class="table table-bordered table-hover" style="width: 100%;">
        <!-- Table content -->
        <thead class="table-primary text-center">
            <tr style="vertical-align: middle;">
                <th rowspan="2">No</th>
                <th rowspan="2">Collection</th>
                <th rowspan="2">Name</th>
                <th rowspan="2">Hands</th>
                <th rowspan="2">Color</th>
                <th rowspan="2">Size</th>
                <th rowspan="2">Cost price</th>
                <th rowspan="2">Sale price</th>
                <th rowspan="2">Location</th>
                <th rowspan="2">All Qty</th>
                <th class="text-center" colspan="2">Store</th>
            </tr>
            <tr>
                <th class="text-center" style="width: 200px;">SAMT</th>
                <th style="width: 200px;">SAKABA</th>
            </tr>
        </thead>
        <tbody>
            <?php
                    $sql = "SELECT * FROM stock
                    GROUP BY s_collection, s_product_name, s_hands, s_color, s_size, s_cost_price, s_sale_price, s_location";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();

                    // Fetch all rows as an associative array
                    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    // Loop through products and output each row
                    foreach ($products as $index => $product) {
                        echo "<tr data-id='" . $product['s_id'] . "'>";
                        echo "<td>" . ($index + 1) . "</td>"; // Display No starting from 1
                        echo "<td>" . htmlspecialchars($product['s_collection']) . "</td>";
                        echo "<td>" . htmlspecialchars($product['s_product_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($product['s_hands']) . "</td>";
                        echo "<td>" . htmlspecialchars($product['s_color']) . "</td>";
                        echo "<td>" . htmlspecialchars($product['s_size']) . "</td>";
                        echo "<td class='text-end'>" . number_format($product['s_cost_price']) . "</td>";
                        echo "<td class='text-end'>" . number_format($product['s_sale_price']) . "</td>";
                        echo "<td>" . htmlspecialchars($product['s_location']) . "</td>";
                        echo "<td>" . htmlspecialchars($product['s_qty']) . "</td>";

                   // Output checkboxes based on s_location
                    // Output checkboxes based on s_location
                    if ($product['s_location'] == 'SAMT') { 
                        echo "<td class='text-center' style='vertical-align: middle;'>";
                        echo '<div class="input-group"><div class="input-group-text">';
                        echo "<input class='form-check-input' type='checkbox' value='' id='checkbox_" . $product['s_id'] . "' onchange='toggleInput(this)' /> <br>";
                        echo "</div><input class='form-control' min='1' type='number' id='input_" . $product['s_id'] . "' value='' style='display: none;' /> </div>";
                        echo "</td>";
                        echo "<td></td>";
                    }
                    else {
                        echo "<td></td>";
                        echo "<td class='text-center' style='vertical-align: middle;'>";
                        echo '<div class="input-group mb-3"><div class="input-group-text">';
                        echo "<input class='form-check-input' type='checkbox' value='' id='checkbox_" . $product['s_id'] . "' onchange='toggleInput(this)' /> <br>";
                        echo "</div><input class='form-control' min='1' type='number' id='input_" . $product['s_id'] . "' value='' style='display: none;' /> </div>";
                        echo "</td>";
                        } 
                    echo "</tr>" ; } ?>
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
                    <div class="d-flex justify-content-start w-50">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">Date</span>
                            <input class="form-control" type="date" value="" id="currentDate"
                                aria-describedby="basic-addon1" />
                        </div>
                        <script>
                        document.getElementById('currentDate').valueAsDate = new Date();
                        </script>

                    </div>
                    <div class="d-flex justify-content-end">
                        <h2>Total 3</h2>
                    </div>
                    <div class="d-flex justify-content-center">
                        <table class="table table-hover table-bordered" style="width: 100%;">
                            <!-- Table content -->
                            <thead class="text-center">
                                <tr class="table-light" style="vertical-align: middle;">
                                    <th>No</th>
                                    <th>Collection</th>
                                    <th>Name</th>
                                    <th>Hands</th>
                                    <th>Color</th>
                                    <th>Size</th>
                                    <th>Cost price</th>
                                    <th>Sale price</th>
                                    <th>Current Qty</th>
                                    <th>Stock In</th>
                                    <th class="text-center">Store</th>
                                </tr>
                            </thead>
                            <tbody class="modal-body" id="previewBody">
                                <!-- Preview content will be inserted here -->
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end">
                        <div class="form-check m-3">
                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                            <label class="form-check-label" for="flexRadioDefault1">
                                Purcahased
                            </label>
                        </div>
                        <div class="form-check m-3">
                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2"
                                checked>
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
function toggleInput(checkbox) {
    var inputId = checkbox.id.replace('checkbox_', 'input_'); // Get the corresponding input field ID
    var inputField = document.getElementById(inputId);
    console.log(inputId);
    if (checkbox.checked) {
        inputField.type = 'number'; // Change input type to number
        inputField.style.display = 'block'; // Show the input field
        // Update the preview when input value changes
        inputField.addEventListener('input', function() {
            updatePreview(inputId);
        });
        updatePreview(inputId);
    } else {
        inputField.style.display = 'none'; // Hide the input field
        inputField.value = ''; // Clear input value 
        removePreview(inputId); // Remove data from previewData
    }
}

function removePreview(inputId) {
    if (inputId) {
        var productId = inputId.replace('input_', ''); // Get the product ID from inputId
        // Find the index of the product in previewData
        var productIndex = previewData.findIndex(item => item.s_id === productId);
        if (productIndex !== -1) {
            previewData.splice(productIndex, 1); // Remove the product from previewData
        }
        // Update the preview display
        var previewBody = document.getElementById('previewBody');
        previewBody.innerHTML = ''; // Clear existing content
        previewData.forEach(function(product, index) {
            var row = document.createElement('tr');
            row.innerHTML = `
                <td>${index + 1}</td>
                <td>${product.s_collection}</td>
                <td>${product.s_product_name}</td>
                ${product.s_hands ? `<td>${product.s_hands}</td>` : `<td></td>`} <!-- Check if s_hands is not null -->
                ${product.s_color ? `<td>${product.s_color}</td>` : `<td></td>`} <!-- Check if s_color is not null -->
                ${product.s_size ? `<td>${product.s_size}</td>` : `<td></td>`} <!-- Check if s_size is not null -->
                <td>${Number(product.s_cost_price).toLocaleString()}</td>
                <td>${Number(product.s_sale_price).toLocaleString()}</td>
                <td style="width:200px; color: red; text-align: center;">${product.s_c_qty}</td>    
                <td style="width:200px; color: green; text-align: center;">
                    +${product.s_qty}(${parseInt(product.s_qty) + parseInt(product.s_c_qty)})
                </td> 
                <td>${product.s_location}</td>
                <!-- Add more columns as needed -->
            `;
            previewBody.appendChild(row);
        });
    }
}

function updatePreview(inputId) {
    if (inputId) {
        var productId = inputId.replace('input_', ''); // Get the product ID from inputId
        var inputField = document.getElementById(inputId);
        var qty = inputField.value; // Get the quantity from input field

        // Send AJAX request to fetch data
        $.ajax({
            type: 'POST',
            url: 'get_product_info.php', // URL to handle AJAX request
            data: {
                productId: productId
            }, // Send product ID as data
            success: function(response) {
                // Parse the JSON response
                var productData = JSON.parse(response);

                // Update previewData or create new entry
                var productIndex = previewData.findIndex(item => item.s_id === productId);
                if (productIndex !== -1) {
                    previewData[productIndex].s_qty = qty;
                    // Update other properties if needed
                } else {
                    // Create a new entry in previewData
                    previewData.push({
                        s_id: productId,
                        s_qty: qty,
                        // Add other properties from productData
                        s_collection: productData.s_collection,
                        s_product_name: productData.s_product_name,
                        s_hands: productData.s_hands,
                        s_color: productData.s_color,
                        s_size: productData.s_size,
                        s_cost_price: productData.s_cost_price,
                        s_c_qty: productData.s_qty,
                        s_sale_price: productData.s_sale_price,
                        s_location: productData.s_location,
                        // Add more properties as needed
                    });
                }

                // Update the preview display
                var previewBody = document.getElementById('previewBody');
                previewBody.innerHTML = ''; // Clear existing content
                previewData.forEach(function(product, index) {
                    var row = document.createElement('tr');
                    row.innerHTML = `
                    <td style="display: none;">${product.s_id}</td> <!-- ซ่อนเพื่อส่งไอดีไปบันทึกอัพเดท -->
                        <td>${index + 1}</td>
                        <td>${product.s_collection}</td>
                        <td>${product.s_product_name}</td>
                        ${product.s_hands ? `<td>${product.s_hands}</td>` : `<td></td>`} <!-- คอลลั่มเท่ากับ null จะไม่แสดง -->
                        ${product.s_color ? `<td>${product.s_color}</td>` : `<td></td>`} <!-- คอลลั่มเท่ากับ null จะไม่แสดง -->
                        ${product.s_size ? `<td>${product.s_size}</td>` : `<td></td>`} <!-- คอลลั่มเท่ากับ null จะไม่แสดง -->
                        <td>${Number(product.s_cost_price).toLocaleString()}</td>
                        <td>${Number(product.s_sale_price).toLocaleString()}</td>
                        <td style="width:200px; color: red; text-align: center;">${product.s_c_qty}</td>    
                        <td style="width:200px; color: green; text-align: center;">
                            +${product.s_qty}(${parseInt(product.s_qty) + parseInt(product.s_c_qty)})
                        </td>                        
                        <td>${product.s_location}</td>
                        <!-- Add more columns as needed -->
                    `;
                    previewBody.appendChild(row);
                });
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }
}

function openPreviewModal() {
    // Call the updatePreview function to update the preview data
    updatePreview();
    // Show the preview modal
    $('#previewModal').modal('show');
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
            // ถ้าผู้ใช้กด OK ให้ดำเนินการส่งข้อมูลไปยังสคริปต์ PHP ด้วย AJAX
            $.ajax({
                type: 'POST',
                url: 'update_stock_qty.php', // ตั้งค่า URL ของสคริปต์ PHP ที่จะปรับปรุงข้อมูลในฐานข้อมูล
                data: {
                    previewData: previewData // ส่งข้อมูลทั้งหมดใน previewData
                },
                success: function(response) {
                    // ดำเนินการหลังจากที่สคริปต์ PHP ปรับปรุงข้อมูลสำเร็จ
                    Swal.fire(
                        'Updated!',
                        'Stock has been updated successfully.',
                        'success'
                    ).then(() => {
                        // รีเฟรชหน้าหรือดำเนินการต่อตามต้องการ
                        location.reload();
                    });
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    alert('Error occurred while updating stock.');
                }
            });
        }
    });
});
</script>