<?php
// Include database connection
include 'connect.php';



// Check if reasons is set
if(isset($_POST['store'])) {
    // Get reasons value
    $store = $_POST['store'];
    $month = $_POST['month'];
    $currentMonth = date('m'); 
    $currentYear = date('Y');

    $previousMonth = date('m', strtotime('-1 month')); // Previous month
    $nextMonth = date('m', strtotime('+1 month')); // Next month
    $startDate = "$currentYear-$previousMonth-21";
    $endDate = "$currentYear-$currentMonth-20";


    try {
        // Set the PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Prepare SQL statement based on reasons
        if ($store == 'all') {
            $sql = "SELECT o.*, p.*, pr.*
                    FROM pr
                    LEFT JOIN product p ON pr.pr_product_id = p.p_product_id
                    LEFT JOIN stockout o ON o.o_mg_code = pr.pr_mg_code
                    WHERE MONTH(pr.pr_date_add) = :month";
        } elseif ($store == 'samt') {
            $sql = "SELECT o.*, p.*, pr.*
                    FROM pr
                    LEFT JOIN product p ON pr.pr_product_id = p.p_product_id
                    LEFT JOIN stockout o ON o.o_mg_code = pr.pr_mg_code
                    WHERE o.o_reasons NOT LIKE '%sale,2%' AND MONTH(pr.pr_date_add) = :month";
        } elseif ($store == 'sakaba') {
            $sql = "SELECT o.*, p.*, pr.*
                    FROM pr
                    LEFT JOIN product p ON pr.pr_product_id = p.p_product_id
                    LEFT JOIN stockout o ON o.o_mg_code = pr.pr_mg_code
                    WHERE o.o_reasons LIKE '%sale,2%' AND MONTH(pr.pr_date_add) = :month";
        } else {
            $sql = "SELECT o.*, p.*, pr.*
                    FROM pr
                    LEFT JOIN product p ON pr.pr_product_id = p.p_product_id
                    LEFT JOIN stockout o ON o.o_mg_code = pr.pr_mg_code
                    WHERE MONTH(pr.pr_date_add) = :month";
        }

        // Add ORDER BY clause to the SQL statement
        $sql .= " AND pr.pr_status = '3' ORDER BY pr.pr_date_add DESC";

        // Prepare the SQL statement
        $stmt = $pdo->prepare($sql);

        // ผูกค่าเดือนกับพารามิเตอร์ใน SQL
        $stmt->bindParam(':month', $month, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        // Fetch all rows as an associative array
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Output the table rows
        foreach ($products as $index => $product) {
            $data_reasons = explode(",", $product['o_reasons']);
            echo '<tr>';
            echo '<td>
            <a class="btn btn-light btn-rounded edit-button" id="showExchange" onclick="openEditModal(\'' . $product['pr_code'] . '\')" style="display: none;">
                <i class="fa-solid fa-right-left"></i>
            </a>' . $product['pr_code'] . '</td>';
            echo  '<td>'.($data_reasons[1] == '2' ? 'SAKABA' : 'SAMT' ).'</td>';
            echo  '<td>'.$product['pr_mg_code'].'</td>';
            echo  '<td>'.$product['p_product_name'].'</td>';
            echo  '<td>'.$product['p_size'].'</td>';
            echo  '<td>'.$product['p_color'].'</td>';
            echo  '<td>'.$product['p_hands'].'</td>';
            echo  '<td class="text-center bg-success-subtle ">'.$product['o_out_qty'].'</td>';
            echo  '<td class="text-center">'.substr($product['pr_date_add'], 0, 10).'</td>';
            echo  '<td>'.$data_reasons[3].'</td>';
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
            /* if($data_reasons[0] == 'out to'){
                //empty
            }elseif($product['o_delivery'] == null || $product['o_delivery'] == 2){
                echo "<td class='text-center'>" . '<a class="btn btn-outline-warning btn-sm btn-floating"><i class="fa-solid fa-hourglass-half"></i></a>' . "</td>";
            } else if($product['o_delivery'] == 1){
                echo "<td class='text-center'>" . '<a class="btn btn-success btn-sm btn-floating"><i class="fa-solid fa-check"></i></a>' . "</td>";
            } */
            echo "<td class='text-center' style='color:".($product['o_pr_code'] !== null ? 'green;' : 'red;')."'>" . ($product['o_pr_code'] !== null ? 'issued<br>' : 'unissue'); 
                        // Check if 'pr_date_add' is not null and create a DateTime object
            $pr_date = $product['pr_date_add'] !== null ? new DateTime($product['pr_date_add']) : null;

            // Format the date to 'Y-m' (year and month) if the DateTime object is created
            $formatted_date = $pr_date ? $pr_date->format('m-Y') : '';
            echo $formatted_date;
            echo "</td>";
            echo '<td class="text-center"><input class="select-checkbox form-check-input" type="checkbox" name="selected_ids[]" value="' . $product['o_mg_code'] . '"></td>';
            echo "<td class='text-center'>" . $product['o_memo'] . "</td>";


            echo '</tr>';
        }

    } catch(PDOException $e) {
        // If an error occurs, output it
        echo "Error: " . $e->getMessage();
    }

    // Close the connection
    $pdo = null;
}
?>