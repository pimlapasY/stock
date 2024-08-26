<?php
// Include database connection
include 'connect.php';

// Get the current page and store from the POST request
$current_page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
$store = isset($_POST['store']) ? $_POST['store'] : null;

$rowsPerPage = 15; // Number of rows per page

try {
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare SQL statement with pagination
    $offset = ($current_page - 1) * $rowsPerPage; // Calculate offset
    if ($store == 'samt') {
        $stmt = $pdo->prepare("SELECT SQL_CALC_FOUND_ROWS o.*, p.*, pr.*
                               FROM stockout o
                               LEFT JOIN product p ON o.o_product_id = p.p_product_id
                               LEFT JOIN pr ON o.o_mg_code = pr.pr_mg_code
                               WHERE o.o_store = '1'
                               ORDER BY o.o_mg_code DESC
                               LIMIT :rowsPerPage OFFSET :offset");
    } elseif ($store == 'sakaba') {
        $stmt = $pdo->prepare("SELECT SQL_CALC_FOUND_ROWS o.*, p.*, pr.*
                               FROM stockout o
                               LEFT JOIN product p ON o.o_product_id = p.p_product_id
                               LEFT JOIN pr ON o.o_mg_code = pr.pr_mg_code
                               WHERE o.o_store != '1'
                               ORDER BY o.o_mg_code DESC
                               LIMIT :rowsPerPage OFFSET :offset");
    } else {
        $stmt = $pdo->prepare("SELECT SQL_CALC_FOUND_ROWS o.*, p.*, pr.*
                               FROM stockout o
                               LEFT JOIN product p ON o.o_product_id = p.p_product_id
                               LEFT JOIN pr ON o.o_mg_code = pr.pr_mg_code
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

    // Output table rows
    $tableRows = '';
    foreach ($products as $index => $product) {
        $data_reasons = explode(",", $product['o_reasons']);
        if ($data_reasons[0] == 'out to') {
            $tableRows .= "<tr class='table-secondary' data-id='" . htmlspecialchars($product['o_id']) . "'>";
        } elseif ($product['o_return'] == 1) {
            $tableRows .= "<tr class='table-warning' data-id='" . htmlspecialchars($product['o_id']) . "'>";
        } else {
            $tableRows .= "<tr data-id='" . htmlspecialchars($product['o_id']) . "'>";
        }
        $tableRows .= "<td>" . ($index + 1) . "</td>"; // Display No starting from 1
        $tableRows .= "<td>" . ($product['o_store']) . "</td>";
        $tableRows .= "<td>" . htmlspecialchars($product['o_mg_code']) . "</td>";
        $tableRows .= "<td>" . htmlspecialchars($product['o_product_code']) . "</td>";
        $tableRows .= "<td>" . htmlspecialchars($product['p_hands']) . "</td>";
        $tableRows .= "<td>" . htmlspecialchars($product['p_color']) . "</td>";
        $tableRows .= "<td>" . htmlspecialchars($product['p_size']) . "</td>";
        $tableRows .= "<td class='text-end'>" . $product['o_out_qty'] . "</td>";
        $tableRows .= "<td class='text-end'>" . date('d/m/Y', strtotime($product['o_out_date'])) . "</td>";

        if ($data_reasons[0] === 'out to') {
            $tableRows .= "<td class='text-center' colspan='4'> TAKE OUT ";
            $tableRows .= $product['o_store'];
            $tableRows .= "</td>"; // Span 4 columns with specific message
        } else {
            $tableRows .= "<td class='text-center'>";
            $tableRows .= $data_reasons[3] != null ? htmlspecialchars($data_reasons[3]) : htmlspecialchars($data_reasons[0]);
            $tableRows .= "</td>";
        }

        $tableRows .= "<td class='text-center'>";

        if ($data_reasons[1] == 1) {
            $tableRows .= '<span class="badge badge-success rounded-pill d-inline">cash</span>';
        } elseif ($data_reasons[1] == 2) {
            $tableRows .= '<span class="badge badge-primary rounded-pill d-inline">QR</span>';
        } elseif ($data_reasons[1] == 3) {
            $tableRows .=  '<span class="badge badge-warning rounded-pill d-inline">shopify</span>';
        }  elseif ($data_reasons[0] == 'out to') {
        } else {
            $tableRows .=  '<span class="badge badge-danger rounded-pill d-inline">sale sample</span>';

       }
        
        $tableRows .= "</td>";

        if ($data_reasons[0] == 'out to') {
            //empty
        } elseif ($product['o_payment'] == 2 || $product['o_payment'] == null) {
            $tableRows .= "<td class='text-center'>" . '<span class="badge badge-secondary rounded-pill d-inline">pending</span>' . "</td>";
        } else if ($product['o_payment'] == 1) {
            $tableRows .= "<td class='text-center'>" . '<span class="badge badge-success rounded-pill d-inline">success</span>' . "</td>";
        }

        if ($data_reasons[0] == 'out to') {
            //empty
        } elseif ($product['o_delivery'] == null || $product['o_delivery'] == 2) {
            $tableRows .= "<td class='text-center'>" . '<span class="badge badge-secondary rounded-pill d-inline">pending</span>' . "</td>";
        } else if ($product['o_delivery'] == 1) {
            $tableRows .= "<td class='text-center'>" . '<span class="badge badge-success rounded-pill d-inline">success</span>' . "</td>";
        }

        if ($data_reasons[0] == 'out to') {
            //empty
        } else {
            $tableRows .= "<td class='text-center " . ($product['o_pr_code'] !== null ? 'text-success' : 'text-secondary') . "'>";
            $tableRows .= ($product['o_pr_code'] !== null ? 'issued<br>' : 'pending');
            $tableRows .= ($product['pr_date_add'] !== null ? substr($product['pr_date_add'], 0, 10) : '');
            $tableRows .= "</td>";
            
        }
        $tableRows .= "<td class='text-center'>" . $product['o_memo'] . "</td>";
        $tableRows .= "</tr>";
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