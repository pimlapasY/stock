<?php
session_start();    
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 0);
// Disable warnings
if (isset($_SESSION['role'])) {

//สำหรับ xamp ทั่วไป
/* $dbHost = "localhost";
$dbUser = "root";
$dbPass = ""; */
if($_SESSION['role'] == 'Dev' || $_SESSION['role'] == 'Test'){
    $dbHost = "27.254.134.24";
    $dbUser = "system_saiko";
    $dbPass = "samtadmin12";
    $dbName = "system_saiko";
    $port = "3306";
}else if($_SESSION['role'] == 'Admin'){
    $dbHost = "192.168.100.122";
    $dbUser = "samt";
    $dbPass = "samtadmin12";
    $dbName = "system_saiko";
    $port = "3306";
}else if($_SESSION['role'] == 'User'){
    $dbHost = "192.168.100.122";
    $dbUser = "samt";
    $dbPass = "samtadmin12";
    $dbName = "system_saiko";
    $port = "3306";
}
try {
    // Establish database connection using PDO
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);

    // Set PDO to throw exceptions on error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Count rows in stockout table where MG_CODE matches the pattern
    // Set character encoding to UTF-8
    $pdo->exec("set names utf8");
} catch (PDOException $e) {
    // Handle database connection error
    die("Database connection failed: " . $e->getMessage());
}
 
}else{ 
    header('Location: login.php');
    exit;
}

?>