<?php
// Include database connection code
include('connect.php');

try {

// Check if data is received from AJAX
if(isset($_POST['previewData'])) {
    
    // Get current date in YYMMDD format
    $currentDate = date("ymd");

    // Check if the product already exists in the stock table
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM stockin");
    $stmt->execute();
    $count = $stmt->fetchColumn();

    // Increment count by 1 for the new row
    $count++;
    $i_no = $currentDate . $count; //ต้องอยู่นอกลูปเพราะว่าต้องการให้เป็น ID เดียวกันก่อน insert 1 ครั้ง
    $reason = $_POST['reason'];
    $date = $_POST['date_create'];
    $memo = $_POST['memo'];
    $status = $_POST['status'];


// Loop through the previewData array received from AJAX
foreach($_POST['previewData'] as $product) {
      
        //เก็บค่าคนอัพเดทหรือ เพิ่มข้อมูล
        $usercode_add =  $_SESSION['id'];

            // Check the status and perform different actions
    if ($status == '1') {
        // Check if the product already exists in the stock table
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM stock WHERE s_product_id = ?");
        $stmt->execute([$product['p_product_id']]);
        $count = $stmt->fetchColumn();

        // If the product does not exist, insert a new record
        if($count == 0) {
            $sql = "INSERT INTO stock (s_product_id, s_product_code, s_product_name, s_qty, s_date_add) VALUES (:id, :code, :name, :qty, now())";
            $stmt = $pdo->prepare($sql);
            // Bind parameters
            $stmt->bindParam(':id', $product['p_product_id'], PDO::PARAM_INT);
            $stmt->bindParam(':code', $product['p_product_code'], PDO::PARAM_STR); // Assuming product code is a string
            $stmt->bindParam(':name', $product['p_product_name'], PDO::PARAM_STR); // Assuming product name is a string
            $stmt->bindParam(':qty', $product['s_qty'], PDO::PARAM_INT);
            // Execute the SQL statement
            $stmt->execute();
        } else {
            // Prepare SQL statement to update stock quantity
            $sql = "UPDATE stock SET s_qty = s_qty + :qty , s_date_update = now() WHERE s_product_id = :id";
            $stmt = $pdo->prepare($sql);
            // Bind parameters
            $stmt->bindParam(':qty', $product['s_qty'], PDO::PARAM_INT);
            $stmt->bindParam(':id', $product['p_product_id'], PDO::PARAM_INT);
            // Execute the SQL statement
            $stmt->execute();
        }
          // Insert into stockin table
          $sql_insert_stockin = 
          "INSERT INTO stockin (i_no, i_product_id, i_product_code, i_qty, i_current_qty, i_username, i_status, i_date, i_memo, i_date_add) 
          VALUES (:no_code, :id, :code, :qty, :current_qty, :usercode, :reason,  :datevalue, :memo, now())";

          $stmt_insert_stockin = $pdo->prepare($sql_insert_stockin);
          $stmt_insert_stockin->bindParam(':datevalue', $date, PDO::PARAM_STR); 
          $stmt_insert_stockin->bindParam(':no_code', $i_no, PDO::PARAM_STR);
          $stmt_insert_stockin->bindParam(':id', $product['p_product_id'], PDO::PARAM_INT);
          $stmt_insert_stockin->bindParam(':code', $product['p_product_code'], PDO::PARAM_STR);
          $stmt_insert_stockin->bindParam(':qty', $product['s_qty'], PDO::PARAM_INT);
          $stmt_insert_stockin->bindParam(':current_qty', $product['p_qty'], PDO::PARAM_INT);
          $stmt_insert_stockin->bindParam(':usercode', $usercode_add, PDO::PARAM_STR);
          $stmt_insert_stockin->bindParam(':reason', $reason, PDO::PARAM_STR);
          $stmt_insert_stockin->bindParam(':memo', $memo, PDO::PARAM_STR);
          $stmt_insert_stockin->execute();

    } else if ($status == '2') {
                    // ดึงข้อมูล product
            $stmt_product = $pdo->prepare("SELECT * FROM product WHERE p_product_id = ?");
            $stmt_product->execute([$product['p_product_id']]);
            $row_product = $stmt_product->fetch(PDO::FETCH_ASSOC);

            // ตรวจสอบจำนวน pr เพื่อสร้าง pr_code อัตโนมัติ
            $stmt_pr = $pdo->prepare("SELECT COUNT(*) FROM pr");
            $stmt_pr->execute();
            $count_pr = $stmt_pr->fetchColumn();
            $count_pr++;
            $prCode = 'PR'. $currentDate . str_pad($count_pr, 1, '0', STR_PAD_LEFT); // รูปแบบ pr_code

            // เตรียม SQL สำหรับ insert ข้อมูลลงในตาราง pr
            $sql_insert_pr = "
                INSERT INTO pr (
                    pr_code, 
                    pr_product_id, 
                    pr_product_code, 
                    pr_product_name, 
                    pr_qty, 
                    pr_memo, 
                    pr_date, 
                    pr_date_add, 
                    pr_exchange, 
                    pr_user_add, 
                    pr_cost, 
                    pr_sale, 
                    pr_vat
                ) VALUES (
                    :prCode, 
                    :productId, 
                    :productCode, 
                    :productName, 
                    :qty, 
                    :memo, 
                    :date, 
                    now(), 
                    :exchange, 
                    :user, 
                    :cost, 
                    :sale, 
                    :vat
                )";

            // เตรียมค่าและ execute
            $stmt_insert_pr = $pdo->prepare($sql_insert_pr);
            $stmt_insert_pr->bindParam(':prCode', $prCode, PDO::PARAM_STR); 
            $stmt_insert_pr->bindParam(':productId', $row_product['p_product_id'], PDO::PARAM_STR);
            $stmt_insert_pr->bindParam(':productCode', $row_product['p_product_code'], PDO::PARAM_STR);
            $stmt_insert_pr->bindParam(':productName', $row_product['p_product_name'], PDO::PARAM_STR);
            $stmt_insert_pr->bindParam(':qty', $product['p_qty'], PDO::PARAM_INT);
            $stmt_insert_pr->bindParam(':memo', $memo, PDO::PARAM_STR);
            $stmt_insert_pr->bindParam(':date', $date, PDO::PARAM_STR); // ควรรับจากฟอร์ม
            $stmt_insert_pr->bindParam(':exchange', $exchange, PDO::PARAM_STR); // ควรรับจากฟอร์ม
            $stmt_insert_pr->bindParam(':user', $usercode_add, PDO::PARAM_STR);
            $stmt_insert_pr->bindParam(':cost', $row_product['p_cost_price'], PDO::PARAM_STR);
            $stmt_insert_pr->bindParam(':sale', $row_product['p_sale_price'], PDO::PARAM_STR);
            $stmt_insert_pr->bindParam(':vat', $row_product['p_vat'], PDO::PARAM_STR);
            $stmt_insert_pr->execute();

        
    }

}
    // Send success response
    echo "Stock updated successfully!";
} else {
    // Send error response if data is not received
    http_response_code(400); // Bad request
    echo "Error: No data received.";
}
} catch (Exception $e) {
    // Log the error message
    error_log("Error occurred while updating stock: " . $e->getMessage());
    // Send error response
    http_response_code(500); // Internal server error
    echo "Error occurred while updating stock.";
}
?>