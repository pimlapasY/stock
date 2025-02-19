<?php
   include('connect.php'); 
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock In</title>
</head>


<body>
    <div class="d-flex flex-wrap">
        <?php include('navbar.php') ?>
        <div class="container pt-5 col-10 mt-5 pb-5">
            <div class="card w-75 mx-auto">
                <div class="card-header">
                    <table class="table table-borderless">
                        <tr>
                            <th class="text-start" colspan="6">
                                <h1>
                                    <i class="fa-solid fa-inbox fa-xl"></i> Stock In
                                </h1>
                            </th>
                        </tr>
                        <tr>
                            <td class="text-start" style="width: 150px; text-transform: uppercase;">
                                <!-- Large -->
                                <span class="badge bg-info text-dark">
                                    <a class="nav-link link-light" href="list.php">
                                        <i class="fa-solid fa-plus"></i>&nbsp; <?php echo $stockList ?></a>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-start">
                                <span class="badge bg-info text-dark">
                                    <i class="fa-solid fa-calendar-days"></i>
                                    DATE
                                </span>
                            </th>
                            <td class="text-start">
                                <input class="form-control w-25" type="date" value="<?php echo date('Y-m-d'); ?>"
                                    id="dateStockIn">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-start" style="text-transform: uppercase;">
                                <span class="badge bg-info text-dark">
                                    <?php echo $stock_in ?>
                                </span>
                                <!-- Default checkbox -->
                            </td>

                            <td class="d-flex justify-content-start">
                                <!-- Default radio -->
                                <div class="form-check d-flex align-items-center">
                                    <input class="form-check-input" type="radio" id="check_purchased"
                                        name="check_stockIn" required>
                                    <label class="form-check-label" for="check_purchased">
                                        <?php echo 'Purchased (Storage)' ?>
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="d-flex justify-content-start">
                                <div class="form-check d-flex align-items-center">
                                    <input class="form-check-input" type="radio" id="check_returned"
                                        name="check_stockIn">
                                    <label class="form-check-label" for="check_returned">
                                        <?php echo 'Returned' ?>
                                    </label>
                                    &nbsp;&nbsp;
                                    <input class="form-control badge-info" type="search" id="mgCodeInput" name="mgCode"
                                        list="mgCodeList" onchange="validateInput(this)" placeholder="Mg Code...">
                                    <!-- Populate datalist with product names -->
                                    <datalist id="mgCodeList">
                                        <?php foreach ($results as $row): ?>
                                        <option value="<?php echo htmlspecialchars($row['o_mg_code']); ?>">
                                            <?php echo htmlspecialchars($row['o_product_name']); ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </datalist>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="card-body">
                    <div class="alert alert-success" role="alert" id="alertSuccess" hidden>
                        <i class="fa-solid fa-square-check"></i> <?php echo $alertFillSuccess ?>
                        <span id="successText"></span>
                    </div>
                    <div class="alert alert-danger" role="alert" id="alertError" hidden>
                        <i class="fa-solid fa-square-xmark"></i> <?php echo $alertNodata ?> <a
                            href="register.php"><?php echo $register ?></a>
                    </div>
                    <div class="alert alert-info" role="alert" id="alertFillData" hidden>
                        <i class="fa-solid fa-circle-info"></i> <?php echo $alertFilldata ?>
                    </div>
                    <form id="myForm">
                        <table class="table w-75 mx-auto">
                            <tbody>
                                <tr>
                                    <!-- ไอดี product จาก product(Master) -->
                                    <input type="text" class="form-control" id="product_id" hidden>
                                </tr>
                                <tr>
                                    <th><?php echo $product_code ?></th>

                                    <td>
                                        <input class="form-control" type="text" id="product" name="product"
                                            list="product_names" onchange="validateInput(this)">
                                        <!-- Populate datalist with product names -->

                                        <datalist id="product_names">
                                            <?php foreach ($productNames_code as $productName_code): ?>
                                            <option value="<?php echo $productName_code; ?>">
                                                <?php endforeach; ?>
                                        </datalist>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo $product_name?></th>

                                    <!-- Replace the input field with a readonly input -->
                                    <td>
                                        <input type="text" class="form-control badge-warning" id="selectedProductName"
                                            name="productName" readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo $unit ?></th>

                                    <td>
                                        <input type="text" class="form-control badge-warning" id="selectedProductUnit"
                                            name="productUnit" readonly>
                                    </td>
                                </tr>
                                <tr>

                                    <th><?php echo $options1_label ?></th>
                                    <td>
                                        <input class="form-control" type="text" id="colorInput" name="productColor"
                                            list="product_names_color" onchange="validateInput(this)">
                                        <datalist id="product_names_color">
                                            <?php foreach ($productNames_color as $productName_color): ?>
                                            <option value="<?php echo $productName_color; ?>">
                                                <?php endforeach; ?>
                                        </datalist>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo $options2_label ?></th>

                                    <td>
                                        <input class="form-control" type="text" id="handInput" name="productHand"
                                            list="product_hand" onchange="validateInput(this)">
                                        <datalist id="product_hand">
                                            <?php foreach ($productNames_hands as $productName_hand): ?>
                                            <option value="<?php echo $productName_hand; ?>">
                                                <?php endforeach; ?>
                                        </datalist>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo $options3_label  ?></th>
                                    <td>
                                        <input class="form-control" type="text" id="sizeInput" name="productSize"
                                            list="product_size" onchange="validateInput(this)">
                                        <datalist id="product_size">
                                            <?php foreach ($productNames_size as $productName_size): ?>
                                            <option value="<?php echo $productName_size; ?>">
                                                <?php endforeach; ?>
                                        </datalist>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo 'Cost' ?></th>
                                    <td>
                                        <input class="form-control text-end badge-warning" type="text"
                                            id="selectedProductCost" readonly>
                                        <!--  <input type="text" class="form-control" id="total_price" name="total_price"
                                style="background:#fff8e4;" readonly> -->

                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo 'Cost(Vat)' ?></th>
                                    <td>
                                        <input class="form-control text-end badge-warning" type="text"
                                            id="selectedProductCostVat" readonly>
                                        <!--  <input type="text" class="form-control" id="total_price" name="total_price"
                                style="background:#fff8e4;" readonly> -->

                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo $qty ?></th>
                                    <!-- <div class="input-group">
                                    <button type="button" class="btn btn-outline-secondary"
                                        id="decrementButton">-</button>
                                    <span class="input-group-text" id="qtyValue">1</span>
                                    <button type="button" class="btn btn-outline-secondary"
                                        id="incrementButton">+</button>
                                </div> -->
                                    <td>
                                        <input type="number" id="qtyValueNum" onchange="updateTotalPrice()"
                                            class="form-control" min="1" value="1">
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo 'Total Price'  ?></th>
                                    <td>
                                        <input class=" form-control text-end badge-info" id="total_price" readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo 'Total Price(Vat)'  ?></th>
                                    <td>
                                        <input class=" form-control text-end badge-info" id="total_priceVat" readonly>
                                    </td>
                                </tr>


                                <!--    <tr>
                            <td colspan="10" class="text-center">
                                <button type="button" class="btn btn-primary" id="addRowBtn"><i
                                        class="fa-solid fa-plus"></i> Add Row</button>

                                <button type="submit" class="btn btn-danger"><i class="fa-solid fa-minus"></i> Delete
                                    Row</button>
                            </td>
                        </tr> -->
                            </tbody>
                        </table>
                        <table class="table mx-auto table-borderless w-100">
                            <tr style="vertical-align: middle;">
                                <th class="text-end w-75">CURRENT QTY :</th>
                                <td class="text-end">
                                    <input class="form-control text-end badge-warning" type="number" id="currentQTY"
                                        readonly>
                                </td>
                            </tr>
                            <tr style="vertical-align: middle;">
                                <th class="text-end">TOTAL QTY :</th>
                                <td class="text-end">
                                    <input id="totalQTY" class="form-control text-end badge-info" type="number"
                                        readonly>
                                </td>
                            </tr>
                            <tr style="vertical-align: middle;">
                                <th class="text-end">MEMO :</th>
                                <td>
                                    <textarea type="text" class="form-control" id="memo" name="memo"></textarea>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-warning" data-mdb-ripple-init onclick="resetInput()"><i
                            class="fa-solid fa-eraser"></i> <?php echo $reset  ?></button>

                    <button type="button" class="btn btn-success" data-mdb-ripple-init onclick="submitStockin()">
                        <i class="fa-solid fa-floppy-disk"></i> Submit
                    </button>
                </div>

            </div>
        </div>
    </div>
    <script src="stock_in.js"></script>
</body>

</html>