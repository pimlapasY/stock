<?php
require 'vendor/autoload.php'; // โหลด PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// เชื่อมต่อฐานข้อมูล
include 'connect.php';

// รับค่าที่ส่งมาจาก AJAX
$input = file_get_contents('php://input');
$request = json_decode($input, true);

$selectedIds = $request['selectedIds'] ?? [];

if (empty($selectedIds)) {
    die('No IDs provided.');
}

// แปลง array เป็น placeholders สำหรับ SQL
$placeholders = implode(',', array_fill(0, count($selectedIds), '?'));

// ระบุคอลัมน์ที่ต้องการดึงข้อมูล
$columns = ['pr_code', 'pr_mg_code', 'pr_product_code', 'pr_product_name', 'pr_qty',];

// Query ข้อมูลเฉพาะคอลัมน์ที่ต้องการ
$query = "SELECT " . implode(', ', $columns) . " FROM pr WHERE pr_id IN ($placeholders)";
$stmt = $pdo->prepare($query);
$stmt->execute($selectedIds);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($data)) {
    die('No data found for the given IDs.');
}

// สร้างไฟล์ Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// เขียน Header
foreach ($columns as $colIndex => $columnName) {
    $sheet->setCellValueByColumnAndRow($colIndex + 1, 1, $columnName); // เขียน Header ในแถวที่ 1
}

// เขียนข้อมูล
$rowIndex = 2; // เริ่มจากแถวที่ 2
foreach ($data as $row) {
    foreach ($columns as $colIndex => $columnName) {
        $value = $row[$columnName] ?? 'N/A'; // ใช้ 'N/A' หากค่าเป็น NULL
        $sheet->setCellValueByColumnAndRow($colIndex + 1, $rowIndex, $value); // เขียนข้อมูลทีละเซลล์
    }
    $rowIndex++;
}

// ส่งไฟล์ Excel ให้ดาวน์โหลด
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="selected_items.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
