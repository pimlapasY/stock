<?php
// Include database connection
include '../connect.php';

if (isset($_POST['ids'])) {
    try {
        // Start transaction
        $pdo->beginTransaction();

        $usercode_add = $_SESSION['id'];
        $ids = $_POST['ids'];
        $memo = $_POST['memo'];
        $prDate = $_POST['prDate'];

        ///////////////////////////////////////////////////////////////////////////////////////////// 
        //Genarate PR CODE
        $currentDate = date("ymd");
        //////////////////////////////////////////////////////////////////////////////////////////// 

        // Prepare the SQL query to fetch details for selected IDs
        $inQuery = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $pdo->prepare("SELECT o.*, p.*, s.s_qty 
                           FROM stockout o 
                           LEFT JOIN product p ON o.o_product_id = p.p_product_id 
                           LEFT JOIN stock s ON s.s_product_id = o.o_product_id 
                           WHERE o.o_mg_code IN ($inQuery)");

        // Bind the parameters
        foreach ($ids as $index => $id) {
            $stmt->bindValue($index + 1, $id);
        }

        // Execute the statement to fetch product details
        $stmt->execute();
        $selectedProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        ///////////////////////////////////////////////////////////////////////////////////////////// 

        // Prepare the insert statement for stockin table (outside the loop)
        $sql_pr = "INSERT INTO pr (pr_product_id, pr_mg_code, pr_product_code, pr_product_name, pr_cost, pr_total_cost, pr_vat, pr_qty, pr_user_add, pr_date, pr_code, pr_memo, pr_date_add) 
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?, now())";
        $stmt_pr = $pdo->prepare($sql_pr);

        //update pr at stockout
        $sql_update_stockout = "UPDATE stockout SET o_pr_code = ? WHERE o_mg_code = ?";
        $stmt_update_stockout = $pdo->prepare($sql_update_stockout);

        $insertedRows = 0;
        foreach ($selectedProducts as $index => $product) {
            // Check if the product already exists in the stock table
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM pr");
            $stmt->execute();
            $count = $stmt->fetchColumn();

            // Increment count by 1 for the new row
            $count++;

            $pr_code = 'PR'.$currentDate . $count;

            $cost_total_price = floatval($product['p_cost_price']*$product['o_out_qty']);
            // Bind value for each insert
            $stmt_pr->bindValue(1, $product['o_product_id']);
            $stmt_pr->bindValue(2, $product['o_mg_code']);
            $stmt_pr->bindValue(3, $product['o_product_code']);
            $stmt_pr->bindValue(4, $product['p_product_name']);
            $stmt_pr->bindValue(5, $product['p_cost_price']);
            $stmt_pr->bindValue(6, $cost_total_price);
            $stmt_pr->bindValue(7, $product['p_vat']);
            $stmt_pr->bindValue(8, $product['o_out_qty']);
            $stmt_pr->bindValue(9, $usercode_add);
            $stmt_pr->bindValue(10, $prDate);
            $stmt_pr->bindValue(11, $pr_code);
            $stmt_pr->bindValue(12, $memo);

            // Execute insert for each product
            if ($stmt_pr->execute()) {
                $insertedRows++;  // Count the inserted rows
                $stmt_update_stockout->bindValue(1, $pr_code);
                $stmt_update_stockout->bindValue(2, $product['o_mg_code']);

                // Execute update for each product
                if (!$stmt_update_stockout->execute()) {
                    throw new Exception("Error updating stockout for product: " . $product['o_mg_code']);
                }
            } else {
                throw new Exception("Error inserting product: " . $pr_code);
            }
        }

        // If we get here, everything worked. Commit the transaction
        $pdo->commit();

        // After loop or bulk processing
        if ($insertedRows > 0) {
            echo json_encode([
                'status' => 'success',
                'message' => "Successfully inserted $insertedRows rows"
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'No data inserted'
            ]);
        }

    } catch (Exception $e) {
        // An error occurred, rollback the transaction
        $pdo->rollBack();
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
} else {
    echo 'No data received';
}
?>