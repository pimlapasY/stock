<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACANA STOCK</title>
</head>

<body>
    <div class="d-flex flex-wrap">
        <?php include('navbar.php') ?>
        <div class="container pt-5 col-10">
            <div class="d-flex justify-content-start">
                <ul class="nav nav-tabs">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <?php 
                    echo ' <a class="nav-link' . ($currentPage == 'stock_list.php' ? ' active' : '') . '"href="stock_list.php" id="productTab" style="font-size: 20px;">'; ?>
                            <i class="fa-solid fa-box fa-lg"></i> All Store</a>
                        </li>
                        <!-- Line break after the first list item -->
                        <?php echo '<a class="nav-link' . ($currentPage == 'stock_samt.php' ? ' active' : '') . '" href="stock_samt.php" id="samtTab" style="font-size: 20px;">'; ?>
                        <i class="fa-solid fa-store fa-lg"></i> SAMT Store</a>
                        </li>
                        <!-- Line break after the first list item -->
                        <li class="nav-item">
                            <?php  echo '<a class="nav-link' . ($currentPage == 'stock_other.php' ? ' active' : '') . '" href="stock_other.php" id="supplierTab" style="font-size: 20px;">';                ?>
                            <i class="fa-solid fa-store fa-lg"></i> Other Store</a>
                        </li>
                    </ul>
                </ul>
            </div>
            <div class="d-flex justify-content-between m-3" style="align-items: center;">
                <div class="d-flex justify-content-start">
                    <a href="register.php" class="btn btn-success">
                        <i class="fa-solid fa-plus"></i> NEW
                    </a>&nbsp;
                    <a href="currently_taken.php" class="btn btn-danger">
                        <i class="fa-solid fa-database"></i> Current Taken
                    </a>&nbsp;
                    <button class="btn btn-info" disabled>
                        <i class="fa-solid fa-cart-arrow-down"></i> Part Sale
                    </button>&nbsp;
                    <button class="btn btn-info" disabled>
                        <i class="fa-solid fa-clipboard-list"></i> PR List
                    </button>
                </div>
                <div class="d-flex justify-content-end w-50">
                    <div class="input-group p-3">
                        <input id="searchInput" type="search" class="form-control rounded" placeholder="Search"
                            aria-label="Search" aria-describedby="search-addon" />
                        <button type="button" class="btn btn-primary" data-mdb-ripple-init
                            onclick="loadData(1, $('#searchInput').val())" disabled>
                            Search
                        </button>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark text-center">
                        <tr style="vertical-align: middle;">
                            <th rowspan="2">No</th>
                            <th rowspan="2"><?php echo $code; ?></th>
                            <th rowspan="2">Collection</th>
                            <th rowspan="2">Name</th>
                            <th rowspan="2">Hands</th>
                            <th rowspan="2">Color</th>
                            <th rowspan="2">Size</th>
                            <th rowspan="2">Cost price</th>
                            <th rowspan="2">Sale price</th>
                            <th rowspan="2">Total Qty</th>
                            <th class="text-center" colspan="2"><?php echo $store; ?></th>
                        </tr>
                        <tr>
                            <th>SAMT</th>
                            <th>SAKABA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
          $stmt = $pdo->prepare("
          SELECT p.*, 
                 IFNULL(SUM(sub.sub_qty), 0) AS total_sub_qty, 
                 IFNULL(s.s_qty, 0) AS stock_qty
          FROM product p
          LEFT JOIN stock s ON s.s_product_id = p.p_product_id
          LEFT JOIN sub_stock sub ON sub.sub_product_id = p.p_product_id
          GROUP BY p.p_product_id, 
                   p.p_product_code, 
                   p.p_product_name, 
                   p.p_hands, 
                   p.p_color, 
                   p.p_size,
                   p.p_unit, 
                   p.p_collection, 
                   p.p_cost_price, 
                   p.p_sale_price
      ");
      

             // Execute the statement
             $stmt->execute();
             $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

             // Loop through products and output each row
             foreach ($products as $index => $product) {

                $stockSAMT = $product['stock_qty']-$product['total_sub_qty'];
             echo "<tr data-id='" . htmlspecialchars($product['p_product_id']) . "'>";
             echo "<td>" . ($index + 1) . "</td>"; // Display No starting from 1
             echo "<td>" . htmlspecialchars($product['p_product_code']) . "</td>";
             echo "<td>" . htmlspecialchars($product['p_collection']) . "</td>";
             echo "<td>" . htmlspecialchars($product['p_product_name']) . "</td>";
             echo "<td>" . htmlspecialchars($product['p_hands']) . "</td>";
             echo "<td>" . htmlspecialchars($product['p_color']) . "</td>";
             echo "<td>" . htmlspecialchars($product['p_size']) . "</td>";
             echo "<td class='text-end'>" . number_format($product['p_cost_price']) . "</td>";
             echo "<td class='text-end'>" . number_format($product['p_sale_price']) . "</td>";
             echo "<td class='text-end' style='color: " . ($product['stock_qty'] == 0 ? "red; background:#FCF3CF;" : "black; background:#E5F9E5;") . "'>" .  htmlspecialchars($product['stock_qty']) . "</td>";
             echo "<td class='text-end' style='color: " . ($product['stock_qty'] == 0 ? "red" : "green") . ";'>" . ($stockSAMT == 0 ? '' : $stockSAMT) . "</td>";
             echo "<td class='text-end' style='color: " . ($product['total_sub_qty'] == 0 ? "red" : "green") . ";'>" . ($product['total_sub_qty'] == 0 ? '': htmlspecialchars($product['total_sub_qty']))  . "</td>";
             echo "</tr>";
             }            
            ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>