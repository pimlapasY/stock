<?php
// Include the database connection
include('connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sup_id'])) {
    try {
        // Get the supplier ID to be deleted
        $sup_id = $_POST['sup_id'];

        // Prepare and execute the DELETE query
        $stmt = $pdo->prepare("DELETE FROM suppliers WHERE sup_id = ?");
        $stmt->execute([$sup_id]);

        // Return a success message (optional)
        echo "Supplier deleted successfully.";
    } catch (PDOException $e) {
        // Handle any database errors
        echo "Error: " . $e->getMessage();
    }
} else {
    // If the request method is not POST or sup_id is not set, return an error
    echo "Invalid request.";
}
?>