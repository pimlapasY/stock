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

function importCSV($filePath) {
    $conn = new mysqli('27.254.134.24', 'system_saiko', 'samtadmin12', 'system_saiko');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $conn->begin_transaction();

    if (($handle = fopen($filePath, 'r')) !== FALSE) {
        fgetcsv($handle);

        while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
            $productId = $data[1];
            $qtyIn = $data[11];
            $qtyOut = $data[12];
            $productCode = $data[2];
            $productName = $data[4];
            $costPrice = $data[8];
            $dateOnly = date('Y-m-d');
            $yymmdd = date('ymd');

            // ตรวจสอบว่า p_product_id มีอยู่ในตาราง product หรือไม่
            $checkProduct = $conn->prepare("SELECT COUNT(*) FROM product WHERE p_product_id = ?");
            $checkProduct->bind_param('s', $productId);
            $checkProduct->execute();
            $checkProduct->bind_result($productExists);
            $checkProduct->fetch();
            $checkProduct->close();

            if (!$productExists) {
                echo "ทำรายการไม่ถูกต้อง มีรายการที่ยังไม่ได้ลงทะเบียน กรุณาลงทะเบียนใหม่ (Product ID: $productId)<br>";
                continue;
            }

            if (!empty($qtyIn)) {
                // ตรวจสอบ s_product_id ในตาราง stock
                $checkStock = $conn->prepare("SELECT COUNT(*) FROM stock WHERE s_product_id = ?");
                $checkStock->bind_param('s', $productId);
                $checkStock->execute();
                $checkStock->bind_result($stockExists);
                $checkStock->fetch();
                $checkStock->close();

                if ($stockExists) {
                    $updateStockIn = $conn->prepare("UPDATE stock SET s_qty = s_qty + ?, s_date_update = NOW() WHERE s_product_id = ?");
                    $updateStockIn->bind_param('ss', $qtyIn, $productId);
                    $updateStockIn->execute();
                } else {
                    $insertStock = $conn->prepare("INSERT INTO stock (s_product_id, s_qty, s_date_add) VALUES (?, ?, NOW())");
                    $insertStock->bind_param('ss', $productId, $qtyIn);
                    $insertStock->execute();
                }

                $countIn = $conn->query("SELECT COUNT(*) FROM stockin WHERE i_date_add = '$dateOnly'")->fetch_row()[0] + 1;
                $iNo = $yymmdd . str_pad($countIn, 2, '0', STR_PAD_LEFT);

                $insertStockIn = $conn->prepare("INSERT INTO stockin (i_no, i_product_id, i_product_code, i_current_qty, i_qty, i_memo, i_date_add) 
                                                 VALUES (?, ?, ?, (SELECT s_qty FROM stock WHERE s_product_id = ?), ?, 'import csv', NOW())");
                $insertStockIn->bind_param('sssss', $iNo, $productId, $productCode, $productId, $qtyIn);
                $insertStockIn->execute();

            } elseif (!empty($qtyOut)) {
                // ตรวจสอบ s_product_id ในตาราง stock
                $checkStock = $conn->prepare("SELECT COUNT(*) FROM stock WHERE s_product_id = ?");
                $checkStock->bind_param('s', $productId);
                $checkStock->execute();
                $checkStock->bind_result($stockExists);
                $checkStock->fetch();
                $checkStock->close();

                if ($stockExists) {
                    $updateStockOut = $conn->prepare("UPDATE stock SET s_qty = s_qty - ?, s_date_update = NOW() WHERE s_product_id = ?");
                    $updateStockOut->bind_param('ss', $qtyOut, $productId);
                    $updateStockOut->execute();
                } else {
                    $initialQty = -$qtyOut;
                    $insertStock = $conn->prepare("INSERT INTO stock (s_product_id, s_qty, s_date_add) VALUES (?, ?, NOW())");
                    $insertStock->bind_param('ss', $productId, $initialQty);
                    $insertStock->execute();
                }

                $countOut = $conn->query("SELECT COUNT(*) FROM stockout WHERE o_date_add = '$dateOnly'")->fetch_row()[0] + 1;
                $oMgCode = 'M' . $yymmdd . str_pad($countOut, 2, '0', STR_PAD_LEFT);
                $totalPrice = $costPrice * $qtyOut;

                $insertStockOut = $conn->prepare("INSERT INTO stockout (o_mg_code, o_product_id, o_product_code, o_product_name, o_out_qty, o_store, o_cost_price, o_total_price, o_memo, o_date_add, o_out_date)
                                                  VALUES (?, ?, ?, ?, ?, '1', ?, ?, 'csv take out', NOW(), ?)");
                $insertStockOut->bind_param('sssssssss', $oMgCode, $productId, $productCode, $productName, $qtyOut, $costPrice, $totalPrice, $dateOnly);
                $insertStockOut->execute();
            }
        }
        fclose($handle);
    }

    $conn->commit();
    $conn->close();
}
?>