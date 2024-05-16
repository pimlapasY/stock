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
?>