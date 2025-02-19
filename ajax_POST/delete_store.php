<?php
include '../connect.php';
if (isset($_POST['id'])) {

    // Get the 'id' from the POST request
    $id = $_POST['id'];

    try {
        // Prepare the update statement
        $stmt = $pdo->prepare("UPDATE store SET st_hide = 1 WHERE st_id = :id");

        // Execute the update statement with the provided id
        $stmt->execute([':id' => $id]);

        // Check if any rows were updated (optional)
        if ($stmt->rowCount() > 0) {
            // Send a success response
            echo json_encode(['status' => 'success', 'message' => 'Store has been hidden.']);
        } else {
            // If no rows were updated, it may mean the id does not exist
            echo json_encode(['status' => 'error', 'message' => 'Store not found or already hidden.']);
        }
    } catch (PDOException $e) {
        // Handle any errors
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
    }
}
