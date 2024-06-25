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

        // Prepare SQL statement based on reasons
        if($reasons == 'sale') {
            $stmt = $pdo->prepare("SELECT o.*, p.*
                                   FROM stockout o
                                   LEFT JOIN product p ON o.o_product_id = p.p_product_id
                                   WHERE o.o_return IS NULL AND o_pr_code IS NULL AND  (o.o_reasons LIKE '%sale,2%' AND o.o_reasons NOT LIKE '%sale sample%')
                                   ORDER BY o_reasons, o.o_mg_code DESC");
        } else {
            $stmt = $pdo->prepare("SELECT o.*, p.*
                                   FROM stockout o
                                   LEFT JOIN product p ON o.o_product_id = p.p_product_id
                                   WHERE o.o_return IS NULL  AND o_pr_code IS NULL AND (o.o_reasons NOT LIKE '%out to%' AND o.o_reasons LIKE '%sale,2%')
                                   ORDER BY o_reasons, o.o_mg_code DESC");
        }

        // Execute the statement
        $stmt->execute();

        // Fetch all rows as an associative array
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Output the table rows
        foreach ($products as $index => $product) {
              
            //แยกข้อมูลของง reasons
            $data_reasons = explode(",", $product['o_reasons']);

         echo "<tr data-id='" . htmlspecialchars($product['o_id']) . "'>";
         echo "<td>" . ($index + 1) . "</td>"; // Display No starting from 1
         echo "<td>" .    
         ($data_reasons[1] == 1 ? 'SAMT' : 
         ($data_reasons[1] == 2 ? 'SAKABA' : 'SAMT')) .
             "</td>";
         echo "<td>" . htmlspecialchars($product['o_mg_code']) . "</td>";
         echo "<td>" . htmlspecialchars($product['o_product_code']) . "</td>";
         /* echo "<td>" . htmlspecialchars('Nipponrika') . "</td>"; */
         echo "<td>" . htmlspecialchars($product['p_hands']) . "</td>";
         echo "<td>" . htmlspecialchars($product['p_color']) . "</td>";
         echo "<td>" . htmlspecialchars($product['p_size']) . "</td>";
         echo "<td class='text-end'>" . $product['o_out_qty'] . "</td>";
         echo "<td class='text-end'>" . date('d/m/Y', strtotime($product['o_out_date'])) . "</td>";
         echo "<td class='text-center'>" . ($data_reasons[3]!= null ? $data_reasons[3] : $data_reasons[0]) . "</td>";
         echo "<td class='text-center'>";
         
         if ($data_reasons[2] == 1) {
             echo '<i class="fa-solid fa-money-bill" style="color: green;"></i><br>cash';
         } elseif ($data_reasons[2] == 2) {
             echo "<i class='fa-solid fa-qrcode' style='color: blue;'></i><br>QR";
         } elseif ($data_reasons[2] == 3) {
             echo "<i class='fa-solid fa-cart-shopping' style='color: orange;''></i><br>shopify";
         } else {
             echo "<p style='color: red;'>FREE</p>"; // กรณีที่ค่าไม่ตรงกับเงื่อนไขที่กำหนด
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
         echo "<td class='text-center' style='color:".($product['o_pr_code'] !== null ? 'green;' : 'red;')."'>" . ($product['o_pr_code'] !== null ? 'issued' : 'unissue') . "</td>";
         /* echo "<td class='text-center' style='background:#FCF3CF;'>" . '' . "</td>"; */
        
        
         echo "<td class='text-center'>" . 
         '<button class="btn btn-outline-warning btn-sm btn-floating edit-btn" data-id="' . $product['o_id'] . '" data-mg-code="' . $product['o_mg_code'] . '" data-payment="' . $product['o_payment'] . '" data-delivery="' . $product['o_delivery'] . '" onclick="showModal(' . $product['o_id'] . ', \'' . $product['o_mg_code'] . '\', \'' . $product['o_payment'] . '\', \'' . $product['o_delivery'] . '\')">
             <i class="fa-regular fa-pen-to-square"></i>
         </button>' . 
            "</td>";
        echo '<td class="text-center"><input class="form-check-input" type="checkbox" name="selected_ids[]" value="' . $product['o_mg_code'] . '"></td>';
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