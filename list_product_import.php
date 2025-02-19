<html>
<script src="js/sweetalert2.all.min.js"></script>

</html>
<?php
if (isset($_POST['import'])) {
    if (isset($_FILES['csvFile']) && $_FILES['csvFile']['error'] == 0) {
        $filePath = $_FILES['csvFile']['tmp_name'];

        // เรียกใช้ฟังก์ชันนำเข้าจาก CSV
        importCSV($filePath);

        // Return a success message with SweetAlert
        echo "<script>
        Swal.fire({
            title: 'สำเร็จ!',
            text: 'ไฟล์ CSV ถูกนำเข้าสำเร็จ!',
            icon: 'success',
            confirmButtonText: 'ตกลง'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'stock_list.php'; // Redirect to stock_list.php
            }
        });
        </script>";
    } else {
        // Return an error message
        echo "<script>
        Swal.fire({
            title: 'เกิดข้อผิดพลาด!',
            text: 'มีข้อผิดพลาดในการอัปโหลดไฟล์.',
            icon: 'error',
            confirmButtonText: 'ตกลง'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'list.php'; // Redirect to stock_list.php
            }
        });
      </script>";
    }
}
function importCSV($filePath)
{
    require_once 'connect.php';

    try {
        // เริ่มต้น Transaction
        $pdo->beginTransaction();

        if (($handle = fopen($filePath, 'r')) !== FALSE) {
            fgetcsv($handle); // ข้าม Header

            while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                $productId = $data[1];
                $qtyIn = $data[11];
                $qtyOut = $data[12];
                $productCode = $data[2];
                $productName = $data[4];
                $costPrice = $data[8];
                $dateOnly = date('Y-m-d');
                $yymmdd = date('ymd');

                // ตรวจสอบว่าผลิตภัณฑ์มีอยู่ในตาราง product หรือไม่
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM product WHERE p_product_id = :productId");
                $stmt->execute([':productId' => $productId]);
                $productExists = $stmt->fetchColumn();
                $stmt->closeCursor();

                if (!$productExists) {
                    echo "ทำรายการไม่ถูกต้อง มีรายการที่ยังไม่ได้ลงทะเบียน กรุณาลงทะเบียนใหม่ (Product ID: $productId)<br>";
                    continue;
                }

                if (!empty($qtyIn)) {
                    // ตรวจสอบว่าสินค้าฝากมีใน stock หรือไม่
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM stock WHERE s_product_id = :productId");
                    $stmt->execute([':productId' => $productId]);
                    $stockExists = $stmt->fetchColumn();
                    $stmt->closeCursor();

                    if ($stockExists) {
                        $stmt = $pdo->prepare("UPDATE stock SET s_qty = s_qty + :qtyIn, s_date_update = NOW() WHERE s_product_id = :productId");
                        $stmt->execute([':qtyIn' => $qtyIn, ':productId' => $productId]);
                    } else {
                        $stmt = $pdo->prepare("INSERT INTO stock (s_product_id, s_qty, s_date_add) VALUES (:productId, :qtyIn, NOW())");
                        $stmt->execute([':productId' => $productId, ':qtyIn' => $qtyIn]);
                    }

                    $countIn = $pdo->query("SELECT COUNT(*) FROM stockin WHERE i_date_add = '$dateOnly'")->fetchColumn() + 1;
                    $iNo = $yymmdd . str_pad($countIn, 2, '0', STR_PAD_LEFT);

                    $stmt = $pdo->prepare("INSERT INTO stockin (i_no, i_product_id, i_product_code, i_current_qty, i_qty, i_memo, i_date_add)
                                           VALUES (:iNo, :productId, :productCode, 
                                                   (SELECT s_qty FROM stock WHERE s_product_id = :productId), 
                                                   :qtyIn, 'import csv', NOW())");
                    $stmt->execute([
                        ':iNo' => $iNo,
                        ':productId' => $productId,
                        ':productCode' => $productCode,
                        ':qtyIn' => $qtyIn,
                    ]);
                } elseif (!empty($qtyOut)) {
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM stock WHERE s_product_id = :productId");
                    $stmt->execute([':productId' => $productId]);
                    $stockExists = $stmt->fetchColumn();
                    $stmt->closeCursor();

                    if ($stockExists) {
                        $stmt = $pdo->prepare("UPDATE stock SET s_qty = s_qty - :qtyOut, s_date_update = NOW() WHERE s_product_id = :productId");
                        $stmt->execute([':qtyOut' => $qtyOut, ':productId' => $productId]);
                    } else {
                        $initialQty = -$qtyOut;
                        $stmt = $pdo->prepare("INSERT INTO stock (s_product_id, s_qty, s_date_add) VALUES (:productId, :initialQty, NOW())");
                        $stmt->execute([':productId' => $productId, ':initialQty' => $initialQty]);
                    }

                    $countOut = $pdo->query("SELECT COUNT(*) FROM stockout WHERE o_date_add = '$dateOnly'")->fetchColumn() + 1;
                    $oMgCode = 'M' . $yymmdd . str_pad($countOut, 2, '0', STR_PAD_LEFT);
                    $totalPrice = $costPrice * $qtyOut;

                    $stmt = $pdo->prepare("INSERT INTO stockout (o_mg_code, o_product_id, o_product_code, o_product_name, o_out_qty, o_store, o_cost_price, o_total_price, o_memo, o_date_add, o_out_date)
                                           VALUES (:oMgCode, :productId, :productCode, :productName, :qtyOut, '1', :costPrice, :totalPrice, 'csv take out', NOW(), :dateOnly)");
                    $stmt->execute([
                        ':oMgCode' => $oMgCode,
                        ':productId' => $productId,
                        ':productCode' => $productCode,
                        ':productName' => $productName,
                        ':qtyOut' => $qtyOut,
                        ':costPrice' => $costPrice,
                        ':totalPrice' => $totalPrice,
                        ':dateOnly' => $dateOnly,
                    ]);
                }
            }
            fclose($handle);
        }
        $pdo->commit(); // ยืนยัน Transaction
    } catch (Exception $e) {
        $pdo->rollBack(); // ยกเลิก Transaction หากเกิดข้อผิดพลาด
        die("Error: " . $e->getMessage());
    }
}

?>