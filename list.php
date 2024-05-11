<?php include('navbar.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List</title>
</head>

<body>
    <div class="container" style="margin-top: 150px;">
        <h1 id="head_list">
            <?php
            echo ' <i class="fa-solid fa-box fa-lg"></i> Product List'; 
            ?>
        </h1>
        <br>

        <div class="d-flex justify-content-center">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <?php 
                if(isset($_GET['page']) && $_GET['page'] === 'register_supplier') {
                    echo ' <a class="nav-link" aria-current="page" href="#productlist" id="productTab"
                    style="font-size: 20px;">';
                } else{
                    echo '<a class="nav-link active" aria-current="page" href="#productlist" id="productTab"
                    style="font-size: 20px;">';
                } 
                ?>
                    <i class="fa-solid fa-box fa-lg"></i> Product List</a>
                </li>
                <!-- Line break after the first list item -->
                <li class="nav-item">
                    <?php 
                if(isset($_GET['page']) && $_GET['page'] === 'register_supplier') {
                    echo '<a class="nav-link active" href="#suplist" id="supplierTab" style="font-size: 20px;">';
                } else{
                    echo '<a class="nav-link" href="#suplist" id="supplierTab" style="font-size: 20px;">';
                } 
                ?>
                    <i class="fa-solid fa-user fa-lg"></i> Supplier List</a>
                </li>
            </ul>
        </div>
        <br>
        <div class="d-flex justify-content-center">
            <div id="tabContent">
                <?php 
                      // Your existing code...
                    if(isset($_GET['page']) && $_GET['page'] === 'register_supplier') {
                        include("list_suppliers.php");
                    }else{
                        include("list_product.php"); 
                    }
                ?>

                <!-- Include form_product.php content by default -->
            </div>
        </div>
    </div>
</body>

</html>

<script>
// Add click event listeners to tab links
$('#productTab').click(function(e) {
    e.preventDefault();
    $('#head_list').html(
        '<i class="fa-solid fa-box fa-lg"></i> Product List');
    // Load list_product.php content via AJAX
    $('#supplierTab').removeClass('active')
    $('#productTab').addClass('active')
    $('#tabContent').load('list_product.php'); // Load list_product.php content via AJAX
});

$('#supplierTab').click(function(e) {
    e.preventDefault(); // Prevent default link behavior
    $('#head_list').html(
        '<i class="fa-solid fa-user fa-xl"></i> Suppliers List');
    $('#productTab').removeClass('active')
    $('#supplierTab').addClass('active')
    $('#tabContent').load('list_suppliers.php');
    // Load list_suppliers.php content via AJAX

});
// Redirect to the appropriate page after successful deletion
if (window.location.href.includes('page=register_supplier')) {
    $('#head_list').html(
        '<i class="fa-solid fa-user fa-xl"></i> Suppliers List');
    $('#productTab').removeClass('active')
    $('#supplierTab').addClass('active')
    $('#tabContent').load('list_suppliers.php');
} else {
    $('#head_list').html(
        '<i class="fa-solid fa-box fa-lg"></i> Product List');
    // Load list_product.php content via AJAX
    $('#supplierTab').removeClass('active')
    $('#productTab').addClass('active')
    $('#tabContent').load('list_product.php'); // Load list_product.php content via AJAX
}
</script>