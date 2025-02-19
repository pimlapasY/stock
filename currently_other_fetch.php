<?php
// Include database connection
include 'connect.php';

// Check if reasons is set
if(isset($_POST['reasons'])) {
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
         if ($product['o_payment_option'] == 1) {
             echo '<span class="badge badge-success rounded-pill d-inline">cash</span>';
         } elseif ($product['o_payment_option'] == 2) {
             echo  '<span class="badge badge-primary rounded-pill d-inline">QR</span>';
         } elseif ($product['o_payment_option'] == 3) {
             echo  '<span class="badge badge-warning rounded-pill d-inline">shopify</span>';
         } else {
             echo  '<span class="badge badge-danger rounded-pill d-inline">sale sample</span>';
         }
      echo "</td>";  
        
         if($product['o_payment'] == 2 || $product['o_payment'] == null){
            echo "<td class='text-center'>" . '<a class="btn btn-outline-secondary btn-sm btn-floating"><i class="fa-solid fa-hourglass-half"></i></a>' . "</td>";
         }else if($product['o_payment'] == 1){
            echo "<td class='text-center'>" . '<a class="btn btn-primary btn-sm btn-floating"><i class="fa-solid fa-check"></i></a>' . "</td>";
         }
         if($product['o_delivery'] == null || $product['o_delivery'] == 2){
            echo "<td class='text-center'>" . '<a class="btn btn-outline-secondary btn-sm btn-floating"><i class="fa-solid fa-hourglass-half"></i></a>' . "</td>";
         }else if($product['o_delivery'] == 1){
            echo "<td class='text-center'>" . '<a class="btn btn-primary btn-sm btn-floating"><i class="fa-solid fa-check"></i></a>' . "</td>";
         }
         /* echo "<td class='text-center' style='text-transform: uppercase; background:#FCF3CF;'>" . $data_reasons[0] . "</td>"; */
         echo "<td class='text-center'><span class='badge ".($product['o_pr_code'] !== null ? 'badge-success' : 'badge-secondary')."'>" . ($product['o_pr_code'] !== null ? 'issued' : 'pending') . "</span></td>";
         /* echo "<td class='text-center' style='background:#FCF3CF;'>" . '' . "</td>"; */
        
        
         
         echo "<td class='text-center'>" . 
         '<button class="btn btn-outline-warning btn-sm edit-btn" data-id="' . $product['o_id'] . '" data-mg-code="' . $product['o_mg_code'] . '" data-payment="' . $product['o_payment'] . '" data-delivery="' . $product['o_delivery'] . '" onclick="showModal(' . $product['o_id'] . ', \'' . $product['o_mg_code'] . '\', \'' . $product['o_payment'] . '\', \'' . $product['o_delivery'] . '\')">
             <i class="fa-solid fa-gear"></i>
         </button>' . 
            "</td>";
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