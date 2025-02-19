<?php
include '../connect.php'; // Include your database connection file

try {
    // Check if POST data is set
    if (isset($_POST['otl_num']) && isset($_POST['otl_name'])) {
        // Get data from the AJAX request
        $otl_num = $_POST['otl_num'];
        $otl_name = $_POST['otl_name'];
        
        // Get current date and time
        $otl_date_add = date('Y-m-d H:i:s');

        // Prepare SQL query to insert data
        $sql = "INSERT INTO options_list (otl_num, otl_name, otl_date_add) VALUES (:otl_num, :otl_name, :otl_date_add)";
        $stmt = $pdo->prepare($sql);
        
        // Bind parameters
        $stmt->bindParam(':otl_num', $otl_num, PDO::PARAM_INT);
        $stmt->bindParam(':otl_name', $otl_name, PDO::PARAM_STR);
        $stmt->bindParam(':otl_date_add', $otl_date_add, PDO::PARAM_STR);

        // Execute the query
        if ($stmt->execute()) {
            echo "Data inserted successfully";
        } else {
            echo "Error inserting data";
        }
    } else {
        echo "Required data not provided";
    }
    
} catch (PDOException $e) {
    // Handle any errors
    echo "Error: " . $e->getMessage();
}

// Close the connection
$pdo = null;
?>