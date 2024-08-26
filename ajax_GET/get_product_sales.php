<?php
include('../connect.php'); // Include your PDO connection script

if (isset($_GET['from_date']) && isset($_GET['to_date'])) {
    $from_date = $_GET['from_date'];
    $to_date = $_GET['to_date'];
} else {
    echo json_encode([
        'tableRows' => '<tr><td colspan="4">Invalid date range</td></tr>',
        'chartData' => '[]',
        'totalSale' => 0,
        'totalQty' => 0,
        'totalItems' => 0
    ]);
    exit;
}

try {
    // Prepare and execute a query to fetch the stock quantity and product cost for the selected date range
    $stmt = $pdo->prepare("SELECT product.p_product_code, product.p_product_name, product.p_sale_price, stockout.o_out_qty, product.p_vat, product.p_cost_price
                           FROM stockout
                           LEFT JOIN product ON stockout.o_product_id = product.p_product_id
                           WHERE stockout.o_return IS NULL AND stockout.o_payment = 1 AND stockout.o_out_date BETWEEN :from_date AND :to_date");

    $stmt->bindParam(':from_date', $from_date);
    $stmt->bindParam(':to_date', $to_date);
    
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($results !== false && count($results) > 0) {
        $aggregatedResults = [];
        $chartData = [];
        $totalSale = 0;
        $totalQty = 0;
        $uniqueProductCodes = []; // Array to store unique product codes


        foreach ($results as $row) {
            $productCode = $row['p_product_code'];
            $salePrice = $row['p_sale_price'];            
            $costPrice = $row['p_cost_price'];
            $outQty = $row['o_out_qty'];
            $totalPrice = $salePrice * $outQty;
            $totalCost = $costPrice * $outQty;
            if (!isset($aggregatedResults[$productCode])) {
                $aggregatedResults[$productCode] = [
                    'p_product_name' => $row['p_product_name'],
                    'total_price' => $totalPrice,
                    'total_qty' => $outQty, 
                    'total_cost' => $totalCost, 
                ];
            } else {
                $aggregatedResults[$productCode]['total_price'] += $totalPrice;
                $aggregatedResults[$productCode]['total_qty'] += $outQty;
            }
                // Collect unique product codes
                if (!in_array($productCode, $uniqueProductCodes)) {
                    $uniqueProductCodes[] = $productCode;
                }
        }

        $tableRows = '';

        foreach ($aggregatedResults as $productCode => $data) {
            $tableRows .= '<tr>';
            $tableRows .= '<td>' . htmlspecialchars($productCode) . '</td>';
            $tableRows .= '<td>' . htmlspecialchars($data['p_product_name']) . '</td>';
            $tableRows .= '<td class="text-end">' . htmlspecialchars($data['total_qty']) . '</td>';
            $tableRows .= '<td class="text-end">' . number_format($data['total_cost'],2) . '</td>';
            $tableRows .= '<td class="text-end">' . number_format($data['total_price'], 2) . '</td>';
            $tableRows .= '</tr>';
            $totalSale += $data['total_price'];
            $totalQty += $data['total_qty'];

            // Prepare data for the chart
            $chartData[] = [
                'product' => htmlspecialchars($data['p_product_name']),
                'total_qty' => $data['total_qty'],
                'total_price' => $data['total_price']
            ];
        }

        $tableRows .= '<tr class="table-info">';
        $tableRows .= '<td class="text-end">Total: ';
        $tableRows .= '</td>';
        $tableRows .= '<td colspan="2" class="text-end">';
        $tableRows .=  $totalQty;
        $tableRows .= '</td>';
        $tableRows .= '<td colspan="2" class="text-end">';
        $tableRows .=  number_format($totalSale, 2);
        $tableRows .= '</td>';
        $tableRows .= '</tr>';

        // Encode data for the chart
        $chartDataJson = json_encode($chartData);

        echo json_encode([
            'tableRows' => $tableRows,
            'chartData' => $chartDataJson,
            'totalSale' => number_format($totalSale, 2),
            'totalQty' => $totalQty,
            'totalItems' => count($uniqueProductCodes) // Count of unique product codes
        ]);
    } else {
        echo json_encode([
            'tableRows' => '<tr><td colspan="4">No results found</td></tr>',
            'chartData' => '[]',
            'totalSale' => 0,
            'totalQty' => 0,
            'totalItems' => 0

        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        'tableRows' => '<tr><td colspan="4">Database error: ' . htmlspecialchars($e->getMessage()) . '</td></tr>',
        'chartData' => '[]',
        'totalSale' => 0,
        'totalQty' => 0,
        'totalItems' => 0

    ]);
}
?>