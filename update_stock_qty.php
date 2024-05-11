<?php
// Include database connection code
include('connect.php');

// Check if data is received from AJAX
if(isset($_POST['previewData'])) {
    // Loop through the previewData array received from AJAX
    foreach($_POST['previewData'] as $product) {
        // Prepare SQL statement to update stock quantity
        $sql = "UPDATE stock SET s_qty = s_qty + :qty WHERE s_id = :id";
        $stmt = $pdo->prepare($sql);
        // Bind parameters
        $stmt->bindParam(':qty', $product['s_qty'], PDO::PARAM_INT);
        $stmt->bindParam(':id', $product['s_id'], PDO::PARAM_INT);
        // Execute the SQL statement
        $stmt->execute();
    }
    // Send success response
    echo "Stock updated successfully!";
} else {
    // Send error response if data is not received
    http_response_code(400); // Bad request
    echo "Error: No data received.";
}
?>