<?php
include('connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are filled

         // Insert data into the database
         $stmt = $pdo->prepare("INSERT INTO stock (s_collection, s_product_code, s_product_name, s_cost_price, s_sale_price, s_location, s_memo, s_date_add) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");

         
    try {
        $stmt->execute([$_POST['s_collecttion'], $_POST['s_product_code'], $_POST['s_product_name'], $_POST['s_cost_price'], $_POST['s_sale_price'], $_POST['s_location'], $_POST['s_memo']]);

        // After successful insertion, redirect to stock_in.php
        echo "<script>
                Swal.fire({
                    title: 'Success!',
                    text: 'Automatically redirecting to the stock in page...',
                    icon: 'success',
                    timer: 2000,
                    timerProgressBar: true
                }).then(() => {
                    window.location.href = 'list.php';
                });
              </script>";
        exit;
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
    <script src="js/sweetalert2.all.min.js"></script>
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
                <th>Collection</th>
                <td>
                    <input class="form-control" name="s_collecttion" required />

                </td>
            </tr>
            <tr>
                <th>Code</th>
                <td>
                    <input class="form-control" name="s_product_code" required />

                </td>
            </tr>
            <tr>
                <th>Name</th>
                <td>
                    <input class="form-control" name="s_product_name" required />
                </td>
            </tr>
            <tr>
                <th>Cost price</th>
                <td><input type="number" class="form-control" name="s_cost_price" required />
                </td>
            </tr>
            <tr>
                <th>Sale price</th>
                <td><input type="number" class="form-control" name="s_sale_price" required />
                </td>
            </tr>
            <tr>
                <th>Holding location</th>
                <td><input class="form-control" name="s_location" value="SAMT" required />
                </td>
            </tr>
            <tr>
                <th>Memo</th>
                <td><textarea class="form-control" name="s_memo"></textarea></td>
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