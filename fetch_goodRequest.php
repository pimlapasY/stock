<?php
include 'connect.php'; 
// Include database connection
session_start();

if ($_SESSION["lang"] == "en") {
  include("lang/lang_en.php");
} else {
  include("lang/lang_th.php");
} 

// Select the count of records where r_reqno exists
$count_sql = "SELECT COUNT(*) FROM request WHERE r_reqno = :r_reqno";
$count_stmt = $pdo->prepare($count_sql);
$count_data = [];

$currentDate = new DateTime();  // Get current date

$page = $_GET['page'] ?? 1;
$search = $_GET['search'] ?? '';

$limit = 10; // Number of records per page
$offset = ($page - 1) * $limit;

if ($_SESSION['role'] == 'admin') {
  $sql = "SELECT * FROM (
    SELECT 
      request.*, 
      user.u_username, 
      ROW_NUMBER() OVER (PARTITION BY request.r_reqno ORDER BY request.r_req_date DESC) as row_num 
    FROM 
      request 
    LEFT JOIN 
      user ON user.u_userid = request.r_prove_username
    WHERE 
      request.r_reqno LIKE :search
  ) AS temp_table 
  WHERE 
    row_num = 1 
  ORDER BY 
    temp_table.r_req_date DESC 
  LIMIT 
    :limit 
  OFFSET 
    :offset
  ";
} else {
  $sql = "SELECT * FROM (
    SELECT 
      request.*, 
      user.u_username, 
      ROW_NUMBER() OVER (PARTITION BY request.r_reqno ORDER BY request.r_req_date DESC) as row_num 
    FROM 
      request 
    LEFT JOIN 
      user ON user.u_userid = request.r_prove_username
    WHERE 
      request.r_reqno LIKE :search
  ) AS temp_table 
  WHERE 
    row_num = 1 
    AND temp_table.r_req_username = :req_username
  ORDER BY 
    temp_table.r_req_date DESC 
  LIMIT 
    :limit 
  OFFSET 
    :offset
  ";
}

// SQL query to select r_objective from request table
$sql_objective = "SELECT GROUP_CONCAT(r_objective SEPARATOR ', ') AS objectives FROM request WHERE r_reqno = :r_reqno";

// Prepare SQL statement
$stmt_objective = $pdo->prepare($sql_objective);

// Prepare SQL statement
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':search', '%' . $search . '%');
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);


if ($_SESSION['role'] != 'admin') {
  $req_username = $_SESSION['id']; // User ID from session
  $stmt->bindParam(':req_username', $req_username, PDO::PARAM_INT);
}

$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$table_data = '';
foreach ($rows as $index => $row) {
   // Bind r_reqno parameter for objective query
   $stmt_objective->bindParam(':r_reqno', $row['r_reqno'], PDO::PARAM_STR);
   // Execute the objective query
   $stmt_objective->execute();
   // Fetch the objectives for the current request
   $rows_objective = $stmt_objective->fetch(PDO::FETCH_ASSOC);

     // Bind r_reqno parameter
     $count_stmt->bindParam(':r_reqno', $row['r_reqno']);
    
     // Execute the query
     $count_stmt->execute();
     
     // Fetch the count
     $count = $count_stmt->fetchColumn();
    
    $table_data .= '<tr>';
   
    
    
    if ($row['r_prove_username'] == null && $_SESSION['role'] == 'admin') {
      $table_data .= '<td><input class="form-check-input checkbox" type="checkbox" value="" data-reqno="' . $row['r_reqno'] . '"></td>';
    } else{
      $table_data .= '<td></td>'  ;
    }

    $table_data .= '<td>'  ;
    $table_data .= (($index + 1) % 10 == 0 ? $page . '0' : ($page - 1) . ($index + 1));
    $table_data .= '</td>';
    $table_data .= '<td><a class="modal-link my_a" data-reqno="' . $row['r_reqno'] . '" data-toggle="modal" data-target="#myModal">' . $row['r_reqno'] . '</a></td>';
    $objectives = $rows_objective['objectives'];
    if (!empty($objectives)) {
        // Add comma only if objectives are not empty
        $objectives = implode(', ', array_filter(explode(', ', $objectives)));
    }
    $table_data .= '<td>'. $objectives .'</td>'; // Display objectives
    $table_data .= '<td>' . $count . '</td>';
    $table_data .= '<td>'.  date('Y-m-d', strtotime($row['r_req_date'])) .'</td>';
    $table_data .= '<td>'.  date('H:i', strtotime($row['r_req_date'])) .' น.</td>';
    if($row['r_prove_username'] == null){
        $table_data .= '<td><span class="badge rounded-pill badge-secondary">' . $wait_accept .'</span></td>';
        $table_data .= '<td><button class="deleteButton btn btn-warning btn-floating" data-reqno="' . $row['r_reqno'] . '" data-mdb-ripple-init><i class="fa-solid fa-xmark"></i></button></td>';
      } else if($row['r_prove_username'] == 99) {
        $table_data .= '<td><span class="badge rounded-pill badge-danger">' . $no_accept .'</span></td>';
        $table_data .= '<td style="color:red;">' . $row['r_remark'] .'</td>';
    }else{
        $table_data .= '<td><span class="badge rounded-pill badge-success">' . $accept.'<br>by '. $row['u_username'] .'</span></td>';
        $table_data .= '<td style="color:green;">' . date('Y-m-d', strtotime($row['r_prove_date'])) . '</td>';
    }
    $table_data .= '</tr>';
}

// Fetch total count of records
$total_sql = "SELECT COUNT(*) FROM (SELECT *, ROW_NUMBER() OVER (PARTITION BY r_reqno ORDER BY r_req_date DESC) as row_num FROM request WHERE r_reqno LIKE :search) AS temp_table WHERE row_num = 1  LIKE :search";
$total_stmt = $pdo->prepare($total_sql);
$total_stmt->bindValue(':search', '%' . $search . '%');
$total_stmt->execute();
$total_rows = $total_stmt->fetchColumn();

// Calculate total pages
$total_pages = ceil($total_rows / $limit);

// Generate pagination
$pagination = '';
for ($i = 1; $i <= $total_pages; $i++) {
    $active_class = ($i == $page) ? 'active' : '';
    $pagination .= '<li class="page-item ' . $active_class . '"><a class="page-link pagination-link" href="#" data-page="' . $i  . '">' . $i . '</a></li>';
}

// Prepare response
$response = [
  'table_data' => $table_data,
  'pagination' => $pagination,
];

echo json_encode($response);
?>