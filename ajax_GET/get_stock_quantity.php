<?php
include('../connect.php'); // Include your PDO connection script

if (isset($_POST['product_code'])) {
    // Extract the inputs
    $productCode = $_POST['product_code'];
    $color = $_POST['color'] ?? '';
    $size = $_POST['size'] ?? '';
    $hand = $_POST['hand'] ?? '';
    $store = $_POST['store'];

    try {
        // Start base query
        if ($store == 1 || isset($_POST['getType']) == 'stockIn') {
            $query = "SELECT 
                    stock.s_qty, 
                    product.p_cost_price, 
                    product.p_sale_price, 
                    product.p_qty, 
                    product.p_vat, 
                    product.p_product_id, 
                    (stock.s_qty - COALESCE(sub_qty_sum, 0)) AS stock_quantity
                FROM 
                    product
                LEFT JOIN 
                    stock ON stock.s_product_id = product.p_product_id
                LEFT JOIN 
                    (
                        SELECT 
                            sub_product_id, 
                            SUM(sub_qty) AS sub_qty_sum
                        FROM 
                            sub_stock
                        GROUP BY 
                            sub_product_id
                    ) AS sub_stock_summary
                ON 
                    sub_stock_summary.sub_product_id = product.p_product_id
                WHERE 
                    product.p_product_code = :product_code
            ";

            // Add conditions dynamically based on provided inputs
            $params = ['product_code' => $productCode];
            if ($color !== '') {
                $query .= " AND product.p_color = :color";
                $params['color'] = $color;
            }
            if ($size !== '') {
                $query .= " AND product.p_size = :size";
                $params['size'] = $size;
            }
            if ($hand !== '') {
                $query .= " AND product.p_hands = :hand";
                $params['hand'] = $hand;
            }

            $stmt = $pdo->prepare($query);
            $stmt->execute($params);

        } else {
            $query = "SELECT 
                    stock.s_qty, 
                    product.p_cost_price, 
                    product.p_sale_price, 
                    product.p_qty, 
                    product.p_product_id, 
                    product.p_vat, 
                    sub_stock.sub_qty
                FROM 
                    product
                LEFT JOIN 
                    stock ON stock.s_product_id = product.p_product_id
                LEFT JOIN 
                    sub_stock ON sub_stock.sub_product_id = product.p_product_id
                WHERE 
                    product.p_product_code = :product_code 
                    AND sub_stock.sub_location = :store
            ";

            $params = [
                'product_code' => $productCode,
                'store' => $store
            ];

            if ($color !== '') {
                $query .= " AND product.p_color = :color";
                $params['color'] = $color;
            }
            if ($size !== '') {
                $query .= " AND product.p_size = :size";
                $params['size'] = $size;
            }
            if ($hand !== '') {
                $query .= " AND product.p_hands = :hand";
                $params['hand'] = $hand;
            }

            $stmt = $pdo->prepare($query);
            $stmt->execute($params);
        }

        // Check the number of rows returned
        $resultCount = $stmt->rowCount();
        
        if ($resultCount > 1) {
            // More than one product found
            echo json_encode(["error1" => "กรุณาระบุเพิ่มอีกเมตริก:เช่น สี, ขนาด หรือ มือ"]);
            return;
        }


        // Fetch the result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
       
        if ($result !== false) {
            echo json_encode($result);
        } else {
            echo json_encode(["error2" => "ไม่พบข้อมูลปริมาณสต็อกสำหรับรหัสผลิตภัณฑ์: $productCode, $color, $size, $hand"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["error" => "ข้อผิดพลาดของฐานข้อมูล: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "ไม่ได้ระบุรหัสผลิตภัณฑ์."]);
}
?>