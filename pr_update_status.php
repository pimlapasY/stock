<?php
// Include database connection
include 'connect.php';
 
//update from 1purchase 2delivery 3stockin
if (isset($_POST['ids']) && isset($_POST['status'])) {
    $ids = $_POST['ids'];
    $status = $_POST['status'];
    $currentDate = date("ymd"); // Format for SQL with '24' for the year

    // Check if the product already exists in the stock table and increment count by 1 for the new row
    $stmt_count = $pdo->prepare("SELECT COUNT(*) FROM stockin");
    $stmt_count->execute();
    $count = $stmt_count->fetchColumn();
    $count++;

    // Generate i_no for the new row
    $i_no = $currentDate . $count;
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
                $stmt_select_pr = $pdo->prepare("SELECT pr_product_id, pr_qty FROM pr WHERE pr_id = :pr_id");
                $stmt_select_pr->bindParam(':pr_id', $id, PDO::PARAM_INT);
                $stmt_select_pr->execute();

                $row = $stmt_select_pr->fetch(PDO::FETCH_ASSOC);
                if ($row) {
                    $pr_product_id = $row['pr_product_id'];
                    $pr_qty = $row['pr_qty'];

                    // Insert into stockin table
                    $stmt_insert_stockin = $pdo->prepare("INSERT INTO stockin (i_no, i_product_id, i_qty, i_status, i_date_add) VALUES (:i_no, :i_product_id, :i_qty, :i_status, NOW())");
                    $stmt_insert_stockin->bindParam(':i_no', $i_no, PDO::PARAM_STR);
                    $stmt_insert_stockin->bindParam(':i_product_id', $pr_product_id, PDO::PARAM_INT);
                    $stmt_insert_stockin->bindParam(':i_qty', $pr_qty, PDO::PARAM_INT);
                    $stmt_insert_stockin->bindParam(':i_status', $i_status, PDO::PARAM_INT);


                    if ($stmt_insert_stockin->execute()) {
                        echo "StockIn record inserted successfully for PR ID $id.<br>";
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
?>