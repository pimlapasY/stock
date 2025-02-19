<?php
// Include database connection
include '../connect.php';

if (isset($_POST['ids'])) {
    $ids = $_POST['ids'];

    // Prepare the SQL query to fetch details for selected IDs
    $inQuery = implode(',', array_fill(0, count($ids), '?'));

    //1. ข้อมูลไว้แก้ไข datalist
    /* $stmt_product = $pdo->prepare("SELECT p.*
                           FROM product p
                           LEFT JOIN pr ON pr.pr_product_id = p.p_product_id
                           WHERE pr.pr_id IN ($inQuery)");

    foreach ($ids as $index => $id) {
        $stmt_product->bindValue($index + 1, $id);
    }

    $stmt_product->execute();
    $selected = $stmt_product->fetchAll(PDO::FETCH_ASSOC); */

    //2. ดึงโชว์ table
    $stmt = $pdo->prepare("SELECT pr.* , p.*, o.*, st.*
                           FROM pr
                           LEFT JOIN product p ON pr.pr_product_id = p.p_product_id
                           LEFT JOIN stockout o ON pr.pr_mg_code = o.o_mg_code
                           LEFT JOIN store st ON st.st_id = o.o_store
                           WHERE pr.pr_id IN ($inQuery)");
    // Bind the parameters
    foreach ($ids as $index => $id) {
        $stmt->bindValue($index + 1, $id);
    }

    // Execute the statement
    $stmt->execute();
    $selectedProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Generate the HTML for the modal body
    $output = '';
    $total_data_count = 0;
    $numRow = 0;
    foreach ($selectedProducts as $index => $product) {
        $numRow++;
            $data_reasons = explode(",", $product['o_reasons']);
            
            
            //เช็คสถานะว่ามีการเปลี่ยน ให้ข้อมูลเก่าโดนซ่อน
            if ($product['pr_exchange'] == null) {
                $output .= '<tr>';
                $output .= '<td class="text-center">';
                //<button class="btn btn-outline-success me-2"><i class="fa-solid fa-check"></i></button>
                $output .=  $numRow;
                //$output .=  '<button class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-rotate-left"></i></button>';
                $output .= '</td>';
            } else {
                $output .= '<tr class="table-secondary" id="exchangeID' . $product['pr_code'] . '" hidden>';
                $output .=  '<td></td>';
            }

            $classColor = ($product['pr_status'] == '1' && $product['pr_exchange'] == null) ? 'success' :
              (($product['pr_status'] == '2' && $product['pr_exchange'] == null) ? 'warning' :
              'secondary');


            $output .= '<td>
                            <a class="btn btn-outline-'.$classColor.' btn-rounded edit-button" id="showExchange" onclick="openEditModal(\'' . $product['pr_id'] . '\')">'
                            . $product['pr_code'] .
                            '</a></td>';

            $output .= '<td>' . $product['st_name']  . '</td>';
            $output .= '<td>' . $product['pr_mg_code'] . '</td>';
            $output .= '<td>' . $product['p_product_name'] . '</td>';


           
    $output .= '<td><select class="form-select " id="p_size' . htmlspecialchars($product['pr_id']) . '"';
    $output .= ' onclick="updateSelect(this, \'p_size\', \'' . htmlspecialchars($product['pr_product_code']) . '\', ' . htmlspecialchars($product['pr_id']) . ')"';
    $output .= ' onchange="previewExchange(this, \'p_size\', \'' . htmlspecialchars($product['pr_id']) . '\')">';
    $output .= '<option value="">' . htmlspecialchars($product['p_size']) . '</option>';
    $output .= '</select></td>';


    $output .= '<td><select class="form-select" id="p_color' . htmlspecialchars($product['pr_id']) . '"';
    $output .= ' onclick="updateSelect(this, \'p_color\', \'' . htmlspecialchars($product['pr_product_code']) . '\', ' . htmlspecialchars($product['pr_id']) . ')"';
    $output .= ' onchange="previewExchange(this, \'p_color\', \'' . htmlspecialchars($product['pr_id']) . '\')">';
    $output .= '<option value="">' . htmlspecialchars($product['p_color']) . '</option>';

    $output .= '</select></td>';


    $output .= '<td><select class="form-select" id="p_hands' . htmlspecialchars($product['pr_id']) . '"';
    $output .= ' onclick="updateSelect(this, \'p_hands\', \'' . htmlspecialchars($product['pr_product_code']) . '\', ' . htmlspecialchars($product['pr_id']) . ')"';
    $output .= ' onchange="previewExchange(this, \'p_hands\', \'' . htmlspecialchars($product['pr_id']) . '\')">';
    $output .= '<option value="">' . htmlspecialchars($product['p_hands']) . '</option>';
    $output .= '</select></td>';


    $output .= '<td class="text-center"><input min="1" class="form-control text-center qty-input" type="number"';
    $output .= ' onchange="previewExchange(this, \'p_color\', \'' . htmlspecialchars($product['pr_id']) . '\')"';
    $output .= ' value="' . htmlspecialchars($product['pr_qty']) . '" oninput="updateTotal()"></td>';

    $output .= '<td>' . $product['o_out_date'] . '</td>';
    $output .= '</tr>';


    $total_data_count += $product['pr_qty'];

    }


    $output .= '<tr class="table-warning">';
        $output .= '<th colspan="8" class="text-end">Total : </th>';
        $output .= '<td colspan="1" class="text-center"><input id="totalQty" class="form-control text-center"
                value="'. $total_data_count .'" readonly></td>';
        $output .= '<td></td>';
        $output .= '</tr>';


    $response = array(
    'html' => $output
    );

    echo json_encode($response);
    }
    ?>