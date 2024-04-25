<?php
include 'connect.php'; 
// Include database connection
session_start();

if ($_SESSION["lang"] == "en") {
  include("lang/lang_en.php");
} else {
  include("lang/lang_th.php");
} 

$currentDate = new DateTime();  // Get current date

$page = $_GET['page'] ?? 1;
$search = $_GET['search'] ?? '';

$limit = 10; // Number of records per page
$offset = ($page - 1) * $limit;
// Fetch data from database
// Fetch data from database
$sql = "SELECT * FROM stock WHERE "; // Base condition

$action = $_POST['action'] ?? '';

// Directly append conditions based on the action
if ($action === '2') {
    // Handle 'ปกติ' action
    $sql .= "s_qty > 7 AND ";
} elseif ($action === '3') {
    // Handle 'สต็อกหมด' action
    $sql .= "s_qty = 0 AND ";
} elseif ($action === '4') {
    // Handle 'ใกล้หมด' action
    $sql .= "(s_qty <= 7 AND s_qty != 0) AND ";
}

// Add search conditions to SQL query
$sql .= "(s_product_code LIKE :search 
           OR s_product_name LIKE :search 
           OR s_color LIKE :search 
           OR s_size LIKE :search)
           ORDER BY s_product_name ASC, s_color ASC 
           LIMIT :limit OFFSET :offset";


// ... (existing PHP code to bind parameters, execute query, and fetch data)


$stmt = $pdo->prepare($sql);
$stmt->bindValue(':search', '%' . $search . '%');
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);


$table_data = '';
foreach ($rows as $index => $row) {
    $table_data .= '<tr>';
    $table_data .= '<td>' . (($index + 1) % 10 == 0 ? $page . '0' : ($page - 1) . ($index + 1)) . '</td>';
    $table_data .= '<td>'. $row['s_product_code'] .'</td>';
    $table_data .= '<td>'. $row['s_product_name'] .'</td>';
    $table_data .= '<td>'. $row['s_qty'] .'</td>';    
    $table_data .= '<td>'. $row['s_color'] .'</td>';
    $table_data .= '<td>'. $row['s_size'] .'</td>';
    $table_data .= '<td>'. $row['s_hands'] .'</td>';
    $table_data .= '<td>'. $row['s_unit'] .'</td>';
    if ($row['s_qty'] == 0) {
      $table_data .= '<td><span class="badge rounded-pill badge-danger">' . $soldOut .'</span></td>';
  } elseif ($row['s_qty'] <= 7) {
      $table_data .= '<td><span class="badge rounded-pill badge-warning">'. $lessStock .'</span></td>';
  } else {
      $table_data .= '<td><span class="badge rounded-pill badge-success">' . $nomal .'</span></td>';
  }
      $table_data .= '</tr>';
}


$total_sql = "SELECT COUNT(*) FROM stock WHERE "; // Base condition

// Directly append conditions based on the action
if ($action === '2') {
  // Handle 'ปกติ' action
  $total_sql .= "s_qty > 7 AND ";
} elseif ($action === '3') {
  // Handle 'สต็อกหมด' action
  $total_sql .= "s_qty = 0 AND ";
} elseif ($action === '4') {
  // Handle 'ใกล้หมด' action
  $total_sql .= "(s_qty <= 7 AND s_qty != 0) AND ";
}

// Add search conditions to total SQL query
$total_sql .= "(s_product_code LIKE :search 
               OR s_product_name LIKE :search 
               OR s_color LIKE :search 
               OR s_size LIKE :search)";

$total_stmt = $pdo->prepare($total_sql);
$total_stmt->bindValue(':search', '%' . $search . '%');
$total_stmt->execute();
$total_rows = $total_stmt->fetchColumn();

// Calculate total pages
$total_pages = ceil($total_rows / $limit);

$pagination = '';
for ($i = 1; $i <= $total_pages; $i++) {
    $active_class = ($i == $page) ? 'active' : '';
    $pagination .= '<li class="page-item ' . $active_class . '"><a class="page-link pagination-link" href="#" data-page="' . $i . '" data-action="' . $action . '">' . $i . '</a></li>';
  }


// Prepare response
$response = [
  'table_data' => $table_data,
  'pagination' => $pagination,

];

echo json_encode($response);
?>