<?php
// include database connection
include 'connect.php';

// Check if POST data exists
if (isset($_POST['pr_code'])) {
    try {
        // Get data from POST
        $pr_code = $_POST['pr_code'];
        $size = $_POST['size'];
        $color = $_POST['color'];
        $hand = $_POST['hand'];
        $qty = $_POST['qty'];
        $pdCode = $_POST['pdCode'];
        $pdName = $_POST['pdName'];
        $prMemo = 'Exchange';
        $prDate = $_POST['prDate'];
        $prMgcode= $_POST['prMgcode'];
        $PRStatusID = isset($_POST['prStatusID']) && !empty($_POST['prStatusID']) ? $_POST['prStatusID'] : null;


        $pr_exchange = '1';

        // สร้าง SQL query เพื่อค้นหาข้อมูล
        $sql = "SELECT p_product_id FROM product WHERE p_product_code = :pdCode AND p_product_name = :pdName AND p_hands = :hand AND p_color = :color AND p_size = :size";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['pdCode' => $pdCode, 'pdName' => $pdName, 'hand' => $hand, 'color' => $color, 'size' => $size]);
        $id = $stmt->fetchColumn();

        // ตรวจสอบผลลัพธ์
        if ($id) {
            echo "ID PRODUCT: " . $id;

           
            // Prepare SQL statement for updating pr_exchange using PDO
            $update_sql = "UPDATE pr SET pr_exchange = :status WHERE pr_code = :pr_code";
            $update_stmt = $pdo->prepare($update_sql);
            $update_stmt->execute([
                'status' => $pr_exchange,
                'pr_code' => $pr_code
            ]);


            // Prepare SQL statement for insertion using PDO
            $insert_sql = "INSERT INTO pr (pr_code, pr_qty, pr_product_id, pr_product_code, pr_product_name, pr_memo, pr_date, pr_mg_code, pr_status,  pr_date_add) 
                           VALUES (:pr_code, :qty, :pdID, :pdCode, :pdName, :exchange, :prDate, :MgCode, :PRStatusID, NOW())";

            $insert_stmt = $pdo->prepare($insert_sql);
            $insert_stmt->execute([
                'pr_code' => $pr_code,
                'qty' => $qty,
                'pdID' => $id,
                'pdCode' => $pdCode,
                'pdName' => $pdName,
                'exchange' => $prMemo,
                'prDate' => $prDate,
                'MgCode' => $prMgcode,
                'PRStatusID' => $PRStatusID
            ]);

            if ($insert_stmt->rowCount() > 0) {
                echo json_encode(['success' => 'Data inserted successfully']);
            } else {
                echo json_encode(['error' => 'Failed to insert data']);
            } 
        } else {
            echo json_encode(['error' => 'ไม่พบข้อมูล']);
        }
    } catch (Exception $e) {
        echo "เกิดข้อผิดพลาด: " . $e->getMessage();
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}
?>