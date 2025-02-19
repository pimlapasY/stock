<?php
// Include database connection
include 'connect.php';

// Check if request is POST and reqno is set
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reqno'])) {
    // Get reqno from POST data
    $reqno = $_POST['reqno'];

    // SQL to fetch data
    $sql = "SELECT 
        request.*, 
        prove_user.u_username AS prove_username, 
        req_user.u_username AS req_username 
    FROM 
        request
    LEFT JOIN 
        user AS prove_user ON prove_user.u_userid = request.r_prove_username
    LEFT JOIN 
        user AS req_user ON req_user.u_userid = request.r_req_username
    WHERE 
        r_reqno = :reqno";

    // Prepare and execute SQL statement
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':reqno', $reqno, PDO::PARAM_STR);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);


    // SQL to fetch data
    $sql_data_table = "SELECT *
    FROM request
    WHERE r_reqno = :reqno";

    // Prepare and execute SQL statement
    $stmt_data_table = $pdo->prepare($sql_data_table);
    $stmt_data_table->bindParam(':reqno', $reqno, PDO::PARAM_STR);
    $stmt_data_table->execute();

    $no = 1;
    // Format the fetched data as HTML table rows
    $data_table = '';
    while ($row = $stmt_data_table->fetch(PDO::FETCH_ASSOC)) {
        // Format each row as a table row
        $data_table .= '<tr class="text-center">';
        $data_table .= '<td>' . $no . '</td>';
        $data_table .= '<td>' . $row['r_product_code'] . '</td>'; // Adjust column names accordingly
        $data_table .= '<td>' . $row['r_description'] . '</td>';
        $data_table .= '<td>' . $row['r_qty'] . '</td>';
        $data_table .= '<td>' . $row['r_unit'] . '</td>';
        $data_table .= '<td>' . $row['r_objective'] . '</td>';
        // Add more columns as needed
        $data_table .= '</tr>';

        $no++;
    }

    $response = [
        'title' => $data['r_reqno'], // Set the title to the request number
        'data_table' =>  $data_table,
        'dataDep' =>  $data['r_department'],
        'dataDate' =>  $data['r_req_date'],
        'status' => $data['r_prove_username'],
        'prove_name' => $data['prove_username'],
        'rk_username' => $data['r_keep_username'],
        'rec_username' => $data['r_rec_username'],
        'req_username' => $data['req_username']
    ];

    echo json_encode($response);
} else {
    // Invalid request
    echo json_encode(['error' => 'Invalid request']);
}
