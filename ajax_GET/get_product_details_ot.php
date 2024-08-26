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
        SELECT p.*, sub.*
            FROM sub_stock sub
            LEFT JOIN product p  ON sub.sub_product_id = p.p_product_id
        WHERE sub.sub_id IN ($placeholders)");
        $stmt->execute($productIds);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // รวมข้อมูล qty เข้ากับข้อมูลผลิตภัณฑ์
        foreach ($rows as &$row) {
            foreach ($products as $product) {
                if ($row['sub_id'] == $product['id']) {
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