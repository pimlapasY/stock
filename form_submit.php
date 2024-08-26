<?php
include('connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" ) {
    if (isset($_POST['form_type']) && $_POST['form_type'] == 'registerStoreForm') {
        // Insert data into the store table
        $stmt = $pdo->prepare("INSERT INTO store (st_name, st_addr, st_tel) VALUES (?, ?, ?)");
        
        try {
            $stmt->execute([$_POST['st_name'], $_POST['st_addr'], $_POST['st_tel']]);

            // After successful insertion, send a success response
            echo json_encode(array("success" => true, "redirect" => "list.php"));
            exit;
        } catch (PDOException $e) {
            // Handle database insertion error
            echo json_encode(array("success" => false, "message" => "Failed to insert store data into the database."));
            exit;
        }

    } elseif (isset($_POST['form_type']) && $_POST['form_type'] == 'registerSupForm') {
        // Insert data into the suppliers table
        $stmt = $pdo->prepare("INSERT INTO suppliers (sup_name, sup_addr, sup_tel, sup_email, sup_main_product, sup_memo) VALUES (?, ?, ?, ?, ?, ?)");
         
        try {
            $stmt->execute([$_POST['sup_name'], $_POST['sup_addr'], $_POST['sup_tel'], $_POST['sup_email'], $_POST['sup_main_product'], $_POST['sup_memo']]);

            // After successful insertion, send a success response
            echo json_encode(array("success" => true, "redirect" => "list.php"));
            exit;
        } catch (PDOException $e) {
            // Handle database insertion error
            echo json_encode(array("success" => false, "message" => "Failed to insert supplier data into the database."));
            exit;
        }
    }
}
?>