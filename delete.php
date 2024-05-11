<?php
// Include the database connection
include('connect.php');

// Check if the sup_id parameter is set
if(isset($_POST['sup_id'])) {
    // Get the supplier ID to be deleted
    $sup_id = $_POST['sup_id'];
    
    try {
        // Prepare and execute the DELETE query
        $stmt = $pdo->prepare("DELETE FROM suppliers WHERE sup_id = ?");
        $stmt->execute([$sup_id]);
        
        // If deletion is successful, send a success response
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        // If an error occurs during deletion, send an error response
        echo json_encode(['error' => 'Error deleting supplier: ' . $e->getMessage()]);
    }
} else {
    // If sup_id parameter is not set, send an error response
    echo json_encode(['error' => 'Supplier ID not provided']);
}
?>