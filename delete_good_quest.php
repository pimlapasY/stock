<?php
// Include the database connection
include('connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reqno'])) {
    try {
        $reqno = $_POST['reqno'];

        $stmt = $pdo->prepare("DELETE FROM request WHERE r_reqno = ?");
        $stmt->execute([$reqno]);
        echo $reqno;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
?>