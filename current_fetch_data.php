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
                                   WHERE o.o_reasons LIKE '%sale%'
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
              
                //แยกข้อมูลของง reasons
                $data_reasons = explode(",", $product['o_reasons']);

             echo "<tr data-id='" . htmlspecialchars($product['o_id']) . "'>";
             echo "<td>" . ($index + 1) . "</td>"; // Display No starting from 1
             echo "<td>" . htmlspecialchars($product['o_mg_code']) . "</td>";
             echo "<td>" . htmlspecialchars($product['o_product_code']) . "</td>";
             echo "<td>" . htmlspecialchars('Nipponrika') . "</td>";
             echo "<td>" . htmlspecialchars($product['p_hands']) . "</td>";
             echo "<td>" . htmlspecialchars($product['p_color']) . "</td>";
             echo "<td>" . htmlspecialchars($product['p_size']) . "</td>";
             echo "<td class='text-end'>" . $product['o_out_qty'] . "</td>";
             echo "<td class='text-end'>" . date('d/m/Y', strtotime($product['o_out_date'])) . "</td>";
             echo "<td class='text-center'>" . $data_reasons[2] . "</td>";
             echo "<td class='text-center'>";

             if ($data_reasons[1] == 1) {
                 echo "<i class='fa-solid fa-money-bill'></i><br>cash";
             } elseif ($data_reasons[1] == 2) {
                 echo "<i class='fa-solid fa-qrcode'></i><br>QR";
             } elseif ($data_reasons[1] == 3) {
                 echo "<i class='fa-solid fa-cart-shopping'></i><br>shopify";
             } else {
                 echo "X"; // กรณีที่ค่าไม่ตรงกับเงื่อนไขที่กำหนด
             }
             
             echo "</td>";  
             echo "<td class='text-center' style='background:#FCF3CF;'>" . $data_reasons[0] . "</td>";
             echo "<td class='text-center' style='background:#FCF3CF; color:".($product['o_req_no'] !== null ? 'green;' : 'red;')."'>" . ($product['o_req_no'] !== null ? 'issue' : 'unissue') . "</td>";
             echo "<td class='text-center' style='background:#FCF3CF;'>" . '' . "</td>";
             if($product['o_delivery'] == null){
                echo "<td class='text-center' style='background:#FCF3CF;'>" . '' . "</td>";
             }else{
                echo "<td class='text-center' style='background:#FCF3CF;'>" . '<a class="btn btn-success btn-sm btn-floating"><i class="fa-solid fa-check"></i></a>' . "</td>";
             }
             echo "<td class='text-center'>" . $product['o_memo'] . "</td>";
             echo '<td class="text-center"><input class="form-check-input" type="checkbox" value="" id="flexCheckDefault"></div></td>';
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