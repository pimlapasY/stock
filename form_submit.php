<?php
include('connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Insert data into the database using prepared statements
    $stmt = $pdo->prepare("INSERT INTO suppliers (sup_name, sup_addr, sup_tel, sup_email, sup_main_product, sup_memo) VALUES (?, ?, ?, ?, ?, ?)");
         
    try {
        $stmt->execute([$_POST['sup_name'], $_POST['sup_addr'], $_POST['sup_tel'], $_POST['sup_email'], $_POST['sup_main_product'], $_POST['sup_memo']]);

        // After successful insertion, send a success response
        echo json_encode(array("success" => true, "redirect" => "list.php"));
        exit;
    } catch (PDOException $e) {
        // Handle database insertion error
        
        // JSON encode a response indicating failure
        echo json_encode(array("success" => false, "message" => "Failed to insert data into the database."));
        
        // Exit the script to prevent further execution
        exit;
    }
    
}
?>