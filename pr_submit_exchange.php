<?php
// include database connection
include 'connect.php';

// Check if POST data exists
if (isset($_POST['pr_code'])) {
    try {
        // Get data from POST
        $pr_code = $_POST['pr_code'];
        $size = $_POST['size'];
        $color = $_POST['color'];
        $hand = $_POST['hand'];
        $qty = $_POST['qty'];
        $pdCode = $_POST['pdCode']; // Assuming this was a typo in the original script
        $pdName = $_POST['pdName']; // Assuming this was a typo in the original script
        
        $exchange_value = '990'; // The new value you want to set for po_exchange
        $po_status = '1';

        // Prepare SQL statement for insertion
        $stmt = $pdo->prepare("INSERT INTO pr (pr_from_edit_id, po_size, po_color, po_hand, pr_qty, pr_product_code, pr_product_name, po_exchange, po_status, pr_date_add) 
        VALUES (:pr_code, :size, :color, :hand, :qty, :pdCode, :pdName, :exchange, :status, now())");


        // Bind parameters
        $stmt->bindParam(':pr_code', $pr_code, PDO::PARAM_STR);
        $stmt->bindParam(':size', $size, PDO::PARAM_STR);
        $stmt->bindParam(':color', $color, PDO::PARAM_STR);
        $stmt->bindParam(':hand', $hand, PDO::PARAM_STR);
        $stmt->bindParam(':qty', $qty, PDO::PARAM_INT);
        $stmt->bindParam(':pdCode', $pdCode, PDO::PARAM_INT);
        $stmt->bindParam(':pdName', $pdName, PDO::PARAM_STR);
         $stmt->bindParam(':exchange', $exchange_value, PDO::PARAM_INT);
        $stmt->bindParam(':status', $po_status, PDO::PARAM_STR);

        // Execute the statement
        $stmt->execute();

        // Check if insertion was successful
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => 'Data inserted successfully']);
        } else {
            echo json_encode(['error' => 'Failed to insert data']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}
?>