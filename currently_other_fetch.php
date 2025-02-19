<?php
// Include database connection
include 'connect.php';

// Check if reasons is set
if (isset($_POST['reasons'])) {
    // Get reasons value
    $reasons = $_POST['reasons'];


    try {
        // Set the PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        $stmt = $pdo->prepare("SELECT o.*, p.*, st_name
                                   FROM stockout o
                                   LEFT JOIN product p ON o.o_product_id = p.p_product_id
                                   LEFT JOIN store ON store.st_id = o.o_store
                                   WHERE o.o_return IS NULL  AND o_pr_code IS NULL AND (o.o_store != '1' AND o.o_reasons = 'sale')
                                   ORDER BY o_reasons, o.o_mg_code DESC");


        // Execute the statement
        $stmt->execute();

        // Fetch all rows as an associative array
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Output the table rows
        foreach ($products as $index => $product) {

            //แยกข้อมูลของง reasons
            $data_reasons = explode(",", $product['o_reasons']);

            echo "<tr data-id='" . htmlspecialchars($product['o_id']) . "'>";
            echo '<td class="text-center"><input class="form-check-input" type="checkbox" name="selected_ids[]" value="' . $product['o_mg_code'] . '"></td>';
            echo "<td>" . ($index + 1) . "</td>"; // Display No starting from 1
            echo "<td>" .
                ($product['st_name']) .
                "</td>";
            echo "<td>" . htmlspecialchars($product['o_mg_code']) . "</td>";
            echo "<td>" . htmlspecialchars($product['o_product_code']) . "</td>";
            /* echo "<td>" . htmlspecialchars('Nipponrika') . "</td>"; */
            echo "<td>" . htmlspecialchars($product['p_hands']) . "</td>";
            echo "<td>" . htmlspecialchars($product['p_color']) . "</td>";
            echo "<td>" . htmlspecialchars($product['p_size']) . "</td>";
            echo "<td class='text-end '>" . $product['o_out_qty'] . "</td>";
            echo "<td class='text-end'>" . date('d/m/Y', strtotime($product['o_out_date'])) . "</td>";
            echo "<td class='text-center'>" .   $product['o_customer'] . "</td>";

            echo "<td class='text-center'>";
            if ($product['o_reasons'] == 'out to') {
            } elseif ($product['o_payment_option'] == 2) {
                echo '<span class="badge badge-primary rounded-pill d-inline">QR</span>';
            } elseif ($product['o_payment_option'] == 3) {
                echo  '<span class="badge badge-warning rounded-pill d-inline">Shopify</span>';
            } elseif ($product['o_payment_option'] == 1) {
                echo '<span class="badge badge-success rounded-pill d-inline">Cash</span>';
            } elseif ($product['o_payment_option'] == 4) {
                echo '<span class="badge badge-warning rounded-pill d-inline">Lazada</span>';
            } elseif ($product['o_payment_option'] == 5) {
                echo '<span class="badge badge-warning rounded-pill d-inline">Shopee</span>';
            }
            echo "</td>";

            echo '<td class="text-center">';
            $btnClass = $product['o_payment'] == 1 ? 'btn-success' : 'btn-outline-warning';
            $icon = $product['o_payment'] == 1 ? 'fa-check' : 'fa-hourglass-half';
            echo '<a class="btn ' . $btnClass . ' btn-sm btn-floating update-payment" onclick="updatePayment(\'' . $product['o_mg_code'] . '\', ' . $product['o_payment'] . ')"><i class="fa-solid ' . $icon . '"></i></a>';
            echo "</td>";


            echo '<td class="text-center">';
            $btnClass2 = $product['o_delivery'] == 1 ? 'btn-success' : 'btn-outline-warning';
            $icon2 = $product['o_delivery'] == 1 ? 'fa-check' : 'fa-hourglass-half';
            echo '<a class="btn ' . $btnClass2 . ' btn-sm btn-floating" onclick="updateDelivery(\'' . $product['o_mg_code'] . '\', ' . $product['o_delivery'] . ')"><i class="fa-solid ' . $icon2 . '"></i></a>';
            echo "</td>";

            /* echo "<td class='text-center' style='text-transform: uppercase; background:#FCF3CF;'>" . $data_reasons[0] . "</td>"; */
            echo "<td class='text-center'><span class='badge " . ($product['o_pr_code'] !== null ? 'badge-success' : 'badge-secondary') . "'>" . ($product['o_pr_code'] !== null ? 'issued' : 'pending') . "</span></td>";
            /* echo "<td class='text-center' style='background:#FCF3CF;'>" . '' . "</td>"; */




            echo "<td class='text-center'>" . $product['o_memo'] . "</td>";
            echo "</tr>";
        }
    } catch (PDOException $e) {
        // If an error occurs, output it
        echo "Error: " . $e->getMessage();
    }

    // Close the connection
    $pdo = null;
}
