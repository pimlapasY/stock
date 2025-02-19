<?php
// Include your database connection file
include 'connect.php';

// Check if AJAX POST request is received
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['form_type']) && $_POST['form_type'] == 'registerCollectionForm') {
        // Sanitize and validate input
        $col_name = isset($_POST['col_name']) ? trim($_POST['col_name']) : '';

        if (empty($col_name)) {
            echo json_encode(["status" => "error", "message" => "Collection name is required."]);
            exit;
        }

        // Prepare SQL query to insert data into the collection table
        $query = "INSERT INTO `collection` (col_name, col_date_add) VALUES (:col_name, NOW())";

        try {
            // Check if the database connection is valid
            if (!$pdo) {
                throw new Exception("Database connection is not established.");
            }

            // Prepare the query
            $stmt = $pdo->prepare($query);

            // Bind the parameters
            $stmt->bindParam(':col_name', $col_name, PDO::PARAM_STR);

            // Execute the query
            $stmt->execute();

            // Check if the record was inserted
            if ($stmt->rowCount() > 0) {
                echo json_encode(["status" => "success", "redirect" => "list_collections.php"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Failed to insert collection."]);
            }
        } catch (PDOException $e) {
            echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid form type."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}

// Close the database connection
$pdo = null;
