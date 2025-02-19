<?php
include('../connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $tel = $_POST['tel'];

    try {

        // 1. ดึงชื่อเก่ามาเก็บไว้ก่อน
        $query = $pdo->prepare("SELECT st_name FROM store WHERE st_id = :id");
        $query->execute([':id' => $id]);
        $store = $query->fetch(PDO::FETCH_ASSOC);

        if (!$store) {
            echo json_encode(['success' => false, 'error' => 'Store not found']);
            exit;
        }
        $oldName = $store['st_name'];

        // 2. อัปเดต st_name_old ก่อนอัปเดต st_name
        $stmt = $pdo->prepare("UPDATE store SET st_name_old = :oldName, st_name = :name, st_addr = :address, st_tel = :tel, st_date_update = NOW() WHERE st_id = :id");
        $stmt->execute([
            ':oldName' => $oldName,
            ':name' => $name,
            ':address' => $address,
            ':tel' => $tel,
            ':id' => $id
        ]);

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
