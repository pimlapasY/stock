<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Good request</title>
</head>
<style>
.valid {
    border-color: green;
}

.invalid {
    border-color: red;
}

input {
    width: 100px;
}

.valid-input-green {
    border-color: green !important;
}

.valid-input-red {
    border-color: red !important;
}
</style>

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


<body>

    <?php include('navbar.php') ;
    
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
        
    $dateNow = date('ymd');
    $stmt = $pdo->prepare("SELECT COUNT(*) AS count FROM stockout WHERE o_mg_code LIKE CONCAT('M', :dateNow, '%')");
    $stmt->bindParam(':dateNow', $dateNow);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $count = $row['count'];

    // Construct MG_CODE
    $MG_CODE = 'M' . $dateNow . str_pad($count + 1, STR_PAD_LEFT);

    
?>
    <div class="container-fluid" style="margin-top: 150px;">
        <div class="card text-center m-5">
            <div class="card-header">
                <table class="table table-borderless">
                    <tr>
                        <th class="text-start" colspan="6">
                            <!-- <h3 class="card-title">SHIPPO ASAHI MOULDS(THAILAND) CO.,LTD.</h3> -->
                            <h1>
                                <i class="fa-solid fa-arrow-right-from-bracket fa-xl"></i> Stock Out
                            </h1>
                        </th>
                    </tr>
                    <tr>
                        <td class="text-start" style="width: 150px; text-transform: uppercase;">
                            <!-- Large -->
                            <span class="badge bg-warning text-dark">
                                <a class="nav-link link-light" href="stock_samt.php">
                                    <i class="fa-solid fa-plus"></i>&nbsp; <?php echo $stockList ?></a>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-start">
                            <span class="badge bg-warning text-dark">
                                <i class="fa-solid fa-hashtag"></i>
                                MG CODE
                            </span>
                        </th>
                        <td>
                            <input type="text" class="form-control" id="MG_code" name="MG_code"
                                value="<?php echo $MG_CODE ?>" style="width:120px; background:#d0f0c0 ;" readonly>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-start">
                            <span class="badge bg-warning text-dark">
                                <i class="fa-solid fa-calendar-days"></i>
                                DATE
                            </span>
                        </th>
                        <td class="text-start">
                            <input class="form-control w-25" type="date" value="<?php echo date('Y-m-d'); ?>"
                                id="dateStockOut">
                        </td>
                    </tr>
                    <tr>
                        <td class="text-start" style="text-transform: uppercase;">
                            <span class="badge bg-warning text-dark">
                                <i class="fa-solid fa-right-from-bracket"></i>
                                <?php echo $stock_out ?>
                            </span>
                            <!-- Default checkbox -->
                        </td>

                        <td class="d-flex justify-content-start">
                            <!-- Default radio -->
                            <div class="form-check me-3">
                                <input class="form-check-input" type="radio" name="flexRadioDefault"
                                    id="flexRadioDefault1" required>
                                <label class="form-check-label" for="flexRadioDefault1"><?php echo $sale ?></label>
                            </div>
                            <div class="form-check me-3">
                                <input class="form-check-input" type="radio" name="flexRadioDefault"
                                    id="flexRadioDefault2">
                                <label class="form-check-label" for="flexRadioDefault2"> <?php echo $take_out ?></label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="flexRadioDefault"
                                    id="flexRadioDefault3">
                                <label class="form-check-label" for="flexRadioDefault3">
                                    <?php echo 'Sale sample' ?></label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-start"></td>
                        <td class="text-start" id="selectContainerSale" style="display:none;">
                            <!-- Add your list select here -->
                            <div class="input-group mb-3">
                                <button class="btn btn-outline-success" type="store"><?php echo 'From' ?></button>
                                <select class="form-select" id="store" style="text-transform: uppercase;">
                                    <option selected value="1">samt</option>
                                    <option value="2">sakaba</option>
                                </select>
                            </div>
                            <div class="input-group mb-3">
                                <button class="btn btn-outline-warning"
                                    type="paidOption"><?php echo $paid_by ?></button>
                                <select class="form-select" id="paidOption">
                                    <option selected><?php echo $choose ?>...</option>
                                    <option value="1">Cash</option>
                                    <option value="2">QR</option>
                                    <option value="3">Shopify</option>
                                </select>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text btn btn-outline-warning"
                                    id="basic-addon1"><?php echo $cus_name ?></span>
                                <input type="text" class="form-control" aria-label="Username" id="cusname"
                                    aria-describedby="basic-addon1" />
                            </div>
                        </td>
                        <td class="text-start" id="selectContainerTakeOut" style="display:none;">
                            <div class="input-group mb-3">
                                <button class="btn btn-outline-warning" type="stockToOption">To</button>
                                <select class="form-select" id="stockToOption">
                                    <option selected><?php echo $choose ?>...</option>
                                    <option>SAKABA</option>
                                    <option>Other</option>
                                </select>
                            </div>
                        </td>
                        <td>
                            <input type="text" class="form-control" id="otherInput" style="display: none;"
                                placeholder="Enter Other">

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
                    <p>
                        ** <i class="fa-solid fa-sack-xmark"></i>
                        Out of stock : Please stock more of this product. &nbsp;
                        <i class="fa-solid fa-arrow-right"></i> &nbsp;
                        <!-- <a href="stock_in.php">
                            <i class="fa-solid fa-inbox"></i>
                            <?php echo $stock_in ?>
                        </a> -->
                        <button class="btn btn-sm btn-info" onclick="window.location.href = 'stock_in.php'"
                            style="color: white;">
                            <i class="fa-solid fa-inbox"></i>
                            <?php echo $stock_in ?>
                        </button>**
                    </p>
                    <!-- <p style="color:green;">
                        <i class="fa-solid fa-check"></i>
                        Have QTY = The product can stock out following the maximum quantity in
                        stock.
                    </p> -->
                </div>
            </div>
            <div class="card-body">

                <table class="table  table-borderless">
                    <thead>
                        <tr class="text-center table-warning">
                            <th><?php echo $product_code ?></th>
                            <th><?php echo $product_name?></th>
                            <th><?php echo $unit ?></th>
                            <th><?php echo $color ?></th>
                            <th><?php echo $hands ?></th>
                            <th><?php echo $size  ?></th>
                            <th><?php echo 'Cost Price'  ?></th>
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
                                        onclick="decrementQty()">-</button>
                                    <span class="input-group-text" id="qtyValue"></span>
                                    <button type="button" class="btn btn-outline-secondary" onclick="incrementQty()"
                                        id="incrementBtn">+</button>
                                </div>
                            </td>

                            <td>
                                <input class="form-control text-end" id="total_price" style="background:#fff8e4;"
                                    readonly></input>
                            </td>

                            <td style="white-space: nowrap;">
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
                        <tr>
                            <th class="text-end" colspan="7">MEMO :</th>
                            <td colspan="4">
                                <textarea type="text" class="form-control" id="memo" name="memo"></textarea>
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



                    <!-- <th colspan="8">ได้ตรวจสอบจำนวน และรายละเอียดต่างๆเรียบร้อยแล้ว</th> -->
                </table>
            </div>
            <div class="card-footer text-end">
                <button type="button" class="btn btn-success btn-lg" data-mdb-ripple-init onclick="submitStockOut()"><i
                        class="fa-solid fa-floppy-disk"></i> Submit</button>
            </div>
        </div>
    </div>
