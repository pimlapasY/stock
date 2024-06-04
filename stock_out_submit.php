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
    $reasons = $_POST['reasons'];
    $date = $_POST['date'];

    // Split reasons data
    $data_reasons = explode(",", $reasons);

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

        // Check if the reason is 'sale' or 'sale sample'
        if ($data_reasons[0] == 'sale' || $data_reasons[0] == 'sale sample') {
            // Prepare SQL statement to update stock quantity
            $updateStmt = $pdo->prepare("UPDATE stock SET s_qty = s_qty - ? WHERE s_product_id = ?");

            // Bind parameters for update
            $updateStmt->bindParam(1, $qtyValue);
            $updateStmt->bindParam(2, $productID);

            // Execute SQL statement to update stock quantity
            $updateStmt->execute();

            if ($data_reasons[1] == '2') {
                // Prepare SQL statement to update stock quantity
                $updateSakaba = $pdo->prepare("UPDATE sub_stock SET sub_qty = sub_qty - ? WHERE sub_product_id = ?");

                // Bind parameters for update
                $updateSakaba->bindParam(1, $qtyValue);
                $updateSakaba->bindParam(2, $productID);

                // Execute SQL statement to update stock quantity
                $updateSakaba->execute();
            }
        } else {
                   // Check if the sub_stock record exists
            $checkStmt = $pdo->prepare("SELECT * FROM sub_stock WHERE sub_product_id = ? AND sub_location = ?");
            $checkStmt->bindParam(1, $productID);
            $checkStmt->bindParam(2, $data_reasons[1]);
            $checkStmt->execute();

            if ($checkStmt->rowCount() > 0) {
                // If exists, update the sub_qty
                $updateSubQtyStmt = $pdo->prepare("UPDATE sub_stock SET sub_qty = sub_qty + ? WHERE sub_product_id = ? AND sub_location = ?");
                $updateSubQtyStmt->bindParam(1, $qtyValue);
                $updateSubQtyStmt->bindParam(2, $productID);
                $updateSubQtyStmt->bindParam(3, $data_reasons[1]);
                $updateSubQtyStmt->execute();
            } else {
                // If not exists, insert new entry into sub_stock
                $insertSubStockStmt = $pdo->prepare("INSERT INTO sub_stock (sub_product_id, sub_qty, sub_location, sub_date_add) VALUES (?, ?, ?, NOW())");
                $insertSubStockStmt->bindParam(1, $productID);
                $insertSubStockStmt->bindParam(2, $qtyValue);
                $insertSubStockStmt->bindParam(3, $data_reasons[1]);
                $insertSubStockStmt->execute();
            }
        }

        // Print success message
        echo "Form submitted successfully";
    } catch (PDOException $e) {
        // Print error message
        echo "Error: " . $e->getMessage();
    }
}
?>