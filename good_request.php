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

.bg-input-red {
    background: #ffdbd9 !important;
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
    $stmt_code = $pdo->query("SELECT DISTINCT p_product_code FROM product");
    $products_code = $stmt_code->fetchAll(PDO::FETCH_COLUMN); 
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
    <div class="container">
        <div class="card border border-secondary text-center m-5">
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
                        <th class="text-start" colspan="3">
                            <h5><?php echo $mat_goods ?></h5>
                        </th>
                        <th class="text-end" colspan="3">
                            <h5><?php echo "Date: " . date("d-m-y") ;?></h5>
                        </th>
                    </tr>
                    <tr>
                        <th class="text-start" colspan="6">
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
                        <tr class="table-primary uppercase" style="text-align:center; white-space: nowrap;">
                            <th>No.</th>
                            <th><?php echo $product_code ?></th>
                            <th><?php echo $description ?></th>
                            <th><?php echo $qty ?></th>
                            <th><?php echo $unit ?></th>
                            <th><?php echo $purpose  ?></th>
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
                            <td>
                                <input type="text" class="form-control product-name-input badge-warning"
                                    id="p_name<?php echo $i; ?>" <?php if ($value == '') { ?> <?php } ?> readonly>
                            </td>
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
                            <td>
                                <input type="text" class="form-control unit-input badge-warning"
                                    id="unit<?php echo $i; ?>" readonly>
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
                        <!-- <tr>
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
                        </tr>
                        <tr>
                            <th colspan="3">
                                <div class="input-group mb-3">
                                    <button class="btn btn-outline-secondary" type="button" id="rq_name"
                                        data-mdb-ripple-init data-mdb-ripple-color="dark">
                                        <?php echo $REQUEST_NAME ?>
                                        :</button>
                                    <input value="<?php echo $row['u_username'] ?>" class="form-control" name="rq_name"
                                        aria-describedby="rq_name" required readonly />
                                    <div class="invalid-feedback">
                                        ** <?php echo $require ?>
                                    </div>
                                </div>
                            </th>
                            <th class="text-end">
                                <div class="input-group mb-3">
                                    <button class="btn btn-outline-secondary" type="button" id="ap_by"
                                        data-mdb-ripple-init data-mdb-ripple-color="dark">
                                        <?php echo $APPROVED_BY ?>
                                        :</button>
                                    <input class="form-control" name="ap_by" value="@everyone" aria-describedby="ap_by"
                                        disabled />
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="3">
                                <div class="input-group mb-3">
                                    <button class="btn btn-outline-secondary" type="button" id="st_keeper"
                                        data-mdb-ripple-init data-mdb-ripple-color="dark">
                                        <?php echo $STORE_KEEPER ?>
                                        :</button>
                                    <input class="form-control" name="st_keeper" aria-describedby="st_keeper"
                                        required />
                                    <div class="invalid-feedback">
                                        ** <?php echo $require ?>
                                    </div>
                                </div>
                            </th>
                            <th class="text-end">
                                <div class="input-group mb-3">
                                    <button class="btn btn-outline-secondary" type="button" id="gr_by"
                                        data-mdb-ripple-init data-mdb-ripple-color="dark">
                                        <?php echo $GOODS_RECEIVED_BY ?> :</button>
                                    <input class="form-control" name="gr_by" aria-describedby="gr_by" required />
                                    <div class="invalid-feedback">
                                        ** <?php echo $require ?>
                                    </div>
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
    row.find('#product' + index).removeClass('is-invalid').val('');
    row.find('#p_name' + index).val('');
    row.find('#qtyValue' + index).val('');
    row.find('#unit' + index).val('');
    row.find('#target' + index).val('');
}


function submitForm() {
    // Check if the required fields are empty
    var rqName = $("input[name='rq_name']").val().trim();
    var stKeeper = $("input[name='st_keeper']").val().trim();
    var grBy = $("input[name='gr_by']").val().trim();

    // Apply Bootstrap validation classes based on whether the fields are empty
    if (rqName === '') {
        $("input[name='rq_name']").removeClass('is-valid').addClass('is-invalid');
    } else {
        $("input[name='rq_name']").removeClass('is-invalid');
    }

    if (stKeeper === '') {
        $("input[name='st_keeper']").removeClass('is-valid').addClass('is-invalid');
    } else {
        $("input[name='st_keeper']").removeClass('is-invalid');
    }

    if (grBy === '') {
        $("input[name='gr_by']").removeClass('is-valid').addClass('is-invalid');
    } else {
        $("input[name='gr_by']").removeClass('is-invalid');
    }

    if (rqName === '' || stKeeper === '' || grBy === '') {
        // Show alert if any required field is empty
        Swal.fire({
            icon: 'warning',
            title: 'Empty Fields',
            text: 'Please fill in all required fields.',
        });
        return false; // Prevent form submission
    }
    // Define arrays to store form data
    var formData = [];

    // Loop through each row of inputs
    for (var i = 0; i < 5; i++) {
        // Get values of product, quantity, and unit inputs
        var productCode = $('#product' + i).val();


        if (i == 0 && productCode.trim() == '' && $('#qtyValue' + i).val().trim() == '') {
            $('#product0').removeClass('is-valid').addClass('is-invalid');

            Swal.fire({
                icon: 'warning',
                title: 'No Data',
                text: 'Please fill in both the product code and quantity.',
            });

            // Prevent form submission
            return false;
        } else {
            $('#product0').removeClass('is-invalid');
        }
        // Check if productCode has a value
        if (productCode.trim() !== '' && $('#qtyValue' + i).val().trim() !== '') {
            var qty = $('#qtyValue' + i).val();
            var name = $('#p_name' + i).val();
            var unit = $('#unit' + i).val();
            var target = $('#target' + i).val();

            // Push values into formData array
            formData.push({
                product_code: productCode,
                name: name,
                qty: qty,
                unit: unit,
                target: target
            });
        } else if (productCode.trim() == '' && $('#qtyValue' + i).val().trim() == '') {
            break;
        } else {
            if (productCode.trim() == '') {
                $('productCode').addClass('is-invalid');
                Swal.fire({
                    icon: 'warning',
                    title: 'No.' + (i + 1) + '<br>Please to reset or fill data',
                    text: 'Product code cannot be empty.',
                });
            } else if ($('#qtyValue' + i).val().trim() == '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'No.' + (i + 1) + '<br>Please to reset or fill data ',
                    text: 'Quantity cannot be empty.',
                });
            }

            // Prevent form submission
            return false;
        }
    }

    // Show SweetAlert confirmation dialog
    Swal.fire({
        title: 'Are you sure?',
        text: 'Once submitted, you will not be able to edit this request!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, submit it!',
        cancelButtonText: 'No, cancel!',
    }).then((result) => {
        if (result.isConfirmed) {
            // Proceed with form submission
            $.ajax({
                url: "g_submit_form.php",
                method: "POST",
                data: {
                    formData: formData,
                    r_department: "<?php echo $row['u_department']; ?>",
                    r_req_username: $("input[name='rq_name']").val(),
                    store_keeper: $("input[name='st_keeper']").val(),
                    r_rec_username: $("input[name='gr_by']").val(),

                },
                success: function(response) {
                    // Handle success response
                    console.log("Form submitted successfully!");
                    Swal.fire({
                        title: 'Success!',
                        text: 'REQUEST NO: ' + response,
                        icon: 'success'
                    }).then(() => {
                        console.log(response)
                        // Redirect to another page
                        window.location.href = "g_history.php";
                        // Reload the page
                        //location.reload();
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
            return false;
        }
    });
}
</script>