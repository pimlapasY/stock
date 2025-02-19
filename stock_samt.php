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
    <div class="d-flex flex-wrap">
        <?php include('navbar.php');
     
     try {
        // Fetch store IDs and names from the database
        $stmt_store = $pdo->query("SELECT st_id, st_name FROM store WHERE st_id != 1");
        $store_options = $stmt_store->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
       
    ?>
        <div class="container-fluid mt-5 pt-5 col-10">
            <h1 id="head_list">
                <?php
            echo ' <i class="fa-solid fa-boxes-stacked"></i> Stock List'; 
            ?>
            </h1>
            <hr>
            <div class="d-flex justify-content-start">
                <ul class="nav nav-tabs">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <?php 
                echo '<a class="nav-link' . ($currentPage == 'stock_list.php' ? ' active' : '') . '"href="stock_list.php" id="productTab"
                    style="font-size: 20px;">';
                ?>
                            <i class="fa-solid fa-box fa-lg"></i> <?php echo $allStore ?></a>
                        </li>
                        <!-- Line break after the first list item -->
                        <?php
                echo '<a class="nav-link' . ($currentPage == 'stock_samt.php' ? ' active' : '') . '" href="stock_samt.php" id="samtTab" style="font-size: 20px;">';
                ?>
                        <i class="fa-solid fa-store fa-lg"></i> <?php echo $samtStore ?></a>
                        </li>
                        <!-- Line break after the first list item -->
                        <li class="nav-item">
                            <?php 
                echo '<a class="nav-link' . ($currentPage == 'stock_other.php' ? ' active' : '') . '" href="stock_other.php" id="supplierTab" style="font-size: 20px;">';               
                ?>
                            <i class="fa-solid fa-store fa-lg"></i> <?php echo $otherStore ?></a>
                        </li>
                    </ul>
                </ul>
            </div>

            <div class="d-flex justify-content-between m-3" style="align-items: center;">
                <div class="d-flex justify-content-start">
                    <a href="register.php" class="btn btn-success"><i
                            class="fa-solid fa-plus"></i><?php echo $register ?></a>&nbsp;
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
                <table id="productTable" class="table table-bordered table-hover">
                    <thead class="table-info text-center">
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
                            <th rowspan="2"><?php echo $salePrice.' (vat%)'; ?></th>
                            <th rowspan="2"><?php echo $qty; ?></th>
                            <th rowspan="2"><?php echo $other; ?></th> <!-- สมมติว่าคุณมีตัวแปรสำหรับ "Other stock" -->
                            <!-- <th class="text-center" colspan="2"><?php echo $store; ?></th> -->
                        </tr>
                    </thead>

                    <tbody class="table-group-divider table-divider-color">
                        <?php
         $stmt = $pdo->prepare("SELECT p.*, SUM(sub.sub_qty) AS total_sub_qty, s.s_qty, s.s_return_date
         FROM product p 
         LEFT JOIN stock s ON s.s_product_id = p.p_product_id 
         LEFT JOIN sub_stock sub ON sub.sub_product_id = p.p_product_id 
         GROUP BY p.p_product_code, p.p_product_name, p.p_hands, p.p_color, FIELD(p.p_size, 'SS', 'S', 'M', 'L', 'XL', 'XXL'), p.p_unit, p.p_collection, p.p_cost_price, p.p_sale_price
         HAVING s.s_qty != 0;");
           // Execute the statement
           $stmt->execute();
           $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

           // Loop through products and output each row
           foreach ($products as $index => $product) {
            $difference = $product['s_qty'] - $product['total_sub_qty'];
            // Check if s_return_date exists and is within 2 days from today
            $returnDate = $product['s_return_date'];
            if ($returnDate != null) {
                $returnDateTime = new DateTime($returnDate);
                $currentDateTime = new DateTime();
                $interval = $currentDateTime->diff($returnDateTime);
                $daysDiff = $interval->days;               
                // If return date is within 2 days from today, mark the row as red
                $rowColor = ($daysDiff <= 2) ? "class='table-warning'" : '';
            } else {
                $rowColor = '';
            }
            
            // กำหนดสีของข้อความและพื้นหลังตามค่าของ difference
            $textColor = ($difference <= 0) ? "red" : "green";
            $backgroundColor = "#E5F9E5";

            
           echo "<tr $rowColor data-id='" . htmlspecialchars($product['p_product_id']) . "'>";

           echo "<td class='text-center' style='vertical-align: middle;'>";
           echo '<div class="input-group"><div class="input-group-text">';
           echo "<input class='form-check-input' type='checkbox'  name='selected_ids[]' value='".htmlspecialchars($product['p_product_id'])."' id='checkbox_" . htmlspecialchars($product['p_product_id']) . "' onchange='toggleInput(this)' /> <br>";
           echo "</div><input class='form-control' min='1' max='" . $difference . "' type='number' id='input_" . htmlspecialchars($product['p_product_id']) . "' style='display: none; width: 70px;' /> </div>";
           echo "</td>";
           
           /* echo "<td>".$product['s_return_date']."</td>"; */
           echo "<td>" . ($index + 1) . "</td>"; // Display No starting from 1
           echo "<td>" . htmlspecialchars($product['p_product_code']) . "</td>";
           echo "<td>" . htmlspecialchars($product['p_collection']) . "</td>";
           echo "<td>" . htmlspecialchars($product['p_product_name']) . "</td>";
           echo "<td>" . htmlspecialchars($product['p_hands']) . "</td>";
           echo "<td>" . htmlspecialchars($product['p_color']) . "</td>";
           echo "<td>" . htmlspecialchars($product['p_size']) . "</td>";
           echo "<td class='text-end'>" . number_format($product['p_cost_price'],2) . "</td>";
           echo "<td class='text-end'>" . number_format($product['p_sale_price'],2) . "</td>";
           echo "<td class='text-end'>" . number_format(($product['p_sale_price']*$product['p_vat']/100)+$product['p_sale_price'],2) . "</td>";
           
           echo "<td class='text-end' style='color: $textColor; background: $backgroundColor;'>" . htmlspecialchars($difference) . "</td>";

          
           
           echo "<td class='text-center'>" . 
           ($product['total_sub_qty'] == null ? '' : 
           '<a href="#" class="info-icon btn btn-rounded btn-info" data-product-id="' . htmlspecialchars($product['p_product_id']) . '">
               <i class="fa-solid fa-magnifying-glass"></i> ' .'
           </a>') . 
           "</td>";
           
            
           echo "</tr>";
           
        }        
        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header text-bg-light">
                        <h5 class="modal-title" id="previewModalLabel">
                            Product Details(SAMT)
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
                        <div id="previewStockOut" style="display: none;" class="p-3">
                            <h5 class="modal-title">Stock out for: <span class="text-danger">*require</span></h5>
                            <div class="form-check me-3 ms-3">
                                <input class="form-check-input" type="radio" name="flexRadioDefault"
                                    id="flexRadioDefault1" value="sale" required>
                                <label class="form-check-label" for="flexRadioDefault1"><?php echo $sale ?></label>
                            </div>
                            <div id="saleDetails">
                                <div class="input-group mb-3 ms-5 w-75">
                                    <button class="btn btn-dark" type="paidOption"><?php echo $paid_by ?></button>
                                    <select class="form-select" id="paidOption">
                                        <option disabled selected><?php echo $choose ?>...</option>
                                        <option value="1">Cash</option>
                                        <option value="2">QR</option>
                                        <option value="3">Shopify</option>
                                    </select>
                                </div>
                                <div class="input-group mb-4 ms-5 w-75">
                                    <span class="input-group-text  text-bg-dark"
                                        id="basic-addon1"><?php echo $cus_name ?></span>
                                    <input type="text" class="form-control" aria-label="Username" id="cusname"
                                        aria-describedby="basic-addon1" />
                                </div>
                            </div>
                            <div class="form-check me-3 ms-3">
                                <input class="form-check-input" type="radio" name="flexRadioDefault"
                                    id="flexRadioDefault2" value="out to">
                                <label class="form-check-label" for="flexRadioDefault2"> <?php echo $take_out ?></label>
                            </div>
                            <div class="input-group mb-3 ms-5 w-75 mb-4" id="takeOutDetails">
                                <button class="btn btn-dark" type="button">To</button>
                                <select class="form-select" id="stockToOption">
                                    <option value="" disabled selected>Select a store</option>
                                    <?php foreach ($store_options as $store_option): ?>
                                    <option value="<?php echo htmlspecialchars($store_option['st_id']); ?>">
                                        <?php echo htmlspecialchars($store_option['st_id']) . ' - ' . htmlspecialchars($store_option['st_name']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-check ms-3">
                                <input class="form-check-input" type="radio" name="flexRadioDefault"
                                    id="flexRadioDefault3" value="sale sample">
                                <label class="form-check-label" for="flexRadioDefault3">
                                    <?php echo 'Sale sample' ?></label>
                            </div>
                        </div>
                        <div class="ms-3">
                            <label for="memo">Memo: </label>
                            <textarea class="form-control w-50" name="memo" id="memo" rows="2"></textarea>
                        </div>
                        <br>
                        <table class="table table-bordered table-hover">
                            <thead class="table-dark text-center">
                                <tr style="vertical-align: middle;">
                                    <th><?php echo $num; ?></th>
                                    <th><?php echo $productCode; ?></th>
                                    <th><?php echo $productName; ?></th>
                                    <th><?php echo $options1_label; ?></th>
                                    <th><?php echo $options2_label; ?></th>
                                    <th><?php echo $options3_label; ?></th>
                                    <th id="priceLabel"><?php echo $salePrice; ?></th>
                                    <th id="priceVatLabel"><?php echo $salePrice.'(vat%)'; ?></th>
                                    <th><?php echo $qty; ?></th>
                                    <th id="totalVatLabel"><?php echo $total_sale.'(vat%)' ?></th>
                                </tr>
                            </thead>
                            <tbody id='productDetails'>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal"
                            aria-label="Close">Close</button>
                        <button type="button" class="btn btn-success" id="submitProductDetails">Submit</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal info other stock -->
        <div class="modal fade" id="previewStockModal" tabindex="-1" role="dialog" aria-labelledby="previewProductLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header text-bg-light">
                        <h5 class="modal-title" id="previewProductLabel">
                            <i class="fa-solid fa-check-double"></i> Other Stock
                            <span class="badge" id="addTitle"></span>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <p id="modalOtherStockLabel"></p>
                            <table class="table table-bordered table-sm" id="stockModal">
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="stock_samt.js"></script>
</body>

</html>