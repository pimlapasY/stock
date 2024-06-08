<?php
// get_product_name.php

// Assuming you have already established a PDO connection
include('../connect.php');

if(isset($_POST['product_code'])) {
    $productCode = $_POST['product_code'];
    
    // Fetch the product name and unit from the database based on the product code
    $stmt = $pdo->prepare("SELECT p_product_name, p_unit FROM product WHERE p_product_code = ?");
    $stmt->execute([$productCode]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Concatenate product name and unit with a delimiter
    $response = $row['p_product_name'] . '|' . $row['p_unit'];
    
    // Return the concatenated product name and unit as the response
    echo $response;
}
// Check if mgCode is set in the POST request
if (isset($_POST['mgCode'])) {
    $mgCode = $_POST['mgCode'];
    
    // Fetch the product name and unit from the database based on the mgCode
    $stmt = $pdo->prepare("SELECT o.*, p.*, s.s_qty FROM stockout o
                           LEFT JOIN product p ON p.p_product_id = o.o_product_id
                           LEFT JOIN stock s ON o.o_product_id
                           WHERE o.o_mg_code = ? AND o.o_return IS NULL");
    $stmt->execute([$mgCode]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        // Prepare the response array
        $response = [
            'o_product_id' =>$row['o_product_id'],
            'o_product_code' =>$row['o_product_code'],
            'product_name' => $row['p_product_name'],
            'unit' => $row['p_unit'],
            'color' => $row['p_color'],
            'hand' => $row['p_hands'],
            'size' => $row['p_size'],
            'cost' => $row['p_cost_price'],
            'qtyOut' => $row['o_out_qty'],
            'qtyStock' => $row['s_qty']
        ];
    } else {
        // Handle case where no data is found
        $response = ['error' => 'No data found'];
    }

    // Return the response as JSON
    echo json_encode($response);
}
?>