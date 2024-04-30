<?php
include('connect.php');
date_default_timezone_set('Asia/Bangkok');
$r_reqno = date("Ymd His");

// Check if the form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $department = $_POST['r_department'];
    $rec_date = date("Y-m-d H:i:s");
    $rec_username = $_POST['r_rec_username'];

    // Retrieve formData array sent via AJAX
    $formData = $_POST['formData'];

    // Prepare SQL statement to insert data into the database
    $sql = "INSERT INTO request (r_reqno, r_department, r_product_code, r_qty, r_unit, r_rec_date, r_rec_username) 
            VALUES (:r_reqno, :department, :product_code, :qty, :unit, :rec_date, :rec_username)";

    // Prepare the SQL statement
    $stmt = $pdo->prepare($sql);

    // Loop through formData array and insert each row of data into the database
    foreach ($formData as $data) {
        // Bind parameters
        $stmt->bindParam(':r_reqno', $r_reqno);
        $stmt->bindParam(':department', $department);
        $stmt->bindParam(':product_code', $data['product_code']);
        $stmt->bindParam(':qty', $data['qty']);
        $stmt->bindParam(':unit', $data['unit']);
        $stmt->bindParam(':rec_date', $rec_date);
        $stmt->bindParam(':rec_username', $rec_username);

        // Execute the prepared statement
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            // Return error message
            echo json_encode(array("message" => "Error: " . $e->getMessage()));
            exit; // Stop execution if an error occurs
        }
    }

    // Return success message
    echo json_encode(array("message" => "Form submitted successfully"));
} else {
    // Return error message if form data is not submitted
    echo json_encode(array("message" => "Form data not submitted"));
}
?>