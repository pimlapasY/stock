<?php
// Include database connection
include 'connect.php';

// Check if store is set
if(isset($_POST['store'])) {
    // Get store value
    $store = $_POST['store'];

    try {
        // Set the PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare SQL statement based on store
        if($store == 'samt') {
            $stmt = $pdo->prepare("SELECT o.*, p.*
                                   FROM stockout o
                                   LEFT JOIN product p ON o.o_product_id = p.p_product_id
                                   WHERE o.o_reasons NOT LIKE '%sale,2%'
                                   ORDER BY o.o_mg_code DESC");
        } elseif($store == 'sakaba') {
            $stmt = $pdo->prepare("SELECT o.*, p.*
                                   FROM stockout o
                                   LEFT JOIN product p ON o.o_product_id = p.p_product_id
                                   WHERE o.o_reasons LIKE '%sale,2%'
                                   ORDER BY o.o_mg_code DESC");
        } else {
            $stmt = $pdo->prepare("SELECT o.*, p.*
                                   FROM stockout o
                                   LEFT JOIN product p ON o.o_product_id = p.p_product_id
                                   ORDER BY o.o_mg_code DESC");
        }

        // Execute the statement
        $stmt->execute();

        // Fetch all rows as an associative array
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Output the table rows
        foreach ($products as $index => $product) {
            // Split data for reasons
            $data_reasons = explode(",", $product['o_reasons']);
            if ($data_reasons[0] == 'out to') {
                echo "<tr class='table-secondary' data-id='" . htmlspecialchars($product['o_id']) . "'>";
            } elseif ($product['o_return'] == 1) {
                echo "<tr class='table-warning' data-id='" . htmlspecialchars($product['o_id']) . "'>";
            } else {
                echo "<tr data-id='" . htmlspecialchars($product['o_id']) . "'>";
            }
            echo "<td>" . ($index + 1) . "</td>"; // Display No starting from 1
            echo "<td>" . ($data_reasons[1] == 1 ? 'SAMT' : ($data_reasons[1] == 2 ? 'SAKABA' : 'SAMT')) . "</td>";
            echo "<td>" . htmlspecialchars($product['o_mg_code']) . "</td>";
            echo "<td>" . htmlspecialchars($product['o_product_code']) . "</td>";
            echo "<td>" . htmlspecialchars($product['p_hands']) . "</td>";
            echo "<td>" . htmlspecialchars($product['p_color']) . "</td>";
            echo "<td>" . htmlspecialchars($product['p_size']) . "</td>";
            echo "<td class='text-end'>" . $product['o_out_qty'] . "</td>";
            echo "<td class='text-end'>" . date('d/m/Y', strtotime($product['o_out_date'])) . "</td>";
          
            if ($data_reasons[0] === 'out to') {
                echo "<td class='text-center' colspan='4'> OUT TO SAKABA </td>"; // Span 4 columns with specific message
            } else {
                echo "<td class='text-center'>";
                echo $data_reasons[3] != null ? htmlspecialchars($data_reasons[3]) : htmlspecialchars($data_reasons[0]);
                echo "</td>";
            }
            
            echo "<td class='text-center'>";

            if ($data_reasons[2] == 1) {
                echo '<i class="fa-solid fa-money-bill" style="color: green;"></i><br>cash';
            } elseif ($data_reasons[2] == 2) {
                echo "<i class='fa-solid fa-qrcode' style='color: blue;'></i><br>QR";
            } elseif ($data_reasons[2] == 3) {
                echo "<i class='fa-solid fa-cart-shopping' style='color: orange;'></i><br>shopify";
            } elseif($data_reasons[0] == 'out to') {
                //empty
            } else {
                echo "<p style='color: red;'>FREE</p>"; // Default case
            }

            echo "</td>";  
            if($data_reasons[0] == 'out to'){
                //empty
            }elseif($product['o_payment'] == 2 || $product['o_payment'] == null){
                echo "<td class='text-center'>" . '<a class="btn btn-outline-warning btn-sm btn-floating"><i class="fa-solid fa-hourglass-half"></i></a>' . "</td>";
            } else if($product['o_payment'] == 1){
                echo "<td class='text-center'>" . '<a class="btn btn-success btn-sm btn-floating"><i class="fa-solid fa-check"></i></a>' . "</td>";
            }
            if($data_reasons[0] == 'out to'){
                //empty
            }elseif($product['o_delivery'] == null || $product['o_delivery'] == 2){
                echo "<td class='text-center'>" . '<a class="btn btn-outline-warning btn-sm btn-floating"><i class="fa-solid fa-hourglass-half"></i></a>' . "</td>";
            } else if($product['o_delivery'] == 1){
                echo "<td class='text-center'>" . '<a class="btn btn-success btn-sm btn-floating"><i class="fa-solid fa-check"></i></a>' . "</td>";
            }
            if($data_reasons[0] == 'out to'){
                //empty
            }else{
            echo "<td class='text-center' style='color:".($product['o_req_no'] !== null ? 'green;' : 'red;')."'>" . ($product['o_req_no'] !== null ? 'issued' : 'unissued') . "</td>";
            }
            echo "<td class='text-center'>" . $product['o_memo'] . "</td>";
            echo "</tr>";
        }

    } catch(PDOException $e) {
        // If an error occurs, output it
        echo "Error: " . $e->getMessage();
    }

    // Close the connection
    $pdo = null;
}
?>