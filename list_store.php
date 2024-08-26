<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store List</title>
</head>

<body>
    <table class="table table-bordered table-hover" style="width: 100%;">
        <thead class="table-warning">
            <tr class="text-center">
                <th>No</th>
                <th>Name</th>
                <th>Address</th>
                <th>Tel</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Include the database connection
            include('connect.php');

            try {
                // Prepare and execute SELECT query
                $stmt = $pdo->prepare("SELECT * FROM store");
                $stmt->execute();

                // Fetch all rows as an associative array
                $stores = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Loop through each supplier and display their data in table rows
                foreach ($stores as $index => $store) {
                    echo "<tr>";
                    echo "<td>" . ($index + 1) . "</td>"; // Display No starting from 1
                    echo "<td>" . htmlspecialchars($store['st_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($store['st_addr']) . "</td>";
                    echo "<td>" . htmlspecialchars($store['st_tel']) . "</td>";
                    echo "</tr>";
                }
            } catch (PDOException $e) {
                // Handle any database errors
                echo "Error: " . $e->getMessage();
            }
            ?>
        </tbody>
    </table>
</body>

</html>