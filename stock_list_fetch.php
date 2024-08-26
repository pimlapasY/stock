<?php
// Include necessary files and initialize connection

// Pagination parameters
$limit = 10; // Number of records per page

// Calculate offset
$page = isset($_POST['page']) ? $_POST['page'] : 1;
$offset = ($page - 1) * $limit;

// Search query
$query = isset($_POST['query']) ? $_POST['query'] : '';

// Query to fetch data based on pagination and search
$stmt = $pdo->prepare("SELECT SQL_CALC_FOUND_ROWS p.*,sub.sub_qty, s.s_qty, IFNULL(s.s_qty, 0) AS stock_qty
             FROM product p 
             LEFT JOIN stock s ON s.s_product_id = p.p_product_id 
             LEFT JOIN sub_stock sub ON sub.sub_product_id = s.s_id
             WHERE p.p_product_name LIKE :query
             GROUP BY p.p_product_code, p.p_product_name, p.p_hands, p.p_color, FIELD(p.p_size, 'SS', 'S', 'M', 'L', 'XL', 'XXL'), p.p_unit, p.p_collection, p.p_cost_price, p.p_sale_price
             LIMIT :limit OFFSET :offset");

$stmt->bindValue(':query', '%' . $query . '%', PDO::PARAM_STR);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Count total records (ignoring limit and offset) for pagination
$total_records = $pdo->query("SELECT FOUND_ROWS()")->fetchColumn();

// Output table HTML
echo '<table class="table table-bordered table-hover">
        <thead class="table-dark text-center">
            <tr style="vertical-align: middle;">
                <th rowspan="2">No</th>
                <th rowspan="2">Collection</th>
                <th rowspan="2">Name</th>
                <th rowspan="2">Hands</th>
                <th rowspan="2">Color</th>
                <th rowspan="2">Size</th>
                <th rowspan="2">Cost price</th>
                <th rowspan="2">Sale price</th>
                <th rowspan="2">Total Qty</th>
                <th class="text-center" colspan="2">Store</th>
            </tr>
            <tr>
                <th>SAMT</th>
                <th>SAKABA</th>
            </tr>
        </thead>
        <tbody>';

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
    echo "<td class='text-end' style='color: " . ($product['stock_qty'] == 0 ? "red" : "green") . "; background:#FBE7C6;'>" . htmlspecialchars($product['stock_qty']) . "</td>";
    echo "<td class='text-end' style='color: " . ($product['s_qty'] == 0 ? "red" : "green") . ";'>" . htmlspecialchars(($product['s_qty']) - ($product['sub_qty'])) . "</td>";
    echo "<td class='text-end' style='color: " . ($product['sub_qty'] == 0 ? "red" : "green") . ";'>" . htmlspecialchars($product['sub_qty']) . "</td>";
    echo "</tr>";
}

echo '</tbody>
    </table>';
?>