<?php include('navbar.php'); 

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
    //MGCODE
    $stmt_mg = $pdo->query("SELECT DISTINCT o_mg_code FROM stockout");
    $mg_code = $stmt_mg->fetchAll(PDO::FETCH_COLUMN);  

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock In</title>
</head>

<body>
    <div class="container-fluid" style="margin-top: 150px;">
        <div class="card text-center m-5">
            <div class="card-header">
                <table class="table table-borderless">
                    <tr>
                        <th class="text-start" colspan="6">
                            <h1>
                                <i class="fa-solid fa-inbox fa-xl"></i> Stock In
                            </h1>
                        </th>
                    </tr>

                    <tr>
                        <td class="text-start" style="width: 150px; text-transform: uppercase;">
                            <!-- Large -->
                            <span class="badge bg-info text-dark">
                                <a class="nav-link link-light" href="list.php">
                                    <i class="fa-solid fa-plus"></i>&nbsp; <?php echo $stockList ?></a>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-start">
                            <span class="badge bg-info text-dark">
                                <i class="fa-solid fa-calendar-days"></i>
                                DATE
                            </span>
                        </th>
                        <td class="text-start">
                            <input class="form-control w-25" type="date" value="<?php echo date('Y-m-d'); ?>"
                                id="dateStockIn">
                        </td>
                    </tr>
                    <tr>
                        <td class="text-start" style="text-transform: uppercase;">
                            <span class="badge bg-info text-dark">
                                <?php echo $stock_in ?>
                            </span>
                            <!-- Default checkbox -->
                        </td>

                        <td class="d-flex justify-content-start">
                            <!-- Default radio -->
                            <div class="form-check d-flex align-items-center">
                                <input class="form-check-input" type="radio" id="check_purchased" name="check_stockIn"
                                    required>
                                <label class="form-check-label" for="check_purchased">
                                    <?php echo 'Purchased (Storage)' ?>
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="d-flex justify-content-start">
                            <div class="form-check d-flex align-items-center">
                                <input class="form-check-input" type="radio" id="check_returned" name="check_stockIn">
                                <label class="form-check-label" for="check_returned">
                                    <?php echo 'Returned' ?>
                                </label>
                            </div>
                        </td>
                    </tr>
                </table>
                <div class="text-start">
                    <p>
                        ** <i class="fa-solid fa-rectangle-xmark"></i>
                        No data available : Please register for the product. &nbsp;
                        <i class="fa-solid fa-arrow-right"></i>&nbsp;
                        <!-- <a href="register.php">
                            <i class="fa-solid fa-circle-plus"></i>
                            <?php echo $register ?>
                        </a>  -->
                        <button class="btn btn-sm btn-success" onclick="window.location.href = 'register.php'"
                            style="color: white;">
                            <i class="fa-solid fa-circle-plus"></i>
                            <?php echo $register ?>
                        </button>**
                    </p>
                </div>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr class="text-center table-info">
                            <th><?php echo $product_code ?></th>
                            <th><?php echo $product_name?></th>
                            <th><?php echo $unit ?></th>
                            <th><?php echo $color ?></th>
                            <th><?php echo $hands ?></th>
                            <th><?php echo $size  ?></th>
                            <th><?php echo 'Cost' ?></th>
                            <th><?php echo $qty ?></th>
                            <th><?php echo 'Total price'  ?></th>
                            <th><?php echo $reset  ?></th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider table-divider-color">
                        <tr>
                            <!-- ไอดี product จาก product(Master) -->
                            <input type="text" class="form-control" id="product_id" hidden>


                            <td>
                                <input class="form-control" type="text" id="product" name="product" list="product_names"
                                    onchange="validateInput(this)">
                                <!-- Populate datalist with product names -->
                                <datalist id="product_names">
                                    <?php foreach ($productNames_code as $productName_code): ?>
                                    <option value="<?php echo $productName_code; ?>">
                                        <?php endforeach; ?>
                                </datalist>
                            </td>
                            <!-- Replace the input field with a readonly input -->
                            <td>
                                <input type="text" class="form-control" id="selectedProductName" name="productName"
                                    style="background:#fff8e4;" readonly>
                            </td>
                            <td>
                                <input type="text" class="form-control" id="selectedProductUnit" name="productUnit"
                                    style="background:#fff8e4;" readonly>
                            </td>
                            <td>
                                <input class="form-control" type="text" id="colorInput" name="productColor"
                                    list="product_names_color" onchange="validateInput(this)">
                                <datalist id="product_names_color">
                                    <?php foreach ($productNames_color as $productName_color): ?>
                                    <option value="<?php echo $productName_color; ?>">
                                        <?php endforeach; ?>
                                </datalist>
                            </td>
                            <td>
                                <input class="form-control" type="text" id="handInput" name="productHand"
                                    list="product_hand" onchange="validateInput(this)">
                                <datalist id="product_hand">
                                    <?php foreach ($productNames_hands as $productName_hand): ?>
                                    <option value="<?php echo $productName_hand; ?>">
                                        <?php endforeach; ?>
                                </datalist>
                            </td>
                            <td>
                                <input class="form-control" type="text" id="sizeInput" name="productSize"
                                    list="product_size" onchange="validateInput(this)">
                                <datalist id="product_size">
                                    <?php foreach ($productNames_size as $productName_size): ?>
                                    <option value="<?php echo $productName_size; ?>">
                                        <?php endforeach; ?>
                                </datalist>
                            </td>
                            <td>

                                <input class="form-control text-end" type="text" id="selectedProductCost"
                                    style="background:#fff8e4;" readonly>
                                <!--  <input type="text" class="form-control" id="total_price" name="total_price"
                                style="background:#fff8e4;" readonly> -->

                            </td>
                            <td>
                                <div class="input-group">
                                    <button type="button" class="btn btn-outline-secondary"
                                        id="decrementButton">-</button>
                                    <span class="input-group-text" id="qtyValue">1</span>
                                    <button type="button" class="btn btn-outline-secondary"
                                        id="incrementButton">+</button>
                                </div>
                            </td>

                            <td>
                                <input class=" form-control text-end" id="total_price" style="background:#fff8e4;"
                                    readonly></input>
                            </td>

                            <td style="white-space: nowrap;" class="text-center">
                                <script>
                                function resetInput() {
                                    var inputs = document.querySelectorAll(
                                        "#product, #selectedProductName, #myInput, #selectedProductUnit, #colorInput, #sizeInput, #handInput, #total_price, #selectedProductCost"
                                    );


                                    // Reset the value of the input fields
                                    inputs.forEach(function(input) {
                                        input.value = "";
                                        input.classList.remove('valid-input-red');
                                    });

                                    // Reset the quantity value to 0
                                    $('#qtyValue').text('');
                                }
                                </script>
                                <button type="button" class="btn btn-warning btn-floating" data-mdb-ripple-init
                                    onclick="resetInput()"><i class="fa-solid fa-eraser"></i></button>
                            </td>
                        </tr>
                        <!--    <tr>
                            <td colspan="10" class="text-center">
                                <button type="button" class="btn btn-primary" id="addRowBtn"><i
                                        class="fa-solid fa-plus"></i> Add Row</button>

                                <button type="submit" class="btn btn-danger"><i class="fa-solid fa-minus"></i> Delete
                                    Row</button>
                            </td>
                        </tr> -->
                    </tbody>
                </table>
                <table class="table mx-auto table-borderless w-100">
                    <tr style="vertical-align: middle;">
                        <th class="text-end w-75">CURRENT QTY :</th>
                        <td class="text-end">
                            <input class="form-control text-end" type="number" id="currentQTY"
                                style="background:#fff8e4;" readonly>
                        </td>
                    </tr>
                    <tr style="vertical-align: middle;">
                        <th class="text-end">TOTAL QTY :</th>
                        <td class="text-end">
                            <input id="totalQTY" class="form-control text-end" style="background:#c9e9f6 ;"
                                type="number" readonly>
                        </td>
                    </tr>
                    <tr style="vertical-align: middle;">
                        <th class="text-end">MEMO :</th>
                        <td>
                            <textarea type="text" class="form-control" id="memo" name="memo"></textarea>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="card-footer text-end">
                <button type="button" class="btn btn-success btn-lg" data-mdb-ripple-init onclick="submitStockin()">
                    <i class="fa-solid fa-floppy-disk"></i> Submit
                </button>
            </div>

        </div>
    </div>
