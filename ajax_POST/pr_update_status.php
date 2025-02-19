<?php
// Include database connection
include '../connect.php';

//update from 1purchase 2delivery 3stockin
if (isset($_POST['ids']) && isset($_POST['status'])) {
    $ids = $_POST['ids'];
    $status = $_POST['status'];
    $currentDate = date("ymd"); // Format for SQL with '24' for the year
    // กำหนดวันที่ปัจจุบันในรูปแบบ yyyy-mm-dd
    $currentDate2 = date('Y-m-d');
    // ตรวจสอบว่ามีรายการใน stockin ที่ตรงกับวันที่ปัจจุบันหรือไม่
    $stmt_count = $pdo->prepare("SELECT COUNT(*) FROM stockin WHERE i_date = :currentDate");
    $stmt_count->bindParam(':currentDate', $currentDate2, PDO::PARAM_STR);
    $stmt_count->execute();
    $count = $stmt_count->fetchColumn();
    $count++;
    if ($count == 0) {
        // ถ้าไม่มีรายการในวันนั้น เริ่มต้นที่ 01
        $i_no = $currentDate . "01";
    } else {
        // ถ้ามีรายการอยู่แล้ว เพิ่มลำดับ +1
        $count++;
        $i_no = $currentDate . str_pad($count, 2, "0", STR_PAD_LEFT); // เติมเลข 0 ให้อยู่ในรูปแบบ 2 หลัก
    }

    $i_status = 1; // Define the status value

    if (is_array($ids)) {
        foreach ($ids as $id) {
            // Update pr at stockout
            $sql_update_pr = "UPDATE pr SET pr_status = :status, pr_date_update = :currentDate WHERE pr_id = :pr_id";
            $stmt_update_pr = $pdo->prepare($sql_update_pr);

            // Bind the parameters
            $stmt_update_pr->bindParam(':status', $status, PDO::PARAM_INT);
            $stmt_update_pr->bindParam(':pr_id', $id, PDO::PARAM_INT);
            $stmt_update_pr->bindParam(':currentDate', $currentDate, PDO::PARAM_STR);

            // Execute the statement and check for errors
            if ($stmt_update_pr->execute()) {
                echo "Record with ID $id updated successfully.<br>";
            } else {
                echo "Error updating record with ID $id: " . implode(":", $stmt_update_pr->errorInfo()) . "<br>";
                continue; // Skip to the next iteration if update fails
            }

            // Insert into stockin table if status is 3 (assuming $status === 3 means stockin)
            if ($status == 3) {
                // Select pr_product_id and pr_qty from pr table based on pr_id
                $stmt_select_pr = $pdo->prepare("SELECT * FROM pr WHERE pr_id = :pr_id");
                $stmt_select_pr->bindParam(':pr_id', $id, PDO::PARAM_INT);
                $stmt_select_pr->execute();

                $row = $stmt_select_pr->fetch(PDO::FETCH_ASSOC);
                $pr_product_id = $row['pr_product_id'];
                $pr_qty = $row['pr_qty'];
                $pr_product_code = $row['pr_product_code'];
                $pr_product_name = $row['pr_product_name'];
                $pr_cost = $row['pr_cost'];
                $pr_total_cost = $row['pr_total_cost'];
                $pr_vat = $row['pr_vat'];

                if ($row) {
                    // เช็ค stock samt ว่ามี row แล้วหรือไม่
                    $stmt_select_stock = $pdo->prepare("SELECT s_product_id, s_qty FROM stock WHERE s_product_id = :product_id");
                    $stmt_select_stock->bindParam(':product_id',  $pr_product_id, PDO::PARAM_STR);
                    $stmt_select_stock->execute();

                    $rowStock = $stmt_select_stock->fetch(PDO::FETCH_ASSOC);
                    // มีรายการอยู่แล้ว อัปเดต s_qty
                    $current_qty = (int) $rowStock['s_qty']; // แปลงเป็นตัวเลข
                    $additional_qty = (int) $pr_qty;        // แปลงเป็นตัวเลข
                    $new_qty = $current_qty + $additional_qty; // บวกค่า

                    /* --------------------------------------------------------------------- */

                    $memo = 'Stock in FROM PR';
                    // กำหนดวันที่ปัจจุบันในรูปแบบ yyyy-mm-dd
                    $i_date = date('Y-m-d');

                    // Insert into stockin table
                    $stmt_insert_stockin = $pdo->prepare("INSERT INTO stockin (i_no, i_product_id, i_product_code, i_current_qty, i_qty, i_cost, i_total_cost, i_vat, i_status, i_memo, i_date, i_date_add) 
                                                            VALUES (:i_no, :i_product_id, :i_product_code, :i_current_qty, :i_qty, :i_cost, :i_total_cost, :i_vat, :i_status,:i_memo, :i_date, NOW())
                                                            ");
                    $stmt_insert_stockin->bindParam(':i_no', $i_no, PDO::PARAM_STR);
                    $stmt_insert_stockin->bindParam(':i_product_id', $pr_product_id, PDO::PARAM_STR);
                    $stmt_insert_stockin->bindParam(':i_product_code', $pr_product_code, PDO::PARAM_STR);
                    $stmt_insert_stockin->bindParam(':i_current_qty', $current_qty, PDO::PARAM_INT);
                    $stmt_insert_stockin->bindParam(':i_qty', $pr_qty, PDO::PARAM_INT);
                    $stmt_insert_stockin->bindParam(':i_cost', $pr_cost, PDO::PARAM_STR);
                    $stmt_insert_stockin->bindParam(':i_total_cost', $pr_total_cost, PDO::PARAM_STR);
                    $stmt_insert_stockin->bindParam(':i_vat', $pr_vat, PDO::PARAM_STR);
                    $stmt_insert_stockin->bindParam(':i_status', $i_status, PDO::PARAM_STR);
                    $stmt_insert_stockin->bindParam(':i_memo', $memo, PDO::PARAM_STR);
                    $stmt_insert_stockin->bindParam(':i_date', $i_date, PDO::PARAM_STR);
                    /* --------------------------------------------------------------------- */
                    if ($rowStock) {
                        $stmt_update_qty = $pdo->prepare("UPDATE stock 
                                              SET s_qty = :new_qty, s_date_update = NOW() 
                                              WHERE s_product_id = :stock_id");
                        $stmt_update_qty->bindParam(':new_qty', $new_qty, PDO::PARAM_INT);
                        $stmt_update_qty->bindParam(':stock_id', $pr_product_id, PDO::PARAM_STR);
                        $stmt_update_qty->execute();

                        $alert = "อัปเดตสต็อกสำเร็จ!";
                    } else {
                        // เตรียมคำสั่ง SQL
                        $stmt_update_stock = $pdo->prepare("INSERT INTO stock (s_product_id, s_product_code, s_product_name, s_qty, s_date_add) 
                                                                VALUES (:stock_id, :stock_code, :stock_name, :stock_qty, NOW())
                                                            ");
                        // ผูกค่าตัวแปร
                        $stmt_update_stock->bindParam(':stock_id', $pr_product_id, PDO::PARAM_STR);
                        $stmt_update_stock->bindParam(':stock_code', $pr_product_code, PDO::PARAM_STR);
                        $stmt_update_stock->bindParam(':stock_name', $pr_product_name, PDO::PARAM_STR);
                        $stmt_update_stock->bindParam(':stock_qty', $pr_qty, PDO::PARAM_INT);

                        // รันคำสั่ง
                        $stmt_update_stock->execute();

                        $alert = "เพิ่มข้อมูลสต็อกสำเร็จ!";
                    }

                    if ($stmt_insert_stockin->execute()) {
                        echo "StockIn record inserted successfully for PR ID $id.<br>$alert";
                    } else {
                        echo "Error inserting StockIn record for PR ID $id: " . implode(":", $stmt_insert_stockin->errorInfo()) . "<br>";
                    }
                } else {
                    echo "Error: PR ID $id not found in the database.<br>";
                }
            }
        }
    } else {
        echo "Error: 'ids' is not an array.";
    }
} else {
    echo "Error: Required data not provided.";
}



// Check if POST data is received
if (isset($_POST['pr_id'])) {
    //$prCode = $_POST['pr_code'];
    $prStatus = $_POST['status'];
    $prID = $_POST['pr_id'];

    try {
        // Set the PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare the update statement
        $stmt = $pdo->prepare("UPDATE pr SET pr_status = :prStatus, pr_date_update = NOW() WHERE pr_id = :prID");

        // Bind parameters
        // Assuming '2' is the status code for "delivered"
        $stmt->bindParam(':prStatus', $prStatus, PDO::PARAM_STR);
        $stmt->bindParam(':prID', $prID, PDO::PARAM_STR);

        // Execute the update
        $stmt->execute();

        // Output success message in JSON format
        echo json_encode(["status" => "success", "message" => "Payment status updated successfully."]);
    } catch (PDOException $e) {
        // If an error occurs, output it in JSON format
        echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
    }

    // Close the connection
    $pdo = null;
} else {
    // Handle invalid request in JSON format
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}



//update สถานะการชำระเงิน
// Check if POST data is received
if (isset($_POST['pr_code'])) {
    //$prCode = $_POST['pr_code'];
    $prStatus = $_POST['status'];
    $prCode = $_POST['pr_code'];

    try {
        // Set the PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare the update statement
        $stmt = $pdo->prepare("UPDATE pr SET pr_status = :prStatus, pr_date_update = NOW() WHERE pr_code = :prCode");

        // Bind parameters
        // Assuming '2' is the status code for "delivered"
        $stmt->bindParam(':prStatus', $prStatus, PDO::PARAM_STR);
        $stmt->bindParam(':prCode', $prCode, PDO::PARAM_STR);

        // Execute the update
        $stmt->execute();

        // Output success message in JSON format
        echo json_encode(["status" => "success", "message" => "Payment status updated successfully."]);
    } catch (PDOException $e) {
        // If an error occurs, output it in JSON format
        echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
    }

    // Close the connection
    $pdo = null;
} else {
    // Handle invalid request in JSON format
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}
