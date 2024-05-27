<?php
// Include database connection
include 'connect.php';

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the raw POST data
    $postData = file_get_contents("php://input");
    // Decode the JSON data
    $data = json_decode($postData, true);

    // Get the product ID and updated fields from the decoded data
    $productId = $data['id'];
    $o_payment = $data['o_payment'];
    $o_delivery = $data['o_delivery'];

    try {
        // Set the PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare the update statement
        $stmt = $pdo->prepare("UPDATE stockout SET o_payment = :o_payment, o_delivery = :o_delivery WHERE o_id = :id");
        $stmt->bindParam(':o_payment', $o_payment, PDO::PARAM_INT);
        $stmt->bindParam(':o_delivery', $o_delivery, PDO::PARAM_INT);
        $stmt->bindParam(':id', $productId, PDO::PARAM_INT);

        // Execute the update
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to update record.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
?>