<?php
session_start();

error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 1); 

$dbHost = "192.168.100.122";
$dbUser = "samt";
$dbPass = "samtadmin12";
$dbName = "Saiko2";
$port = "3306";

try {
    // Establish database connection using PDO
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);

    // Set PDO to throw exceptions on error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Set character encoding to UTF-8
    $pdo->exec("set names utf8");
} catch (PDOException $e) {
    // Handle database connection error
    die("Database connection failed: " . $e->getMessage());
}


?>