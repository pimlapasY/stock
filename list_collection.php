<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
</head>
<?php include('connect.php'); ?>

<body>
    <div class="table-responsive">
        <table class="table table-sm table-bordered table-hover mx-auto">
            <!-- Table content -->
            <thead class="table-primary text-center">
                <tr style="vertical-align: middle;">
                    <th class="text-center" colspan="1"><?php echo 'ID'; ?></th>
                    <th><?php echo 'Name'; ?></th>
                    <th><?php echo 'Actions'; ?></th>
                    <th><?php echo 'Date Add'; ?></th>

                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch individual product rows
                $stmt = $pdo->prepare("SELECT * FROM collection");

                // Execute the statement
                $stmt->execute();
                $collection = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Loop through products and output each row
                foreach ($collection as $index => $col) {
                    echo "<tr data-id='" . htmlspecialchars($col['col_id']) . "'>";
                    echo "<td>" . $col['col_id'] . "</td>";
                    echo "<td>" . $col['col_name'] . "</td>";
                    echo "<td class='text-center'>";
                    echo "<a class='btn btn-link text-warning'  onclick='editCol(" . $col['col_id'] . ")'>Edit</a>";
                    echo "<a class='btn btn-link text-danger'   onclick='deleteCol(" . $col['col_id'] . ")'>Delete</a>";
                    echo "</td>";
                    $date = new DateTime($col['col_date_add']);
                    echo "<td>" . $date->format('d/m/Y H:i') . "</td>";
                    echo "</tr>";
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
                    <h5 class="modal-title" id="editModalLabel">Edit collection</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <div class="mb-3">
                            <label for="colName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="colName" name="colName"
                                onchange="markChanged()">
                        </div>
                        <input type="text" id="colId" hidden>
                    </form>
                    <div class="alert" role="alert" id="alertCol" hidden>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="validateAndSave()">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<script>
    let isChanged = false;

    // Mark the form as changed when the user modifies the input
    function markChanged() {
        isChanged = true;
    }

    function validateAndSave() {
        const colName = document.getElementById('colName').value;

        if (!isChanged) {
            // Show alert if no changes were made
            $('#alertCol').removeClass('alert-danger').addClass('alert-warning').html(
                    'No changes detected.')
                .prop('hidden', false);
            return;
        }

        if (!colName.trim()) {
            $('#alertCol').removeClass('alert-warning').addClass('alert-danger').html('Name is required!').prop('hidden',
                false);
            return;
        }

        // Call the saveChanges function if validation passes
        saveChanges();
    }

    function saveChanges() {
        const colId = document.getElementById('colId').value;
        const colName = document.getElementById('colName').value;

        $.ajax({
            url: 'ajax_POST/save_collection.php',
            method: 'POST',
            data: {
                col_id: colId,
                col_name: colName
            },
            success: function(response) {
                alert('Changes saved successfully!');
                $('#editModal').modal('hide'); // Close the modal
                window.location.href =
                    'list.php?page=register_collection';
            },
            error: function(xhr, status, error) {
                console.error(" Error:", error);
                alert('Error saving changes.');
            }
        });
    }

    function editCol(ID) {
        $('#alertCol').removeClass('alert-warning').removeClass('alert-danger').prop('hidden',
            true);
        $.ajax({
            url: 'ajax_GET/get_collection_list.php',
            method: 'POST',
            data: {
                id: ID
            },
            dataType: 'json',
            success: function(col) {
                $('#colId').val(col.col_id);
                $('#colName').val(col.col_name);
                $('#editModal').modal('show');
            },
            error: function(xhr, status,
                error) {
                console.error("Error:", error);
            }
        });
    }

    function deleteCol(ID) {}
</script>