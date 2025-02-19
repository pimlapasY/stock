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
    <div class="d-flex flex-wrap mb-5">
        <?php include('navbar.php'); ?>
        <div class="container pt-5 mt-5 col-10">
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
                <i class="fa-solid fa-square-check"></i> <?php echo $alertFillSuccess ?>
            </div>
            <div class="alert alert-danger" role="alert" id="alertError" hidden>
                <i class="fa-solid fa-square-xmark"></i> <?php echo $alertNodata ?> <a
                    href="register.php"><?php echo $register ?></a>
            </div>
            <div class="alert alert-info" role="alert" id="alertFillData" hidden>
                <i class="fa-solid fa-circle-info"></i> <?php echo $alertFilldata ?>
            </div>
            <input type="text" class="form-control" id="product_id" hidden>

            <div class="d-flex flex-column justify-content-center w-50 mx-auto">

                <div class="mb-3">
                    <label for="prCode" class="form-label">
                        <th><?php echo $prCode; ?></th>
                    </label><br>
                    <span class="badge text-bg-info"><?php echo 'PR' . $currentDate . $count ?></span>

                    <input type="text" class="form-control badge-info" id="prCode" readonly
                        value="<?php echo 'PR' . $currentDate . $count ?>" hidden>
                </div>
                <div class="mb-3">
                    <label for="product" class="form-label"><?php echo $productCode ?></label>
                    <input class="form-control" type="search" id="product" name="product" list="product_names"
                        onchange="validateInput(this)">
                    <!-- Populate datalist with product names -->
                    <datalist id="product_names">
                        <?php foreach ($productNames_code as $productName_code): ?>
                        <option value="<?php echo $productName_code; ?>">
                            <?php endforeach; ?>
                    </datalist>
                </div>
                <div class="mb-3">
                    <label for="selectedProductName" class="form-label"><?php echo $productName ?></label>
                    <input type="text" class="form-control badge-warning" id="selectedProductName" name="productName"
                        readonly>
                </div>
                <div class="mb-3">
                    <label for="selectedProductUnit" class="form-label"><?php echo $unit  ?></label>
                    <input type="text" class="form-control badge-warning" id="selectedProductUnit" name="productUnit"
                        readonly>
                </div>
                <div class="mb-3">
                    <label for="colorInput" class="form-label"><?php echo $options1_label ?></label>
                    <input class="form-control" type="search" id="colorInput" name="productColor"
                        list="product_names_color" onchange="validateInput(this)">
                    <datalist id="product_names_color">
                        <?php foreach ($productNames_color as $productName_color): ?>
                        <option value="<?php echo $productName_color; ?>">
                            <?php endforeach; ?>
                    </datalist>
                </div>

                <div class="mb-3">
                    <label for="handInput" class="form-label"><?php echo $options2_label ?></label>
                    <input class="form-control" type="search" id="handInput" name="productHand" list="product_hand"
                        onchange="validateInput(this)">
                    <datalist id="product_hand">
                        <?php foreach ($productNames_hands as $productName_hand): ?>
                        <option value="<?php echo $productName_hand; ?>">
                            <?php endforeach; ?>
                    </datalist>
                </div>
                <div class="mb-3">
                    <label for="sizeInput" class="form-label"><?php echo $options3_label ?></label>
                    <input class="form-control" type="search" id="sizeInput" name="productSize" list="product_size"
                        onchange="validateInput(this)">
                    <datalist id="product_size">
                        <?php foreach ($productNames_size as $productName_size): ?>
                        <option value="<?php echo $productName_size; ?>">
                            <?php endforeach; ?>
                    </datalist>
                </div>
                <div class="mb-3">
                    <label for="qtyValueNum" class="form-label"><?php echo $qty ?></label>
                    <input type="number" id="qtyValueNum" class="form-control" min="1" value="1">
                </div>
                <div class="mb-3">
                    <label for="selectedProductCost" class="form-label"><?php echo $costPrice ?></label>
                    <input class="form-control text-end badge-warning" type="text" id="selectedProductCost" readonly>
                </div>
                <div class="mb-3">
                    <label for="total_price" class="form-label"><?php echo $total_cost ?></label>
                    <input class="form-control text-end badge-info" type="text" id="total_price" readonly>
                </div>
                <div class="mb-3">
                    <label for="TotalVat" class="form-label"><?php echo $total_cost.'(vat%)' ?></label>
                    <input class="form-control text-end badge-info" type="text" id="TotalVat" readonly>
                </div>
                <div class="mb-3 d-flex justify-content-between">
                    <button class="btn btn-outline-warning" onclick="resetInput()">
                        <?php echo $reset ?>
                    </button>&nbsp;
                    <button class="btn btn-info" disabled id="createPR" onclick="submitPR()">
                        <?php echo $pr_add ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script src="pr_create.js"></script>
</body>

</html>