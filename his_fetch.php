<?php
// Include database connection
include 'connect.php';

// Get the current page and store from the POST request
$current_page = isset($_POST['page']) ? (int)$_POST['page'] : null;
$store = isset($_POST['store']) ? $_POST['store'] : null;

$rowsPerPage = 20; // Number of rows per page

try {
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // สร้าง query เพื่อคำนวณจำนวนแถวทั้งหมดตามเงื่อนไข
    if ($store == 'samt') {
        $count_stmt = $pdo->prepare("SELECT COUNT(*) FROM stockout o WHERE o.o_store = '1'");
    } elseif ($store == 'sakaba') {
        $count_stmt = $pdo->prepare("SELECT COUNT(*) FROM stockout o WHERE o.o_store != '1'");
    } else {
        $count_stmt = $pdo->prepare("SELECT COUNT(*) FROM stockout");
    }

    $count_stmt->execute();
    $totalRows = $count_stmt->fetchColumn(); // จำนวนแถวทั้งหมด
    $totalPages = ceil($totalRows / $rowsPerPage); // คำนวณจำนวนหน้าทั้งหมด

    // ถ้าไม่ได้ส่งค่า page ให้เริ่มต้นที่หน้าสุดท้าย
    if ($current_page === null) {
        $current_page = $totalPages;
    }

    // ตรวจสอบให้หน้าปัจจุบันไม่เกินจำนวนหน้าทั้งหมด
    $current_page = max(1, min($current_page, $totalPages));

    // คำนวณ OFFSET สำหรับดึงข้อมูล
    $offset = ($current_page - 1) * $rowsPerPage;

    // Query สำหรับดึงข้อมูลตามเงื่อนไขและจำกัดแถว
    if ($store == 'samt') {
        $stmt = $pdo->prepare("SELECT o.*, p.*, store.st_name, u.u_username
                              FROM stockout o
                              LEFT JOIN product p ON o.o_product_id = p.p_product_id
                              LEFT JOIN store ON store.st_id = o.o_store
                              LEFT JOIN user u ON u.u_userid = o.o_username
                              WHERE o.o_store = '1'
                              ORDER BY o.o_out_date DESC
                              LIMIT :rowsPerPage OFFSET :offset");
    } elseif ($store == 'sakaba') {
        $stmt = $pdo->prepare("SELECT o.*, p.*, store.st_name, u.u_username
                              FROM stockout o
                              LEFT JOIN product p ON o.o_product_id = p.p_product_id
                              LEFT JOIN store ON store.st_id = o.o_store
                              LEFT JOIN user u ON u.u_userid = o.o_username
                              WHERE o.o_store != '1'
                              ORDER BY o.o_out_date DESC
                              LIMIT :rowsPerPage OFFSET :offset");
    } else {
        $stmt = $pdo->prepare("SELECT o.*, p.*, store.st_name, u.u_username
                              FROM stockout o
                              LEFT JOIN product p ON o.o_product_id = p.p_product_id
                              LEFT JOIN store ON store.st_id = o.o_store
                              LEFT JOIN user u ON u.u_userid = o.o_username
                              ORDER BY o.o_out_date DESC
                              LIMIT :rowsPerPage OFFSET :offset");
    }

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
        $rowClass = ($product['o_reasons'] == 'out to') ? 'table-secondary' : (($product['o_return'] == 1) ? 'table-warning' : '');
        $tableRows .= "<tr class='" . htmlspecialchars($rowClass) . "' data-id='" . htmlspecialchars($product['o_id']) . "'>";
        $tableRows .= "<td>" . $startNumber . "</td>";  // เลขลำดับจะเริ่มจากมากไปน้อย
        $tableRows .= "<td>" . date('d/m/Y', strtotime($product['o_out_date'])) . "</td>";

        $tableRows .= "<td>" . htmlspecialchars($product['st_name']) . "</td>";

        $tableRows .= "<td>" . htmlspecialchars($product['o_mg_code']) . "</td>";
        $tableRows .= "<td>" . htmlspecialchars($product['o_product_code']) . "</td>";
        $tableRows .= "<td>" . htmlspecialchars($product['p_hands']) . "</td>";
        $tableRows .= "<td>" . htmlspecialchars($product['p_color']) . "</td>";
        $tableRows .= "<td>" . htmlspecialchars($product['p_size']) . "</td>";
        $tableRows .= "<td class='text-end'>" . $product['o_out_qty'] . "</td>";

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

        if ($product['o_reasons'] == 'out to') {
        } elseif ($product['o_payment_option'] == 2) {
            $tableRows .= '<span class="badge badge-primary rounded-pill d-inline">QR</span>';
        } elseif ($product['o_payment_option'] == 3) {
            $tableRows .=  '<span class="badge badge-warning rounded-pill d-inline">Shopify</span>';
        } elseif ($product['o_payment_option'] == 1) {
            $tableRows .= '<span class="badge badge-success rounded-pill d-inline">Cash</span>';
        } elseif ($product['o_payment_option'] == 4) {
            $tableRows .=  '<span class="badge badge-warning rounded-pill d-inline">Lazada</span>';
        } elseif ($product['o_payment_option'] == 5) {
            $tableRows .=  '<span class="badge badge-warning rounded-pill d-inline">Shopee</span>';
        }

        $tableRows .= "</td>";

        if ($product['o_reasons'] == 'out to') {
            //empty
        } elseif ($product['o_payment'] == 2 || $product['o_payment'] == null) {
            $tableRows .= "<td class='text-center'>" . '<span class="badge badge-secondary d-inline">-</span>' . "</td>";
        } else if ($product['o_payment'] == 1) {
            $tableRows .= "<td class='text-center'>" . '<span class="badge badge-success d-inline"><i class="fa-solid fa-check"></i></span>' . "</td>";
        }

        if ($product['o_reasons'] == 'out to') {
            //empty
        } elseif ($product['o_delivery'] == null || $product['o_delivery'] == 2) {
            $tableRows .= "<td class='text-center'>" . '<span class="badge badge-secondary d-inline">-</span>' . "</td>";
        } else if ($product['o_delivery'] == 1) {
            $tableRows .= "<td class='text-center'>" . '<span class="badge badge-success d-inline"><i class="fa-solid fa-check"></i></span>' . "</td>";
        }

        $tableRows .= "<td class='text-center'><span class='badge badge-secondary d-inline'>" . $product['u_username'] . "</span></td>";

        if ($product['o_reasons'] == 'out to') {
            //empty
        } else {
            $tableRows .= "<td class='text-center " . ($product['o_pr_code'] !== null ? 'text-success' : 'text-secondary') . "'>";
            $tableRows .= ($product['o_pr_code'] !== null ? '<span class="badge badge-success d-inline"><i class="fa-solid fa-check"></i></span><br>'
                : '<span class="badge badge-secondary d-inline">-</span>');
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
