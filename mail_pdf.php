<?php
require 'fpdf.php'; // โหลด FPDF

include 'connect.php';

// รับค่าที่ส่งมาจาก AJAX
$input = file_get_contents('php://input');
$request = json_decode($input, true);

$selectedIds = $request['selectedIds'] ?? [];

if (empty($selectedIds)) {
    die('No IDs provided.');
}

// Query ข้อมูลจากฐานข้อมูล
$placeholders = implode(',', array_fill(0, count($selectedIds), '?'));
$query = "SELECT pr_code, pr_mg_code, pr_product_id, pr_product_code, pr_product_name, pr_qty FROM pr WHERE pr_id IN ($placeholders)";
$stmt = $pdo->prepare($query);
$stmt->execute($selectedIds);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($data)) {
    die('No data found for the given IDs.');
}

// สร้าง PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);

// เขียน Header
$pdf->Cell(30, 10, 'pr_code', 1);
$pdf->Cell(30, 10, 'pr_mg_code', 1);
$pdf->Cell(30, 10, 'pr_product_id', 1);
$pdf->Cell(30, 10, 'pr_product_code', 1);
$pdf->Cell(50, 10, 'pr_product_name', 1);
$pdf->Cell(20, 10, 'pr_qty', 1);
$pdf->Ln();

// เขียนข้อมูล
foreach ($data as $row) {
    $pdf->Cell(30, 10, $row['pr_code'], 1);
    $pdf->Cell(30, 10, $row['pr_mg_code'], 1);
    $pdf->Cell(30, 10, $row['pr_product_id'], 1);
    $pdf->Cell(30, 10, $row['pr_product_code'], 1);
    $pdf->Cell(50, 10, $row['pr_product_name'], 1);
    $pdf->Cell(20, 10, $row['pr_qty'], 1);
    $pdf->Ln();
}

// ส่งไฟล์ PDF ให้ดาวน์โหลด
$pdf->Output('D', 'selected_items.pdf');
exit;
