<?php
include '../connect.php';

// Determine the type of data to fetch
$type = isset($_GET['type']) ? $_GET['type'] : 'yearly';

switch ($type) {
    case 'monthly':
        $stmt = $pdo->prepare("
            WITH months AS (
                SELECT 
                    DATE_FORMAT(CONCAT(YEAR(CURDATE()), '-', LPAD(n, 2, '0'), '-01'), '%Y-%m') AS month
                FROM (
                    SELECT 1 AS n UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6
                    UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL SELECT 12
                ) numbers
            )
            SELECT 
                m.month AS month,
                p.p_product_code,
                p.p_product_name,
                COALESCE(SUM(stockout.o_out_qty * p.p_sale_price), 0) AS amount_exclude_vat,
                COALESCE(SUM(stockout.o_out_qty * p.p_sale_price * (1 + p.p_vat / 100)), 0) AS amount_include_vat,
                'Monthly Data' AS description
            FROM 
                months m
            LEFT JOIN 
                stockout ON DATE_FORMAT(stockout.o_out_date, '%Y-%m') = m.month
            LEFT JOIN 
                product p ON stockout.o_product_id = p.p_product_id
            WHERE 
                stockout.o_return IS NULL 
                AND stockout.o_payment = 1
            GROUP BY 
                m.month, p.p_product_code
            ORDER BY 
                m.month, p.p_product_code
        ");
        break;
         
        case 'weekly':
            $stmt = $pdo->prepare("
                SELECT 
                    DATE_FORMAT(stockout.o_out_date - INTERVAL WEEKDAY(stockout.o_out_date) DAY, '%Y-%m-%d') AS week_start,
                    DATE_FORMAT(stockout.o_out_date - INTERVAL WEEKDAY(stockout.o_out_date) DAY + INTERVAL 6 DAY, '%Y-%m-%d') AS week_end,
                    SUM(stockout.o_out_qty * product.p_sale_price) AS amount_exclude_vat,
                    SUM(stockout.o_out_qty * product.p_sale_price * (1 + product.p_vat / 100)) AS amount_include_vat,
                    'Weekly Data' AS description
                FROM 
                    stockout
                LEFT JOIN 
                    product ON stockout.o_product_id = product.p_product_id
                WHERE 
                    stockout.o_return IS NULL 
                    AND stockout.o_payment = 1
                GROUP BY 
                    week_start, week_end
                ORDER BY 
                    week_start
            ");
            break;
    case 'yearly':
    default:
        $stmt = $pdo->prepare("
            SELECT 
                YEAR(stockout.o_out_date) AS year,
                SUM(stockout.o_out_qty * product.p_sale_price) AS amount_exclude_vat,
                SUM(stockout.o_out_qty * product.p_sale_price * (1 + product.p_vat / 100)) AS amount_include_vat,
                'Yearly Data' AS description
            FROM 
                stockout
            LEFT JOIN 
                product ON stockout.o_product_id = product.p_product_id
            WHERE 
                stockout.o_return IS NULL 
                AND stockout.o_payment = 1
            GROUP BY 
                YEAR(stockout.o_out_date)
            ORDER BY 
                year
        ");
        break;
}

$stmt->execute();
$data = $stmt->fetchAll();

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($data);