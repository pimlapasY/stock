<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>

<body>
    <div class="d-flex flex-wrap">
        <?php include('navbar.php'); ?>
        <div class="container-fluid pt-5  mt-5 col-10">
            <h1 class="mt-5">
                <i class="fa-solid fa-file-lines fa-xl"></i> <?php echo $register ?>
            </h1>
            <br>
            <hr>
            <div class="">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#" id="productTab"
                            style="font-size: 20px;">
                            <i class="fa-solid fa-box fa-lg"></i> <?php echo $product ?></a>
                    </li>
                    <!-- Line break after the first list item -->
                    <li class="nav-item">
                        <a class="nav-link" href="#" id="supplierTab" style="font-size: 20px;">
                            <i class="fa-solid fa-user fa-lg"></i> <?php echo $supplier ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" id="storeTab" style="font-size: 20px;">
                            <i class="fa-solid fa-store"></i> <?php echo $store ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" id="collectionTab" style="font-size: 20px;">
                            <i class="fa-solid fa-bookmark"></i> <?php echo $collection; ?></a>
                    </li>
                </ul>
            </div>
            <br>
            <div class="d-flex justify-content-center">
                <div id="tabContent">
                    <?php include("reg_form_product.php"); ?>
                    <!-- Include form_product.php content by default -->
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<script>
    $(document).ready(function() {
        // Add click event listeners to tab links
        $('#productTab').click(function(e) {
            e.preventDefault(); // Prevent default link behavior
            $('#supplierTab').removeClass('active');
            $('#storeTab').removeClass('active');
            $('#collectionTab').removeClass('active');
            $(this).addClass('active');
            $('#tabContent').load('reg_form_product.php'); // Load form_product.php content via AJAX
        });

        $('#supplierTab').click(function(e) {
            e.preventDefault(); // Prevent default link behavior
            $('#productTab').removeClass('active');
            $('#storeTab').removeClass('active');
            $('#collectionTab').removeClass('active');
            $(this).addClass('active');
            $('#tabContent').load('reg_form_supplier.php'); // Load form_supplier.php content via AJAX
        });

        $('#storeTab').click(function(e) {
            e.preventDefault(); // Prevent default link behavior
            $('#productTab').removeClass('active');
            $('#supplierTab').removeClass('active');
            $('#collectionTab').removeClass('active');
            $(this).addClass('active');
            $('#tabContent').load('reg_form_store.php'); // Load form_store.php content via AJAX
        });

        $('#collectionTab').click(function(e) {
            e.preventDefault(); // Prevent default link behavior
            $('#productTab').removeClass('active');
            $('#supplierTab').removeClass('active');
            $('#storeTab').removeClass('active');
            $(this).addClass('active');
            $('#tabContent').load('reg_form_collection.php'); // Load form_store.php content via AJAX
        });
    });
</script>