</body>

</html>
<script>
function validateInput(input) {
    // Check if the input has a value
    if (input.value.trim() !== '') {
        input.classList.remove('valid-input-red');
        // If the input has a value, add the valid-input class
        //input.classList.add('valid-input-green');
    } else {
        // If the input doesn't have a value, remove the valid-input class
        //input.classList.remove('valid-input-green');
        input.classList.add('valid-input-red');
    }
}
</script>


<script>
// Get references to the dropdown menu and the input field
const stockToOption = document.getElementById('stockToOption');
const otherInput = document.getElementById('otherInput');

// Function to format the input value with commas
function formatNumberWithCommas(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// Event listener to format the number input on keyup
document.addEventListener('DOMContentLoaded', (event) => {
    const inputElements = document.querySelectorAll(
        'input[type="text"][id="selectedProductCost"], #total_price');
    inputElements.forEach(input => {
        input.addEventListener('keyup', (event) => {
            let value = input.value.replace(/,/g, ''); // Remove existing commas
            if (!isNaN(value) && value.length > 0) {
                input.value = formatNumberWithCommas(value);
            }
        });
    });
});



$('#product').on('input', function() {
    // Get the selected product code from the input field
    var selectedProductCode = $(this).val();
    console.log('Selected product code:', selectedProductCode);

    // Send an AJAX request to fetch the product name
    $.ajax({
        url: 'ajax_GET/get_product_name.php', // URL to your PHP script that fetches the product name
        method: 'POST',
        data: {
            product_code: selectedProductCode
        },
        success: function(response) {
            // Split the response into product name and unit
            var parts = response.split('|');
            var productName = parts[0];
            var productUnit = parts[1];

            // Update the value of the readonly input fields with the fetched product name and unit
            $('#selectedProductName').val(productName);
            $('#selectedProductUnit').val(productUnit);

        }
    });
}); // Function to update quantity input based on product, color, or size changes
// Function to update quantity input based on product, color, or size changes
$('#product, #colorInput, #sizeInput, #handInput').on('change', function() {
    updateQuantityInput();
});


function updateQuantityInput() {
    var selectedProductCode = $('#product').val();
    var selectedColor = $('#colorInput').val();
    var selectedSize = $('#sizeInput').val();
    var selectedHand = $('#handInput').val();
    var qtyInput = $('#qtyValue');
    var incrementButton = $('#incrementBtn');
    var total_price = $('#total_price').val();
    var qtyValue = parseInt($('#qtyValue').text());
    var productCost = parseFloat($('#selectedProductCost').val());

    // AJAX call to get the stock quantity and product cost of the selected product, color, size, and hand
    $.ajax({
        url: 'ajax_GET/get_stock_quantity.php',
        method: 'POST',
        data: {
            product_code: selectedProductCode,
            color: selectedColor,
            size: selectedSize,
            hand: selectedHand
        },
        success: function(response) {
            var data = JSON.parse(response);
            if (data.error) {
                qtyInput.html('<b style="color: orange;">No Data</b>');
                incrementButton.prop('disabled', true);
            }


            var productID = data.p_product_id;
            var stockQuantity = parseInt(data.s_qty);
            var productCost = parseFloat(data.p_cost_price);
            var productQTY = parseInt(data.p_qty);
            console.log('s_qty = ' + stockQuantity);
            console.log('p_cost_price = ' + productCost);
            console.log('p_qty = ' + productQTY);
            console.log('productID = ' + productID);


            if (stockQuantity > 0 || productQTY == 0) {
                qtyInput.text(1);
                incrementButton.prop('disabled', false);

                //var totalPrice = (stockQuantity * productCost).toFixed(2);
                var totalPrice = (1 * productCost).toFixed(2);

                totalPrice = parseFloat(totalPrice).toLocaleString(); // Calculate the total price

                $('#total_price').val(totalPrice);
                $('#selectedProductCost').val(productCost);
                $('#product_id').val(productID);
                if (stockQuantity > 0) {
                    $('#total_price').val(totalPrice);
                    $('#currentQTY').val(stockQuantity);
                    $('#totalQTY').val(stockQuantity + parseInt($('#qtyValue').text()));
                } else {
                    $('#total_price').val(productCost);
                    $('#currentQTY').val(0);
                    $('#totalQTY').val(stockQuantity + parseInt($('#qtyValue').text()));

                }
            }
            /* else if (productQTY == 0) {
                           qtyInput.html('<b style="color: red;">out of stock</b>');
                           incrementButton.prop('disabled', true);
                           $('#selectedProductCost').val(productCost);
                           $('#product_id').val(productID);
                       } */

            // Handle stock quantity and product cost
            /*    if (stockQuantity <= 0) {
                   qtyInput.html('<b style="color: red;">Sold out</b>');
                   incrementButton.prop('disabled', true);
               } else {
                   qtyInput.text(stockQuantity);
                   incrementButton.prop('disabled', false);
               } */

            // Set the max attribute of the quantity span to the stock quantity
            //qtyInput.attr('max', stockQuantity);
        },
        error: function() {
            console.error('Error fetching stock quantity and product cost.');
        }
    });
}

var interval; // Variable to store the interval

// Function to decrement quantity value
function decrementQty() {
    var qtyValue = parseInt($('#qtyValue').text());
    if (qtyValue > 1) {
        $('#qtyValue').text(qtyValue - 1);
        updateTotalPrice(); // Call updateTotalPrice after decrementing quantity
    }
}

// Function to increment quantity value
function incrementQty() {
    var qtyValue = parseInt($('#qtyValue').text());
    $('#qtyValue').text(qtyValue + 1);
    updateTotalPrice(); // Call updateTotalPrice after incrementing quantity
}

// Start continuous decrement
function startDecrement() {
    interval = setInterval(decrementQty, 100);
}

// Start continuous increment
function startIncrement() {
    interval = setInterval(incrementQty, 100);
}

// Stop the continuous change
function stopChange() {
    clearInterval(interval);
}

// Attach event handlers for increment and decrement buttons
$(document).ready(function() {
    $('#decrementButton').mousedown(startDecrement);
    $('#decrementButton').mouseup(stopChange);
    $('#decrementButton').mouseleave(stopChange); // Also stop when mouse leaves the button

    $('#incrementButton').mousedown(startIncrement);
    $('#incrementButton').mouseup(stopChange);
    $('#incrementButton').mouseleave(stopChange); // Also stop when mouse leaves the button
});



// Function to update total price
function updateTotalPrice() {
    var qtyValue = parseInt($('#qtyValue').text());
    var productCost = parseFloat($('#selectedProductCost').val().replace(/,/g,
        '')); // Get the product cost and remove commas

    var totalPrice = (qtyValue * productCost).toFixed(2);
    totalPrice = parseFloat(totalPrice).toLocaleString(); // Calculate the total price and format it with commas

    $('#total_price').val(totalPrice); // Update the total price element
    var currentQTY = parseInt($('#currentQTY').val() || 0);
    $('#totalQTY').val(currentQTY + qtyValue);
}




function submitStockin() {
    var productID = $('#product_id').val();
    var productCode = $('#product').val();
    var productName = $('#selectedProductName').val();
    var productCost = $('#selectedProductCost').val();
    var productTotal = $('#total_price').val();
    var memo = $('#memo').val();
    var qtyValue = parseInt($('#qtyValue').text());
    var date = $('#dateStockIn').val();
    var currentQTY = $('#currentQTY').val();

    let status;

    if ($('#check_purchased').is(':checked')) {
        status = 1;
    } else if ($('#check_returned').is(':checked')) {
        status = 2;
    }


    var data = {
        productID: productID,
        productCode: productCode,
        productName: productName,
        productCost: productCost,
        productTotal: productTotal,
        memo: memo,
        qtyValue: qtyValue,
        date: date,
        status: status,
        currentQTY: currentQTY
    };


    if (productID != '' && qtyValue != 0) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to submit the form?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, submit it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'stock_in_submit.php',
                    method: 'POST',
                    data: data,
                    success: function(response) {
                        // จัดการข้อมูลหลังจากที่ส่งไปยังเซิร์ฟเวอร์สำเร็จ
                        // Handle success response
                        // Handle success response
                        Swal.fire('Success!', 'Form submitted successfully', 'success').then((
                            result) => {
                            if (result.isConfirmed) {
                                window.location.reload(); // Reload the window
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        // จัดการข้อมูลหลังจากที่เกิดข้อผิดพลาดในการส่งข้อมูล
                        Swal.fire('Error!', 'Failed to submit form', 'error');

                    }

                });
            } else {
                console.log('cancel')
            }
        });
    } else if (qtyValue == '') {

        Swal.fire({
            title: 'ERROR',
            text: 'Please stock in or add QTY of product',
            icon: 'error',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
    } else {
        Swal.fire({
            title: 'ERROR',
            text: 'Please fill to data or register of product',
            icon: 'error',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
    }

};
</script>