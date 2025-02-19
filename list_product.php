<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productlist</title>
</head>
<?php include('connect.php'); ?>
<style>
.modal-header {
    background-color: #cfe2f3;
    /* Optionally change the text color */
}
</style>

<body>
    <div class="table-responsive">
        <div class="d-flex mb-3">
            <div class="m-0">
                <a href="register.php" class="btn btn-success"><i class="fa-solid fa-plus"></i> NEW</a>&nbsp;
                <!-- New Export CSV Button -->
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                    <i class="fa-solid fa-file-csv"></i> Import CSV
                </button>&nbsp;
                <button class="btn btn-warning" onclick="exportToCSV()">
                    <i class="fa-solid fa-file-csv"></i> Export CSV
                </button>&nbsp;
            </div>
            <div>
                <button type="button" class="btn btn-outline-primary" onclick="openPreviewModal(1)"><i
                        class="fa-solid fa-inbox"></i>
                    Stock in
                </button>&nbsp;
                <button class="btn btn-outline-info" onclick="openPreviewModal(2)">
                    <i class=" fa-solid fa-clipboard-list"></i>
                    add PR
                </button>&nbsp;
            </div>
        </div>

        <table class="table table-sm table-bordered table-hover mx-auto" style="width: 100%;">
            <!-- Table content -->
            <thead class="table-primary text-center">
                <tr style="vertical-align: middle;">
                    <th class="text-center" colspan="1"><?php echo $store; ?></th>
                    <th rowspan="2"><?php echo $num; ?></th>
                    <th rowspan="2"><?php echo $product_code; ?></th>
                    <th rowspan="2"><?php echo $collection; ?></th>
                    <th rowspan="2"><?php echo $product_name; ?></th>
                    <th rowspan="2"><?php echo $options1_label; ?></th>
                    <th rowspan="2"><?php echo $options2_label; ?></th>
                    <th rowspan="2"><?php echo $options3_label; ?></th>
                    <th rowspan="2"><?php echo $costPrice; ?></th>
                    <th rowspan="2"><?php echo $salePrice; ?></th>
                    <th rowspan="2"><?php echo $salePrice . '(vat%)'; ?></th>
                    <th rowspan="2"><?php echo $qty; ?></th>
                </tr>
                <tr>
                    <th class="text-center">SAMT</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch individual product rows
                $stmt = $pdo->prepare("SELECT p.*, IFNULL(s.s_qty, 0) AS stock_qty
                    FROM product p
                    LEFT JOIN stock s ON s.s_product_id = p.p_product_id
                    ORDER BY p.p_date_add DESC
                    ");

                // Execute the statement
                $stmt->execute();
                $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Loop through products and output each row
                foreach ($products as $index => $product) {
                    echo "<tr data-id='" . htmlspecialchars($product['p_product_id']) . "'>";
                    echo "<td class='text-center' style='vertical-align: middle;'>";
                    echo '<div class="input-group mx-auto"><div class="input-group-text">';
                    echo "<input class='form-check-input' type='checkbox' value='' id='checkbox_" . htmlspecialchars($product['p_product_id']) . "' onchange='toggleInput(this)' /> <br>";
                    echo "</div><input class='form-control ' min='1' type='number' id='input_" . htmlspecialchars($product['p_product_id']) . "' value='' style='display: none;' /> </div>";
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
                    echo "<td class='text-end' style='color: " . ($product['stock_qty'] == 0 ? "red ;background:#FCF3CF;" : "green;") . "'>" . htmlspecialchars($product['stock_qty']) . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

    </div>
    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Import CSV</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- นำเข้า excel csv -->
                    <form action="list_product_import.php" method="post" enctype="multipart/form-data">
                        <label for="csvFile">Choose CSV file:</label>
                        <input type="file" name="csvFile" class="form-control" id="csvFile" accept=".csv" required><br>
                        <button name="import" class="btn btn-primary" onclick="showLoading()">
                            Import
                            CSV
                        </button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Preview Modal -->
    <div id="previewModal" class="modal modal-xl modal fade" style="display:none; width:100%;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">
                        <i class="fa-solid fa-inbox fa-lg"></i> Preview Changes
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="margin: 50px;">
                    <!--  <div class="d-flex justify-content-start w-50">
                        <p>PR CODE : </p>&nbsp;
                        <p><?php echo date('Ymd'); ?></p>
                    </div> -->
                    <div class="d-flex justify-content-start w-50">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1"><b>Date</b></span>
                            <input class="form-control" type="date" value="" name="date_create" id="currentDate"
                                aria-describedby="basic-addon1" />
                        </div>
                        <script>
                        document.getElementById('currentDate').valueAsDate = new Date();
                        </script>

                    </div>
                    <div class="d-flex justify-content-end">
                        <h2 id="total">Total</h2>
                    </div>
                    <div class="d-flex justify-content-center">
                        <table class="table table-hover table-bordered table-sm" style="width: 100%;">
                            <!-- Table content -->
                            <thead class="text-center">
                                <tr class="table-light" style="vertical-align: middle;">
                                    <th><?php echo $num; ?></th>
                                    <th><?php echo $productCode; ?></th>
                                    <th><?php echo $productName; ?></th>
                                    <th><?php echo $options1_label; ?></th>
                                    <th><?php echo $options2_label; ?></th>
                                    <th><?php echo $options3_label; ?></th>
                                    <th><?php echo $costPrice; ?></th>
                                    <th><?php echo $costPrice . '(Vat%)'; ?></th>
                                    <th><?php echo $qty; ?></th>
                                    <th class="text-center" colspan="1">Store</th>
                                    <th>Total Price</th>
                                </tr>
                            </thead>
                            <tbody class="modal-body" id="previewBody">
                                <!-- Preview content will be inserted here -->
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-start m-3" id="checkRadio">
                        <div class="form-check me-4">
                            <input class="form-check-input" type="radio" name="reason" id="flexRadioDefault1" value="1"
                                checked>
                            <label class="form-check-label" for="flexRadioDefault1">Purchased</label>
                        </div>
                        <div class="form-check me-4">
                            <input class="form-check-input" type="radio" name="reason" id="flexRadioDefault2" value="2">
                            <label class="form-check-label" for="flexRadioDefault2">Returned</label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-start mt-3">
                        <textarea class="form-control w-50" name="memo" placeholder="memo"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <input id="statusType" hidden>
                    <button type="button" class="btn btn-secondary modal-footer" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="confirmStockInButton" onclick="confirmButton(1)"
                        hidden>Stock In</button>
                    <button type="button" class="btn btn-success" id="confirmPRButton" onclick="confirmButton(2)"
                        hidden>Add
                        PR</button>
                </div>
            </div>
        </div>
    </div>
    <script src="list_product.js"></script>
</body>

</html>