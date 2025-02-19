<?php
// Include database connection
include 'connect.php';

if (isset($_POST['ids'])) {
    $ids = $_POST['ids'];

    // Prepare the SQL query to fetch details for selected IDs
    $inQuery = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT o.*, p.* 
                           FROM stockout o
                           LEFT JOIN product p ON o.o_product_id = p.p_product_id
                           WHERE o.o_mg_code IN ($inQuery)");

    // Bind the parameters
    foreach ($ids as $index => $id) {
        $stmt->bindValue($index + 1, $id);
    }

    // Execute the statement
    $stmt->execute();
    $selectedProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Generate the HTML for the modal body
    $output = '<div class="table-responsive">
                <table class="table text-center table-bordered table-sm">';
    $output .= '<thead style="text-transform: uppercase;">
                <tr class="table-light">
                <th>#</th>
                <th>MG</th>
                <th>CODE</th>
                <th>product</th>
                <th>Option 1</th>
                <th>Option 2</th>
                <th>Option 3</th>
                <th>qty</th>
                <th>Sold date</th>
                <th>customer</th>
                <th>paid by</th>
                </tr></thead><tbody class="table-group-divider table-divider-color">';

    foreach ($selectedProducts as $index => $product) {
        $data_reasons = explode(",", $product['o_reasons']);
        $output .= '<tr>';
        $output .= "<td>" . ($index + 1) . "</td>"; // Display No starting from 1
        $output .= '<td class="text-start">' . htmlspecialchars($product['o_mg_code']) . '</td>';
        $output .= '<td class="text-start">' . htmlspecialchars($product['o_product_code']) . '</td>';
        $output .= '<td class="text-start">' . $product['p_product_name']  . '</td>';
        $output .= "<td>" . htmlspecialchars($product['p_hands']) . "</td>";
        $output .= "<td>" . htmlspecialchars($product['p_color']) . "</td>";
        $output .= "<td>" . htmlspecialchars($product['p_size']) . "</td>";
        $output .= "<td class='text-end text-success'>" . htmlspecialchars($product['o_out_qty']) . "</td>";
        $output .= "<td class='text-center'>" . date('d/m/Y', strtotime($product['o_out_date'])) . "</td>";
        $output .= "<td class='text-center'>" . $product['o_customer'] . "</td>";
        $output .=  "<td class='text-center'>";
             
        if ($product['o_payment_option'] == 1) {
            $output .= '<span class="badge badge-success rounded-pill d-inline">cash</span>';
        } elseif ($product['o_payment_option'] == 2) {
            $output .=  '<span class="badge badge-primary rounded-pill d-inline">QR</span>';
        } elseif ($product['o_payment_option'] == 3) {
            $output .=  '<span class="badge badge-warning rounded-pill d-inline">shopify</span>';;
        } else {
            $output .=  '<span class="badge badge-danger rounded-pill d-inline">sale sample</span>';// กรณีที่ค่าไม่ตรงกับเงื่อนไขที่กำหนด
        }
       /*  
        $output .=  "</td>";  
        if($product['o_payment'] == 2 || $product['o_payment'] == null){
            $output .=  "<td class='text-center'>" . '<a class="btn btn-outline-warning btn-sm btn-floating"><i class="fa-solid fa-hourglass-half"></i></a>' . "</td>";
        }else if($product['o_payment'] == 1){
            $output .=  "<td class='text-center'>" . '<a class="btn btn-success btn-sm btn-floating"><i class="fa-solid fa-check"></i></a>' . "</td>";
        }
        if($product['o_delivery'] == null || $product['o_delivery'] == 2){
            $output .=  "<td class='text-center'>" . '<a class="btn btn-outline-warning btn-sm btn-floating"><i class="fa-solid fa-hourglass-half"></i></a>' . "</td>";
        }else if($product['o_delivery'] == 1){
            $output .=  "<td class='text-center'>" . '<a class="btn btn-success btn-sm btn-floating"><i class="fa-solid fa-check"></i></a>' . "</td>";
        } */

        /* echo "<td class='text-center' style='text-transform: uppercase; background:#FCF3CF;'>" . $data_reasons[0] . "</td>"; */
        //$output .=  "<td class='text-center' style='background:#FCF3CF; color:".($product['o_req_no'] !== null ? 'green;' : 'red;')."'>" . ($product['o_req_no'] !== null ? 'issued' : 'unissue') . "</td>";
        /* echo "<td class='text-center' style='background:#FCF3CF;'>" . '' . "</td>"; */
       
       // $output .=  "<td class='text-center'>" . $product['o_memo'] . "</td>";
        $output .= '</tr>';

        $total_data_count += $product['o_out_qty'];
    }

    $output .= '</tbody></table></div>' ;
    $output .=  '<h1 class="text-end"> Total: ' . $total_data_count . '</h1>';

    echo $output;
}
?>