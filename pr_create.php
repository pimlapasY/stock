<?php include('connect.php');
$currentDate = date("ymd");

// Select pr_product_id and pr_qty from pr table based on pr_id
$stmt_select_pr = $pdo->prepare("SELECT COUNT(pr_id) as Count FROM pr");
$stmt_select_pr->execute();

$row = $stmt_select_pr->fetch(PDO::FETCH_ASSOC);
$count = $row ? $row['Count'] : 0;

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
    $stmt_mg = $pdo->query("SELECT DISTINCT o_mg_code FROM stockout WHERE o_return IS NULL");
    $mg_code = $stmt_mg->fetchAll(PDO::FETCH_COLUMN);  
    // SQL query to select the specified columns from the stockout table
    $sql = "SELECT o_mg_code, o_product_name
    FROM stockout 
    WHERE o_return IS NULL";

    // Prepare and execute the query
    $stmt = $pdo->query($sql);

    // Fetch all results as an associative array
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    <title>PR Management</title>
</head>

<body>
    <div class="d-flex flex-wrap">
        <?php include('navbar.php'); ?>
        <div class="container pt-5 col-10">
            <div class="d-flex justify-content-start p-2">
                <h1 id="head_list">
                    <?php
                echo '<i class="fa-solid fa-file-circle-plus fa-xl"></i> ' . $pr_add;
                ?><br>

                </h1>
                <div class="ms-2">
                    <span class="badge bg-success">Purchase</span>
                </div>
            </div>
            <hr>
            <div class="alert alert-success" role="alert" id="alertSuccess" hidden>
                Sure! You can create a PR, and you can successfully purchase this product.
            </div>
            <div class="alert alert-danger" role="alert" id="alertError" hidden>
                No data available : Please register for the product. <a href="register.php">Register</a>
            </div>
            <div class="alert alert-warning" role="alert" id="alertFillData" hidden>
                Check the following details before creating a Purchase Requisition (PR). Color, Hand or Size.
            </div>
            <input type="text" class="form-control" id="product_id" hidden>

            <div class="d-flex flex-column justify-content-center w-50 mx-auto">

                <div class="mb-3">
                    <label for="prCode" class="form-label">PR Code:</label><br>
                    <span class="badge text-bg-info"><?php echo 'PR' . $currentDate . $count ?></span>

                    <input type="text" class="form-control badge-info" id="prCode" readonly
                        value="<?php echo 'PR' . $currentDate . $count ?>" hidden>
                </div>
                <div class="mb-3">
                    <label for="product" class="form-label">Product Code:</label>
                    <input class="form-control" type="text" id="product" name="product" list="product_names"
                        onchange="validateInput(this)">
                    <!-- Populate datalist with product names -->
                    <datalist id="product_names">
                        <?php foreach ($productNames_code as $productName_code): ?>
                        <option value="<?php echo $productName_code; ?>">
                            <?php endforeach; ?>
                    </datalist>
                </div>
                <div class="mb-3">
                    <label for="selectedProductName" class="form-label">Product Name:</label>
                    <input type="text" class="form-control badge-warning" id="selectedProductName" name="productName"
                        readonly>
                </div>
                <div class="mb-3">
                    <label for="selectedProductUnit" class="form-label">Unit:</label>
                    <input type="text" class="form-control badge-warning" id="selectedProductUnit" name="productUnit"
                        readonly>
                </div>
                <div class="mb-3">
                    <label for="colorInput" class="form-label">Color:</label>
                    <input class="form-control" type="text" id="colorInput" name="productColor"
                        list="product_names_color" onchange="validateInput(this)">
                    <datalist id="product_names_color">
                        <?php foreach ($productNames_color as $productName_color): ?>
                        <option value="<?php echo $productName_color; ?>">
                            <?php endforeach; ?>
                    </datalist>
                </div>
                <div class="mb-3">
                    <label for="handInput" class="form-label">Hand:</label>
                    <input class="form-control" type="text" id="handInput" name="productHand" list="product_hand"
                        onchange="validateInput(this)">
                    <datalist id="product_hand">
                        <?php foreach ($productNames_hands as $productName_hand): ?>
                        <option value="<?php echo $productName_hand; ?>">
                            <?php endforeach; ?>
                    </datalist>
                </div>
                <div class="mb-3">
                    <label for="sizeInput" class="form-label">Size:</label>
                    <input class="form-control" type="text" id="sizeInput" name="productSize" list="product_size"
                        onchange="validateInput(this)">
                    <datalist id="product_size">
                        <?php foreach ($productNames_size as $productName_size): ?>
                        <option value="<?php echo $productName_size; ?>">
                            <?php endforeach; ?>
                    </datalist>
                </div>
                <div class="mb-3">
                    <label for="qtyValueNum" class="form-label">QTY:</label>
                    <input type="number" id="qtyValueNum" class="form-control" min="1" value="1">
                </div>
                <div class="mb-3">
                    <label for="selectedProductCost" class="form-label">Cost Price:</label>
                    <input class="form-control text-end badge-warning" type="text" id="selectedProductCost" readonly>
                </div>

                <div class="mb-3 d-flex justify-content-end">
                    <button class="btn btn-outline-warning" onclick="resetInput()">
                        RESET
                    </button>&nbsp;
                    <button class="btn btn-info" disabled id="createPR" onclick="submitPR()">
                        CREATE
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<script>
function submitPR() {
    var productID = $('#product_id').val();
    var productCode = $('#product').val();
    var productName = $('#selectedProductName').val();
    var prCode = $('#prCode').val();
    var prQty = $('#qtyValueNum').val();

    var data = {
        productID: productID,
        productCode: productCode,
        prCode: prCode,
        productName: productName,
        prQty: prQty
    };

    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to create PR?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, submit it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'pr_create_submit.php',
                method: 'POST',
                data: data,
                success: function(response) {
                    // Handle success response
                    Swal.fire('Success!', 'Form submitted successfully', 'success').then((
                        result) => {
                        if (result.isConfirmed) {
                            window.location.href =
                                'pr_management.php'; // Redirect to pr_management.php
                        }
                    });
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    Swal.fire('Error!', 'Failed to submit form', 'error');
                }
            });
        } else {
            console.log('cancel');
        }
    });
}

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
});

