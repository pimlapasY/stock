<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Exchange</title>

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


<body>
    <div class="d-flex flex-wrap">
        <?php include('navbar.php');

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

        $dateNow = date('ymd');
        $stmt = $pdo->prepare("SELECT COUNT(*) AS count FROM stockout WHERE o_mg_code LIKE CONCAT('EXC', :dateNow, '%')");
        $stmt->bindParam(':dateNow', $dateNow);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $count = $row['count'];

        // Fetch store IDs and names from the database
        $stmt_store = $pdo->query("SELECT st_id, st_name FROM store WHERE st_id != 1");
        $store_options = $stmt_store->fetchAll(PDO::FETCH_ASSOC);

        // Construct MG_CODE
        $EXC_CODE = 'EXC' . $dateNow . str_pad($count + 1, 2, '0', STR_PAD_LEFT);
        ?>

        <div class="container-fluid pt-5 col-12 mt-5 pb-5">
            <div class="card w-75 mx-auto ">
                <div class="card-header">
                    <table class="table table-borderless">
                        <tr>
                            <th class="text-start" colspan="6">
                                <!-- <h3 class="card-title">SHIPPO ASAHI MOULDS(THAILAND) CO.,LTD.</h3> -->
                                <h1>
                                    <i class="fa-solid fa-arrow-right-arrow-left"></i> Stock Exchange
                                </h1>
                            </th>
                        </tr>
                        <tr>
                            <th class="text-start">
                                <span class="badge bg-primary">
                                    <i class="fa-solid fa-hashtag"></i>
                                    EXC CODE
                                </span>
                            </th>
                            <td>
                                <input type="text" class="form-control badge-success w-50" id="EXC_code" name="EXC_code"
                                    value="<?php echo $EXC_CODE ?>" readonly>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-start">
                                <span class="badge bg-primary text-white">
                                    <i class="fa-solid fa-calendar-days"></i>
                                    DATE
                                </span>
                            </th>
                            <td class="text-start">
                                <input class="form-control w-50" type="date" value="<?php echo date('Y-m-d'); ?>"
                                    id="dateStockOut">
                            </td>
                        </tr>
                        </tr>
                    </table>
                </div>
                <div class="card-body">

                </div>
                <div class="card-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-warning" data-mdb-ripple-init onclick="resetInput()"><i
                            class="fa-solid fa-eraser"></i> <?php echo $reset  ?></button>
                    <button id="submitStockOutBtn" type="button" class="btn btn-success" data-mdb-ripple-init
                        onclick="submitStockOut()"><i class="fa-solid fa-floppy-disk"></i> Submit</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>