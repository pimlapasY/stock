<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier List</title>
</head>

<body>
    <table class="table table-bordered table-hover" style="width: 100%;">
        <thead class="table-info">
            <tr class="text-center">
                <th>No</th>
                <th>Name</th>
                <th>Address</th>
                <th>Tel</th>
                <th>Email</th>
                <th>Mainly product</th>
                <th>Memo</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Include the database connection
            include('connect.php');

            try {
                // Prepare and execute SELECT query
                $stmt = $pdo->prepare("SELECT * FROM suppliers");
                $stmt->execute();

                // Fetch all rows as an associative array
                $suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Loop through each supplier and display their data in table rows
                foreach ($suppliers as $index => $supplier) {
                    echo "<tr>";
                    echo "<td>" . ($index + 1) . "</td>"; // Display No starting from 1
                    echo "<td>" . htmlspecialchars($supplier['sup_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($supplier['sup_addr']) . "</td>";
                    echo "<td>" . htmlspecialchars($supplier['sup_tel']) . "</td>";
                    echo "<td>" . htmlspecialchars($supplier['sup_email']) . "</td>";
                    echo "<td>" . htmlspecialchars($supplier['sup_main_product']) . "</td>";
                    echo "<td>" . htmlspecialchars($supplier['sup_memo']) . "</td>";
                    echo "<td>";
                    // Form for deletion
                    echo "<form method='post' class='delete-form'>";
                    echo "<input type='hidden' name='sup_id' value='" . htmlspecialchars($supplier['sup_id']) . "'>";
                    echo "<input type='hidden' name='sup_name' value='" . htmlspecialchars($supplier['sup_name']) . "'>";
                    echo "<button type='submit' name='delete' class='btn btn-danger btn-rounded delete-btn' data-mdb-ripple-init><i class='fa-regular fa-trash-can'></i></button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
            } catch (PDOException $e) {
                // Handle any database errors
                echo "Error: " . $e->getMessage();
            }
            ?>
        </tbody>
    </table>

    <script>
    $(document).ready(function() {
        // Handle form submission for deletion
        $('.delete-form').submit(function(e) {
            e.preventDefault(); // Prevent form submission

            // Get the supplier ID to be deleted
            var sup_id = $(this).find("input[name='sup_id']").val();
            var sup_name = $(this).find("input[name='sup_name']").val();

            // Show confirmation dialog
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to delete the supplier named '" +
                    sup_name + "'", // Add a space before the supplier name
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // If user confirms, proceed with deletion
                    $.ajax({
                        type: 'POST',
                        url: 'delete_supplier.php', // Adjust the URL to your delete script
                        data: {
                            sup_id: sup_id
                        },
                        success: function(response) {
                            // Redirect after successful deletion
                            window.location.href =
                                'list.php?page=register_supplier';
                        },
                        error: function(xhr, status, error) {
                            // Handle errors here if necessary
                            console.error(xhr.responseText);
                        }
                    });
                }
            });
        });
    });
    </script>
</body>

</html>