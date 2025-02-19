<?php
include '../connect.php';

if (isset($_POST['ids']) && is_array($_POST['ids']) && !empty($_POST['ids'])) {
    try {
        // กรองข้อมูล ids และตรวจสอบรูปแบบ
        $ids = array_filter($_POST['ids'], function ($id) {
            return preg_match('/^[a-zA-Z0-9_-]+$/', $id); // อนุญาตเฉพาะตัวอักษร a-z, A-Z, 0-9, _, -
        });

        if (empty($ids)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'ไม่มีรายการที่ถูกเลือกหรือรูปแบบไม่ถูกต้อง'
            ]);
            exit;
        }

        // เริ่ม Transaction
        $pdo->beginTransaction();

        // เตรียมคำสั่ง SQL สำหรับอัปเดต
        $inQuery = implode(',', array_fill(0, count($ids), '?'));
        $sql_update_stockout = "UPDATE stockout SET o_pr_code = ? WHERE o_mg_code IN ($inQuery)";
        $stmt_update_stockout = $pdo->prepare($sql_update_stockout);

        // ใส่ค่าพารามิเตอร์
        $pr_code = 1; // ค่าที่ต้องการอัปเดต
        $stmt_update_stockout->bindValue(1, $pr_code); // ค่าที่จะอัปเดตสำหรับ o_pr_code

        foreach ($ids as $index => $id) {
            $stmt_update_stockout->bindValue($index + 2, $id); // ใส่ค่า o_mg_code
        }

        // ดำเนินการอัปเดต
        $stmt_update_stockout->execute();

        // ตรวจสอบจำนวนแถวที่ถูกอัปเดต
        $updatedRows = $stmt_update_stockout->rowCount();

        // Commit Transaction
        $pdo->commit();

        // ส่งผลลัพธ์กลับ
        if ($updatedRows > 0) {
            echo json_encode([
                'status' => 'success',
                'message' => "อัปเดตข้อมูลสำเร็จจำนวน $updatedRows รายการ"
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'ไม่มีข้อมูลที่ถูกอัปเดต'
            ]);
        }
    } catch (Exception $e) {
        // Rollback Transaction เมื่อเกิดข้อผิดพลาด
        $pdo->rollBack();
        error_log($e->getMessage());
        echo json_encode([
            'status' => 'error',
            'message' => 'เกิดข้อผิดพลาดในการประมวลผลข้อมูล'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'ข้อมูลนำเข้าไม่ถูกต้อง'
    ]);
}
