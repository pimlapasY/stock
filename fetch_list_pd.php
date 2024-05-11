<?php
// Include your database connection
include('connect.php');

// Get current page number
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$records_per_page = 10;
$offset = ($page - 1) * $records_per_page;

// Prepare and execute SQL query to fetch data for the current page
$stmt = $pdo->prepare("SELECT 
                        s_id,
                        s_collection, 
                        s_product_name, 
                        s_hands, 
                        s_color, 
                        s_size, 
                        s_cost_price, 
                        s_sale_price, 
                        SUM(s_qty) AS total_qty 
                    FROM stock 
                    WHERE s_location IN ('SAMT', 'SAKABA') 
                    GROUP BY s_collection, s_product_name, s_hands, s_color, s_size, s_cost_price, s_sale_price 
                    LIMIT $offset, $records_per_page");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return JSON response
header('Content-Type: application/json');
echo json_encode($products);
?>