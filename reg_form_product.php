<?php
include('connect.php');
session_start();

if ($_SESSION["lang"] == "en" || !isset($_SESSION["lang"])) {
    include("lang/lang_en.php");
} else {
    include("lang/lang_th.php");
}

// Prepare and execute the query
$stmt_options1 = $pdo->query("SELECT  DISTINCT otl_name FROM options_list WHERE otl_num = 1");
$options1 = $stmt_options1->fetchAll(PDO::FETCH_COLUMN);

$stmt_options2 = $pdo->query("SELECT DISTINCT otl_name FROM options_list WHERE otl_num = 2");
$options2 = $stmt_options2->fetchAll(PDO::FETCH_COLUMN);

$stmt_options3 = $pdo->query("SELECT DISTINCT otl_name FROM options_list WHERE otl_num = 3");
$options3 = $stmt_options3->fetchAll(PDO::FETCH_COLUMN);

$stmt_sup = $pdo->query("SELECT DISTINCT sup_name FROM suppliers");
$suppliers = $stmt_sup->fetchAll(PDO::FETCH_COLUMN);

$stmt_collections = $pdo->query("SELECT DISTINCT col_name FROM collection");
$collections = $stmt_collections->fetchAll(PDO::FETCH_COLUMN);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Convert only the first part to uppercase
    $option1 = !empty($_POST['p_hands']) ? (strtoupper($_POST['option1']) . ': ' . $_POST['p_hands']) : null;
    $option2 = !empty($_POST['p_color']) ? (strtoupper($_POST['option2']) . ': ' . $_POST['p_color']) : null;
    $option3 = !empty($_POST['p_size']) ? (strtoupper($_POST['option3']) . ': ' . $_POST['p_size']) : null;


    // Prepare an SQL statement
    $sql = "INSERT INTO product (p_product_code, p_collection, p_product_name, p_hands, p_color, p_size, p_unit, p_cost_price, p_sale_price, p_vat, p_usercode_add, p_supplier, p_date_add) 
            VALUES (:p_product_code, :p_collection, :p_product_name, :p_hands, :p_color, :p_size, :p_unit, :p_cost_price, :p_sale_price, :p_vat, :p_usercode_add, :p_supplier, now())";
    $stmt = $pdo->prepare($sql);

    // Bind parameters to statement
    $stmt->bindParam(':p_product_code', $_POST['p_product_code']);
    $stmt->bindParam(':p_collection', $_POST['p_collection']);
    $stmt->bindParam(':p_product_name', $_POST['p_product_name']);
    $stmt->bindParam(':p_hands', $option1);
    $stmt->bindParam(':p_color', $option2);
    $stmt->bindParam(':p_size', $option3);
    $stmt->bindParam(':p_unit', $_POST['p_unit']);
    $stmt->bindParam(':p_cost_price', $_POST['p_cost_price']);
    $stmt->bindParam(':p_sale_price', $_POST['p_sale_price']);
    $stmt->bindParam(':p_vat',  $_POST['p_vat']);
    $stmt->bindParam(':p_usercode_add',  $_POST['p_usercode_add']);
    $stmt->bindParam(':p_supplier',  $_POST['supplier_name']);

    // Execute the statement
    try {
        if ($stmt->execute()) {
            // After successful insertion, redirect to the list.php page
            echo "<script>
                    Swal.fire({
                        title: 'Success!',
                        text: 'Automatically redirecting to the product list page...',
                        icon: 'success',
                        timer: 2000,
                        timerProgressBar: true
                    }).then(() => {
                        window.location.href = 'list.php';
                    });
                  </script>";
            exit;
        }
    } catch (PDOException $e) {
        // Handle database insertion error
        die("Database error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REGISTER</title>
    <script>
        // Function to show confirmation dialog
        function confirmSubmit() {
            // Check if any required fields are empty
            var form = document.getElementById("registerForm");
            var inputs = form.querySelectorAll("input[required], textarea[required]");
            for (var i = 0; i < inputs.length; i++) {
                if (!inputs[i].value) {
                    Swal.fire({
                        title: "Error!",
                        text: "Please fill in required fields.",
                        icon: "error"
                    });
                    return false; // Prevent form submission
                }
            }

            Swal.fire({
                title: "Are you sure?",
                text: "Once submitted, data will be inserted into the database!",
                icon: "info",
                showCancelButton: true,
                confirmButtonText: "Yes, submit!",
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById("registerForm").submit();
                }
            });
        }
    </script>
</head>

<style>
    th {
        width: 200px;
        font-size: 16px;
    }
</style>

<body>

    <!-- Modal code -->
    <div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header text-bg-light">
                    <h5 class="modal-title" id="previewModalLabel">
                    </h5>
                    <span class="badge badge-info" id="addTitle"></span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <p>Current List:</p>
                    <select class="form-select mb-3" id="currentOptions">

                    </select>
                    <p>New List:</p>
                    <input class="form-control" type="text" id="numList" hidden>
                    <input class="form-control" type="text" id="valueList">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"
                        aria-label="Close">Close</button>
                    <button type="button" class="btn btn-info" onclick="submitList()">ADD</button>
                </div>
            </div>
        </div>
    </div>

    <form id="registerForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <table class="table table-borderless table-hover" style="width: 700px;" id="optionsTable">
            <tr>
                <th><?php echo $product_code; ?>:</th>
                <td>
                    <input class="form-control" name="p_product_code" required />
                </td>
            </tr>
            <tr>
                <th><?php echo $collection; ?>:</th>
                <td>
                    <select class="form-select" name="p_collection">
                        <option disabled><?php echo $choosePlaceholder; ?></option>
                        <?php foreach ($collections as $col): ?>
                            <option value="<?php echo $col; ?>">
                                <?php echo htmlspecialchars($col); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><?php echo $product_name; ?>:</th>
                <td>
                    <input class="form-control" name="p_product_name" required />
                </td>
            </tr>

            <tr>
                <th><?php echo $options1_label; ?><br><a class="btn btn-link" href="#" id="option-list1">+ options
                        list</a></th>
                <td>
                    <select class="form-select" name="option1">
                        <option disabled><?php echo $choosePlaceholder; ?></option>
                        <?php foreach ($options1 as $option1): ?>
                            <option value="<?php echo $option1; ?>">
                                <?php echo htmlspecialchars($option1) . ':'; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td class="relative-cell">
                    <input class="form-control" name="p_hands" required />
                    <span class="text-danger asterisk">*</span>
                </td>
            </tr>
            <tr hidden>
                <th><?php echo $options2_label; ?><br><a class="btn btn-link" href="#" id="option-list2">+ options
                        list</a></th>
                <td>
                    <select class="form-select" name="option2">
                        <option disabled><?php echo $choosePlaceholder; ?></option>
                        <?php foreach ($options2 as $option2): ?>
                            <option value="<?php echo $option2; ?>">
                                <?php echo htmlspecialchars($option2) . ':'; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td class="relative-cell">
                    <input class="form-control" name="p_color" />
                    <span class="text-danger asterisk">*</span>
                </td>
            </tr>
            <tr hidden>
                <th><?php echo $options3_label; ?><br><a class="btn btn-link" href="#" id="option-list3">+ options
                        list</a></th>
                <td>
                    <select class="form-select" name="option3">
                        <option disabled><?php echo $choosePlaceholder; ?></option>
                        <?php foreach ($options3 as $option3): ?>
                            <option value="<?php echo $option3; ?>">
                                <?php echo htmlspecialchars($option3) . ':'; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td class="relative-cell">
                    <input class="form-control" name="p_size" />
                    <span class="text-danger asterisk">*</span>
                </td>
            </tr>
            <tr>
                <td>
                    <button class="btn btn-tertiary" type="button" id="deleteRowButton" disabled>
                        <i class="fa-solid fa-square-minus"></i> Delete Row
                    </button>
                </td>
                <td>
                    <button class="btn btn-tertiary" type="button" id="addRowButton">
                        <i class="fa-solid fa-circle-plus"></i> Add Row
                    </button>
                </td>
            </tr>

            <tr>
                <th><?php echo $unit; ?>:</th>
                <td>
                    <input class="form-control" name="p_unit" required />
                </td>
            </tr>
            <tr>
                <th><?php echo $costPrice; ?>:</th>
                <td>
                    <input type="number" min="1" class="form-control" name="p_cost_price" required />
                </td>
            </tr>
            <tr>
                <th><?php echo $salePrice; ?>:</th>
                <td>
                    <input type="number" min="1" class="form-control" name="p_sale_price" required />
                </td>
            </tr>
            <tr>
                <th><?php echo 'vat(%)'; ?>:</th>
                <td>
                    <input type="number" min="1" class="form-control" name="p_vat" required />
                </td>
            </tr>
            <tr>
                <th><?php echo $holdingLocation; ?>:</th>
                <td>
                    <input class="form-control" value="SAMT" disabled />
                </td>
            </tr>
            <tr>
                <th><?php echo $supplier; ?>:</th>
                <td>
                    <select class="form-select" name="supplier">
                        <option disabled selected><?php echo $choosePlaceholder; ?></option>
                        <?php foreach ($suppliers as $supplier): ?>
                            <option value="<?php echo htmlspecialchars($supplier); ?>">
                                <?php echo htmlspecialchars($supplier); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><?php echo $memo; ?>:</th>
                <td>
                    <textarea type="text" class="form-control" name="memo" placeholder="Note"></textarea>
                </td>
            </tr>
            <tr>
                <td>
                    <input class="form-control" name="p_usercode_add"
                        value="<?php echo htmlspecialchars($row['u_usercode']); ?>" hidden />
                </td>
            </tr>

            <tr>
                <td colspan="2" class="text-end">
                    <!-- Call confirmSubmit() function on button click -->
                    <button type="button" class="btn btn-success btn-lg" onclick="confirmSubmit()">
                        <i class="fa-solid fa-floppy-disk"></i> SUBMIT
                    </button>
                </td>
            </tr>
        </table>

    </form>
</body>

</html>
<script>
    $(document).ready(function() {
        const $rows = $('#optionsTable tr[hidden]');

        let currentIndex = 0;

        $('#addRowButton').click(function() {
            if (currentIndex < $rows.length) {
                $rows.eq(currentIndex).removeAttr('hidden');
                currentIndex++;
            }
            if (currentIndex > 0) {
                $('#deleteRowButton').prop('disabled', false);
            }

            // Disable the button if all rows are shown
            if (currentIndex >= $rows.length) {
                $('#addRowButton').prop('disabled', true);
            }
        });

        $('#deleteRowButton').click(function() {
            if (currentIndex > 0) {
                currentIndex--;
                $rows.eq(currentIndex).attr('hidden', true);
            }

            // Disable the delete button if no rows are visible
            if (currentIndex <= 0) {
                $('#deleteRowButton').prop('disabled', true);
            }

            // Re-enable the add button if rows can be added again
            if (currentIndex < $rows.length) {
                $('#addRowButton').prop('disabled', false);
            }
        });

        $('#option-list1').on('click', function() {
            $('#previewModal').modal('show');
            $('#previewModalLabel').html('Option 1 List');
            $('#addTitle').html('ADD');
            $('#numList').val(1);
            $('#currentOptions').html(`
         <option disabled>choose...</option>
                        <?php foreach ($options1 as $option1): ?>
                        <option value="<?php echo $option1; ?>">
                            <?php echo $option1 . ':'; ?>
                        </option>
                        <?php endforeach; ?>
        `);

        });
        $('#option-list2').on('click', function() {
            $('#previewModal').modal('show');
            $('#previewModalLabel').html('Option 2 List');
            $('#addTitle').html('ADD');
            $('#numList').val(2);
            $('#currentOptions').html(`
         <option disabled>choose...</option>
                        <?php foreach ($options2 as $option2): ?>
                        <option value="<?php echo $option2; ?>">
                            <?php echo $option2 . ':'; ?>
                        </option>
                        <?php endforeach; ?>
        `);
        });
        $('#option-list3').on('click', function() {
            $('#previewModal').modal('show');
            $('#previewModalLabel').html('Option 3 List');
            $('#addTitle').html('ADD');
            $('#numList').val(3);
            $('#currentOptions').html(`
         <option disabled>choose...</option>
                        <?php foreach ($options3 as $option3): ?>
                        <option value="<?php echo $option3; ?>">
                            <?php echo $option3 . ':'; ?>
                        </option>
                        <?php endforeach; ?>
        `);
        });
    });

    function submitList() {
        // Get the input values
        var num = $('#numList').val();
        var valueList = $('#valueList').val();
        // Check if valueList is null or an empty string
        if (valueList === null || valueList.trim() === '') {
            Swal.fire({
                title: 'Warning!',
                text: 'Please provide a value.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return false;
        }
        // AJAX request to send data to the server
        $.ajax({
            url: 'ajax_POST/insert_options_list.php', // Replace with your server endpoint
            type: 'POST',
            data: {
                otl_num: num,
                otl_name: valueList
            },
            success: function(response) {
                // Show success message with SweetAlert
                Swal.fire({
                    title: 'Success!',
                    text: 'Data has been added successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    $('#previewModal').modal('hide');
                    $('#valueList').val('');
                    // Optionally, refresh the table or perform other actions
                    /* $('#optionsListTable').append(
                        `<tr><td>${num}</td><td>${valueList}</td><td>${new Date().toLocaleString()}</td></tr>`
                    ); */
                });
            },
            error: function(error) {
                // Show error message with SweetAlert
                Swal.fire({
                    title: 'Error!',
                    text: 'An error occurred while submitting the data.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    }
</script>