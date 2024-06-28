<?php
// Include database connection
include 'connect.php';

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