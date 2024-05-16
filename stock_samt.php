<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACANA STOCK</title>
    <?php include('connect.php') ?>
</head>

<body>
    <?php include('./navbar.php') ?>
    <div class="container" style="margin-top: 150px;">
        <div class="d-flex justify-content-start">
            <ul class="nav nav-tabs">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <?php 
                    echo ' <a class="nav-link' . ($currentPage == 'stock_list.php' ? ' active' : '') . '"href="stock_list.php" id="productTab"
                    style="font-size: 20px;">';
               
                ?>
                        <i class="fa-solid fa-box fa-lg"></i> All Store</a>
                    </li>
                    <!-- Line break after the first list item -->
                    <?php
                echo '<a class="nav-link' . ($currentPage == 'stock_samt.php' ? ' active' : '') . '" href="stock_samt.php" id="samtTab" style="font-size: 20px;">';
                ?>
                    <i class="fa-solid fa-store fa-lg"></i> SAMT Store</a>
                    </li>
                    <!-- Line break after the first list item -->
                    <li class="nav-item">
                        <?php 
               
                    echo '<a class="nav-link' . ($currentPage == 'stock_sakaba.php' ? ' active' : '') . '" href="stock_sakaba.php" id="supplierTab" style="font-size: 20px;">';
                
                ?>
                        <i class="fa-solid fa-store fa-lg"></i> SAKABA Store</a>
                    </li>
                </ul>
            </ul>
        </div>
        <div class="d-flex justify-content-between m-3" style="align-items: center;">
            <div class="d-flex justify-content-end w-100">
                <div class="input-group p-3">
                    <input id="searchInput" type="search" class="form-control rounded" placeholder="Search"
                        aria-label="Search" aria-describedby="search-addon" />
                    <button type="button" class="btn btn-primary" data-mdb-ripple-init
                        onclick="loadData(1, $('#searchInput').val())">Search</button>
                </div>
            </div>
        </div>


        <table class="table table-bordered table-hover">
            <thead class="table-info text-center">
                <tr style="vertical-align: middle;">
                    <th rowspan="2">No</th>
                    <th rowspan="2">Collection</th>
                    <th rowspan="2">Name</th>
                    <th rowspan="2">Hands</th>
                    <th rowspan="2">Color</th>
                    <th rowspan="2">Size</th>
                    <th rowspan="2">Cost price</th>
                    <th rowspan="2">Sale price</th>
                    <th rowspan="2">Samt Qty</th>
                    <!--  <th class="text-center" colspan="2">Store</th> -->
                </tr>
                <!-- <tr>
                    <th>SAMT</th>
                    <th>SAKABA</th>
                </tr> -->
            </thead>
            <tbody>
                <?php
         $stmt = $pdo->prepare("SELECT p.*, sub.sub_qty, s.s_qty
         FROM product p 
         LEFT JOIN stock s ON s.s_product_id = p.p_product_id 
         LEFT JOIN sub_stock sub ON sub.sub_product_id = s.s_id
         GROUP BY p.p_product_code, p.p_product_name, p.p_hands, p.p_color, FIELD(p.p_size, 'SS', 'S', 'M', 'L', 'XL', 'XXL'), p.p_unit, p.p_collection, p.p_cost_price, p.p_sale_price
         HAVING s.s_qty!=0;");


           // Execute the statement
           $stmt->execute();
           $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

           // Loop through products and output each row
           foreach ($products as $index => $product) {
           echo "<tr data-id='" . htmlspecialchars($product['p_product_id']) . "'>";
           echo "<td>" . ($index + 1) . "</td>"; // Display No starting from 1
           echo "<td>" . htmlspecialchars($product['p_collection']) . "</td>";
           echo "<td>" . htmlspecialchars($product['p_product_name']) . "</td>";
           echo "<td>" . htmlspecialchars($product['p_hands']) . "</td>";
           echo "<td>" . htmlspecialchars($product['p_color']) . "</td>";
           echo "<td>" . htmlspecialchars($product['p_size']) . "</td>";
           echo "<td class='text-end'>" . number_format($product['p_cost_price']) . "</td>";
           echo "<td class='text-end'>" . number_format($product['p_sale_price']) . "</td>";
           echo "<td class='text-end' style='color: " . ($product['s_qty'] == 0 ? "red" : "green") . ";background:#E5F9E5;'>" .  htmlspecialchars(($product['s_qty'])-($product['sub_qty'])) . "</td>";
           echo "</tr>";
           }
            ?>
            </tbody>
        </table>
    </div>
</body>

</html>