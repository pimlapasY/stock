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

    // Prepare SQL statement with pagination
    $offset = ($current_page - 1) * $rowsPerPage; // Calculate offset
    if ($store == 'samt') {
        $stmt = $pdo->prepare("SELECT SQL_CALC_FOUND_ROWS o.*, p.*, pr.*, store.st_name
                               FROM stockout o
                               LEFT JOIN product p ON o.o_product_id = p.p_product_id
                               LEFT JOIN pr ON o.o_mg_code = pr.pr_mg_code
                               LEFT JOIN store ON store.st_id = o.o_store
                               WHERE o.o_store = '1'
                               ORDER BY o.o_mg_code DESC
                               LIMIT :rowsPerPage OFFSET :offset");
    } elseif ($store == 'sakaba') {
        $stmt = $pdo->prepare("SELECT SQL_CALC_FOUND_ROWS o.*, p.*, pr.*, store.st_name
                               FROM stockout o
                               LEFT JOIN product p ON o.o_product_id = p.p_product_id
                               LEFT JOIN pr ON o.o_mg_code = pr.pr_mg_code
                               LEFT JOIN store ON store.st_id = o.o_store
                               WHERE o.o_store != '1'
                               ORDER BY o.o_mg_code DESC
                               LIMIT :rowsPerPage OFFSET :offset");
    } else {
        $stmt = $pdo->prepare("SELECT SQL_CALC_FOUND_ROWS o.*, p.*, pr.*, store.st_name
                               FROM stockout o
                               LEFT JOIN product p ON o.o_product_id = p.p_product_id
                               LEFT JOIN pr ON o.o_mg_code = pr.pr_mg_code
                               LEFT JOIN store ON store.st_id = o.o_store
                               ORDER BY o.o_mg_code DESC
                               LIMIT :rowsPerPage OFFSET :offset");
    }

    // Bind parameters and execute statement
    $stmt->bindParam(':rowsPerPage', $rowsPerPage, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch all rows as an associative array
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

   
// Get total rows for pagination
$total_rows = $pdo->query("SELECT FOUND_ROWS()")->fetchColumn();
$total_pages = ceil($total_rows / $rowsPerPage);

// Calculate starting number for this page
$startNumber = $total_rows - (($current_page - 1) * $rowsPerPage);

    // Output table rows
    $tableRows = '';
    foreach ($products as $index => $product) {
        $rowClass = ($product['o_reasons'] == 'out to') ? 'table-secondary' : (($product['o_return'] == 1) ? 'table-warning' : '');
        $tableRows .= "<tr class='" . htmlspecialchars($rowClass) . "' data-id='" . htmlspecialchars($product['o_id']) . "'>";
        $tableRows .= "<td>" . $startNumber . "</td>";  // เลขลำดับจะเริ่มจากมากไปน้อย
        
        if ($rowClass !== 'table-secondary') {
            $tableRows .= "<td>" . htmlspecialchars($product['st_name']) . "</td>";
        }else{
            $tableRows .= "<td></td>";
        }
        $tableRows .= "<td>" . htmlspecialchars($product['o_mg_code']) . "</td>";
        $tableRows .= "<td>" . htmlspecialchars($product['o_product_code']) . "</td>";
        $tableRows .= "<td>" . htmlspecialchars($product['p_hands']) . "</td>";
        $tableRows .= "<td>" . htmlspecialchars($product['p_color']) . "</td>";
        $tableRows .= "<td>" . htmlspecialchars($product['p_size']) . "</td>";
        $tableRows .= "<td class='text-end'>" . $product['o_out_qty'] . "</td>";
        $tableRows .= "<td class='text-end'>" . date('d/m/Y', strtotime($product['o_out_date'])) . "</td>";

        if ($product['o_reasons'] === 'out to') {
            $tableRows .= "<td class='text-center' colspan='4'> TAKE OUT ";
            $tableRows .= $product['st_name'];
            $tableRows .= "</td>"; // Span 4 columns with specific message
        } else {
            $tableRows .= "<td class='text-center'>";
            $tableRows .= htmlspecialchars($product['o_customer']);
            $tableRows .= "</td>";
        }

        $tableRows .= "<td class='text-center'>";

        if ($product['o_reasons'] == 'out to'){

        } elseif ($product['o_payment_option'] == 2) {
            $tableRows .= '<span class="badge badge-primary rounded-pill d-inline">QR</span>';
        } elseif ($product['o_payment_option'] == 3) {
            $tableRows .=  '<span class="badge badge-warning rounded-pill d-inline">shopify</span>';
        } elseif ($product['o_payment_option'] == 1) {
            $tableRows .= '<span class="badge badge-success rounded-pill d-inline">cash</span>';
        } else {
            $tableRows .=  '<span class="badge badge-danger rounded-pill d-inline">sale sample</span>';
       }
        
        $tableRows .= "</td>";

        if ($product['o_reasons'] == 'out to') {
            //empty
        } elseif ($product['o_payment'] == 2 || $product['o_payment'] == null) {
            $tableRows .= "<td class='text-center'>" . '<span class="badge badge-secondary rounded-pill d-inline">pending</span>' . "</td>";
        } else if ($product['o_payment'] == 1) {
            $tableRows .= "<td class='text-center'>" . '<span class="badge badge-success rounded-pill d-inline">success</span>' . "</td>";
        }

        if ($product['o_reasons'] == 'out to') {
            //empty
        } elseif ($product['o_delivery'] == null || $product['o_delivery'] == 2) {
            $tableRows .= "<td class='text-center'>" . '<span class="badge badge-secondary rounded-pill d-inline">pending</span>' . "</td>";
        } else if ($product['o_delivery'] == 1) {
            $tableRows .= "<td class='text-center'>" . '<span class="badge badge-success rounded-pill d-inline">success</span>' . "</td>";
        }

        if ($product['o_reasons'] == 'out to') {
        //empty
        } else {
            $tableRows .= "<td class='text-center " . ($product['o_pr_code'] !== null ? 'text-success' : 'text-secondary') . "'>";
            $tableRows .= ($product['o_pr_code'] !== null ? 'issued<br>' : 'pending');
            $tableRows .= ($product['pr_date_add'] !== null ? substr($product['pr_date_add'], 0, 10) : '');
            $tableRows .= "</td>";
        }
        $tableRows .= "<td class='text-center'>" . $product['o_memo'] . "</td>";
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
?>