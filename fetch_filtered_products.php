<?php
require_once 'connect.php'; // ใช้ไฟล์ที่มีตัวแปร $pdo

$option1 = isset($_POST['option1']) ? trim($_POST['option1']) : '';
$option2 = isset($_POST['option2']) ? trim($_POST['option2']) : '';
$option3 = isset($_POST['option3']) ? trim($_POST['option3']) : '';

$query = "SELECT p.*, SUM(sub.sub_qty) AS total_sub_qty, s.s_qty, s.s_return_date
          FROM product p
          LEFT JOIN stock s ON s.s_product_id = p.p_product_id
          LEFT JOIN sub_stock sub ON sub.sub_product_id = p.p_product_id
          WHERE 1";

$params = [];
if ($option1 !== '') {
    $query .= " AND p.p_hands LIKE ?";
    $params[] = "%$option1%";
}
if ($option2 !== '') {
    $query .= " AND p.p_color LIKE ?";
    $params[] = "%$option2%";
}
if ($option3 !== '') {
    $query .= " AND p.p_size LIKE ?";
    $params[] = "%$option3%";
}

$query .= " GROUP BY p.p_product_code, p.p_product_name, p.p_hands, FIELD(p.p_size, 'SS', 'S', 'M', 'L', 'XL', 'XXL'), p.p_color , p.p_unit, p.p_collection, p.p_cost_price, p.p_sale_price
            HAVING s.s_qty != 0";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($products as $index => $product) {
    $difference = $product['s_qty'] - $product['total_sub_qty'];
    $rowColor = '';
    if ($product['s_return_date']) {
        $returnDateTime = new DateTime($product['s_return_date']);
        $currentDateTime = new DateTime();
        $daysDiff = $currentDateTime->diff($returnDateTime)->days;
        $rowColor = ($daysDiff <= 2) ? "class='table-warning'" : '';
    }
    $textColor = ($difference <= 0) ? "red" : "green";
    $backgroundColor = "#E5F9E5";

    echo "<tr $rowColor data-id='" . htmlspecialchars($product['p_product_id']) . "'>";
    echo "<td class='text-center' style='vertical-align: middle;'>
              <div class='input-group'>
                  <div class='input-group-text'>
                      <input class='form-check-input' type='checkbox' name='selected_ids[]' value='" . htmlspecialchars($product['p_product_id']) . "' />
                  </div>
                  <input class='form-control' min='1' max='" . $difference . "' type='number' id='input_" . htmlspecialchars($product['p_product_id']) . "' style='display: none; width: 70px;' />
              </div>
          </td>";
    echo "<td>" . ($index + 1) . "</td>";
    echo "<td>" . htmlspecialchars($product['p_product_code']) . "</td>";
    echo "<td>" . htmlspecialchars($product['p_collection']) . "</td>";
    echo "<td>" . htmlspecialchars($product['p_product_name']) . "</td>";
    echo "<td>" . htmlspecialchars($product['p_hands']) . "</td>";
    echo "<td>" . htmlspecialchars($product['p_color']) . "</td>";
    echo "<td>" . htmlspecialchars($product['p_size']) . "</td>";
    echo "<td class='text-end'>" . number_format($product['p_cost_price'], 2) . "</td>";
    echo "<td class='text-end'>" . number_format($product['p_sale_price'], 2) . "</td>";
    echo "<td class='text-end'>" . number_format(($product['p_sale_price'] * $product['p_vat'] / 100) + $product['p_sale_price'], 2) . "</td>";
    echo "<td class='text-end' style='color: $textColor; background: $backgroundColor;'>" . htmlspecialchars($difference) . "</td>";
    echo "<td class='text-center'>" . ($product['total_sub_qty'] == null ? '' : '<a href="#" class="info-icon btn btn-rounded btn-info" data-product-id="' . htmlspecialchars($product['p_product_id']) . "'><i class='fa-solid fa-magnifying-glass'></i></a>") . "</td>";
    echo "</tr>";
}
