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
    $productCost = $_POST['productCost'];
    $productTotal = $_POST['productTotal'];
    $memo = $_POST['memo'];
    $qtyValueNum = $_POST['qtyValueNum'];
    $username = $_SESSION['id'];
    $date = $_POST['date'];
    $status = $_POST['status'];
    $currentQTY = $_POST['currentQTY'];

        // Get current date in YYMMDD format
        $currentDate = date("ymd");

        // Check if the product already exists in the stock table
        $stmt_count = $pdo->prepare("SELECT COUNT(*) FROM stockin");
        $stmt_count->execute();
        $count = $stmt_count->fetchColumn();

        // Increment count by 1 for the new row
        $count++;
        $i_no = $currentDate . $count; //ต้องอยู่นอกลูปเพราะว่าต้องการให้เป็น ID เดียวกันก่อน insert 1 ครั้ง

    try {
        // Prepare SQL statement
        $stmt = $pdo->prepare("INSERT INTO stockin (i_no, i_product_id, i_product_code, i_current_qty, i_qty, i_total_price, i_status, i_memo, i_username, i_date, i_mg_code, i_date_add) 
        VALUES (?,?,?,?,?,?,?,?,?,?,?, now())");

        // Bind parameters
        $stmt->bindParam(1, $i_no);
        $stmt->bindParam(2, $productID);
        $stmt->bindParam(3, $productCode);
        $stmt->bindParam(4, $currentQTY);
        $stmt->bindParam(5, $qtyValueNum);
        $stmt->bindParam(6, $productTotal);
        $stmt->bindParam(7, $status);
        $stmt->bindParam(8, $memo);
        $stmt->bindParam(9, $username);
        $stmt->bindParam(10, $date);
        $stmt->bindParam(11, $mgCode);


        // Execute SQL statement
        $stmt->execute();


        if($status == 2){

        // Prepare SQL statement to update stock quantity
        $RTupdateStmt = $pdo->prepare("UPDATE stockout SET o_return = '1' WHERE o_mg_code = ?");

        // Bind parameters for update
        $RTupdateStmt->bindParam(1, $mgCode);

        // Execute SQL statement to update stock quantity
        $RTupdateStmt->execute();

        
        // Prepare SQL statement to update stock quantity
        $updateStmt = $pdo->prepare("UPDATE stock SET s_qty = s_qty + ?, s_return_date = NOW() WHERE s_product_id = ?");

        // Bind parameters for update
        $updateStmt->bindParam(1, $qtyValueNum);
        $updateStmt->bindParam(2, $productID);

        // Execute SQL statement to update stock quantity
        $updateStmt->execute();
        }else{
             // Prepare SQL statement to update stock quantity
        $updateStmt = $pdo->prepare("UPDATE stock SET s_qty = s_qty + ?, s_date_update = NOW() WHERE s_product_id = ?");

        // Bind parameters for update
        $updateStmt->bindParam(1, $qtyValueNum);
        $updateStmt->bindParam(2, $productID);

        // Execute SQL statement to update stock quantity
        $updateStmt->execute();
        }
/* 
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
        } */

        // Print success message
        echo "Form submitted successfully";
    } catch (PDOException $e) {
        // Print error message
        echo "Error: " . $e->getMessage();
    }
}
?>