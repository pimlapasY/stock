<?php
include('connect.php');

date_default_timezone_set('Asia/Bangkok');

// Get last two digits of the current year
$year_last_two_digits = substr(date("Y"), -2);

// Get current date with last two digits of the year
$r_reqno_date = $year_last_two_digits . date("md");
$current_date = date('Ymd');

// Retrieve count of requests made on current date
$sql_count = "SELECT COUNT(*) as count FROM request WHERE DATE_FORMAT(r_req_date, '%Y%m%d') = '$current_date'";
$result_count = $pdo->query($sql_count);
$count = 1;

if ($result_count) {
    $row_count = $result_count->fetch(PDO::FETCH_ASSOC);
    $count = $row_count["count"] + 1;
}

// Generate request number
$r_reqno = $r_reqno_date . $count;


// Check if the form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $department = $_POST['r_department'];
    $rec_date = date("Y-m-d H:i:s");
    $rq_username =  $_SESSION['id'];
    $st_username = $_POST['store_keeper'];
    $rec_username = $_POST['r_rec_username'];

    // Retrieve formData array sent via AJAX
    $formData = $_POST['formData'];
    // Prepare SQL statement to insert data into the database
    $sql = "INSERT INTO request (r_reqno, r_description,  r_department, r_product_code, r_qty, r_unit, 	r_objective, r_req_username, 	r_keep_username, r_req_date, r_rec_username) 
            VALUES (:r_reqno, :name, :department, :product_code, :qty, :unit, :target, :rq_username, :st_username, :rec_date, :rec_username)";

    // Prepare the SQL statement
    $stmt = $pdo->prepare($sql);

    // Loop through formData array and insert each row of data into the database
    foreach ($formData as $data) {
        // Bind parameters
        $stmt->bindParam(':r_reqno', $r_reqno);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':department', $department);
        $stmt->bindParam(':product_code', $data['product_code']);
        $stmt->bindParam(':qty', $data['qty']);
        $stmt->bindParam(':unit', $data['unit']);
        $stmt->bindParam(':target', $data['target']);
        $stmt->bindParam(':rec_date', $rec_date);
        $stmt->bindParam(':rq_username',  $rq_username);
        $stmt->bindParam(':st_username',  $st_username);
        $stmt->bindParam(':rec_username', $rec_username);

        // Execute the prepared statement
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            // Return error message
            echo json_encode(array("message" => "Error: " . $e->getMessage()));
            exit; // Stop execution if an error occurs
        }
    

  
}  // Return success message
    echo $r_reqno;
} else {
    // Return error message if form data is not submitted
    echo json_encode(array("message" => "Form data not submitted"));
}
?>