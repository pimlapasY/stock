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
                <th>Edit</th>
                <th>Delete</th>
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
                    echo "<td>" . "<button id='edit' class='btn btn-outline-warning'><i class='fa-solid fa-pen-to-square'></i></button>" . "</td>";
                    echo "<td>" . "<button id='delete' class='btn btn-outline-danger btn-rounded delete-btn' data-mdb-ripple-init><i class='fa-regular fa-trash-can'></i></button>" . "</td>";
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