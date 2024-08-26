<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List</title>
</head>
<style>
.input-group {
    justify-content: center;
}
</style>

<body>
    <div class="d-flex flex-wrap">
        <?php include('navbar.php'); ?>
        <div class="container pt-5 col-10">
            <h1 id="head_list">
                <?php
            echo ' <i class="fa-solid fa-box fa-lg"></i> Product List'; 
            ?>
            </h1>
            <br>

            <div class="d-flex justify-content-start">
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
                    echo '<a class="nav-link active" href="#supList" id="supplierTab" style="font-size: 20px;">';
                } else{
                    echo '<a class="nav-link" href="#supList" id="supplierTab" style="font-size: 20px;">';
                } 
                ?>
                        <i class="fa-solid fa-user fa-lg"></i> Supplier List</a>
                    </li>
                    <li class="nav-item">
                        <?php 
                if(isset($_GET['page']) && $_GET['page'] === 'register_store') {
                    echo '<a class="nav-link active" href="#storeList" id="storeTab" style="font-size: 20px;">';
                } else{
                    echo '<a class="nav-link" href="#storeList" id="storeTab" style="font-size: 20px;">';
                } 
                ?>
                        <i class="fa-solid fa-store fa-xl"></i> Store List</a>
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
                    }elseif(isset($_GET['page']) && $_GET['page'] === 'register_store'){
                        include("list_store.php");
                    }
                    else{
                        include("list_product.php"); 
                    }
                ?>

                    <!-- Include form_product.php content by default -->
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<script>
$(document).ready(function() {
    // Add click event listener to productTab
    $('#productTab').click(function(e) {
        e.preventDefault();
        $('#head_list').html('<i class="fa-solid fa-box fa-lg"></i> Product List');
        $('#supplierTab').removeClass('active');
        $('#storeTab').removeClass('active');
        $(this).addClass('active');
        $('#tabContent').load('list_product.php'); // Load list_product.php content via AJAX
        window.location.href = 'list.php'; // Reload list.php with query parameter
    });

    // Add click event listener to supplierTab
    $('#supplierTab').click(function(e) {
        e.preventDefault();
        $('#head_list').html('<i class="fa-solid fa-user fa-xl"></i> Suppliers List');
        $('#productTab').removeClass('active');
        $('#storeTab').removeClass('active');
        $(this).addClass('active');
        $('#tabContent').load('list_suppliers.php'); // Load list_suppliers.php content via AJAX
    });

    // Add click event listener to storeTab
    $('#storeTab').click(function(e) {
        e.preventDefault();
        $('#head_list').html('<i class="fa-solid fa-store fa-xl"></i> Store List');
        $('#productTab').removeClass('active');
        $('#supplierTab').removeClass('active');
        $(this).addClass('active');
        $('#tabContent').load('list_store.php'); // Load list_store.php content via AJAX
    });

    // Redirect to the appropriate page after successful deletion
    if (window.location.href.includes('page=register_supplier')) {
        $('#head_list').html('<i class="fa-solid fa-user fa-xl"></i> Suppliers List');
        $('#productTab').removeClass('active');
        $('#supplierTab').addClass('active');
        $('#tabContent').load('list_suppliers.php');
    } else if (window.location.href.includes('page=register_store')) {
        $('#head_list').html('<i class="fa-solid fa-store fa-xl"></i> Store List');
        $('#productTab').removeClass('active');
        $('#supplierTab').removeClass('active');
        $('#storeTab').addClass('active');
        $('#tabContent').load('list_store.php');
    }

});
</script>