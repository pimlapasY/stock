<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection file
include 'connect.php';

// Check if user is logged in
if (isset($_SESSION['role'])) {
    // Get form data
    $mgCode = $_POST['mgCode'];
    $productID = $_POST['productID'];
    $memo = $_POST['memo'];
    $qtyValueNum = $_POST['qtyValueNum'];
    $username = $_SESSION['id'];
    $date = $_POST['date'];
    $status = $_POST['status'];
    $currentQTY = $_POST['currentQTY']; // Correct this line

    // Check for valid numeric input
    if (!is_numeric($qtyValueNum) || !is_numeric($currentQTY)) {
        echo "Invalid quantity values.";
        exit;
    }

    // Fetch product details
    $stmt_product = $pdo->prepare("SELECT * FROM product WHERE p_product_id = :productID");
    $stmt_product->bindParam(':productID', $productID, PDO::PARAM_INT);
    $stmt_product->execute();
    $details_product = $stmt_product->fetch(PDO::FETCH_ASSOC);

    // Prepare ID for stockin
    $currentDate = date("ymd");
    $stmt_count = $pdo->prepare("SELECT COUNT(*) FROM stockin");
    $stmt_count->execute();
    $count = $stmt_count->fetchColumn() + 1; // Increment for new row
    $i_no = $currentDate . $count;

    try {
        // Insert into stockin
        $stmt = $pdo->prepare("INSERT INTO stockin (i_no, i_product_id, i_product_code, i_current_qty, i_qty, i_cost, i_sale, i_status, i_memo, i_username, i_date, i_mg_code, i_date_add, i_vat) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)");

        // Bind parameters
        $stmt->bindParam(1, $i_no);
        $stmt->bindParam(2, $productID);
        $stmt->bindParam(3, $details_product['p_product_code']);
        $stmt->bindParam(4, $currentQTY);
        $stmt->bindParam(5, $qtyValueNum);
        $stmt->bindParam(6, $details_product['p_cost_price']);
        $stmt->bindParam(7, $details_product['p_sale_price']);
        $stmt->bindParam(8, $status);
        $stmt->bindParam(9, $memo);
        $stmt->bindParam(10, $username);
        $stmt->bindParam(11, $date);
        $stmt->bindParam(12, $mgCode);
        $stmt->bindParam(13, $details_product['p_vat']);

        // Execute the statement
        $stmt->execute();

        // Check status
        if ($status == 2) {
            // Update stockout
            $RTupdateStmt = $pdo->prepare("UPDATE stockout SET o_return = '1' WHERE o_mg_code = ?");
            $RTupdateStmt->bindParam(1, $mgCode);
            $RTupdateStmt->execute();

            // Update stock quantity
            $updateStmt = $pdo->prepare("UPDATE stock SET s_qty = s_qty + ?, s_return_date = NOW() WHERE s_product_id = ?");
            $updateStmt->bindParam(1, $qtyValueNum);
            $updateStmt->bindParam(2, $productID);
            $updateStmt->execute();
        } else {
            // Check if the product exists
            $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM stock WHERE s_product_id = ?");
            $checkStmt->bindParam(1, $productID);
            $checkStmt->execute();
            $productExists = $checkStmt->fetchColumn();

            if ($productExists > 0) {
                // Update stock quantity
                $updateStmt = $pdo->prepare("UPDATE stock SET s_qty = s_qty + ?, s_date_update = NOW() WHERE s_product_id = ?");
                $updateStmt->bindParam(1, $qtyValueNum);
                $updateStmt->bindParam(2, $productID);
                $updateStmt->execute();
            } else {
                // Insert new row into stock
                $insertStmt = $pdo->prepare("INSERT INTO stock (s_product_id, s_product_code, s_product_name, s_qty, s_date_add) VALUES (?, ?, ?, ?, NOW())");
                $insertStmt->bindParam(1, $productID);
                $insertStmt->bindParam(2, $details_product['p_product_code']);
                $insertStmt->bindParam(3, $details_product['p_product_name']);
                $insertStmt->bindParam(4, $qtyValueNum);
                $insertStmt->execute();
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