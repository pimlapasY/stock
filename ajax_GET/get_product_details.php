<?php
// get_product_details.php

// Assuming you have already established a PDO connection
include('../connect.php');

if (isset($_POST['products'])) {
    $products = $_POST['products'];

    // ตรวจสอบให้แน่ใจว่า products เป็นอาร์เรย์
    if (is_array($products)) {
        // สร้าง placeholder สำหรับคำสั่ง SQL
        $placeholders = implode(',', array_fill(0, count($products), '?'));

        // สร้าง array สำหรับ IDs
        $productIds = array_map(function($product) { return $product['id']; }, $products);

                // Prepare and execute query
        $stmt = $pdo->prepare("
        SELECT p.*, 
            COALESCE(SUM(sub.sub_qty), 0) AS total_sub_qty, 
            COALESCE(s.s_qty, 0) AS s_qty, 
            s.s_return_date
        FROM product p 
        LEFT JOIN stock s ON s.s_product_id = p.p_product_id 
        LEFT JOIN sub_stock sub ON sub.sub_product_id = p.p_product_id 
        WHERE p.p_product_id IN ($placeholders)
        AND COALESCE(s.s_qty, 0) != 0
        GROUP BY p.p_product_code, p.p_product_name, p.p_hands, p.p_color, FIELD(p.p_size, 'SS', 'S', 'M', 'L', 'XL', 'XXL'), p.p_unit, p.p_collection, p.p_cost_price, p.p_sale_price
        ");
        $stmt->execute($productIds);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // รวมข้อมูล qty เข้ากับข้อมูลผลิตภัณฑ์
        foreach ($rows as &$row) {
            foreach ($products as $product) {
                if ($row['p_product_id'] == $product['id']) {
                    $row['qty'] = $product['qty'];
                    break;
                }
            }
        }

        echo json_encode($rows); // ส่งข้อมูลทั้งหมดกลับเป็น JSON
    } else {
        echo json_encode(["error" => "Invalid product data."]);
    }
}
?>