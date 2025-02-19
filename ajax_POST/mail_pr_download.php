<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['selectedIds']) && is_array($_POST['selectedIds'])) {
    $selectedIds = $_POST['selectedIds'];

    try {
        // เชื่อมต่อฐานข้อมูล
        include '../connect.php';

        // ตรวจสอบว่ามี ID ที่ส่งมา
        if (count($selectedIds) === 0) {
            http_response_code(400);
            echo "No IDs provided.";
            exit;
        }

        // สร้าง SQL
        $currentDate = date('Y-m-d H:i:s'); // วันที่ปัจจุบัน
        $placeholders = implode(',', array_fill(0, count($selectedIds), '?')); // สร้าง ?,?,?,...
        $sql = "UPDATE pr SET pr_download = ? WHERE pr_id IN ($placeholders)";

        // เตรียม statement
        $stmt = $pdo->prepare($sql);
        $params = array_merge([$currentDate], $selectedIds);
        $stmt->execute($params);

        // ส่งข้อความตอบกลับ
        echo json_encode([
            'status' => 'success',
            'message' => 'Records updated successfully',
        ]);
    } catch (Exception $e) {
        // จัดการข้อผิดพลาด
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Error updating records: ' . $e->getMessage(),
        ]);
    }
} else {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request or no IDs provided.',
    ]);
}
