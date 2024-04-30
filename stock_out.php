<?php
include('header.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Good request</title>
</head>
<style>
input {
    border: 2px solid red;
    padding: 5px;
    outline: none;
}

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
    $stmt_code = $pdo->query("SELECT DISTINCT s_product_code FROM stock");
    $productNames_code = $stmt_code->fetchAll(PDO::FETCH_COLUMN); 
    // Fetch product names from the database
    $stmt_color = $pdo->query("SELECT DISTINCT s_color FROM stock");
    $productNames_color = $stmt_color->fetchAll(PDO::FETCH_COLUMN);       
    // Fetch product names from the database
    $stmt_size = $pdo->query("SELECT DISTINCT s_size FROM stock");
    $productNames_size = $stmt_size->fetchAll(PDO::FETCH_COLUMN);    
    // Fetch product names from the database
    $stmt_hands = $pdo->query("SELECT DISTINCT s_hands FROM stock");
    $productNames_hands = $stmt_hands->fetchAll(PDO::FETCH_COLUMN);   
    
?>
    <div class="container-fluid">
        <div class="card  border border-secondary text-center m-5">
            <div class="card-header ">
                <table class="table table-borderless">
                    <tr>
                        <th class="text-center" colspan="6">
                            <!-- <h3 class="card-title">SHIPPO ASAHI MOULDS(THAILAND) CO.,LTD.</h3> -->
                            <h4 class="card-title"><button type="button" class="btn btn-primary btn-lg w-50"
                                    data-mdb-ripple-init data-mdb-ripple-color="dark">
                                    <p5><?php echo $request ?></p5>
                                </button>
                            </h4>
                        </th>
                    </tr>
                    <tr>
                        <th colspan="3">
                            <h5><?php echo $mat_goods ?></h5>
                        </th>
                        <th class="text-end" colspan="3">
                            <h5><?php echo "Date: " . date("d-m-y") ;?></h5>
                        </th>
                    </tr>
                    <tr>
                        <th colspan="6">
                            <button type="button" class="btn btn-secondary btn-rounded btn-lg" data-mdb-ripple-init>
                                <?php echo $department. ' : '. $row['u_deparment'] ?>
                            </button>
                        </th>
                    </tr>
                </table>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr class="text-center table-primary">
                            <th>No.</th>
                            <th><?php echo $product_code ?></th>
                            <th><?php echo $product_name?></th>
                            <th><?php echo $unit ?></th>
                            <th><?php echo $color ?></th>
                            <th><?php echo $hands ?></th>
                            <th><?php echo $size  ?></th>
                            <th><?php echo $qty ?></th>
                            <th><?php echo $target  ?></th>
                            <th><?php echo $reset  ?></th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider table-divider-color">
                        <?php for ($i = 1; $i <= 5; $i++) : ?>

                        <tr>
                            <th scope="row"><?php echo $i; ?></th>
                            <td> <input class="form-control" type="text" id="product" name="product_<?php echo $i; ?>"
                                    list="product_names" onchange="validateInput(this)">
                                <!-- Populate datalist with product names -->
                                <datalist id="product_names">
                                    <?php foreach ($productNames_code as $productName_code): ?>
                                    <option value="<?php echo $productName_code; ?>">
                                        <?php endforeach; ?>
                                </datalist>
                            </td>
                            <!-- Replace the input field with a readonly input -->
                            <td><input type="text" class="form-control" id="selectedProductName"
                                    name="productName_<?php echo $i; ?>" style="background:#fff8e4;" readonly></td>
                            <td><input type="text" class="form-control" id="selectedProductUnit"
                                    name="productUnit_<?php echo $i; ?>" style="background:#fff8e4;" readonly></td>
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
                                <select class="form-select" name="productTarget_<?php echo $i; ?>"
                                    aria-label="Default select example" style="width: max-content;" id="target_select">
                                    <option selected><?php echo $target; ?></option>
                                    <option value="1">
                                        <span class="badge rounded-pill badge-warning">For Sale</span>
                                    </option>
                                    <option value="2">
                                        <span class="badge rounded-pill badge-warning">For Customer</span>
                                    </option>
                                </select>
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
                                    onclick="resetInput()"><i class="fas fa-eraser"></i></button>
                                <button type="button" class="btn btn-primary btn-floating" data-mdb-ripple-init><i
                                        class="fa-solid fa-check"></i></button>
                            </td>
                        </tr>
                        <?php endfor; ?>
                        <tr>
                            <td colspan="10" class="text-center">
                                <button type="button" class="btn btn-primary" id="addRowBtn"><i
                                        class="fa-solid fa-plus"></i> Add Row</button>

                                <button type="submit" class="btn btn-danger"><i class="fa-solid fa-minus"></i> Delete
                                    Row</button>
                            </td>
                        </tr>

                    </tbody>



                    <!-- <th colspan="8">ได้ตรวจสอบจำนวน และรายละเอียดต่างๆเรียบร้อยแล้ว</th> -->
                </table>
                <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th colspan="3">
                            <td>
                                <div class="d-flex justify-content-end">
                                    <input class="form-check-input" type="checkbox" id="check_recheck" value=""
                                        aria-label="..." />
                                    <span class="rounded-pill badge-warning">
                                        <h6>ได้ตรวจสอบจำนวน
                                            และรายละเอียดต่างๆเรียบร้อยแล้ว</h6>
                                    </span>
                                </div>
                            </td>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="3">
                                <div class="input-group mb-3">
                                    <button class="btn btn-secondary btn-lg" type="button"><?php echo $REQUEST_NAME ?>
                                        :</button>
                                    <select class="form-select">
                                        <option selected>Select an option</option>
                                        <option value="1">Dear</option>
                                        <option value="2">Show</option>
                                    </select>
                                </div>
                            </th>
                            <th class="text-end">
                                <div class="input-group mb-3">
                                    <button class="btn btn-secondary btn-lg" type="button"><?php echo $APPROVED_BY ?>
                                        :</button>
                                    <select class="form-select">
                                        <option selected>Select an option</option>
                                        <option value="1">Dear</option>
                                        <option value="2">Show</option>
                                    </select>
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="3">
                                <div class="input-group mb-3">
                                    <button class="btn btn-secondary btn-lg" type="button"><?php echo $STORE_KEEPER ?>
                                        :</button>
                                    <select class="form-select">
                                        <option selected>Select an option</option>
                                        <option value="1">Dear</option>
                                        <option value="2">Show</option>
                                    </select>
                                </div>
                            </th>
                            <th class="text-end">
                                <div class="input-group mb-3">
                                    <button class="btn btn-secondary btn-lg"
                                        type="button"><?php echo $GOODS_RECEIVED_BY ?> :</button>
                                    <select class="form-select">
                                        <option selected>Select an option</option>
                                        <option value="1">Dear</option>
                                        <option value="2">Show</option>
                                    </select>
                                </div>
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="card-footer text-end">
                <button type="button" class="btn btn-success" data-mdb-ripple-init>Submit</button>
            </div>
        </div>
    </div>
</body>

</html>

<script>
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
</script>