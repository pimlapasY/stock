<?php
// Assuming you have a PDO connection established already
include('../connect.php'); // Include your PDO connection script

// Check if the product code, color, size, and hand are provided via POST request
if(isset($_POST['product_code']) && isset($_POST['color']) && isset($_POST['size']) && isset($_POST['hand'])) {
    // Extract the inputs
    $productCode = $_POST['product_code'];
    $color = $_POST['color'];
    $size = $_POST['size'];
    $hand = $_POST['hand'];

    try {
        // Prepare and execute a query to fetch the stock quantity and product cost for the selected product code, color, size, and hand
        $stmt = $pdo->prepare("SELECT stock.s_qty, product.p_cost_price, product.p_qty
            FROM product
            LEFT JOIN stock ON stock.s_product_id = product.p_product_id
            WHERE product.p_product_code = ? AND product.p_color = ? AND product.p_size = ? AND product.p_hands = ?
        ");
        $stmt->execute([$productCode, $color, $size, $hand]);

        // Fetch the result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if a result was found
        if($result !== false) {
            // Encode the result as JSON and echo it back to the JavaScript function
            echo json_encode($result);
        } else {
            // Echo a message indicating that the stock quantity was not found
            echo json_encode(["error" => "Stock quantity not found for product code: $productCode, color: $color, size: $size, hand: $hand"]);
        }
    } catch (PDOException $e) {
        // Handle potential errors
        echo json_encode(["error" => "Database error: " . $e->getMessage()]);
    }
} else {
    // Echo an error message if any of the required parameters are not provided
    echo json_encode(["error" => "Product code, color, size, or hand not provided."]);
}
?>