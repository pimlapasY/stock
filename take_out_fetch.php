<?php
// Include database connection
include 'connect.php';

// Get the current page and store from the POST request
$current_page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
$store = isset($_POST['store']) ? $_POST['store'] : null;

$rowsPerPage = 20; // Number of rows per page

try {
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query สำหรับดึงข้อมูลตามเงื่อนไขและจำกัดแถว
    $offset = ($current_page - 1) * $rowsPerPage;

    $stmt = $pdo->prepare("SELECT tout.*, p.*, store.st_name, u.u_username
                           FROM take_out tout
                           LEFT JOIN product p ON tout.to_product_id = p.p_product_id
                           LEFT JOIN store ON store.st_id = tout.to_store
                           LEFT JOIN user u ON u.u_userid = tout.to_username
                           WHERE tout.to_store != '1'
                           ORDER BY tout.to_out_date DESC
                           LIMIT :rowsPerPage OFFSET :offset");
    $count_stmt = $pdo->prepare("SELECT COUNT(*) FROM take_out tout WHERE tout.to_store != '1'");


    // Bind parameters for LIMIT and OFFSET
    $stmt->bindParam(':rowsPerPage', $rowsPerPage, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch data
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Query total rows
    $count_stmt->execute();
    $total_rows = $count_stmt->fetchColumn();

    // Calculate total pages
    $total_pages = ceil($total_rows / $rowsPerPage);

    // Calculate starting number for this page
    $startNumber = $total_rows - (($current_page - 1) * $rowsPerPage);

    // Output table rows
    $tableRows = '';
    foreach ($products as $index => $product) {
        $tableRows .= "<tr data-id='" . htmlspecialchars($product['to_id']) . "'>";
        $tableRows .= "<td>" . $startNumber . "</td>";  // เลขลำดับจะเริ่มจากมากไปน้อย
        $tableRows .= "<td >" . date('d/m/Y', strtotime($product['to_out_date'])) . "</td>";

        $tableRows .= "<td > TAKE OUT ";
        $tableRows .= "<span class='text-primary'>" . $product['st_name'] . "</span>";
        $tableRows .= "</td>"; // Span 4 columns with specific message

        $tableRows .= "<td>" . htmlspecialchars($product['to_code']) . "</td>";
        $tableRows .= "<td>" . htmlspecialchars($product['to_product_code']) . "</td>";
        $tableRows .= "<td>" . htmlspecialchars($product['p_hands']) . "</td>";
        $tableRows .= "<td>" . htmlspecialchars($product['p_color']) . "</td>";
        $tableRows .= "<td>" . htmlspecialchars($product['p_size']) . "</td>";
        $tableRows .= "<td >" . $product['to_out_qty'] . "</td>";



        if ($product['to_delivery'] == null || $product['to_delivery'] == 2) {
            $tableRows .= "<td >" . '<span class="badge badge-secondary rounded-pill d-inline">-</span>' . "</td>";
        } else if ($product['to_delivery'] == 1) {
            $tableRows .= "<td >" . '<span class="badge badge-success rounded-pill d-inline"><i class="fa-solid fa-check"></i></span>' . "</td>";
        }
        $tableRows .= "<td class='text-center'><span class='badge badge-secondary d-inline'>" . $product['u_username'] . "</span></td>";
        $tableRows .= "<td >" . $product['to_memo'] . "</td>";
        $tableRows .= "</tr>";
        $startNumber--;  // ลดค่าลงทีละ 1

    }

    echo json_encode([
        'tableRows' => $tableRows,
        'totalPages' => $total_pages,
    ]);
} catch (PDOException $e) {
    // If an error occurs, output it
    echo json_encode(['error' => "Error: " . $e->getMessage()]);
}

// Close the connection
$pdo = null;
