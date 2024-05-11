<?php include('navbar.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>

<body>
    <div class="container" style="margin-top: 150px;">
        <h1>
            <i class="fa-solid fa-file-lines fa-xl"></i> Register
        </h1>
        <br>

        <div class="d-flex justify-content-start" style="margin-left: 300px;">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#" id="productTab" style="font-size: 20px;">
                        <i class="fa-solid fa-box fa-lg"></i> Product</a>
                </li>
                <!-- Line break after the first list item -->
                <li class="nav-item">
                    <a class="nav-link" href="#" id="supplierTab" style="font-size: 20px;">
                        <i class="fa-solid fa-user fa-lg"></i> Supplier</a>
                </li>
            </ul>
        </div>
        <br>
        <div class="d-flex justify-content-center">
            <div id="tabContent">
                <?php include("form_product.php"); ?>
                <!-- Include form_product.php content by default -->
            </div>
        </div>
    </div>
</body>

</html>

<script>
// Add click event listeners to tab links
$('#productTab').click(function(e) {
    e.preventDefault(); // Prevent default link behavior
    $('#supplierTab').removeClass('active')
    $('#productTab').addClass('active')
    $('#tabContent').load('form_product.php'); // Load form_product.php content via AJAX
});

$('#supplierTab').click(function(e) {
    $('#productTab').removeClass('active')
    $('#supplierTab').addClass('active')
    e.preventDefault(); // Prevent default link behavior
    $('#tabContent').load('form_supplier.php'); // Load form_supplier.php content via AJAX
});
</script>