</body>

</html>

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

// Add event listener to the dropdown menu
stockToOption.addEventListener('change', function() {
    // Check if the selected option is "Other"
    if (stockToOption.value == 'Other') {
        // If "Other" is selected, show the input field
        otherInput.style.display = 'block';
    } else {
        // If any other option is selected, hide the input field
        otherInput.style.display = 'none';
    }
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


            if (stockQuantity > 0) {
                qtyInput.text(stockQuantity);
                incrementButton.prop('disabled', false);

                var totalPrice = (stockQuantity * productCost).toFixed(2);
                totalPrice = parseFloat(totalPrice).toLocaleString(); // Calculate the total price

                $('#total_price').val(totalPrice);
                $('#selectedProductCost').val(productCost);
                $('#product_id').val(productID);

            } else if (productQTY == 0) {
                qtyInput.html('<b style="color: red;">out of stock</b>');
                incrementButton.prop('disabled', true);
                $('#selectedProductCost').val(productCost);
                $('#product_id').val(productID);
            }

            // Handle stock quantity and product cost
            /*    if (stockQuantity <= 0) {
                   qtyInput.html('<b style="color: red;">Sold out</b>');
                   incrementButton.prop('disabled', true);
               } else {
                   qtyInput.text(stockQuantity);
                   incrementButton.prop('disabled', false);
               } */

            // Set the max attribute of the quantity span to the stock quantity
            qtyInput.attr('max', stockQuantity);
        },
        error: function() {
            console.error('Error fetching stock quantity and product cost.');
        }
    });
}

// Function to decrement quantity value
function decrementQty() {
    var qtyValue = parseInt($('#qtyValue').text());
    if (qtyValue > 0) {
        $('#qtyValue').text(qtyValue - 1);
        updateTotalPrice(); // Call updateTotalPrice after decrementing quantity
    }
}


