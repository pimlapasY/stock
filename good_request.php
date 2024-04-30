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

/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* Firefox */
input[type=number] {
    -moz-appearance: textfield;
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
    $products_code = $stmt_code->fetchAll(PDO::FETCH_COLUMN); 
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
    <div class="container">
        <div class="card  border border-secondary text-center m-5">
            <div class="card-header ">
                <table class="table table-borderless">
                    <tr>
                        <th class="text-center" colspan="6">
                            <h3 class="card-title"><button type="button" class="btn btn-secondary btn-lg w-50"
                                    data-mdb-ripple-init data-mdb-ripple-color="dark">SHIPPO ASAHI MOULDS(THAILAND)
                                    CO.,LTD.</button>
                            </h3><br>
                            <h3 class="card-title" style="text-transform: uppercase;"><?php echo $request ?></h3>
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
                                <?php echo $department. ' : '. $row['u_department'] ?>
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
                            <th><?php echo $qty ?></th>
                            <th><?php echo $unit ?></th>
                            <th><?php echo $target  ?></th>
                            <th><?php echo $reset  ?></th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider table-divider-color">
                        <?php
                         for ($i = 0; $i < 5; $i++) : 
                         ?>
                        <tr>
                            <th scope="row"><?php echo $i+1; ?></th>
                            <td>
                                <input class="form-control product-input" type="text" id="product<?php echo $i; ?>"
                                    list="product_code<?php echo $i; ?>" onchange="validateInput(this)">
                                <!-- Populate datalist with product names -->
                                <datalist id="product_code<?php echo $i; ?>">
                                    <?php foreach ($products_code as $product_code_item) : ?>
                                    <option value="<?php echo $product_code_item; ?>">
                                        <?php endforeach; ?>
                                </datalist>
                            </td>
                            <!-- Replace the input field with a readonly input -->
                            <td><input type="text" class="form-control product-name-input" id="p_name<?php echo $i; ?>"
                                    style="background:#fff8e4;" readonly></td>
                            <td>
                                <div class="input-group">
                                    <button type="button" class="btn btn-outline-secondary decrement-btn"
                                        onclick="decrementQty(<?php echo $i; ?>)">-</button>
                                    <input type="number" class="input-group-text qty-value"
                                        id="qtyValue<?php echo $i; ?>">
                                    <button type="button" class="btn btn-outline-secondary increment-btn"
                                        onclick="incrementQty(<?php echo $i; ?>)">+</button>
                                </div>
                            </td>
                            <td><input type="text" class="form-control unit-input" id="unit<?php echo $i; ?>"
                                    style="background:#fff8e4;" readonly>
                            </td>
                            <td>
                                <input type="text" class="form-control target-input" id="target<?php echo $i; ?>">
                            </td>
                            <td style="white-space: nowrap;">
                                <button type="button" class="btn btn-warning btn-floating reset-btn"
                                    onclick="resetInput(<?php echo $i; ?>)"><i class="fas fa-eraser"></i></button>
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
                            <td>
                                <div class="d-flex justify-content-start">
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
                                    <input class="form-control" name="rq_name">
                                </div>
                            </th>
                            <th class="text-end">
                                <div class="input-group mb-3">
                                    <button class="btn btn-secondary btn-lg" type="button"><?php echo $APPROVED_BY ?>
                                        :</button>
                                    <input class="form-control" name="ap_by" disabled>
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="3">
                                <div class="input-group mb-3">
                                    <button class="btn btn-secondary btn-lg" type="button"><?php echo $STORE_KEEPER ?>
                                        :</button>
                                    <input class="form-control" name="st_keeper">
                                </div>
                            </th>
                            <th class="text-end">
                                <div class="input-group mb-3">
                                    <button class="btn btn-secondary btn-lg"
                                        type="button"><?php echo $GOODS_RECEIVED_BY ?> :</button>
                                    <input class="form-control" name="gr_by">
                                </div>
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="card-footer text-end">
                <button type="button" class="btn btn-success btn-lg" onclick="submitForm()">Submit</button>
            </div>
        </div>
    </div>
</body>

</html>
<!-- Add SweetAlert CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Loop through each product input field
    $('.product-input').each(function(index) {
        var productId = $(this).attr('id');
        $(this).on('input', function() {
            var selectedProductCode = $(this).val();
            console.log('Selected product code:', selectedProductCode);

            $.ajax({
                url: 'ajax_GET/get_product_name.php',
                method: 'POST',
                data: {
                    product_code: selectedProductCode
                },
                success: function(response) {
                    var parts = response.split('|');
                    var productName = parts[0];
                    var productUnit = parts[1];

                    $('#p_name' + index).val(productName);
                    $('#unit' + index).val(productUnit);
                }
            });
        });
    });
});


// Function to update quantity input based on product, color, or size changes
var minValue = 1; // Minimum value allowed

function incrementQty(index) {
    var input = $('#qtyValue' + index);
    var value = parseInt(input.val(), 10);
    input.val(isNaN(value) ? 1 : value + 1);
}

function decrementQty(index) {
    var input = $('#qtyValue' + index);
    var value = parseInt(input.val(), 10);
    input.val(isNaN(value) || value <= minValue ? minValue : value - 1);
}

function resetInput(index) {
    var row = $('#product' + index).closest('tr');
    row.find('#product' + index).val('');
    row.find('#p_name' + index).val('');
    row.find('#qtyValue' + index).val('');
    row.find('#unit' + index).val('');
    row.find('#target' + index).val('');
}


function validateInput(input) {
    if (input.value.trim() !== '') {
        $(input).removeClass('valid-input-red');
    } else {
        $(input).addClass('valid-input-red');
    }
}

function submitForm() {
    // Define arrays to store form data
    var formData = [];

    // Loop through each row of inputs
    for (var i = 0; i < 5; i++) {
        // Get values of product, quantity, and unit inputs
        var productCode = $('#product' + i).val();
        var qty = $('#qtyValue' + i).val();
        var unit = $('#unit' + i).val();

        // Push values into formData array
        formData.push({
            product_code: productCode,
            qty: qty,
            unit: unit
        });
    }

    // Show SweetAlert confirmation dialog
    Swal.fire({
        title: 'Are you sure?',
        text: 'Once submitted, you will not be able to edit this request!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, submit it!',
        cancelButtonText: 'No, cancel!',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Proceed with form submission
            $.ajax({
                url: "submit_form.php",
                method: "POST",
                data: {
                    formData: formData,
                    r_department: "<?php echo $row['u_department']; ?>",
                    r_rec_username: "<?php echo $row['u_username']; ?>"
                },
                success: function(response) {
                    // Handle success response
                    console.log("Form submitted successfully!");
                    Swal.fire({
                        title: 'Success!',
                        text: 'Your request has been submitted successfully.',
                        icon: 'success'
                    });
                    // You can perform further actions here, like resetting the form
                },
                error: function(xhr, status, error) {
                    // Handle errors
                    console.error("Error submitting form:", error);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Failed to submit the request. Please try again later.',
                        icon: 'error'
                    });
                }
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            Swal.fire('Cancelled', 'Your request has not been submitted.', 'info');
        }
    });
}
</script>