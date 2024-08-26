<?php
// เชื่อมต่อฐานข้อมูล
include '../connect.php';

// รับค่าพารามิเตอร์จากคำขอ
$productCode = $_POST['productCode'] ?? null;
$color = $_POST['color'] ?? null;
$size = $_POST['size'] ?? null;
$hands = $_POST['hands'] ?? null;

// สร้างคำสั่ง SQL
$sql = "SELECT p_product_name, p_unit, p_cost_price FROM product WHERE 1=1";

if ($productCode) {
    $sql .= " AND p_product_code = :productCode";
}
if ($color) {
    $sql .= " AND p_color = :color";
}
if ($size) {
    $sql .= " AND p_size = :size";
}
if ($hands) {
    $sql .= " AND p_hands = :hands";
}

// เตรียมและเรียกใช้คำสั่ง SQL
$stmt = $pdo->prepare($sql);

if ($productCode) {
    $stmt->bindValue(':productCode', $productCode);
}
if ($color) {
    $stmt->bindValue(':color', $color);
}
if ($size) {
    $stmt->bindValue(':size', $size);
}
if ($hands) {
    $stmt->bindValue(':hands', $hands);
}

$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// ส่งข้อมูลกลับเป็น JSON
echo json_encode($result);
?>