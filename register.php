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

            <div class="d-flex justify-content-start" style="margin-left: 300px;">
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
    const $rows = $('#optionsTable tr[hidden]');
    let currentIndex = 0;

    $('#addRowButton').click(function() {
        if (currentIndex < $rows.length) {
            $rows.eq(currentIndex).removeAttr('hidden');
            currentIndex++;
        }
        if (currentIndex > 0) {
            $('#deleteRowButton').prop('disabled', false);
        }

        // Disable the button if all rows are shown
        if (currentIndex >= $rows.length) {
            $('#addRowButton').prop('disabled', true);
        }
    });

    $('#deleteRowButton').click(function() {
        if (currentIndex > 0) {
            currentIndex--;
            $rows.eq(currentIndex).attr('hidden', true);
        }

        // Disable the delete button if no rows are visible
        if (currentIndex <= 0) {
            $('#deleteRowButton').prop('disabled', true);
        }

        // Re-enable the add button if rows can be added again
        if (currentIndex < $rows.length) {
            $('#addRowButton').prop('disabled', false);
        }
    });
});


// Add click event listeners to tab links
$('#productTab').click(function(e) {
    e.preventDefault(); // Prevent default link behavior
    $('#supplierTab').removeClass('active');
    $('#storeTab').removeClass('active');
    $(this).addClass('active');
    $('#tabContent').load('reg_form_product.php'); // Load form_product.php content via AJAX
});

$('#supplierTab').click(function(e) {
    e.preventDefault(); // Prevent default link behavior
    $('#productTab').removeClass('active');
    $('#storeTab').removeClass('active');
    $(this).addClass('active');
    $('#tabContent').load('reg_form_supplier.php'); // Load form_supplier.php content via AJAX
});

$('#storeTab').click(function(e) {
    e.preventDefault(); // Prevent default link behavior
    $('#productTab').removeClass('active');
    $('#supplierTab').removeClass('active');
    $(this).addClass('active');
    $('#tabContent').load('reg_form_store.php'); // Load form_store.php content via AJAX
});
</script>