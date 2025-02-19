<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store List</title>
</head>

<body>
    <div class="table-responsive">
        <table class="table table-bordered table-hover" style="width: 100%;">
            <thead class="table-warning">
                <tr class="text-center">
                    <th>No</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Tel</th>
                    <th>Edit</th>
                    <th>Action</th>
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
                        echo "<td class='text-center'>" . "<button id='edit' onclick='editStore(" . $store['st_id'] . ")' class='btn btn-outline-warning'><i class='fa-solid fa-pen-to-square'></i></button>" . "</td>";

                        echo "<td class='text-center'>";
                        echo "<button id='delete' onclick='deleteStore(" . $store['st_id'] . ")' class='btn btn-link text-danger btn-rounded delete-btn me-5' data-mdb-ripple-init " . (($store['st_hide'] == '1') ? 'disabled' : "") . ">Disable</button>";
                        echo "<button id='open' onclick='openStore(" . $store['st_id'] . ")' class='btn  btn-link btn-rounded delete-btn' data-mdb-ripple-init " . (($store['st_hide'] == '1') ? '' : "disabled") . ">Enable</button>";
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
    <!-- Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <!-- เพิ่มคลาส modal-dialog-centered -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Store</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <div class="mb-3">
                            <label for="storeName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="storeName" name="storeName">
                        </div>
                        <div class="mb-3">
                            <label for="storeAddress" class="form-label">Address</label>
                            <input type="text" class="form-control" id="storeAddress" name="storeAddress">
                        </div>
                        <div class="mb-3">
                            <label for="storeTel" class="form-label">Tel</label>
                            <input type="text" class="form-control" id="storeTel" name="storeTel">
                        </div>
                        <input type="hidden" id="storeId" name="storeId">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="saveChanges()">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<script>
    function deleteStore(ID) {
        $.ajax({
            url: 'ajax_POST/delete_store.php',
            method: 'POST',
            data: {
                id: ID
            },
            dataType: 'json',
            success: function(store) {
                Swal.fire(
                    'ปิดการใช้งาน!',
                    'store ของคุณถูกปิดการใช้งานเรียบร้อย',
                    'success'
                ).then(() => {
                    window.location.href =
                        'list.php?page=register_store'; // Redirect to list.php with query parameter
                });

            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
            }
        });
    }


    function openStore(ID) {
        $.ajax({
            url: 'ajax_POST/open_store.php',
            method: 'POST',
            data: {
                id: ID
            },
            dataType: 'json',
            success: function(store) {
                Swal.fire(
                    'ปิดการใช้งาน!',
                    'store ของคุณถูกเปิดใช้งานเรียบร้อย',
                    'success'
                ).then(() => {
                    window.location.href =
                        'list.php?page=register_store'; // Redirect to list.php with query parameter
                });

            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
            }
        });
    }


    function editStore(ID) {
        $.ajax({
            url: 'ajax_GET/get_store.php',
            method: 'POST',
            data: {
                id: ID
            },
            dataType: 'json',
            success: function(store) {
                $('#storeId').val(store.st_id);
                $('#storeName').val(store.st_name);
                $('#storeAddress').val(store.st_addr);
                $('#storeTel').val(store.st_tel);
                $('#editModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
            }
        });
    }

    function saveChanges() {
        // Get data from the form
        const storeId = $('#storeId').val();
        const storeName = $('#storeName').val();
        const storeAddress = $('#storeAddress').val();
        const storeTel = $('#storeTel').val();

        // Send updated data via AJAX
        $.ajax({
            url: 'ajax_POST/update_store.php',
            method: 'POST',
            data: {
                id: storeId,
                name: storeName,
                address: storeAddress,
                tel: storeTel
            },
            dataType: 'json', // Expecting JSON response from the server
            success: function(response) {
                if (response.success) {
                    alert('Store updated successfully!');
                    window.location.href =
                        'list.php?page=register_store';
                } else {
                    alert('Error updating store!');
                }
            },
            error: function(xhr, status, error) {
                console.error("There was an error updating the store!", error);
            }
        });
    }
</script>