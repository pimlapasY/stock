<?php
// Include database connection
include 'connect.php';

// Check if POST data is received
if (isset($_POST['o_mg_code']) && isset($_POST['new_payment_status'])) {
    $o_mg_code = $_POST['o_mg_code'];
    $new_payment_status = $_POST['new_payment_status'];

    try {
        // Set the PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare the update statement
        $stmt = $pdo->prepare("UPDATE stockout SET o_payment = :new_payment_status WHERE o_mg_code = :o_mg_code");

        // Bind parameters
        $stmt->bindParam(':new_payment_status', $new_payment_status, PDO::PARAM_INT);
        $stmt->bindParam(':o_mg_code', $o_mg_code, PDO::PARAM_STR);

        // Execute the update
        $stmt->execute();

        // Output success message (optional)
        echo "Payment status updated successfully.";
    } catch(PDOException $e) {
        // If an error occurs, output it
        echo "Error: " . $e->getMessage();
    }

    // Close the connection
    $pdo = null;
} else {
    // Handle invalid request (optional)
    echo "Invalid request.";
}
?>