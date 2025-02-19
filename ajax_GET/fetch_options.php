<?php
include '../connect.php';

if (isset($_POST['type'], $_POST['value'], $_POST['productCode'])) {
    $type = $_POST['type'];
    $value = $_POST['value'];
    $productCode = $_POST['productCode'];

    $response = array();
    
    if ($type == 'p_size') {
        $stmt = $pdo->prepare("SELECT DISTINCT p_size FROM product WHERE p_product_code = ?");
        $stmt->execute([$productCode]);
        $response['options'] = $stmt->fetchAll(PDO::FETCH_COLUMN);
    } elseif ($type == 'p_color') {
        $stmt = $pdo->prepare("SELECT DISTINCT p_color FROM product WHERE p_product_code = ?");
        $stmt->execute([$productCode]);
        $response['options'] = $stmt->fetchAll(PDO::FETCH_COLUMN);
    } elseif ($type == 'p_hands') {
        $stmt = $pdo->prepare("SELECT DISTINCT p_hands FROM product WHERE p_product_code = ?");
        $stmt->execute([$productCode]);
        $response['options'] = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    echo json_encode($response);
}
?>