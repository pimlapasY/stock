<?php
include 'connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and sanitize inputs
    $reqnos = $_POST['reqnos'];
    $prove_username = filter_input(INPUT_POST, 'prove_username', FILTER_VALIDATE_INT);

    // Check if inputs are valid
    if (!empty($reqnos) && is_array($reqnos) && $prove_username !== false && $prove_username !== null) {
        $placeholders = rtrim(str_repeat('?,', count($reqnos)), ',');
        $sql = "UPDATE request SET r_prove_username = ?, r_prove_date = NOW() WHERE r_reqno IN ($placeholders)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(1, $prove_username, PDO::PARAM_INT);
        foreach ($reqnos as $key => $reqno) {
            $stmt->bindValue($key + 2, $reqno, PDO::PARAM_STR);
        }

        // Execute the statement and check for success
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Database update failed']);
        }
    } else {
        // Log the invalid inputs for debugging
        error_log('Invalid input: reqnos=' . json_encode($reqnos) . ', prove_username=' . $prove_username);

        echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>