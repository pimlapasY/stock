<?php
// fetch_stockin_details.php

// Include database connection or any necessary files
include 'connect.php';

if (isset($_POST['i_no'])) {

    $i_no = $_POST['i_no'];
    
    // Prepare SQL statement
    $stmt = $pdo->prepare("SELECT i.*, p.*, u.u_username FROM stockin i 
    LEFT JOIN product p ON p.p_product_id = i.i_product_id 
    LEFT JOIN user u ON u.u_userid = i.i_username 
    WHERE i.i_no = ?");
    
    // Bind parameter
    $stmt->bindParam(1, $i_no, PDO::PARAM_STR);
    
    // Execute the statement
    $stmt->execute();
    
    // Fetch all rows
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    



    // Check if any rows are returned
    if ($stmt->rowCount() > 0) {
         // Output total number of records
        $total_records = $stmt->rowCount();
        echo "<div class='p-3'>";
        echo "<p>" . 'Date : ' . htmlspecialchars($products[0]['i_date_add']) . "</p>";
        echo "<p>" . 'By : ' . htmlspecialchars($products[0]['u_username']) . "</p>";
        echo "<p style='color:" . ($products[0]['i_status'] == 1 ? 'green' : 'orange') . "'>" . ($products[0]['i_status'] == 1 ? 'Purchased' : 'Returned') . "</p>";
        echo '</div>';
        echo '<h4 class="text-end">Total : '. $total_records.' </h4>';
        // Output HTML structure to display details in the modal
        echo "<table class='table table-bordered text-center'>";
        echo "<thead>";
        echo "<tr class='table-secondary'>";
        //echo "<th>Product ID</th>";
        echo "<th>Product Code</th>";
        echo "<th>Product Name</th>";
        echo "<th>Color</th>";
        echo "<th>Size</th>";
        echo "<th>Hand</th>";
        echo "<th>Quantity Add</th>";
        echo "<th>Current Qty</th>";
        echo "<th>Total Price</th>";
        //echo "<th>Supplier</th>";
        /* echo "<th>Status</th>";
        echo "<th>Memo</th>";
        echo "<th>User</th>";
        echo "<th>Date Added</th>"; */
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        
        // Loop through products and output each row
        foreach ($products as $product) {
            $total_qty_add += $product['i_qty'];
            $total_price += ($product['p_cost_price']*$product['i_qty']);
            echo "<tr class='text-center'>";
            //echo "<td>" . htmlspecialchars($product['i_product_id']) . "</td>";
            echo "<td>" . htmlspecialchars($product['i_product_code']) . "</td>";
            echo "<td>" . htmlspecialchars($product['p_product_name']) . "</td>";
            echo "<td>" . htmlspecialchars($product['p_color']) . "</td>";
            echo "<td>" . htmlspecialchars($product['p_size']) . "</td>";
            echo "<td>" . htmlspecialchars($product['p_hands']) . "</td>";
            echo "<td style='color:green;'>+" . htmlspecialchars($product['i_qty']) . "</td>";
            echo "<td style='color:orange;'>" . htmlspecialchars($product['i_current_qty'])  . '(' . htmlspecialchars($product['i_current_qty']+$product['i_qty']) . ')' . "</td>";
            echo "<td class='text-end'>" . number_format($product['p_cost_price']*$product['i_qty']) . "</td>";
            //echo "<td>" . htmlspecialchars($product['i_supplier']) . "</td>";
        /*     echo "<td>" . ($product['i_status'] == 1 ? 'Purchased' : 'Returned') . "</td>";
            echo "<td>" . htmlspecialchars($product['i_memo']) . "</td>";
            echo "<td>" . htmlspecialchars($product['i_username']) . "</td>";
            echo "<td>" . htmlspecialchars($product['i_date_add']) . "</td>"; */
            echo "</tr>";
        }
        
        echo '<tr class="table-secondary">
        <th colspan="5">Total</th>
        <td>'.$total_qty_add.'</td>
        <td class="text-end" colspan="3">'.number_format($total_price).'</td>
        </tr>';
        echo "</tbody>";
        echo "</table>";
    } else {
        echo "No data found for stockin number: $i_no";
    }

} else {
    echo "Invalid request";
}
?>