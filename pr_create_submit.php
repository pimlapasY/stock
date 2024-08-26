<?php
// Include the database configuration file
include 'connect.php'; // Ensure you have a db_config.php file with your DB connection details

// Check if the form data is received
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productID = $_POST['productID'];
    $productCode = $_POST['productCode'];
    $prCode = $_POST['prCode'];
    $productName = $_POST['productName'];
    $prQty = $_POST['prQty'];
    $currentDate = date("Y-m-d");

    // Validate the input data (Optional)
   /*  if (empty($productID) || empty($productCode) || empty($prCode) || empty($productName) || empty($prQty)) {
        // Return error response
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit;
    } */

    try {
        // Insert the data into the database
        $sql = "INSERT INTO pr (pr_product_id, pr_product_code, pr_code, pr_product_name, pr_qty, pr_date, pr_date_add) VALUES (:productID, :productCode, :prCode, :productName, :prQty, :currentDate, now())";
        $stmt = $pdo->prepare($sql);

        // Bind the parameters
        $stmt->bindParam(':productID', $productID, PDO::PARAM_INT);
        $stmt->bindParam(':productCode', $productCode, PDO::PARAM_STR);
        $stmt->bindParam(':prCode', $prCode, PDO::PARAM_STR);
        $stmt->bindParam(':productName', $productName, PDO::PARAM_STR);
        $stmt->bindParam(':prQty', $prQty, PDO::PARAM_INT);
        $stmt->bindParam(':currentDate', $currentDate, PDO::PARAM_STR);

        // Execute the statement
        if ($stmt->execute()) {
            // Return success response
            echo json_encode(['status' => 'success', 'message' => 'PR created successfully.']);
        } else {
            // Return error response
            echo json_encode(['status' => 'error', 'message' => 'Failed to create PR.']);
        }
    } catch (PDOException $e) {
        // Return error response
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    // Return error response if not a POST request
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>