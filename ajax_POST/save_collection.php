<?php
include('../connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['col_id'];
    $name = $_POST['col_name'];

    try {

        // 1. ดึงชื่อเก่ามาเก็บไว้ก่อน
        /* $query = $pdo->prepare("SELECT col_name FROM collection WHERE col_id = :id");
        $query->execute([':id' => $id]);
        $collection = $query->fetch(PDO::FETCH_ASSOC);

        if (!$collection) {
            echo json_encode(['success' => false, 'error' => 'collection not found']);
            exit;
        }
        $oldName = $collection['st_name']; */

        // 2. อัปเดต st_name_old ก่อนอัปเดต st_name
        $stmt = $pdo->prepare("UPDATE collection SET col_name = :name, col_date_update = NOW() WHERE col_id = :id");
        $stmt->execute([
            ':id' => $id,
            ':name' => $name
        ]);

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}