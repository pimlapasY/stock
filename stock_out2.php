<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Out</title>

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
        $stmt = $pdo->prepare("SELECT COUNT(*) AS count FROM stockout WHERE o_mg_code LIKE CONCAT('M', :dateNow, '%')");
        $stmt->bindParam(':dateNow', $dateNow);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $count = $row['count'];

        // Fetch store IDs and names from the database
        $stmt_store = $pdo->query("SELECT st_id, st_name FROM store WHERE st_id != 1");
        $store_options = $stmt_store->fetchAll(PDO::FETCH_ASSOC);

        // Construct MG_CODE
        $MG_CODE = 'M' . $dateNow . str_pad($count + 1, 2, '0', STR_PAD_LEFT);
        ?>

        <div class="container-fluid pt-5 col-10 mt-5 pb-5">
            <div class="card w-75 mx-auto ">
                <div class="card-header">
                    <table class="table table-borderless">
                        <tr>
                            <th class="text-start" colspan="6">
                                <!-- <h3 class="card-title">SHIPPO ASAHI MOULDS(THAILAND) CO.,LTD.</h3> -->
                                <h1>
                                    <i class="fa-solid fa-money-bill-transfer"></i> Stock Out
                                </h1>
                            </th>
                        </tr>
                        <tr>
                            <td class="text-start" style="width: 150px; text-transform: uppercase;">
                                <!-- Large -->
                                <span class="badge bg-warning text-dark">
                                    <a class="nav-link link-light" href="stock_samt.php">
                                        <i class="fa-solid fa-plus"></i>&nbsp; <?php echo $stockList ?></a>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-start">
                                <span class="badge bg-warning text-dark">
                                    <i class="fa-solid fa-hashtag"></i>
                                    MG CODE
                                </span>
                            </th>
                            <td>
                                <input type="text" class="form-control badge-success w-25" id="MG_code" name="MG_code"
                                    value="<?php echo $MG_CODE ?>" readonly>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-start">
                                <span class="badge bg-warning text-dark">
                                    <i class="fa-solid fa-calendar-days"></i>
                                    DATE
                                </span>
                            </th>
                            <td class="text-start">
                                <input class="form-control w-25" type="date" value="<?php echo date('Y-m-d'); ?>"
                                    id="dateStockOut">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-start" style="text-transform: uppercase;">
                                <span class="badge bg-warning text-dark">
                                    <i class="fa-solid fa-right-from-bracket"></i>
                                    <?php echo $stock_out ?>
                                </span>
                                <!-- Default checkbox -->
                            </td>

                            <td class="d-flex justify-content-start">
                                <!-- Default radio -->
                                <div class="form-check me-3">
                                    <input class="form-check-input" type="radio" name="flexRadioDefault"
                                        id="flexRadioDefault1" required>
                                    <label class="form-check-label" for="flexRadioDefault1"><?php echo $sale ?></label>
                                </div>
                                <div class="form-check me-3">
                                    <input class="form-check-input" type="radio" name="flexRadioDefault"
                                        id="flexRadioDefault2">
                                    <label class="form-check-label" for="flexRadioDefault2">
                                        <?php echo $take_out ?></label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="flexRadioDefault"
                                        id="flexRadioDefault3">
                                    <label class="form-check-label" for="flexRadioDefault3">
                                        <?php echo $saleSample ?></label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-start"></td>
                            <td class="text-start" id="selectContainerSale" style="display:none;">
                                <!-- Add your list select here -->
                                <div class="input-group mb-3">
                                    <button class="btn btn-outline-success" type="store"><?php echo 'From' ?></button>
                                    <select class="form-select" id="store" style="text-transform: uppercase;">
                                        <option value="1">SAMT (main)</option>
                                        <?php foreach ($store_options as $store_option): ?>
                                            <option value="<?php echo htmlspecialchars($store_option['st_id']); ?>">
                                                <?php echo htmlspecialchars($store_option['st_id']) . ' - ' . htmlspecialchars($store_option['st_name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="input-group mb-3">
                                    <button class="btn btn-outline-warning"
                                        type="paidOption"><?php echo $paid_by ?></button>
                                    <select class="form-select" id="paidOption">
                                        <option selected disabled value=''><?php echo $choose ?>...</option>
                                        <option value="1">Cash</option>
                                        <option value="2">QR</option>
                                        <option value="3">Shopify</option>
                                        <option value="4">Lazada</option>
                                        <option value="5">Shopee</option>
                                        <option value="99">Other</option>
                                    </select>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text btn btn-outline-warning"
                                        id="basic-addon1"><?php echo $cus_name ?></span>
                                    <input type="text" class="form-control" aria-label="Username" id="cusname"
                                        aria-describedby="basic-addon1" />
                                </div>
                            </td>
                            <td class="text-start" id="selectContainerTakeOut" style="display:none;">
                                <div class="input-group mb-3">
                                    <button class="btn btn-outline-warning" type="stockToOption">To</button>
                                    <select class="form-select" id="stockToOption">
                                        <option value="" disabled selected>Select a store</option>
                                        <?php foreach ($store_options as $store_option): ?>
                                            <option value="<?php echo htmlspecialchars($store_option['st_id']); ?>">
                                                <?php echo htmlspecialchars($store_option['st_id']) . ' - ' . htmlspecialchars($store_option['st_name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <input type="text" class="form-control" id="otherInput" style="display: none;"
                                    placeholder="Enter Other">
                            </td>
                        </tr>


                    </table>
                    <!--    <div class="text-start">
                    <p>
                        ** <i class="fa-solid fa-rectangle-xmark"></i>
                        No data available : Please register for the product. &nbsp;
                        <i class="fa-solid fa-arrow-right"></i>&nbsp;

                        <button class="btn btn-sm btn-success" onclick="window.location.href = 'register.php'"
                            style="color: white;">
                            <i class="fa-solid fa-circle-plus"></i>
                            <?php echo $register ?>
                        </button>**
                    </p>
                    <p>
                        ** <i class="fa-solid fa-sack-xmark"></i>
                        Out of stock : Please stock more of this product. &nbsp;
                        <i class="fa-solid fa-arrow-right"></i> &nbsp;

                        <button class="btn btn-sm btn-info" onclick="window.location.href = 'stock_in.php'"
                            style="color: white;">
                            <i class="fa-solid fa-inbox"></i>
                            <?php echo $stock_in ?>
                        </button>**
                    </p>

                </div> -->
                </div>
                <div class="card-body">
                    <div class="alert alert-warning" id="qtyValueText" hidden>
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <?php echo $alertNoStock ?>
                    </div>
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
                    <form id="myForm">
                        <table class="table w-75 mx-auto">
                            <tbody class="">
                                <tr>
                                    <th><?php echo $product_code ?></th>
                                    <!-- ไอดี product จาก product(Master) -->
                                    <input type="text" class="form-control" id="product_id" hidden>
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
                                    <th><?php echo $product_name ?></th>
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
                                    <th><?php echo $options2_label  ?></th>
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
                                    <th><?php echo $options3_label ?></th>
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
                                    <th><?php echo $qty ?></th>
                                    <td>
                                        <!--  <div class="input-group">
                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="decrementQty()">-</button>
                                    
                                    <button type="button" class="btn btn-outline-secondary" onclick="incrementQty()"
                                        id="incrementBtn">+</button>
                                </div> -->

                                        <input type="number" min="1" id="qtyValueNum" onchange="updateTotalPrice()"
                                            class="form-control">
                                    </td>
                                </tr>
                                <!-- <tr>
                                    <th><?php echo $salePrice ?></th>
                                    <td>
                                        <input class="form-control text-end badge-warning" type="text"
                                            id="selectedProductCost" readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo $salePrice . '(Vat)' ?></th>
                                    <td>
                                        <input class="form-control text-end badge-warning" type="text"
                                            id="selectedProductCostVat" readonly>
                                    </td>
                                </tr> -->
                                <tr>
                                    <th>
                                        <?php echo $salePrice ?>

                                    </th>
                                    <td>
                                        <input class="form-control text-end badge-danger" type="text"
                                            id="selectedProductSale" onchange="calculateTotalSale()">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-start" style="text-transform: uppercase;">
                                        <span class="badge bg-success">
                                            <i class="fa-solid fa-tags"></i>
                                            <?php echo $discount ?>
                                        </span>
                                        <!-- Default checkbox -->
                                    </td>
                                    <td>
                                        <div class="d-flex" id="selectedCalculateTotalSale">
                                            <select name="disType" id="disType" class="form-select me-3"
                                                onchange="calculateTotalSale()">
                                                <option value="99"><?php echo $optionNone; ?></option>
                                                <option value="1"><?php echo $optionPerItem; ?></option>
                                                <option value="2"><?php echo $optionTotal; ?></option>
                                                <option value="3"><?php echo $optionPercentage; ?></option>
                                            </select>
                                            <input type="number" id="inputDiscount" class="form-control"
                                                onchange="calculateTotalSale()" hidden>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo $salePrice . ' - ' . $discount ?></th>
                                    <td>
                                        <input class="form-control text-end badge-warning" type="text"
                                            id="salePerDiscount" readonly>
                                    </td>
                                </tr>
                                <!--  <tr>
                                    <th><?php echo $salePrice . '(Vat)' ?></th>
                                    <td>
                                        <input class="form-control text-end badge-warning" type="text"
                                            id="selectedProductSaleVat" readonly>
                                    </td>
                                </tr> -->
                                <!-- <tr>
                                    <th><?php echo $total_cost ?></th>
                                    <td>
                                        <input class="form-control text-end badge-warning" id="total_price" readonly>
                                        </input>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo $total_cost . '(Vat)' ?></th>
                                    <td>
                                        <input class="form-control text-end badge-warning" id="total_price_vat"
                                            readonly>
                                        </input>
                                    </td>
                                </tr> -->
                                <tr hidden>
                                    <th><?php echo $total_sale ?></th>
                                    <td>
                                        <input class="form-control text-end badge-info" id="total_sale" readonly>
                                        </input>
                                    </td>
                                </tr>
                                <tr hidden>
                                    <th><?php echo $total_sale . '(Vat)' ?></th>
                                    <td>
                                        <input class="form-control text-end badge-info" id="total_sale_vat" readonly>
                                        </input>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo $total_sale ?></th>
                                    <td>
                                        <input class="form-control text-end badge-success" id="total_sale_dis" readonly>
                                        </input>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo 'Vat (%)' ?></th>
                                    <td>
                                        <div class="d-flex">
                                            <input class="form-control text-end badge-danger w-25 me-3" id="vat"
                                                onchange="calculateTotalSale()">
                                            </input>
                                            <input class="form-control text-end badge-success w-75" id="vat_amount"
                                                readonly>
                                            </input>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo $total_sale . '(Vat) - ' . $discount ?></th>
                                    <td>
                                        <input class="form-control text-end badge-success" id="total_sale_dis_vat"
                                            readonly>
                                        </input>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo $memo ?></th>
                                    <td>
                                        <textarea type="text" class="form-control" id="memo" name="memo"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="10" class="text-center">
                                        <button type="button" class="btn btn-primary" id="addRowBtn"><i
                                                class="fa-solid fa-plus"></i> Add Row</button>

                                        <button type="submit" class="btn btn-danger"><i class="fa-solid fa-minus"></i>
                                            Delete
                                            Row</button>
                                    </td>
                                </tr>
                            </tbody>
                            <!-- <th colspan="8">ได้ตรวจสอบจำนวน และรายละเอียดต่างๆเรียบร้อยแล้ว</th> -->
                        </table>
                    </form>
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
<script src="stock_out2.js"></script>

</html>