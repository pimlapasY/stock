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
    <?php include('navbar.php');

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

    ?>

    <div class="table-responsive">
        <div class="container-fluid pt-5 mt-5  col-12">
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
                            echo '<a class="nav-link' . ($currentPage == 'stock_list.php' ? ' active' : '') . '"href="stock_list.php" id="productTab" style="font-size: 20px;">';
                            echo ' <span class="badge text-bg-danger rounded-pill">' .   $stockSamt['total_quantity']     . '</span>'
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
                            echo ' <span class="badge text-bg-secondary rounded-pill">' .   $stockOther['total_quantity_other']     . '</span>'
                            ?>
                            <i class="fa-solid fa-store fa-lg"></i> <?php echo $otherStore ?></a>
                        </li>
                    </ul>
                </ul>
            </div>
            <div class="d-flex justify-content-between" style="align-items: center;">
                <div class="d-flex justify-content-start">
                    <a href="register.php" class="btn btn-success">
                        <i class="fa-solid fa-plus"></i> <?php echo $register ?>
                    </a>&nbsp;
                    <a href="currently_taken.php" class="btn btn-danger">
                        <i class="fa-solid fa-database"></i> <?php echo $cr_taken; ?>
                    </a>&nbsp;
                    <a class="btn btn-info" href="currently_taken.php#saleTab">
                        <i class="fa-solid fa-cart-arrow-down"></i> <?php echo $sale; ?>
                    </a>&nbsp;
                    <a class="btn btn-info" href="./list.php">
                        <i class="fa-solid fa-clipboard-list"></i> <?php echo $pr_list; ?>
                    </a>
                </div>
                <div class="d-flex justify-content-end w-50">
                    <div class="input-group p-3">
                        <div class="input-group p-3">
                            <input id="searchInput" type="search" class="form-control rounded"
                                placeholder="<?php echo $search; ?>" aria-label="Search"
                                aria-describedby="search-addon" />
                            <button id="btnSearch" type="button" class="btn btn-primary" onclick="searchTable()">
                                <?php echo $search; ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="">
                <table id="productTable" class="table table-sm table-bordered table-hover">
                    <thead class="table-dark text-center">
                        <th rowspan="2"><?php echo $num; ?></th>
                        <th rowspan="2"><?php echo $productCode; ?></th>
                        <th rowspan="2"><?php echo $collection; ?></th>
                        <th rowspan="2"><?php echo $productName; ?></th>
                        <th rowspan="2"><?php echo $options1_label; ?></th>
                        <th rowspan="2"><?php echo $options2_label; ?></th>
                        <th rowspan="2"><?php echo $options3_label; ?></th>
                        <th rowspan="2"><?php echo $costPrice; ?></th>
                        <th rowspan="2"><?php echo $salePrice; ?></th>
                        <th rowspan="2"><?php echo $salePrice . ' (vat%)'; ?></th>
                        <th rowspan="2"><?php echo $qty; ?></th>
                        <th class="text-center" colspan="2"><?php echo $store; ?></th>
                        </tr>
                        <tr>
                            <th>SAMT</th>
                            <th><?php echo $other; ?></th>
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
                   p.p_sale_price");


                        // Execute the statement
                        $stmt->execute();
                        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        // Loop through products and output each row
                        foreach ($products as $index => $product) {

                            $stockSAMT = $product['stock_qty'] - $product['total_sub_qty'];
                            echo "<tr data-id='" . htmlspecialchars($product['p_product_id']) . "'>";
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
                            echo "<td class='text-end' style='color: " . ($product['stock_qty'] == 0 ? "red; background:#FCF3CF;" : "black; background:#E5F9E5;") . "'>" .  htmlspecialchars($product['stock_qty']) . "</td>";
                            echo "<td class='text-end' style='color: " . ($product['stock_qty'] == 0 ? "red" : "green") . ";'>" . ($stockSAMT == 0 ? '' : $stockSAMT) . "</td>";
                            echo "<td class='text-end' style='color: " . ($product['total_sub_qty'] == 0 ? "red" : "green") . ";'>" . ($product['total_sub_qty'] == 0 ? '' : htmlspecialchars($product['total_sub_qty']))  . "</td>";
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

<script>
    // Add event listener for Enter key on search input
    document.getElementById("searchInput").addEventListener("keydown", function(event) {
        if (event.key === "Enter") {
            searchTable();
        }
    });

    function searchTable() {
        // Get the value from the search input
        const input = document.getElementById("searchInput").value.toLowerCase();

        // Get the table and rows
        const table = document.getElementById("productTable");
        const rows = table.getElementsByTagName("tr");

        // Loop through all rows (except the header) and hide those that don't match the search query
        for (let i = 1; i < rows.length; i++) {
            const cells = rows[i].getElementsByTagName("td");
            let rowContainsSearchTerm = false;

            // Loop through each cell in the row
            for (let j = 0; j < cells.length; j++) {
                // Remove any previous highlight
                const cellText = cells[j].innerText;
                cells[j].innerHTML = cellText;

                if (cellText.toLowerCase().includes(input)) {
                    rowContainsSearchTerm = true;
                    // Highlight the matching term
                    const regex = new RegExp(`(${input})`, 'gi');
                    cells[j].innerHTML = cellText.replace(regex, "<span class='highlight'>$1</span>");
                }
            }

            // Show or hide the row based on the search
            if (rowContainsSearchTerm) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }
        }
    }
</script>