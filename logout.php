<?php
include('connect.php'); // Include your database connection

session_start(); // Start the session

$ID = $_SESSION['id']; // Get the user ID from the session

try {
    // Prepare the SQL query to update the logout date in the user table
    $stmt = $pdo->prepare("UPDATE user SET u_logout_date = NOW() WHERE u_userid = :id");
    
    // Bind the ID parameter to the query
    $stmt->bindParam(':id', $ID, PDO::PARAM_INT);
    
    // Execute the query to update the database
    $stmt->execute();
    
    // Unset all session variables
    $_SESSION = array();
    
    // Destroy the session
    session_destroy();
    
    // Send JSON response indicating success
    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    // Handle any exceptions or errors during the query execution
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>