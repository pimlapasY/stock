<?php
header('Content-Type: application/json');

try {
    require_once 'connect.php';
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $code = isset($_POST['code']) ? trim($_POST['code']) : '';
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';

    $sql = "SELECT p_hands, p_color, p_size FROM product WHERE p_product_code = :code OR p_product_name = :name";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['code' => $code, 'name' => $name]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    function mergeOptions($column, $results)
    {
        $allValues = [];

        foreach ($results as $row) {
            if (!empty($row[$column])) {
                $values = explode(',', $row[$column]); // แยกค่าด้วย ','
                $allValues = array_merge($allValues, $values); // รวมค่าจากหลายแถว
            }
        }

        return array_values(array_unique(array_map('trim', $allValues))); // ลบค่าซ้ำ + trim ช่องว่าง
    }

    echo json_encode([
        "option1" => mergeOptions('p_hands', $results),
        "option2" => mergeOptions('p_color', $results),
        "option3" => mergeOptions('p_size', $results)
    ]);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
