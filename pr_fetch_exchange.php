<?php
// fetch_data_by_pr_code.php

// Include the database connection
include 'connect.php';

try {
    // Get pr_code from POST data
    $pr_code = $_POST['pr_code'];

    // Prepare SQL query
    $stmt = $pdo->prepare("SELECT o.*, p.*, pr.*
                           FROM pr
                           LEFT JOIN product p ON pr.pr_product_id = p.p_product_id
                           LEFT JOIN stockout o ON o.o_mg_code = pr.pr_mg_code
                           WHERE pr.pr_code = :pr_code");

    // Bind parameter
    $stmt->bindParam(':pr_code', $pr_code, PDO::PARAM_STR);

    // Execute query
    $stmt->execute();

    // Fetch the result as an associative array
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if data is found
    if ($data) {
        // Return data as JSON
        echo json_encode($data);
    } else {
        // No data found for the given pr_code
        echo json_encode(['error' => 'No data found for pr_code: ' . $pr_code]);
    }

} catch (PDOException $e) {
    // Handle database errors
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>