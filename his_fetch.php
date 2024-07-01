<?php
// Include database connection
include 'connect.php';
// Variables for pagination
$rowsPerPage = 10; // Number of rows per page
$current_page = isset($_POST['page']) ? (int)$_POST['page'] : 1; // Current page number, default is 1

// Check if store is set
if(isset($_POST['store'])) {
    // Get store value
    $store = $_POST['store'];

    try {
        // Set the PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $offset = ($current_page - 1) * $rowsPerPage; // Calculate offset

        // Prepare SQL statement based on store
        if($store == 'samt') {
            $stmt = $pdo->prepare("SELECT SQL_CALC_FOUND_ROWS o.*, p.*, pr.*
                                   FROM stockout o
                                   LEFT JOIN product p ON o.o_product_id = p.p_product_id
                                    LEFT JOIN pr ON o.o_mg_code = pr.pr_mg_code
                                   WHERE o.o_reasons NOT LIKE '%sale,2%'
                                   ORDER BY o.o_mg_code DESC LIMIT :rowsPerPage OFFSET :offset");
        } elseif($store == 'sakaba') {
            $stmt = $pdo->prepare("SELECT SQL_CALC_FOUND_ROWS o.*, p.*, pr.*
                                   FROM stockout o
                                   LEFT JOIN product p ON o.o_product_id = p.p_product_id
                                    LEFT JOIN pr ON o.o_mg_code = pr.pr_mg_code
                                   WHERE o.o_reasons LIKE '%sale,2%'
                                   ORDER BY o.o_mg_code DESC LIMIT :rowsPerPage OFFSET :offset");
        } else {
            $stmt = $pdo->prepare("SELECT SQL_CALC_FOUND_ROWS o.*, p.*, pr.*
                                   FROM stockout o
                                   LEFT JOIN product p ON o.o_product_id = p.p_product_id
                                    LEFT JOIN pr ON o.o_mg_code = pr.pr_mg_code
                                   ORDER BY o.o_mg_code DESC LIMIT :rowsPerPage OFFSET :offset");
        }

        $stmt->bindParam(':rowsPerPage', $rowsPerPage, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        // Execute the statement
        $stmt->execute();

        // Fetch all rows as an associative array
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        
        // Get total rows
        $total_rows = $pdo->query("SELECT FOUND_ROWS()")->fetchColumn();

        // Start output buffering
        ob_start();
        

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
             
                echo "<td class='text-center' style='color:".($product['o_pr_code'] !== null ? 'green;' : 'red;')."'>" . ($product['o_pr_code'] !== null ? 'issued<br>' : 'unissue') ;
                echo ($product['pr_date_add'] !== null ? substr($product['pr_date_add'], 0, 10) : '');
                echo "</td>";
            }
            echo "<td class='text-center'>" . $product['o_memo'] . "</td>";
            echo "</tr>";
        }
         // Capture table rows
         $tableRows = ob_get_clean();

         // Calculate total pages
         $total_pages = ceil($total_rows / $rowsPerPage);
 
         // Output the table rows and pagination links as JSON
         echo json_encode([
             'tableRows' => $tableRows,
             'pagination' => $total_pages
         ]);

    } catch(PDOException $e) {
        // If an error occurs, output it
        echo "Error: " . $e->getMessage();
    }

    // Close the connection
    $pdo = null;
}
?>