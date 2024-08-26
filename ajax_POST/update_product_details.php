<?php
include('../connect.php');

// Get the JSON data from the request
$data = json_decode(file_get_contents('php://input'), true);

if ($data && isset($data['products']) && isset($data['updateForm'])) {
    $products = $data['products'];
    $status = isset($data['status']) ? $data['status'] : null;
    $date = isset($data['date']) ? $data['date'] : null;
    $typeStatus = isset($data['typeStatus']) ? $data['typeStatus']: null;
    $storeID = isset($data['storeID']) ? $data['storeID'] : null ;


    if ($data['updateForm'] == 1) {
        foreach ($products as $product) {
            $productId = $product['productId'];
            $qty = $product['qty'];
            
            try {

                if($typeStatus == 1 || $typeStatus == 3){
                    // Update the product details in the database
                    $stmt = $pdo->prepare("UPDATE stock SET s_qty = s_qty - ?, s_date_update = NOW() WHERE s_product_id = ?");
                    $stmt->execute([$qty, $productId]);
                }elseif ($typeStatus == 2){
                     // Fetch store name
                    $storeNameStmt = $pdo->prepare("SELECT st_name FROM store WHERE st_id = ?");
                    $storeNameStmt->execute([$storeID]);
                    $storeName = $storeNameStmt->fetchColumn(); // Fetch the store name

                        // Check if the sub_stock record exists
                    $checkStmt = $pdo->prepare("SELECT * FROM sub_stock WHERE sub_product_id = ? AND sub_location = ?");
                    $checkStmt->execute([$productId, $storeID]);

                    if ($checkStmt->rowCount() > 0) {
                        // If exists, update the sub_qty
                        $updateSubQtyStmt = $pdo->prepare("UPDATE sub_stock SET sub_qty = sub_qty + ?, sub_name = ? WHERE sub_product_id = ? AND sub_location = ?");
                        $updateSubQtyStmt->execute([$qty, $storeName, $productId, $storeID]);
                    } else {
                         // Insert new entry into sub_stock
                        $insertSubStockStmt = $pdo->prepare("INSERT INTO sub_stock (sub_product_id, sub_name, sub_qty, sub_location, sub_date_add) VALUES (?, ?, ?, ?, NOW())");
                        $insertSubStockStmt->execute([$productId, $storeName, $qty, $storeID]);
                    }
                }
                // Select the product details from the product table
                $stmt = $pdo->prepare("SELECT * FROM product WHERE p_product_id = ?");
                $stmt->execute([$productId]);
                $productDetails = $stmt->fetch(PDO::FETCH_ASSOC);

                // Generate the o_mg_code
                $currentDate = date('Y-m-d');
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM stockout WHERE DATE(o_out_date) = ?");
                $stmt->execute([$currentDate]);
                $count = $stmt->fetchColumn();
                $count = str_pad($count + 1, 2, '0', STR_PAD_LEFT); // Zero-pad the count to 2 digits
                $yy = date('y');
                $MM = date('m');
                $DD = date('d');
                $o_mg_code = 'M' . $yy . $MM . $DD . $count;
                
                // Insert into the stockout table
                $stmt = $pdo->prepare("INSERT INTO stockout (o_mg_code, o_product_id, o_product_code, o_product_name, o_out_qty, o_reasons, o_cost_price, o_total_price, o_store, o_out_date)
                                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
                $stmt->execute([
                    $o_mg_code,
                    $productId,
                    $productDetails['p_product_code'],
                    $productDetails['p_product_name'],
                    $qty,
                    $status,
                    $productDetails['p_cost_price'],
                    $productDetails['p_cost_price'] * $qty,
                    $storeID
                ]);
            } catch (Exception $e) {
                echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                exit;
            }
        }
        echo json_encode(['status' => 'success']);
        
    } elseif ($data['updateForm'] == 2) {
        foreach ($products as $product) {
            $productId = $product['productId'];
            $qty = $product['qty'];

            try {
                // Select the product details from the product table
                $stmt = $pdo->prepare("SELECT * FROM product WHERE p_product_id = ?");
                $stmt->execute([$productId]);
                $productDetails = $stmt->fetch(PDO::FETCH_ASSOC);

                // Generate the pr_code
                $currentDate = date('Y-m-d');
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM pr WHERE DATE(pr_date) = ?");
                $stmt->execute([$currentDate]);
                $count = $stmt->fetchColumn();
                $count = str_pad($count + 1, 2, '0', STR_PAD_LEFT); // Zero-pad the count to 2 digits
                $yy = date('y');
                $MM = date('m');
                $DD = date('d');
                $pr_code = 'PR' . $yy . $MM . $DD . $count;

                // Insert into the pr table
                $stmt = $pdo->prepare("INSERT INTO pr (pr_code, pr_product_id, pr_product_code, pr_product_name, pr_qty, pr_date, pr_date_add)
                                       VALUES (?, ?, ?, ?, ?, ?, NOW())");
                $stmt->execute([
                    $pr_code,
                    $productId,
                    $productDetails['p_product_code'],
                    $productDetails['p_product_name'],
                    $qty,
                    $currentDate
                ]);
            } catch (Exception $e) {
                echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                exit;
            }
        }
        echo json_encode(['status' => 'success']);
        
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid updateForm value']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
}
?>