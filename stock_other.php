<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<style>
    .highlight {
        background-color: yellow;
        font-weight: bold;
    }
</style>

<body>

    <?php
    include('navbar.php');
    try {
        // Fetch store names from the database
        $stmt_store = $pdo->query("SELECT st_name FROM store WHERE st_id != 1 AND st_hide IS NULL");
        $store_options = $stmt_store->fetchAll(PDO::FETCH_COLUMN);
        /* -------------------------------------------------- */
        // สร้างคำสั่ง SQL เพื่อแปลง s_qty เป็นจำนวนเต็มและคำนวณผลรวมจาก stock
        $sql_stock_samt = "SELECT SUM(CAST(s_qty AS UNSIGNED)) AS total_quantity FROM stock";
        $stmt_stock_samt = $pdo->prepare($sql_stock_samt); // เตรียมคำสั่ง SQL
        $stmt_stock_samt->execute(); // รันคำสั่ง SQL
        $stockSamt = $stmt_stock_samt->fetch(PDO::FETCH_ASSOC); // ดึงผลลัพธ์

        /* -------------------------------------------------- */

        // สร้างคำสั่ง SQL เพื่อแปลง sub_qty เป็นจำนวนเต็มและคำนวณผลรวมจาก sub_stock
        $sql_stock_other = "SELECT SUM(CAST(sub_qty AS UNSIGNED)) AS total_quantity_other FROM sub_stock";
        $stmt_stock_other = $pdo->prepare($sql_stock_other); // เตรียมคำสั่ง SQL
        $stmt_stock_other->execute(); // รันคำสั่ง SQL
        $stockOther = $stmt_stock_other->fetch(PDO::FETCH_ASSOC); // ดึงผลลัพธ์

        /* -------------------------------------- */

        // คำนวณส่วนต่างระหว่าง stock และ sub_stock
        $diffStockSamt = ($stockSamt['total_quantity'] ?? 0) - ($stockOther['total_quantity_other'] ?? 0);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    } ?>
    <div class="table-responsive">

        <div class="container-fluid pt-5 mt-5 col-12">
            <h1 id="head_list mt-5">
                <?php
                echo ' <i class="fa-solid fa-boxes-stacked"></i> Stock List';
                ?>
            </h1>
            <hr>
            <div class="d-flex justify-content-start">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <?php
                        echo '<a class="nav-link' . ($currentPage == 'stock_list.php' ? ' active' : '') . '"href="stock_list.php" id="productTab"
                    style="font-size: 20px;">';
                        echo ' <span class="badge text-bg-secondary rounded-pill">' .   $stockSamt['total_quantity']     . '</span>'

                        ?>
                        <i class="fa-solid fa-box fa-lg"></i> <?php echo $allStore ?></a>
                    </li>
                    <!-- Line break after the first list item -->
                    <?php
                    echo '<a class="nav-link' . ($currentPage == 'stock_samt.php' ? ' active' : '') . '" href="stock_samt.php" id="samtTab" style="font-size: 20px;">';
                    echo ' <span class="badge text-bg-secondary rounded-pill">' . $diffStockSamt  . '</span>'
                    ?>
                    <i class="fa-solid fa-store fa-lg"></i> <?php echo $samtStore ?></a>
                    </li>
                    <!-- Line break after the first list item -->
                    <li class="nav-item">
                        <?php
                        echo '<a class="nav-link' . ($currentPage == 'stock_other.php' ? ' active' : '') . '" href="stock_other.php" id="supplierTab" style="font-size: 20px;">';
                        echo ' <span class="badge text-bg-danger rounded-pill">' .   $stockOther['total_quantity_other']     . '</span>'

                        ?>
                        <i class="fa-solid fa-store fa-lg"></i> <?php echo $otherStore ?></a>
                    </li>
                </ul>
            </div>
            <div class="d-flex justify-content-between mt-3 mb-3" style="align-items: center;">
                <div class="d-flex justify-content-start">
                    <?php $st_id = isset($_POST['st_id']) ? $_POST['st_id'] : 'all'; // Get the selected store ID 
                    ?>
                    <form method="POST" id="storeForm">
                        <div>
                            <select class="form-select badge-danger" id="stockToOption" name="st_id"
                                onchange="document.getElementById('storeForm').submit();">
                                <option value="all" <?php echo ($st_id == 'all' ? 'selected' : ''); ?>>All</option>
                                <?php foreach ($store_options as $store_option): ?>
                                    <option value="<?php echo htmlspecialchars($store_option); ?>"
                                        <?php echo ($st_id == $store_option ? 'selected' : ''); ?>>
                                        <?php echo htmlspecialchars($store_option); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </form>

                    <a href="register.php" class="btn btn-success ms-3"><i class="fa-solid fa-plus"></i>
                        <?php echo $register ?></a>&nbsp;
                    <button type="button" class="btn btn-outline-warning preview-btn-stockOut">
                        <i class="fa-solid fa-cart-flatbed"></i> <?php echo $stock_out; ?>
                    </button>&nbsp;
                    <button class="btn btn-outline-info preview-btn-pr">
                        <i class="fa-solid fa-clipboard-list"></i> <?php echo $pr_add; ?>
                    </button>
                </div>



                <div class="d-flex justify-content-end w-50">
                    <div class="input-group p-3">
                        <input id="searchInput" type="search" class="form-control rounded"
                            placeholder="<?php echo $search; ?>" aria-label="Search" aria-describedby="search-addon" />
                        <button id="btnSearch" type="button" class="btn btn-primary" onclick="searchTable()">
                            <?php echo $search; ?>
                        </button>
                    </div>
                </div>

            </div>

            <div class="">
                <table id="productTable" class="table table-sm table-bordered table-hover">
                    <thead class="table-danger text-center">
                        <tr style="vertical-align: middle;">
                            <th><input type="checkbox" id="checkAll" class="form-check-input"></th>
                            <th rowspan="2"><?php echo $num; ?></th>
                            <th rowspan="2"><?php echo $productCode; ?></th>
                            <th rowspan="2"><?php echo $collection; ?></th>
                            <th rowspan="2"><?php echo $productName; ?></th>
                            <th rowspan="2"><?php echo $options1_label; ?></th>
                            <th rowspan="2"><?php echo $options2_label; ?></th>
                            <th rowspan="2"><?php echo $options3_label; ?></th>
                            <th rowspan="2"><?php echo $costPrice; ?></th>
                            <th rowspan="2"><?php echo $salePrice; ?></th>
                            <th rowspan="2"><?php echo  $salePrice . ' (vat%)'; ?></th>
                            <th rowspan="2"><?php echo $qty; ?></th>
                            <th rowspan="2"><?php echo $other; ?></th> <!-- สมมติว่าคุณมีตัวแปรสำหรับ "Other stock" -->
                            <!-- <th class="text-center" colspan="2"><?php echo $store; ?></th> -->
                        </tr>
                    </thead>
                    <tbody class="table-group-divider table-divider-color">
                        <?php



                        // Start building the SQL query
                        $sql = " SELECT p.*, sub.*, store.st_name
    FROM sub_stock sub
    LEFT JOIN product p ON sub.sub_product_id = p.p_product_id
    LEFT JOIN store ON sub.sub_location = store.st_id
    WHERE store.st_hide IS NULL";

                        // Conditionally add the WHERE clause if a specific store is selected
                        if ($st_id != 'all') {
                            $sql .= " AND store.st_name = :st_id";
                        }

                        $sql .= " GROUP BY sub.sub_id, sub.sub_product_id, sub.sub_location, p.p_product_code, p.p_product_name, p.p_hands, p.p_color, 
            FIELD(p.p_size, 'SS', 'S', 'M', 'L', 'XL', 'XXL'), p.p_unit, p.p_collection, p.p_cost_price, p.p_sale_price;";

                        // Prepare the statement
                        $stmt = $pdo->prepare($sql);

                        // Bind the store ID parameter if it's not 'all'
                        if ($st_id != 'all') {
                            $stmt->bindParam(':st_id', $st_id, PDO::PARAM_STR); // Bind the store ID
                        }

                        // Execute the statement
                        $stmt->execute();
                        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        // Loop through products and output each row
                        foreach ($products as $index => $product) {
                            echo "<tr data-id='" . htmlspecialchars($product['p_product_id']) . "'>";

                            echo "<td class='text-center' style='vertical-align: middle;'>";

                            echo '<div class="input-group">';
                            echo '<div class="input-group-text">';
                            echo "<input class='form-check-input' type='checkbox' name='selected_ids[]' value='" . htmlspecialchars($product['sub_id']) . "' id='checkbox_" . htmlspecialchars($product['sub_id']) . "' onchange='toggleInput(this)' />";
                            echo '</div>';
                            echo "<input class='form-control' min='1' max='" . htmlspecialchars($product['sub_qty']) . "' type='number' id='input_" . htmlspecialchars($product['sub_id']) . "' style='display: none; width:80px' />";
                            echo '</div>';

                            echo "</td>";

                            echo "<td>" . ($index + 1) . "</td>"; // Display No starting from 1
                            echo "<td>" . htmlspecialchars($product['p_product_code']) . "</td>";
                            echo "<td>" . htmlspecialchars($product['p_collection']) . "</td>";
                            echo "<td>" . htmlspecialchars($product['p_product_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($product['p_hands']) . "</td>";
                            echo "<td>" . htmlspecialchars($product['p_color']) . "</td>";
                            echo "<td>" . htmlspecialchars($product['p_size']) . "</td>";
                            echo "<td class='text-end'>" . number_format($product['p_cost_price'], 2) . "</td>";
                            echo "<td class='text-end'>" . number_format($product['p_sale_price'], 2) . "</td>";
                            echo "<td class='text-end'>" . number_format(($product['p_sale_price'] * $product['p_vat'] / 100) + $product['p_sale_price'], 2) . "</td>";
                            //echo "<td class='text-end' style='color: " . ($product['s_qty'] == 0 ? "red" : "black") . ";'>" . htmlspecialchars($product['s_qty']) . "</td>";
                            echo "<td class='text-end' style='color: " . ($product['sub_qty'] <= 0 ? "black; background:pink;" : "green; background:#E5F9E5;") . "'>" . $product['sub_qty'] . "</td>";

                            echo "<td>" . $product['st_name'] . "</td>";

                            echo "</tr>";
                        }

                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header text-bg-light">
                    <h5 class="modal-title" id="previewModalLabel">
                        Product Details(Others)
                        <span class="badge" id="addTitle"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" id="updateForm" hidden>
                    <div class="d-flex justify-content-start w-50">
                        <div class="input-group ms-3 mb-3">
                            <span class="input-group-text" id="basic-addon1"><b>Date</b></span>
                            <input class="form-control" type="date" value="" name="date_create" id="currentDate"
                                aria-describedby="basic-addon1" />
                        </div>
                        <script>
                            document.getElementById('currentDate').valueAsDate = new Date();
                        </script>
                    </div>
                    <!-- Default radio -->
                    <div id="previewStockOut" class="p-3">
                        <h5 class="modal-title">Stock out for: <span class="text-danger">*require</span></h5>
                        <div class="form-check me-3 ms-3">
                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1"
                                value="sale" required checked>
                            <label class="form-check-label" for="flexRadioDefault1"><?php echo $sale ?></label>
                        </div>
                        <div id="saleDetails">
                            <div class="input-group mb-3 ms-5 w-75">
                                <button class="btn btn-dark" type="paidOption"><?php echo $paid_by ?></button>
                                <select class="form-select" id="paidOption">
                                    <option selected><?php echo $choose ?>...</option>
                                    <option value="1">Cash</option>
                                    <option value="2">QR</option>
                                </select>
                            </div>
                            <div class="input-group mb-4 ms-5 w-75">
                                <span class="input-group-text  text-bg-dark"
                                    id="basic-addon1"><?php echo $cus_name ?></span>
                                <input type="text" class="form-control" aria-label="Username" id="cusname"
                                    aria-describedby="basic-addon1" />
                            </div>
                        </div>
                    </div>
                    <div class="ms-3">
                        <label for="memo">Memo: </label>
                        <textarea class="form-control w-50" name="memo" id="memo" rows="2"></textarea>
                    </div>
                    <br>
                    <div class="table-responsive">
                        <table id="productTable" class="table table-bordered table-hover">
                            <thead class="table-dark text-center">
                                <tr style="vertical-align: middle;">
                                    <th><?php echo $num; ?></th>
                                    <th><?php echo $store; ?></th>
                                    <th><?php echo $productCode; ?></th>
                                    <th><?php echo $productName; ?></th>
                                    <th><?php echo $options1_label; ?></th>
                                    <th><?php echo $options2_label; ?></th>
                                    <th><?php echo $options3_label; ?></th>
                                    <th id="priceLabel1"><?php echo $salePrice; ?></th>
                                    <th id="priceLabel2"><?php echo $salePrice . '(vat%)'; ?></th>
                                    <th><?php echo $qty; ?></th>
                                    <th id="priceLabel3"><?php echo $total_sale ?></th>
                                </tr>
                            </thead>
                            <tbody id='productDetails'>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal" aria-label="Close">
                            Close
                        </button>
                        <button type="button" class="btn btn-success" id="submitProductDetails">Submit</button>
                    </div>
                </div>
            </div>
        </div>
        <script src="stock_other.js"></script>
</body>

</html>