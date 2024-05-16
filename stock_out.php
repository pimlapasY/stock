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
<?php
include('connect.php');
 $dateNow = date('ymd');
 $stmt = $pdo->prepare("SELECT COUNT(*) AS count FROM stockout WHERE o_mg_code LIKE CONCAT('MG', :dateNow, '%')");
 $stmt->bindParam(':dateNow', $dateNow);
 $stmt->execute();
 $row = $stmt->fetch(PDO::FETCH_ASSOC);
 $count = $row['count'];

 // Construct MG_CODE
  $MG_CODE = 'M' . $dateNow . str_pad($count + 1, STR_PAD_LEFT);

?>

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
                                <a class="nav-link link-light" href="list.php">
                                    <i class="fa-solid fa-plus"></i>&nbsp; <?php echo $stockList ?></a>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-start" style="text-transform: uppercase;">
                            <span class="badge bg-warning text-dark">
                                <?php echo $stock_out ?>
                            </span>
                            <!-- Default checkbox -->
                        </td>

                        <td class="d-flex justify-content-start">
                            <!-- Default radio -->
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="flexRadioDefault"
                                    id="flexRadioDefault1" required>
                                <label class="form-check-label" for="flexRadioDefault1"><?php echo $sale ?></label>
                            </div>&nbsp;&nbsp;&nbsp;
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="flexRadioDefault"
                                    id="flexRadioDefault2">
                                <label class="form-check-label" for="flexRadioDefault2"> <?php echo $take_out ?></label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-start"></td>
                        <td class="text-start" id="selectContainerSale" style="display:none;">
                            <!-- Add your list select here -->
                            <div class="input-group mb-3">
                                <button class="btn btn-outline-secondary"
                                    type="paidOption"><?php echo $paid_by ?></button>
                                <select class="form-select" id="paidOption">
                                    <option selected><?php echo $choose ?>...</option>
                                    <option value="1">Cash</option>
                                    <option value="2">QR</option>
                                    <option value="3">Shopify</option>
                                </select>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1"><?php echo $cus_name ?></span>
                                <input type="text" class="form-control" aria-label="Username"
                                    aria-describedby="basic-addon1" />
                            </div>
                        </td>
                        <td class="text-start" id="selectContainerTakeOut" style="display:none;">
                            <div class="input-group mb-3">
                                <button class="btn btn-outline-secondary" type="stockToOption">To</button>
                                <select class="form-select" id="stockToOption">
                                    <option selected><?php echo $choose ?>...</option>
                                    <option value="1">SAKABA</option>
                                    <option value="2">Sale Sample</option>
                                    <option value="3">Other</option>
                                </select>
                            </div>
                        </td>
                        <td>
                            <input type="text" class="form-control" id="otherInput" style="display: none;"
                                placeholder="Enter Other">

                        </td>
                    </tr>
                </table>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr class="text-center table-warning">
                            <th>No.</th>
                            <th><?php echo 'MG CODE' ?></th>
                            <th><?php echo $product_code ?></th>
                            <th><?php echo $product_name?></th>
                            <th><?php echo $unit ?></th>
                            <th><?php echo $color ?></th>
                            <th><?php echo $hands ?></th>
                            <th><?php echo $size  ?></th>
                            <th><?php echo $qty ?></th>
                            <th><?php echo 'Total price'  ?></th>
                            <th><?php echo 'Memo'  ?></th>
                            <th><?php echo $reset  ?></th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider table-divider-color">
                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                        <tr>
                            <th scope="row">
                                <?php echo $i; ?>
                            </th>
                            <td>
                                <input type="text" class="form-control" id="MG_code" name="MG_code<?php echo $i; ?>"
                                    value="<?php echo $MG_CODE.$i ?>" style="width:120px;" readonly>
                            </td>
                            <td>
                                <input class="form-control" type="text" id="product" name="product_<?php echo $i; ?>"
                                    list="product_names" onchange="validateInput(this)">
                                <!-- Populate datalist with product names -->
                                <datalist id="product_names">
                                    <?php foreach ($productNames_code as $productName_code): ?>
                                    <option value="<?php echo $productName_code; ?>">
                                        <?php endforeach; ?>
                                </datalist>
                            </td>
                            <!-- Replace the input field with a readonly input -->
                            <td>
                                <input type="text" class="form-control" id="selectedProductName"
                                    name="productName_<?php echo $i; ?>" style="background:#fff8e4;" readonly>
                            </td>
                            <td>
                                <input type="text" class="form-control" id="selectedProductUnit"
                                    name="productUnit_<?php echo $i; ?>" style="background:#fff8e4;" readonly>
                            </td>
                            <td>
                                <input class="form-control" type="text" id="colorInput"
                                    name="productColor_<?php echo $i; ?>" list="product_names_color"
                                    onchange="validateInput(this)">
                                <datalist id="product_names_color">
                                    <?php foreach ($productNames_color as $productName_color): ?>
                                    <option value="<?php echo $productName_color; ?>">
                                        <?php endforeach; ?>
                                </datalist>
                            </td>
                            <td>
                                <input class="form-control" type="text" id="handInput"
                                    name="productHand_<?php echo $i; ?>" list="product_hand"
                                    onchange="validateInput(this)">
                                <datalist id="product_hand">
                                    <?php foreach ($productNames_hands as $productName_hand): ?>
                                    <option value="<?php echo $productName_hand; ?>">
                                        <?php endforeach; ?>
                                </datalist>
                            </td>
                            <td>
                                <input class="form-control" type="text" id="sizeInput"
                                    name="productSize_<?php echo $i; ?>" list="product_size"
                                    onchange="validateInput(this)">
                                <datalist id="product_size">
                                    <?php foreach ($productNames_size as $productName_size): ?>
                                    <option value="<?php echo $productName_size; ?>">
                                        <?php endforeach; ?>
                                </datalist>
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
                                <input type="text" class="form-control" id="total_price"
                                    name="total_price<?php echo $i; ?>" style="background:#fff8e4;" readonly>
                            </td>
                            <td>
                                <input type="text" class="form-control" id="memo" name="memo_<?php echo $i; ?>">
                            </td>
                            <td style="white-space: nowrap;">
                                <script>
                                function resetInput() {
                                    var inputs = document.querySelectorAll(
                                        "#product, #selectedProductName, #myInput, #selectedProductUnit, #colorInput, #sizeInput, #handInput"
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
                                    onclick="resetInput()"><i class="fa-solid fa-minus"></i></button>
                            </td>
                        </tr>
                        <?php endfor; ?>
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
                <button type="button" class="btn btn-success btn-lg" data-mdb-ripple-init><i
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

// Add event listener to the dropdown menu
stockToOption.addEventListener('change', function() {
    // Check if the selected option is "Other"
    if (stockToOption.value == '3') {
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

// Function to update quantity input based on product, color, and size
function updateQuantityInput() {
    var selectedProductCode = $('#product').val();
    var selectedColor = $('#colorInput').val();
    var selectedSize = $('#sizeInput').val();
    var selectedHand = $('#handInput').val();
    var qtyInput = $('#qtyValue');
    var incrementButton = $('#incrementBtn');

    // AJAX call to get the stock quantity of the selected product, color, and size
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
            var stockQuantity = parseInt(response);

            // Disable increment button if stock is not available or quantity is already at maximum
            if (stockQuantity <= 0) {
                qtyInput.html('<b style="color: red;">sold out</b>');
                incrementButton.prop('disabled', true);
            } else if (stockQuantity > 0) {
                // Set the default quantity value to the maximum stock quantity
                qtyInput.text(stockQuantity);
                incrementButton.prop('disabled', false);
            } else {
                qtyInput.html('<b style="color: orange;">No Data</b>');
                incrementButton.prop('disabled', true);
            }

            // Set the max attribute of the quantity span to the stock quantity
            qtyInput.attr('max', stockQuantity);
        },
        error: function() {
            console.error('Error fetching stock quantity.');
        }
    });
}
// Function to decrement quantity value
function decrementQty() {
    var qtyValue = parseInt($('#qtyValue').text());
    if (qtyValue > 0) {
        $('#qtyValue').text(qtyValue - 1);
    }
}

// Function to increment quantity value
function incrementQty() {
    var qtyValue = parseInt($('#qtyValue').text());
    var maxQuantity = parseInt($('#qtyValue').attr('max'));
    if (qtyValue < maxQuantity) {
        $('#qtyValue').text(qtyValue + 1);
    }
}

// Get the radio buttons
var saleRadio = document.getElementById('flexRadioDefault1');
var takeOutRadio = document.getElementById('flexRadioDefault2');
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
}

// Call the function initially
toggleSelectContainer();

// Add event listener to the radio buttons
saleRadio.addEventListener('change', toggleSelectContainer);
takeOutRadio.addEventListener('change', toggleSelectContainer);
</script>