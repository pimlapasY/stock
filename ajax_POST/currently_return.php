<?php
// Include database connection
include '../connect.php';

try {
    if (!isset($_POST['ids']) || empty($_POST['ids'])) {
        throw new Exception('ไม่ได้รับข้อมูล ID ที่เลือก');
    }

    $pdo->beginTransaction();

    // Get current date and generate unique ID
    $currentDate = date("ymd");
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM stockin");
    $stmt->execute();
    $count = $stmt->fetchColumn();
    $count++;
    $i_no = $currentDate . $count;
    $usercode_add = $_SESSION['id'] ?? null;

    if (!$usercode_add) {
        throw new Exception('ไม่พบข้อมูลผู้ใช้งาน กรุณาเข้าสู่ระบบใหม่');
    }

    $ids = $_POST['ids'];
    $memo = $_POST['memo'] ?? null;
    $returnDate = $_POST['returnDate'] ?? null;

    if (!$returnDate) {
        throw new Exception('กรุณาระบุวันที่รับคืน');
    }

    // Prepare query to fetch product details
    $inQuery = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT o.*, p.*, s.s_qty
                          FROM stockout o
                          LEFT JOIN product p ON o.o_product_id = p.p_product_id
                          LEFT JOIN stock s ON s.s_product_id = o.o_product_id
                          WHERE o.o_mg_code IN ($inQuery)");

    foreach ($ids as $index => $id) {
        $stmt->bindValue($index + 1, $id);
    }

    $stmt->execute();
    $selectedProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($selectedProducts)) {
        throw new Exception('ไม่พบข้อมูลสินค้าที่เลือก');
    }

    // Prepare statements
    $sql_stockin = "INSERT INTO stockin (i_no, i_product_id, i_mg_code, i_qty, 
                    i_product_code, i_status, i_username, i_total_cost, 
                    i_current_qty, i_memo, i_date, i_date_add) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, now())";
    $stmt_stockin = $pdo->prepare($sql_stockin);

    $sql_update_stock = "UPDATE stock SET s_qty = s_qty + ?, 
                        s_return_date = now() WHERE s_product_id = ?";
    $stmt_update_stock = $pdo->prepare($sql_update_stock);

    $sql_update_stockout = "UPDATE stockout SET o_return = 1 WHERE o_mg_code = ?";
    $stmt_update_stockout = $pdo->prepare($sql_update_stockout);

    $insertedRows = 0;
    foreach ($selectedProducts as $product) {
        // Validate product data
        if (!isset($product['o_product_id']) || !isset($product['o_out_qty'])) {
            throw new Exception('ข้อมูลสินค้าไม่ครบถ้วน');
        }

        $total_price = floatval($product['o_out_qty'] * $product['p_cost_price']);

        // Execute stockin insert
        $stmt_stockin->execute([
            $i_no,
            $product['o_product_id'],
            $product['o_mg_code'],
            $product['o_out_qty'],
            $product['o_product_code'],
            '2',
            $usercode_add,
            $total_price,
            $product['s_qty'],
            $memo,
            $returnDate
        ]);

        // Update stock
        $stmt_update_stock->execute([
            $product['o_out_qty'],
            $product['o_product_id']
        ]);

        // Update stockout
        $stmt_update_stockout->execute([$product['o_mg_code']]);

        $insertedRows++;
    }

    if ($insertedRows === 0) {
        throw new Exception('ไม่สามารถบันทึกข้อมูลได้');
    }

    $pdo->commit();
    echo json_encode([
        'status' => 'success',
        'message' => "บันทึกข้อมูลสำเร็จ จำนวน $insertedRows รายการ"
    ]);

} catch (Exception $e) {
    if (isset($pdo)) {
        $pdo->rollBack();
    }
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>