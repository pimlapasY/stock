<?php
include '../connect.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/vendor/autoload.php';

header('Content-Type: application/json');

try {
    if (!isset($pdo)) {
        throw new Exception('Database connection is not established.');
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['selectedIds']) || !is_array($_POST['selectedIds'])) {
        throw new Exception('Invalid request');
    }

    $selectedIds = array_map('intval', $_POST['selectedIds']); // Ensure IDs are integers
    if (empty($selectedIds)) {
        throw new Exception('No IDs selected');
    }

    // สร้าง placeholders (?, ?, ?)
    $placeholders = implode(',', array_fill(0, count($selectedIds), '?'));

    // เตรียมคำสั่ง SQL
    $sql = "SELECT
            pr.pr_total_cost,
            pr.pr_code,
            pr.pr_product_id,
            pr.pr_product_code,
            pr.pr_product_name,
            product.p_cost_price,
            product.p_hands,
            product.p_color,
            product.p_size,
            SUM(pr.pr_qty) AS total_qty,
            MAX(pr.pr_date) AS pr_date
        FROM pr
        LEFT JOIN product ON product.p_product_id = pr.pr_product_id
        WHERE pr.pr_id IN ($placeholders)
        GROUP BY 
            pr.pr_product_code, 
            pr.pr_product_id, 
            pr.pr_product_name,
            product.p_size,
            product.p_hands, 
            product.p_color";

    // เตรียม statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute($selectedIds);

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!$results) {
        throw new Exception('No data found');
    }

    // สร้าง HTML Table
    $tableHtml = '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse; width: 100%;">';
    $tableHtml .= '<thead>
    <tr>
        <th>Product Code</th>
        <th>Product Name</th>
        <th>Option1</th>
        <th>Option2</th>
        <th>Option3</th>
        <th>Quantity</th>
        <th>Cost(NoVAT)/PCS</th>
    </tr>
</thead>
<tbody>';

    $totalCost = 0;
    $totalByCode = []; // เก็บข้อมูลทั้งหมด
    foreach ($results as $row) {
        $totalQty = htmlspecialchars($row['total_qty']); // ดึงค่าจากฐานข้อมูล
        $costPrice = htmlspecialchars($row['p_cost_price']); // ดึงค่าจากฐานข้อมูล

        // ตรวจสอบและคำนวณเฉพาะเมื่อทั้งสองค่าเป็นตัวเลข
        if (is_numeric($totalQty) && is_numeric($costPrice)) {
            $totalAmount = (float)$totalQty * (float)$costPrice;
            $totalPriceProduct = number_format($totalAmount, 2); // แสดงผลลัพธ์ในรูปแบบ 2 ทศนิยม
        } else {
            $totalPriceProduct = 'N/A'; // กรณีไม่ใช่ตัวเลข
        }

        $tableHtml .= '<tr>
                        <td>' . htmlspecialchars($row['pr_product_code']) . '</td>
                        <td>' . htmlspecialchars($row['pr_product_name']) . '</td>
                        <td>' . htmlspecialchars($row['p_hands']) . '</td>
                        <td>' . htmlspecialchars($row['p_color']) . '</td>
                        <td>' . htmlspecialchars($row['p_size']) . '</td>
                        <td>' . htmlspecialchars($row['total_qty']) . '</td>
                        <td>' . htmlspecialchars($costPrice) . '</td>
                    </tr>';
        // ตรวจสอบและแปลงค่าก่อนทำการบวก
        $totalCost += isset($row['pr_total_cost']) ? floatval($row['pr_total_cost']) : 0;
        $code = $row['pr_product_code'];
        $name = $row['pr_product_name'];

        if (!isset($totalByCode[$code])) {
            $totalByCode[$code] = [
                'name' => $name,
                'total_qty' => 0
            ];
        }
        $totalByCode[$code]['total_qty'] += $row['total_qty'];
    }

    // Append totals section
    $tableHtml .= '<tfoot>
                        <tr>
                            <th colspan="5">Total</th>
                            <th colspan="2">Quantity</th>
                        </tr>';

    foreach ($totalByCode as $code => $data) {
        $tableHtml .= '
    <tr>
        <td colspan="1">Code: ' . htmlspecialchars($code) . '</td>
        <td colspan="4">Name: ' . htmlspecialchars($data['name']) . '</td>
        <td class="text-center" colspan="2">' . htmlspecialchars($data['total_qty']) . '</td>
    </tr>
    ';
    }

    $tableHtml .= '</tfoot>';
    $tableHtml .= '</tbody></table>';
    $tableHtml .= '<br><br><span>Thank you for your cooperation<br>Best regards,</span>';

    // ส่งอีเมลด้วย PHPMailer
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'mailawn.thaicloudsolutions.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'system@samt.co.th';
        $mail->Password = 'Shippo@2025!';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('system@samt.co.th', '');
        $mail->addAddress('pyasavoot@system-samt.com'); // ผู้รับคนแรก
        /* $mail->addAddress('interpreter2@samt.co.th'); */ // ผู้รับคนที่สอง
        $mail->isHTML(true);
        $mail->Subject = 'Request for PO Issuance';
        $mail->Body = 'Dear K.Nuch <br><br> Please issue the PO. As per the details outlined below.<br><br> Total Cost: ' . number_format($totalCost) . 'THB<br><br><h3>Details :</h3>' . $tableHtml;

        $mail->send();

        // อัปเดตฐานข้อมูล
        $currentDate = date('Y-m-d H:i:s');
        $updateSql = "UPDATE pr SET pr_mail = ? WHERE pr_id IN ($placeholders)";
        $updateStmt = $pdo->prepare($updateSql);
        $updateStmt->execute(array_merge([$currentDate], $selectedIds));

        echo json_encode(['status' => 'success', 'message' => 'Email sent and database updated successfully']);
    } catch (Exception $e) {
        throw new Exception('Email could not be sent. Error: ' . $mail->ErrorInfo);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