$('#product, #colorInput, #sizeInput, #handInput, #qtyValueNum').on('change', function() {
    updateQuantityInput();
});

function updateQuantityInput() {
    var selectedProductCode = $('#product').val();
    var selectedColor = $('#colorInput').val();
    var selectedSize = $('#sizeInput').val();
    var selectedHand = $('#handInput').val();
    var total_price = $('#total_price').val();
    var qtyValueNum = $('#qtyValueNum'); // Assuming the element has an ID of 'qtyValueNum'
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
                //qtyValueNum.prop('disabled', true);
                //incrementButton.prop('disabled', true);
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
                $('#createPR').prop('disabled', false);
                $('#alertError').prop('hidden', true);
                $('#alertFillData').prop('hidden', true);
                $('#alertSuccess').prop('hidden', false);


                qtyValueNum.prop('readonly', false);


                //var totalPrice = (stockQuantity * productCost).toFixed(2);
                var totalPrice = (1 * productCost).toFixed(2);

                totalPrice = parseFloat(totalPrice).toLocaleString(); // Calculate the total price

                $('#total_price').val(totalPrice);
                $('#selectedProductCost').val(productCost);
                $('#product_id').val(productID);
                if (stockQuantity > 0) {
                    $('#total_price').val(totalPrice * parseInt($('#qtyValueNum').val()));
                    $('#currentQTY').val(stockQuantity);
                    $('#totalQTY').val(stockQuantity + parseInt($('#qtyValueNum').val()));
                } else {
                    $('#total_price').val(productCost);
                    $('#currentQTY').val();
                    $('#totalQTY').val(stockQuantity + parseInt($('#qtyValueNum').val()));

                }
            } else if (selectedColor == '' || selectedSize == '' || selectedHand == '') {
                $('#alertFillData').prop('hidden', false);
                $('#alertSuccess').prop('hidden', true);
                $('#alertError').prop('hidden', true);
                $('#createPR').prop('disabled', true);

            } else {
                $('#alertFillData').prop('hidden', true);
                $('#alertError').prop('hidden', false);
                $('#alertSuccess').prop('hidden', true);
                $('#createPR').prop('disabled', true);

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

function resetInput() {
    var inputs = document.querySelectorAll(
        " #currentQTY, #memo, #totalQTY, #product_id, #product, #selectedProductName, #myInput, #selectedProductUnit, #colorInput, #sizeInput, #handInput, #total_price, #selectedProductCost"
    );


    // Reset the value of the input fields
    inputs.forEach(function(input) {
        input.value = "";
    });

    // Reset the quantity value to 0
    $('#qtyValueNum').val(1);
}
</script>