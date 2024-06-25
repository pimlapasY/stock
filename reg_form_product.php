<?php
include('connect.php');
$stmt_supplier = $pdo->query("SELECT DISTINCT sup_name FROM suppliers");
$supplier = $stmt_supplier->fetchAll(PDO::FETCH_COLUMN);  

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prepare an SQL statement
    $sql = "INSERT INTO product (p_product_code, p_collection, p_product_name, p_hands, p_color, p_size, p_unit, p_cost_price, p_sale_price, p_usercode_add, p_supplier, p_date_add) 
            VALUES (:p_product_code, :p_collection, :p_product_name, :p_hands, :p_color, :p_size, :p_unit, :p_cost_price, :p_sale_price, :p_usercode_add, :p_supplier, now())";
    $stmt = $pdo->prepare($sql);

    // Bind parameters to statement
    $stmt->bindParam(':p_product_code', $_POST['p_product_code']);
    $stmt->bindParam(':p_collection', $_POST['p_collection']);
    $stmt->bindParam(':p_product_name', $_POST['p_product_name']);
    $stmt->bindParam(':p_hands', $_POST['p_hands']);
    $stmt->bindParam(':p_color', $_POST['p_color']);
    $stmt->bindParam(':p_size', $_POST['p_size']);
    $stmt->bindParam(':p_unit', $_POST['p_unit']);
    $stmt->bindParam(':p_cost_price', $_POST['p_cost_price']);
    $stmt->bindParam(':p_sale_price', $_POST['p_sale_price']);
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
    <form id="registerForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <table class="table table-borderless table-hover" style="width: 700px;">
            <tr>
                <th>Product Code</th>
                <td>
                    <input class="form-control" name="p_product_code" required />
                </td>
            </tr>
            <tr>
                <th>Collection</th>
                <td>
                    <select class="form-select" name="p_collection">
                        <option selected></option>
                        <option value="it">IT</option>
                        <option value="sport">Sport</option>
                        <option value="home">Home</option>
                        <option value="home">Other</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Product Name</th>
                <td>
                    <input class="form-control" name="p_product_name" required />
                </td>
            </tr>
            <tr>
                <th>Hands</th>
                <td>
                    <input class="form-control" name="p_hands" required />
                </td>
            </tr>
            <tr>
                <th>Color</th>
                <td>
                    <input class="form-control" name="p_color" required />
                </td>
            </tr>
            <tr>
                <th>Size</th>
                <td>
                    <input class="form-control" name="p_size" required />
                </td>
            </tr>
            <tr>
                <th>Unit</th>
                <td>
                    <input class="form-control" name="p_unit" required />
                </td>
            </tr>
            <tr>
                <th>Cost Price</th>
                <td>
                    <input type="number" min="1" class="form-control" name="p_cost_price" required />
                </td>
            </tr>
            <tr>
                <th>Sale Price</th>
                <td>
                    <input type="number" min="1" class="form-control" name="p_sale_price" required />
                </td>
            </tr>
            <tr>
                <th>Holding location</th>
                <td>
                    <input class="form-control" value="SAMT" disabled />
                </td>
            </tr>
            <tr>
                <th>Supplier</th>
                <td>
                    <input class="form-control" type="text" id="supplier" name="supplier_name" list="new_supplier_name">
                    <datalist id="new_supplier_name">
                        <?php foreach ($supplier as $supplier_name): ?>
                        <option value="<?php echo $supplier_name; ?>">
                            <?php endforeach; ?>
                    </datalist>
                </td>
            </tr>
            <tr>
                <th>Memo</th>
                <td>
                    <textarea type="text" class="form-control" name="memo" placeholder="Note"></textarea>
                </td>
            </tr>
            <tr>
                <td>
                    <input class="form-control" name="p_usercode_add" value="<?php echo $row['u_usercode'] ?>" hidden />
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