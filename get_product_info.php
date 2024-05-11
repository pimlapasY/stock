<?php
// Include your database connection file (e.g., connect.php)
include('connect.php');

// Check if the product ID is sent via POST
if(isset($_POST['productId'])) {
    // Retrieve the product ID from the POST request
    $productId = $_POST['productId'];

    // Prepare and execute a query to fetch product data based on the product ID
    $sql = "SELECT * FROM stock WHERE s_id = :productId"; // Adjust table and column names as per your database schema
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':productId', $productId, PDO::PARAM_INT); // Assuming product ID is an integer
    $stmt->execute();

    // Fetch the product data as an associative array
    $productData = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if product data is found
    if($productData) {
        // Encode the product data as JSON and echo it as the response
        echo json_encode($productData);
    } else {
        // If product data is not found, echo an empty JSON object
        echo json_encode(array());
    }
} else {
    // If productId is not set, return an error message
    echo "Error: Product ID is not set.";
}
?>
