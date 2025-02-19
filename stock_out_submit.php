<?php
session_start();
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 0);

// เชื่อมต่อฐานข้อมูล
include 'connect.php';

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (isset($_SESSION['role'])) {
    // รับค่าจากฟอร์ม
    $mgCode = $_POST['mgCode'];
    $productID = $_POST['productID'];
    $memo = $_POST['memo'];
    $qtyValue = $_POST['qtyValue'];
    $username = $_SESSION['id'];
    $reasons = $_POST['reasons'];
    $date = $_POST['date'];
    $store = $_POST['store'];
    $customerName = $_POST['customerName'];
    $salePrice = $_POST['salePrice'];
    $vat = $_POST['vat'];
    $paidBy = isset($_POST['paidOption']) ? $_POST['paidOption'] : null;

    // ดึงข้อมูลสินค้าจากฐานข้อมูล
    $stmt_product = $pdo->prepare("SELECT * FROM product WHERE p_product_id = :productID");
    $stmt_product->bindParam(':productID', $productID, PDO::PARAM_INT);
    $stmt_product->execute();
    $details_product = $stmt_product->fetch(PDO::FETCH_ASSOC);

    // ตรวจสอบส่วนลด
    $discountType = isset($_POST['discountType']) ? $_POST['discountType'] : null;
    $discountPrice = isset($_POST['discountPrice']) ? $_POST['discountPrice'] : null;
    $totalSaleVat = isset($_POST['totalSaleVat']) ? $_POST['totalSaleVat'] : null;
    $totalSaleDis = isset($_POST['totalSaleDis']) ? $_POST['totalSaleDis'] : null;

    if ($discountType != 99) {
        $discountPrice = $_POST['discountPrice'];
    } else {
        $discountType = NULL;
        $discountPrice = NULL;
    }

    try {
        // เริ่มต้น Transaction
        $pdo->beginTransaction();


        // อัปเดตสต็อก
        if ($reasons === 'sale' || $reasons === 'sale sample') {
            // เพิ่มข้อมูลไปยังตาราง stockout
            $stmt = $pdo->prepare("
        INSERT INTO stockout 
        (o_product_id, o_mg_code, o_product_code, o_product_name, o_cost_price, o_sale_price, 
         o_vat, o_out_qty, o_discount, o_discount_total, o_total_price, o_memo, o_username, 
         o_reasons, o_customer, o_payment_option, o_out_date, o_store, o_date_add)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");
            $stmt->execute([
                $productID,                       // รหัสสินค้า (ไอดีของสินค้า)
                $mgCode,                          // รหัสการทำธุรกรรม (อาจจะเป็นรหัสอ้างอิงหรือเอกสาร)
                $details_product['p_product_code'], // รหัสสินค้า (จากรายละเอียดสินค้าในฐานข้อมูล)
                $details_product['p_product_name'], // ชื่อสินค้า (จากรายละเอียดสินค้าในฐานข้อมูล)
                $details_product['p_cost_price'],  // ราคาทุนของสินค้า
                $salePrice,  // ราคาขายของสินค้า
                $vat,         // อัตราภาษีมูลค่าเพิ่มของสินค้า
                $qtyValue,                         // จำนวนสินค้าที่จะเบิก/ขาย
                $discountType,                     // ประเภทของส่วนลด (เช่น เปอร์เซ็นต์หรือจำนวนเงิน)
                $discountPrice,                    // จำนวนส่วนลดที่ใช้กับรายการนี้
                $totalSaleDis,                     // ยอดขายสุทธิหลังหักส่วนลด
                $memo,                             // ข้อความหมายเหตุ (ถ้ามี)
                $username,                         // ชื่อผู้ใช้งานที่ทำรายการ
                $reasons,                          // เหตุผลของการทำรายการ (เช่น เบิกสินค้า/ขายสินค้า)
                $customerName,                     // ชื่อลูกค้าที่เกี่ยวข้องกับรายการนี้
                $paidBy,                           // วิธีการชำระเงิน (เช่น เงินสด โอนเงิน บัตรเครดิต)
                $date,                             // วันที่ทำรายการ
                $store                             // สาขาหรือสถานที่ที่ทำรายการ
            ]);



            $updateStmt = $pdo->prepare("UPDATE stock SET s_qty = s_qty - ? WHERE s_product_id = ?");
            $updateStmt->execute([$qtyValue, $productID]);

            if ($store !== '1') {
                $updateSakaba = $pdo->prepare("
                UPDATE sub_stock 
                SET sub_qty = sub_qty - ?, sub_date_update = NOW() 
                WHERE sub_product_id = ? AND sub_location = ?
                ");
                $updateSakaba->execute([$qtyValue, $productID, $store]);
            }
        } else {
            // สร้างวันที่ปัจจุบันในรูปแบบ yymmdd
            $currentDate = date('ymd');

            // นับจำนวนแถวในฐานข้อมูลที่มีวันที่ตรงกับวันนี้
            $stmtCount = $pdo->prepare("
                SELECT COUNT(*) as count 
                FROM take_out 
                WHERE DATE(to_out_date) = CURDATE()
            ");
            $stmtCount->execute();
            $rowCount = $stmtCount->fetchColumn();

            // เติมเลข 0 ด้านหน้าให้จำนวนแถวเป็น 2 หลัก เช่น 01, 02
            $rowCount = str_pad($rowCount + 1, 2, '0', STR_PAD_LEFT);

            // สร้างรหัส toCode ตามรูปแบบที่กำหนด
            $toCode = 'TO' . $currentDate . $rowCount;

            // เพิ่มข้อมูลลงในตาราง take_out

            $stmt = $pdo->prepare("
        INSERT INTO take_out 
        (to_product_id, to_code, to_product_code, to_product_name, to_cost_price, to_sale_price, 
         to_vat, to_out_qty, to_discount, to_discount_total, to_total_price, to_memo, to_username, 
         to_reasons, to_customer, to_payment_option, to_out_date, to_store, to_date_add)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");
            // ใส่ค่าข้อมูลและดำเนินการเพิ่มข้อมูล
            $stmt->execute([
                $productID,       // ไอดีของสินค้า
                $toCode,          // รหัสที่สร้างขึ้นใหม่
                $details_product['p_product_code'],     // รหัสสินค้า
                $details_product['p_product_name'], // ชื่อสินค้า (จากรายละเอียดสินค้าในฐานข้อมูล)
                $details_product['p_cost_price'],  // ราคาทุนของสินค้า
                $salePrice,  // ราคาขายของสินค้า
                $vst,         // อัตราภาษีมูลค่าเพิ่มของสินค้า
                $qtyValue,                         // จำนวนสินค้าที่จะเบิก/ขาย
                $discountType,                     // ประเภทของส่วนลด (เช่น เปอร์เซ็นต์หรือจำนวนเงิน)
                $discountPrice,                    // จำนวนส่วนลดที่ใช้กับรายการนี้
                $totalSaleDis,                     // ยอดขายสุทธิหลังหักส่วนลด
                $memo,                             // ข้อความหมายเหตุ (ถ้ามี)
                $username,                         // ชื่อผู้ใช้งานที่ทำรายการ
                $reasons,                          // เหตุผลของการทำรายการ (เช่น เบิกสินค้า/ขายสินค้า)
                $customerName,                     // ชื่อลูกค้าที่เกี่ยวข้องกับรายการนี้
                $paidBy,                           // วิธีการชำระเงิน (เช่น เงินสด โอนเงิน บัตรเครดิต)
                $date,                             // วันที่ทำรายการ
                $store                             // สาขาหรือสถานที่ที่ทำรายการ
            ]);


            $checkStmt = $pdo->prepare("SELECT * FROM sub_stock WHERE sub_product_id = ? AND sub_location = ?");
            $checkStmt->execute([$productID, $store]);

            if ($checkStmt->rowCount() > 0) {
                $updateSubQtyStmt = $pdo->prepare("
                UPDATE sub_stock 
                SET sub_qty = sub_qty + :qty, sub_date_update = NOW() 
                WHERE sub_product_id = :productID AND sub_location = :store
                ");
                $updateSubQtyStmt->execute([
                    ':qty' => $qtyValue,
                    ':productID' => $productID,
                    ':store' => $store
                ]);
            } else {
                // ดึง st_name จาก store table โดยใช้ $store (st_id)
                $storeStmt = $pdo->prepare("SELECT st_name FROM store WHERE st_id = ?");
                $storeStmt->execute([$store]);
                $subName = $storeStmt->fetchColumn(); // ดึงค่า st_name

                if (!$subName) {
                    $subName = "Default Store Name"; // หากไม่พบ st_name ให้กำหนดค่าเริ่มต้น
                }

                // ทำการ insert ข้อมูลใหม่
                $insertSubStockStmt = $pdo->prepare("
    INSERT INTO sub_stock (sub_product_id, sub_name, sub_qty, sub_location, sub_date_add) 
    VALUES (:productID, :subName, :qty, :store, NOW())
    ");
                $insertSubStockStmt->execute([
                    ':productID' => $productID,
                    ':subName' => $subName, // ชื่อจาก store table
                    ':qty' => $qtyValue,
                    ':store' => $store
                ]);
            }
        }

        // ยืนยันการทำงาน
        $pdo->commit();
        echo "Form submitted successfully";
    } catch (PDOException $e) {
        // บันทึกข้อผิดพลาดลง log
        error_log("Error in transaction: " . $e->getMessage());
        error_log("Trace: " . $e->getTraceAsString());

        // ยกเลิก Transaction
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
