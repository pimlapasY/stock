<?php
include('../connect.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM collection WHERE col_id = :id");
        $stmt->execute([':id' => $id]);
        $store = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode($store);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}
