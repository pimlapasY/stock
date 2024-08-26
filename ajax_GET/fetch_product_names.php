// fetch_product_names.php
<?php
include '../connect.php';
// Fetch product names
$stmt_code = $pdo->query("SELECT DISTINCT p_product_code FROM product");
$productNames_code = $stmt_code->fetchAll(PDO::FETCH_COLUMN);

$stmt_color = $pdo->query("SELECT DISTINCT p_color FROM product");
$productNames_color = $stmt_color->fetchAll(PDO::FETCH_COLUMN);

$stmt_size = $pdo->query("SELECT DISTINCT p_size FROM product");
$productNames_size = $stmt_size->fetchAll(PDO::FETCH_COLUMN);

$stmt_hands = $pdo->query("SELECT DISTINCT p_hands FROM product");
$productNames_hands = $stmt_hands->fetchAll(PDO::FETCH_COLUMN);

echo json_encode([
    'product_codes' => $productNames_code,
    'product_colors' => $productNames_color,
    'product_sizes' => $productNames_size,
    'product_hands' => $productNames_hands,
]);
?>