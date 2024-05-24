<?php
session_start();    
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 0);

// Include database connection file
include 'connect.php';

// Check if user is logged in
if (isset($_SESSION['role'])) {
    // Get form data
    $mgCode = $_POST['mgCode'];
    $productID = $_POST['productID'];
    $productCode = $_POST['productCode'];
    $productName = $_POST['productName'];
    $productCost = $_POST['productCost'];
    $productTotal = $_POST['productTotal'];
    $memo = $_POST['memo'];
    $qtyValue = $_POST['qtyValue'];
    $username = $_SESSION['id'];
    $reasons =  $_POST['reasons'];
    $date =  $_POST['date'];



    try {
        // Prepare SQL statement
        $stmt = $pdo->prepare("INSERT INTO stockout (o_product_id, o_mg_code, o_product_code, o_product_name, o_cost_price, o_total_price, o_out_qty, o_memo, o_username, o_reasons, o_out_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        // Bind parameters
        $stmt->bindParam(1, $productID);
        $stmt->bindParam(2, $mgCode);
        $stmt->bindParam(3, $productCode);
        $stmt->bindParam(4, $productName);
        $stmt->bindParam(5, $productCost);
        $stmt->bindParam(6, $productTotal);
        $stmt->bindParam(7, $qtyValue);
        $stmt->bindParam(8, $memo);
        $stmt->bindParam(9, $username);
        $stmt->bindParam(10, $reasons);
        $stmt->bindParam(11, $date);



        // Execute SQL statement
        $stmt->execute();

        // Set $stmt to null to release resources
        $stmt = null;

        // Print success message
        echo "Form submitted successfully";
    } catch (PDOException $e) {
        // Print error message
        echo "Error: " . $e->getMessage();
    }
}
?>