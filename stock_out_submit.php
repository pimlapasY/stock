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
    $memo = $_POST['memo'];
    $qtyValue = $_POST['qtyValue'];
    $username = $_SESSION['id'];
    $reasons = $_POST['reasons'];
    $date = $_POST['date'];
    $store = $_POST['store'];
    $customerName = $_POST['customerName'];
    $paidBy = isset($_POST['paidOption']) ? $_POST['paidOption'] : null;
    // Fetch product details
    $stmt_product = $pdo->prepare("SELECT * FROM product WHERE p_product_id = :productID");
    $stmt_product->bindParam(':productID', $productID, PDO::PARAM_INT);
    $stmt_product->execute();
    $details_product = $stmt_product->fetch(PDO::FETCH_ASSOC);


    //เช็คว่า มี discount หรือไม่
    
    // Check if discount data is set
    $discountType = isset($_POST['discountType']) ? $_POST['discountType'] : null;
    $discountPrice = isset($_POST['discountPrice']) ? $_POST['discountPrice'] : null;
    $totalSaleVat = isset($_POST['totalSaleVat']) ? $_POST['totalSaleVat'] : null;
    $totalSaleDis = isset($_POST['totalSaleDis']) ? $_POST['totalSaleDis'] : null;

    if ($discountType != 99) {
        // ใช้ค่าที่ผู้ใช้ป้อนเข้ามา
        $discountPrice = $_POST['discountPrice'];
        $totalSaleDis = $_POST['totalSaleDis'];
    } else {
        // หาก $discountType เป็น 99 ให้กำหนดเป็น NULL และใช้ค่า $totalSaleVat
        $discountType = NULL;
        $discountPrice = NULL;
        $totalSaleDis = $totalSaleVat;
    }



    try {
    // Prepare SQL statement
    $stmt = $pdo->prepare("INSERT INTO stockout (o_product_id, o_mg_code, o_product_code, o_product_name, o_cost_price, o_sale_price, o_vat, o_out_qty, o_discount, o_discount_total, o_total_price, o_memo, o_username, o_reasons, o_customer, o_payment_option,  o_out_date, o_store, o_date_add)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? ,?,  NOW())");

        // Bind parameters
        $stmt->bindParam(1, $productID);
        $stmt->bindParam(2, $mgCode);
        $stmt->bindParam(3, $details_product['p_product_code']);
        $stmt->bindParam(4, $details_product['p_product_name']);
        $stmt->bindParam(5, $details_product['p_cost_price']);
        $stmt->bindParam(6, $details_product['p_sale_price']);
        $stmt->bindParam(7, $details_product['p_vat']);
        $stmt->bindParam(8, $qtyValue);
        $stmt->bindParam(9, $discountType);
        $stmt->bindParam(10, $discountPrice);
        $stmt->bindParam(11, $totalSaleDis);
        $stmt->bindParam(12, $memo);
        $stmt->bindParam(13, $username);
        $stmt->bindParam(14, $reasons);
        $stmt->bindParam(15, $customerName);
        $stmt->bindParam(16, $paidBy);
        $stmt->bindParam(17, $date);
        $stmt->bindParam(18, $store);


    

        // Execute SQL statement
        $stmt->execute();


        // Check if the reason is 'sale' or 'sale sample'
        if ($reasons == 'sale' || $reasons == 'sale sample') {

            //ถ้าเป็นยอดขาย หรือ sale sample ให้ตัดสต็อค samt ทุกกรณี 
            // Prepare SQL statement to update stock quantity
            $updateStmt = $pdo->prepare("UPDATE stock SET s_qty = s_qty - ? WHERE s_product_id = ?");

            // Bind parameters for update
            $updateStmt->bindParam(1, $qtyValue);
            $updateStmt->bindParam(2, $productID);

            // Execute SQL statement to update stock quantity
            $updateStmt->execute();


            //ถ้า store ไม่เท่ากับ SAMT ให้ตัดสต็อคอื่นๆด้วยตามไอดี location
            if ($store != '1') {
                // Prepare SQL statement to update stock quantity
                $updateSakaba = $pdo->prepare("UPDATE sub_stock SET sub_qty = sub_qty - ? WHERE sub_product_id = ? AND sub_location = ?");

                // Bind parameters for update
                $updateSakaba->bindParam(1, $qtyValue);
                $updateSakaba->bindParam(2, $productID);
                $updateSakaba->bindParam(3, $store );

                // Execute SQL statement to update stock quantity
                $updateSakaba->execute();
            }
            
        } else {
                   // Check if the sub_stock record exists
            $checkStmt = $pdo->prepare("SELECT * FROM sub_stock WHERE sub_product_id = ? AND sub_location = ?");
            $checkStmt->bindParam(1, $productID);
            $checkStmt->bindParam(2, $store);
            $checkStmt->execute();

            if ($checkStmt->rowCount() > 0) {
                // If exists, update the sub_qty
                $updateSubQtyStmt = $pdo->prepare("UPDATE sub_stock SET sub_qty = sub_qty + ? WHERE sub_product_id = ? AND sub_location = ?");
                $updateSubQtyStmt->bindParam(1, $qtyValue);
                $updateSubQtyStmt->bindParam(2, $productID);
                $updateSubQtyStmt->bindParam(3, $store);
                $updateSubQtyStmt->execute();
            } else {
                // If not exists, insert new entry into sub_stock
                $insertSubStockStmt = $pdo->prepare("INSERT INTO sub_stock (sub_product_id, sub_qty, sub_location, sub_date_add) VALUES (?, ?, ?, NOW())");
                $insertSubStockStmt->bindParam(1, $productID);
                $insertSubStockStmt->bindParam(2, $qtyValue);
                $insertSubStockStmt->bindParam(3, $store);
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