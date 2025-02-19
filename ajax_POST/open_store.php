<?php
include '../connect.php'; // เชื่อมต่อกับฐานข้อมูล
if (isset($_POST['id'])) { // ตรวจสอบว่ามีการส่งค่า 'id' มาทาง POST หรือไม่

    // รับค่า 'id' จากคำร้อง POST
    $id = $_POST['id'];

    try {
        // เตรียมคำสั่ง SQL สำหรับอัปเดตค่าในตาราง store
        $stmt = $pdo->prepare("UPDATE store SET st_hide = NULL WHERE st_id = :id");

        // รันคำสั่ง SQL พร้อมกับใส่ค่าที่ต้องการ (id)
        $stmt->execute([':id' => $id]);

        // ตรวจสอบว่ามีการอัปเดตแถวในฐานข้อมูลหรือไม่
        if ($stmt->rowCount() > 0) {
            // ถ้ามีการอัปเดต สำเร็จ
            echo json_encode(['status' => 'success', 'message' => 'เปิดร้านสำเร็จ']);
        } else {
            // ถ้าไม่มีการอัปเดต อาจหมายความว่า id นี้ไม่มีในฐานข้อมูลหรือข้อมูลนี้ถูกเปิดอยู่แล้ว
            echo json_encode(['status' => 'error', 'message' => 'ไม่พบร้านค้าหรือร้านค้านี้เปิดใช้งานแล้ว']);
        }
    } catch (PDOException $e) {
        // จัดการข้อผิดพลาดที่เกิดขึ้น (ถ้ามี)
        echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
    }
}
