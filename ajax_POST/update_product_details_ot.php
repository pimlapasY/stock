<?php
include('../connect.php');

// Get the JSON data from the request
$data = json_decode(file_get_contents('php://input'), true);

if ($data && isset($data['products']) && isset($data['updateForm'])) {
    $products = $data['products'];
    $status = isset($data['status']) ? $data['status'] : null;
    $date = isset($data['date']) ? $data['date'] : null;
    $typeStatus = isset($data['typeStatus']) ? $data['typeStatus'] : null;
    $customerName = isset($data['customerName']) ? $data['customerName']: null;
    $paymentOption= isset($data['paymentOption']) ? $data['paymentOption'] : null;
    $userID =  $_SESSION['id'];


try {
        $pdo->beginTransaction();

        if ($data['updateForm'] == 1) {
            foreach ($products as $product) {
                $productId = $product['productId']; // Sub ID
                $qty = $product['qty'];

                // Select the product details from the sub_stock and product tables
                $stmt = $pdo->prepare("SELECT p.*, sub.* FROM sub_stock sub LEFT JOIN product p ON sub.sub_product_id = p.p_product_id WHERE sub.sub_id = ?");
                $stmt->execute([$productId]);
                $productDetails = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($productDetails) {
                    // Update the stock and sub_stock quantities
                    $stmt = $pdo->prepare("UPDATE stock SET s_qty = s_qty - ?, s_date_update = NOW() WHERE s_product_id = ?");
                    $stmt->execute([$qty, $productDetails['p_product_id']]);

                    $updateSubQtyStmt = $pdo->prepare("UPDATE sub_stock SET sub_qty = sub_qty - ?, sub_date_update = NOW() WHERE sub_id = ?");
                    $updateSubQtyStmt->execute([$qty, $productId]);

                    // Generate the o_mg_code
                    $currentDate = date('Y-m-d');
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM stockout WHERE DATE(o_out_date) = ?");
                    $stmt->execute([$currentDate]);
                    $count = $stmt->fetchColumn();
                    $count = str_pad($count + 1, 2, '0', STR_PAD_LEFT);
                    $yy = date('y');
                    $MM = date('m');
                    $DD = date('d');
                    $o_mg_code = 'M' . $yy . $MM . $DD . $count;

                    // Insert into the stockout table
                    $stmt = $pdo->prepare("INSERT INTO stockout (o_mg_code, o_product_id, o_product_code, o_product_name, o_out_qty, 
                    o_store, o_reasons, o_cost_price, o_sale_price,  o_total_price, o_vat, o_payment_option, o_customer, o_out_date, o_username, o_date_add)
                                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?, NOW())");
                    $stmt->execute([
                        $o_mg_code,
                        $productDetails['p_product_id'],
                        $productDetails['p_product_code'],
                        $productDetails['p_product_name'],
                        $qty,
                        $productDetails['sub_location'],
                        $status,
                        $productDetails['p_cost_price'],
                        $productDetails['p_sale_price'],
                        $productDetails['p_sale_price'] * $qty,
                        $productDetails['p_vat'],
                        $paymentOption,
                        $customerName,
                        $currentDate,
                        $userID
                    ]);
                } else {
                    throw new Exception("Product not found for sub_id: $productId");
                }
            }
        } elseif ($data['updateForm'] == 2) {
            foreach ($products as $product) {
                $productId = $product['productId']; // Sub ID
                $qty = $product['qty'];


                // Select the product details from the product table
                $stmt = $pdo->prepare("SELECT p.*, sub.* FROM sub_stock sub LEFT JOIN product p ON sub.sub_product_id = p.p_product_id WHERE sub.sub_id = ?");
                $stmt->execute([$productId]);
                $productDetails = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($productDetails) {
                    // Generate the pr_code
                    $currentDate = date('Y-m-d');
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM pr WHERE DATE(pr_date) = ?");
                    $stmt->execute([$currentDate]);
                    $count = $stmt->fetchColumn();
                    $count = str_pad($count + 1, 2, '0', STR_PAD_LEFT);
                    $yy = date('y');
                    $MM = date('m');
                    $DD = date('d');
                    $pr_code = 'PR' . $yy . $MM . $DD . $count;

                    // Insert into the pr table
                    $stmt = $pdo->prepare("INSERT INTO pr (pr_code, pr_product_id, pr_product_code, pr_product_name, pr_cost, pr_total_cost, pr_sale, pr_vat, pr_qty, pr_user_add,  pr_date, pr_date_add)
                                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
                    $stmt->execute([
                        $pr_code,
                        $productId,
                        $productDetails['p_product_code'],
                        $productDetails['p_product_name'],
                        $productDetails['p_cost_price'],
                        $productDetails['p_cost_price']*$qty,
                        $productDetails['p_sale_price'],
                        $productDetails['p_vat'],
                        $qty,
                        $userID,
                        $currentDate
                    ]);
                } else {
                    throw new Exception("Product not found for product_id: $productId");
                }
            }
        } else {
            throw new Exception('Invalid updateForm value');
        }

        $pdo->commit();
        echo json_encode(['status' => 'success']);

    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }   
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
}
?>