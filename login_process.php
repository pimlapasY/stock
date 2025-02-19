<?php
session_start();

$dbHost = "27.254.134.24";
$dbUser = "system_saiko";
$dbPass = "samtadmin12";
$dbName = "system_saiko";
$port = "3306";

try {
    // Establish database connection using PDO
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;port=$port", $dbUser, $dbPass);

    // Set PDO to throw exceptions on error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Set character encoding to UTF-8
    $pdo->exec("set names utf8");

    // Get username and password from POST request
    $username = $_POST['typeUserX'];
    $password = $_POST['typePasswordX'];

    // Prepare SQL statement to fetch user by username
    $stmt = $pdo->prepare("SELECT * FROM user WHERE u_username = :username AND u_password = :password");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);  // ไม่เข้ารหัส password
    $stmt->execute();

    // Fetch user
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Set session variables
        $_SESSION['lang'] = "en";// Language
        $_SESSION['role'] = $user['u_status'];  // User role (assuming u_status contains role)
        $_SESSION['id'] = $user['u_userid'];    // User ID (assuming u_userid contains user ID)
        $ID =  $_SESSION['id'];
        // Prepare the SQL query to update the logout date in the user table
        $stmt = $pdo->prepare("UPDATE user SET u_login_date = NOW() WHERE u_userid = :id");
        
        // Bind the ID parameter to the query
        $stmt->bindParam(':id', $ID, PDO::PARAM_INT);
        
        // Execute the query to update the database
        $stmt->execute();

        // Login successful
        echo json_encode(['success' => true]);
    } else {
        // Invalid username or password
        echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
    }

} catch (PDOException $e) {
    // Handle database connection error
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $e->getMessage()]);
}
?>