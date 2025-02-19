<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier List</title>
</head>

<body>
    <div class="table-responsive">

        <table class="table table-bordered table-hover">
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
                        // Form for deletion
                        echo "<td class='text-center'>";
                        echo "<button class='btn btn-outline-danger btn-rounded delete-btn' data-sup-id='" . htmlspecialchars($supplier['sup_id']) . "' data-sup-name='" . htmlspecialchars($supplier['sup_name']) . "'><i class='fa-regular fa-trash-can'></i> ลบ</button>";
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
    </div>
    <script>
        $(document).ready(function() {
            // Handle click event on dynamically generated delete buttons
            $(document).on('click', '.delete-btn', function(e) {
                e.preventDefault(); // Prevent default button behavior

                // Get supplier ID and Name from data attributes
                var sup_id = $(this).data('sup-id');
                var sup_name = $(this).data('sup-name');

                // Show confirmation dialog
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to delete the supplier named '" + sup_name + "'",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '',
                    cancelButtonColor: '',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // If user confirms, send AJAX request to delete supplier
                        $.ajax({
                            type: 'POST',
                            url: 'delete_supplier.php', // Adjust to your delete script
                            data: {
                                sup_id: sup_id
                            },
                            success: function(response) {
                                // Refresh the page or update the table
                                Swal.fire(
                                    'Deleted!',
                                    'The supplier has been deleted.',
                                    'success'
                                ).then(() => {
                                    window.location.href =
                                        'list.php?page=register_supplier';
                                });
                            },
                            error: function(xhr, status, error) {
                                // Handle error
                                Swal.fire('Error!', 'Unable to delete supplier.',
                                    'error');
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