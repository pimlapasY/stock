<?php
// Include your database connection file
include('connect.php');

// Check if data is sent via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data sent from the frontend
    $postData = $_POST['data']; // Assuming data is sent as JSON

    // Decode JSON data
    $stockData = json_decode($postData, true);

    // Loop through each product data and update the database
    foreach ($stockData as $product) {
        // Extract product details
        $productId = $product['id'];
        $samQty = $product['samQty'];
        $sakabaQty = $product['sakabaQty'];

        // Update the stock in the database
        $stmt = $pdo->prepare("UPDATE stock SET s_qty = s_qty + :samQty WHERE s_id = :productId AND s_location = 'SAMT'");
        $stmt->bindParam(':samQty', $samQty);
        $stmt->bindParam(':productId', $productId);
        $stmt->execute();

        $stmt = $pdo->prepare("UPDATE stock SET s_qty = s_qty + :sakabaQty WHERE s_id = :productId AND s_location = 'SAKABA'");
        $stmt->bindParam(':sakabaQty', $sakabaQty);
        $stmt->bindParam(':productId', $productId);
        $stmt->execute();
    }

    // Send response
    echo json_encode(['status' => 'success', 'message' => 'Stock updated successfully']);
} else {
    // If request method is not POST, return an error response
    http_response_code(405); // Method Not Allowed
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
}
?>