// Function to increment quantity value
function incrementQty() {
    var qtyValue = parseInt($('#qtyValue').text());
    var maxQuantity = parseInt($('#qtyValue').attr('max'));
    if (qtyValue < maxQuantity) {
        $('#qtyValue').text(qtyValue + 1);
        updateTotalPrice(); // Call updateTotalPrice after incrementing quantity
    }
}


// Function to update total price
function updateTotalPrice() {
    var qtyValue = parseInt($('#qtyValue').text());
    var productCost = parseFloat($('#selectedProductCost').val()); // Get the product cost
    var totalPrice = (qtyValue * productCost).toFixed(2);
    totalPrice = parseFloat(totalPrice).toLocaleString(); // Calculate the total price

    $('#total_price').val(totalPrice); // Update the total price element
}


// Get the radio buttons
var saleRadio = document.getElementById('flexRadioDefault1');
var takeOutRadio = document.getElementById('flexRadioDefault2');
var saleSampleRadio = document.getElementById('flexRadioDefault3');

// Get the select container
var selectContainerSale = document.getElementById('selectContainerSale');
var selectContainerTakeOut = document.getElementById('selectContainerTakeOut');

// Function to toggle select container visibility
function toggleSelectContainer() {
    if (takeOutRadio.checked) {
        selectContainerTakeOut.style.display = 'table-cell'; // Show select container
    } else {
        selectContainerTakeOut.style.display = 'none'; // Hide select container
    }
    if (saleRadio.checked) {
        selectContainerSale.style.display = 'table-cell'; // Show select container
    } else {
        selectContainerSale.style.display = 'none'; // Hide select container
    }
    if (saleSampleRadio.checked) {
        selectContainerSale.style.display = 'none'; // Hide select container
        selectContainerTakeOut.style.display = 'none'; // Hide select container
    }
}

// Call the function initially
toggleSelectContainer();

// Add event listener to the radio buttons
saleRadio.addEventListener('change', toggleSelectContainer);
takeOutRadio.addEventListener('change', toggleSelectContainer);
saleSampleRadio.addEventListener('change', toggleSelectContainer);

function submitStockOut() {

    /* สำหรับเช็คดรอปดาว */

    // Get the radio inputs
    var saleRadio = document.getElementById("flexRadioDefault1");
    var takeOutRadio = document.getElementById("flexRadioDefault2");
    var saleSampleRadio = document.getElementById('flexRadioDefault3');
    var paidOption = $('#paidOption').val();
    var store = $('#store').val();
    var stockToOption = $('#stockToOption').val();
    var customerName = $('#cusname').val(); // Get the value of the customer name input
    var date = $('#dateStockOut').val(); // Get the value of the customer name input
    var otherInput = $('#otherInput').val();

    var reasons_submit; // Define reasons_submit variable

    // Check if the sale radio is checked
    if (saleRadio.checked) {
        reasons_submit = 'sale,' + store + ',' + paidOption + ',' +
            customerName; // Update reasons_submit for 'sale' reason
    }
    // Check if the take out radio is checked
    else if (takeOutRadio.checked) {
        if (stockToOption == 'SAKABA') {
            reasons_submit = 'out to,' + stockToOption; // Update reasons_submit for 'take_out' reason
        } else {
            reasons_submit = 'out to,' + otherInput;
        }
    } else if (saleSampleRadio.checked) {
        reasons_submit = 'sale sample';
    } else {
        Swal.fire({
            title: 'ERROR',
            text: 'Please select stock out to',
            icon: 'error',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
        return false;
    }
    /* สำหรับเช็คดรอปดาว */



    var mgCode = $('#MG_code').val();
    var productID = $('#product_id').val();
    var productCode = $('#product').val();
    var productName = $('#selectedProductName').val();
    var productCost = $('#selectedProductCost').val();
    var productTotal = $('#total_price').val();
    var memo = $('#memo').val();
    var qtyValue = parseInt($('#qtyValue').text());
    console.log(reasons_submit, qtyValue);

    var data = {
        mgCode: mgCode,
        productID: productID,
        productCode: productCode,
        productName: productName,
        productCost: productCost,
        productTotal: productTotal,
        memo: memo,
        qtyValue: qtyValue,
        reasons: reasons_submit,
        date: date
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
                    url: 'stock_out_submit.php',
                    method: 'POST',
                    data: data,
                    success: function(response) {
                        // จัดการข้อมูลหลังจากที่ส่งไปยังเซิร์ฟเวอร์สำเร็จ
                        // Handle success response
                        Swal.fire('Success!', 'Form submitted successfully', 'success')
                            .then((result) => {
                                location.reload();
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