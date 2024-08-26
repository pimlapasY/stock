<?php
include 'connect.php';

// Get search term from POST request
$searchTerm = isset($_POST['searchTerm']) ? $_POST['searchTerm'] : '';

// Prepare the query with placeholders for the search term
$query = "
    SELECT p.*, sub.sub_qty, s.s_qty, s.s_return_date
    FROM product p
    LEFT JOIN stock s ON s.s_product_id = p.p_product_id
    LEFT JOIN sub_stock sub ON sub.sub_product_id = p.p_product_id
    WHERE p.p_color LIKE :searchTerm
       OR p.p_product_name LIKE :searchTerm
       OR p.p_product_code LIKE :searchTerm
    GROUP BY p.p_product_code, p.p_product_name, p.p_hands, p.p_color, FIELD(p.p_size, 'SS', 'S', 'M', 'L', 'XL', 'XXL'), p.p_unit, p.p_collection, p.p_cost_price, p.p_sale_price
    HAVING s.s_qty != 0;
";

try {
    // Prepare and execute the query
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':searchTerm', "%$searchTerm%", PDO::PARAM_STR);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Output each product as a table row
    foreach ($products as $index => $product) {
        // Check if s_return_date exists and is within 2 days from today
        $returnDate = $product['s_return_date'];
        if ($returnDate != null) {
            $returnDateTime = new DateTime($returnDate);
            $currentDateTime = new DateTime();
            $interval = $currentDateTime->diff($returnDateTime);
            $daysDiff = $interval->days;
            // Mark the row as warning if return date is within 2 days
            $rowColor = ($daysDiff <= 2) ? "class='table-warning'" : '';
        } else {
            $rowColor = '';
        }

        // Output table row
        echo "<tr $rowColor data-id='" . htmlspecialchars($product['p_product_id']) . "'>";
        echo "<td>" . ($index + 1) . "</td>"; // Display No starting from 1
        echo "<td>" . htmlspecialchars($product['p_product_code']) . "</td>";
        echo "<td>" . htmlspecialchars($product['p_collection']) . "</td>";
        echo "<td>" . htmlspecialchars($product['p_product_name']) . "</td>";
        echo "<td>" . htmlspecialchars($product['p_hands']) . "</td>";
        echo "<td>" . htmlspecialchars($product['p_color']) . "</td>";
        echo "<td>" . htmlspecialchars($product['p_size']) . "</td>";
        echo "<td class='text-end'>" . number_format($product['p_cost_price']) . "</td>";
        echo "<td class='text-end'>" . number_format($product['p_sale_price']) . "</td>";
        echo "<td class='text-end' style='color: " . ($product['s_qty'] == 0 ? "red" : "green") . ";background:#E5F9E5;'>" . htmlspecialchars(($product['s_qty']) - ($product['sub_qty'])) . "</td>";
        echo "<td class='text-center'>" . ($product['sub_qty'] == null ? '' : '<i class="fa-solid fa-check"></i>') . "</td>";
        echo "<td class='text-center' style='vertical-align: middle;'>";
        echo '<div class="input-group"><div class="input-group-text">';
        echo "<input class='form-check-input' type='checkbox' name='selected_ids[]' value='" . htmlspecialchars($product['p_product_id']) . "' id='checkbox_" . htmlspecialchars($product['p_product_id']) . "' onchange='toggleInput(this)' /> <br>";
        echo "</div><input class='form-control' min='1' max='" . htmlspecialchars(($product['s_qty']) - ($product['sub_qty'])) . "' type='number' id='input_" . htmlspecialchars($product['p_product_id']) . "' style='display: none;' /> </div>";
        echo "</td>";
        echo "</tr>";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>