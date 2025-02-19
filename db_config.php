<?php
if ($_SESSION['role'] == 'Dev' || $_SESSION['role'] == 'Test') {
    $dbHost = "27.254.134.24";
    $dbUser = "system_saiko_demo";
    $dbPass = "samtadmin12";
    $dbName = "system_saiko_demo";
    $port = "3306";
} else {
    $dbHost = "27.254.134.24";
    $dbUser = "system_saiko";
    $dbPass = "samtadmin12";
    $dbName = "system_saiko";
    $port = "3306";
}
