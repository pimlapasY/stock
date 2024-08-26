<?php
include('../connect.php'); // Include your PDO connection script

// get_sub_stock_details.php
try {
    $productId = $_POST['p_product_id'];
    
    // Prepare and execute the query
    $stmt = $pdo->prepare("
        SELECT sub.*, p.*
        FROM sub_stock sub
        LEFT JOIN product p ON sub.sub_product_id = p.p_product_id
        WHERE sub.sub_product_id = ?
    ");
    $stmt->execute([$productId]);
    $subStockDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the results as JSON
    echo json_encode($subStockDetails);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>