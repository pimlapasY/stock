<?php
include 'connect.php';

// รับค่าจาก AJAX
$searchBy = $_POST['searchBy'] ?? '';
$productCode = $_POST['productCode'] ?? '';
$productName = $_POST['productName'] ?? '';
$option1 = $_POST['option1'] ?? ''; // ค้นหาใน p_hands
$option2 = $_POST['option2'] ?? ''; // ค้นหาใน p_color
$option3 = $_POST['option3'] ?? ''; // ค้นหาใน p_size

// เริ่มสร้าง SQL Query
$sql = "SELECT p.*, SUM(sub.sub_qty) AS total_sub_qty, s.s_qty, s.s_return_date
        FROM product p
        LEFT JOIN stock s ON s.s_product_id = p.p_product_id
        LEFT JOIN sub_stock sub ON sub.sub_product_id = p.p_product_id
        WHERE 1 "; // ใช้ WHERE 1 เพื่อให้สามารถต่อเงื่อนไขได้ง่าย

// เงื่อนไขสำหรับค้นหาตามประเภทที่เลือก
if ($searchBy == '1' && !empty($productName)) {
    $sql .= " AND p.p_product_name LIKE :productName ";
} elseif ($searchBy == '2' && !empty($productCode)) {
    $sql .= " AND p.p_product_code LIKE :productCode ";
}

// 🔹 กรองตาม Option1 (p_hands) / Option2 (p_color) / Option3 (p_size) ถ้ามีค่า
if (!empty($option1)) {
    $sql .= " AND p.p_hands LIKE :option1 ";
}
if (!empty($option2)) {
    $sql .= " AND p.p_color LIKE :option2 ";
}
if (!empty($option3)) {
    $sql .= " AND p.p_size LIKE :option3 ";
}

$sql .= " GROUP BY p.p_product_code, p.p_product_name, p.p_hands, 
            FIELD(p.p_size, 'SS', 'S', 'M', 'L', 'XL', 'XXL'), 
            p.p_color, p.p_unit, p.p_collection, p.p_cost_price, p.p_sale_price 
          HAVING s.s_qty != 0;";

$stmt = $pdo->prepare($sql);

// ผูกค่า Parameter ถ้ามี
if ($searchBy == '1' && !empty($productName)) {
    $stmt->bindValue(':productName', "%$productName%", PDO::PARAM_STR);
}
if ($searchBy == '2' && !empty($productCode)) {
    $stmt->bindValue(':productCode', "%$productCode%", PDO::PARAM_STR);
}
if (!empty($option1)) {
    $stmt->bindValue(':option1', "%$option1%", PDO::PARAM_STR);
}
if (!empty($option2)) {
    $stmt->bindValue(':option2', "%$option2%", PDO::PARAM_STR);
}
if (!empty($option3)) {
    $stmt->bindValue(':option3', "%$option3%", PDO::PARAM_STR);
}

$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$data = [];

foreach ($products as $index => $product) {
    $difference = $product['s_qty'] - ($product['total_sub_qty'] ?? 0);

    // ตรวจสอบวันที่คืนสินค้า
    $returnDate = $product['s_return_date'];
    $rowColor = "";
    if ($returnDate != null) {
        $returnDateTime = new DateTime($returnDate);
        $currentDateTime = new DateTime();
        $interval = $currentDateTime->diff($returnDateTime);
        $daysDiff = $interval->days;
        $rowColor = ($daysDiff <= 2) ? "table-warning" : "";
    }

    $textColor = ($difference <= 0) ? "red" : "green";
    $backgroundColor = "#E5F9E5";

    $data[] = [
        "id" => $product['p_product_id'],
        "index" => $index + 1,
        "code" => $product['p_product_code'] ?? "",
        "collection" => $product['p_collection'] ?? "",
        "name" => $product['p_product_name'] ?? "",
        "hands" => $product['p_hands'] ?? "",
        "color" => $product['p_color'] ?? "",
        "size" => $product['p_size'] ?? "",
        "cost_price" => $product['p_cost_price'] !== null ? number_format($product['p_cost_price'], 2) : "",
        "sale_price" => $product['p_sale_price'] !== null ? number_format($product['p_sale_price'], 2) : "",
        "vat_price" => ($product['p_sale_price'] !== null && $product['p_vat'] !== null)
            ? number_format(($product['p_sale_price'] * $product['p_vat'] / 100) + $product['p_sale_price'], 2)
            : "",
        "difference" => $difference,
        "textColor" => $textColor,
        "backgroundColor" => $backgroundColor,
        "rowColor" => $rowColor,
        "total_sub_qty" => $product['total_sub_qty'] ?? "",
    ];
}

header('Content-Type: application/json');
echo json_encode($data);
