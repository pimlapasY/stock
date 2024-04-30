<?php
// Assuming you have a PDO connection established already
include('../connect.php'); // Include your PDO connection script

// Check if the product code, color, and size are provided via POST request
if(isset($_POST['product_code']) && isset($_POST['color']) && isset($_POST['size'])&& isset($_POST['hand'])) {
    // Sanitize the inputs
    $productCode = htmlspecialchars($_POST['product_code']);
    $color = htmlspecialchars($_POST['color']);
    $size = htmlspecialchars($_POST['size']);
    $hand = htmlspecialchars($_POST['hand']);

    // Prepare and execute a query to fetch the stock quantity for the selected product code, color, and size
    $stmt = $pdo->prepare("SELECT s_qty FROM stock WHERE s_product_code = ? AND s_color = ? AND s_size = ? AND s_hands = ?");
    $stmt->execute([$productCode, $color, $size, $hand]);

    // Fetch the result
    $stockQuantity = $stmt->fetchColumn();

    // Check if a stock quantity was found
    if($stockQuantity !== false) {
        // Echo the stock quantity back to the JavaScript function
        echo $stockQuantity;
    } else {
        // Echo a message indicating that the stock quantity was not found
        echo "Stock quantity not found for product code: $productCode, color: $color, size: $size";
    }
} else {
    // Echo an error message if any of the required parameters are not provided
    echo "Product code, color, or size not provided.";
}
?>