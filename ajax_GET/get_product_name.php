<?php
// get_product_name.php

// Assuming you have already established a PDO connection
include('../connect.php');

if(isset($_POST['product_code'])) {
    $productCode = $_POST['product_code'];
    
    // Fetch the product name and unit from the database based on the product code
    $stmt = $pdo->prepare("SELECT s_product_name, s_unit FROM stock WHERE s_product_code = ?");
    $stmt->execute([$productCode]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Concatenate product name and unit with a delimiter
    $response = $row['s_product_name'] . '|' . $row['s_unit'];
    
    // Return the concatenated product name and unit as the response
    echo $response;
}
?>