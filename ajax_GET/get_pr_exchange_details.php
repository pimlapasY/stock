<?php
// Include database connection
include '../connect.php';

if (isset($_POST['ids'])) {
    $ids = $_POST['ids'];

    // Prepare the SQL query to fetch details for selected IDs
    $inQuery = implode(',', array_fill(0, count($ids), '?'));

    $stmt = $pdo->prepare("SELECT pr.* , p.*, o.*, st.*
                           FROM pr
                           LEFT JOIN product p ON pr.pr_product_id = p.p_product_id
                           LEFT JOIN stockout o ON pr.pr_mg_code = o.o_mg_code
                           LEFT JOIN store st ON st.st_id = o.o_store
                           WHERE pr.pr_id IN ($inQuery)");

    foreach ($ids as $index => $id) {
        $stmt->bindValue($index + 1, $id);
    }

    // Execute the statement
    $stmt->execute();
    $selectedProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $output = '';
    $total_data_count = 0;
    $numRow = 0;

    foreach ($selectedProducts as $product) {
        $numRow++;

        // Fetch unique options for p_size, p_color, p_hands
        $stmtOptions = $pdo->prepare("SELECT DISTINCT p_size, p_color, p_hands 
                                       FROM product 
                                       WHERE p_product_code = ?");
        $stmtOptions->execute([$product['pr_product_code']]);
        $options = $stmtOptions->fetchAll(PDO::FETCH_ASSOC);

        // Filter unique options for each attribute
        $uniqueSizes = array_unique(array_column($options, 'p_size'));
        $uniqueColors = array_unique(array_column($options, 'p_color'));
        $uniqueHands = array_unique(array_column($options, 'p_hands'));

        $output .= '<tr>';
        $output .= '<td class="text-center">' . $numRow . '</td>';

        $output .= '<td>
                        <a class="btn btn-outline-dark btn-rounded edit-button" 
                           id="showExchange" 
                           onclick="openEditModal(\'' . htmlspecialchars($product['pr_id']) . '\')">'
            . htmlspecialchars($product['pr_code']) .
            '</a>
                    </td>';

        $output .= '<td>' . htmlspecialchars($product['st_name']) . '</td>';
        $output .= '<td>' . htmlspecialchars($product['pr_mg_code']) . '</td>';
        $output .= '<td>' . htmlspecialchars($product['p_product_name']) . '</td>';

        // Generate p_size select
        $output .= '<td><select class="form-select" id="p_size' . htmlspecialchars($product['pr_id']) . '"';
        $output .= ' onclick="updateSelect(this, \'p_size\', \'' . htmlspecialchars($product['pr_product_code']) . '\', ' . htmlspecialchars($product['pr_id']) . ')"';
        $output .= ' onchange="previewExchange(this, \'p_size\', \'' . htmlspecialchars($product['pr_id']) . '\')">';
        foreach ($uniqueSizes as $size) {
            $selected = ($size === $product['p_size']) ? 'selected' : '';
            $output .= '<option value="' . htmlspecialchars($size) . '" ' . $selected . '>'
                . htmlspecialchars($size) . '</option>';
        }
        $output .= '</select></td>';

        // Generate p_color select
        $output .= '<td><select class="form-select" id="p_color' . htmlspecialchars($product['pr_id']) . '"';
        $output .= ' onclick="updateSelect(this, \'p_color\', \'' . htmlspecialchars($product['pr_product_code']) . '\', ' . htmlspecialchars($product['pr_id']) . ')"';
        $output .= ' onchange="previewExchange(this, \'p_color\', \'' . htmlspecialchars($product['pr_id']) . '\')">';
        foreach ($uniqueColors as $color) {
            $selected = ($color === $product['p_color']) ? 'selected' : '';
            $output .= '<option value="' . htmlspecialchars($color) . '" ' . $selected . '>'
                . htmlspecialchars($color) . '</option>';
        }
        $output .= '</select></td>';

        // Generate p_hands select
        $output .= '<td><select class="form-select" id="p_hands' . htmlspecialchars($product['pr_id']) . '"';
        $output .= ' onclick="updateSelect(this, \'p_hands\', \'' . htmlspecialchars($product['pr_product_code']) . '\', ' . htmlspecialchars($product['pr_id']) . ')"';
        $output .= ' onchange="previewExchange(this, \'p_hands\', \'' . htmlspecialchars($product['pr_id']) . '\')">';
        foreach ($uniqueHands as $hands) {
            $selected = ($hands === $product['p_hands']) ? 'selected' : '';
            $output .= '<option value="' . htmlspecialchars($hands) . '" ' . $selected . '>'
                . htmlspecialchars($hands) . '</option>';
        }
        $output .= '</select></td>';

        $output .= '<td class="text-center"><input min="1" class="form-control text-center qty-input" type="number"';
        $output .= ' value="' . htmlspecialchars($product['pr_qty']) . '" oninput="updateTotal()"></td>';

        $output .= '<td>' . htmlspecialchars($product['o_out_date']) . '</td>';
        $output .= '</tr>';

        $total_data_count += $product['pr_qty'];
    }

    $output .= '<tr class="table-warning">';
    $output .= '<th colspan="8" class="text-end">Total : </th>';
    $output .= '<td colspan="1" class="text-center"><input id="totalQty" class="form-control text-center"';
    $output .= ' value="' . $total_data_count . '" readonly></td>';
    $output .= '<td></td>';
    $output .= '</tr>';

    $response = array(
        'html' => $output
    );

    echo json_encode($response);
}
