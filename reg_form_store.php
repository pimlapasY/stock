<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REGISTER</title>
    <script>
        // Function to show confirmation dialog and submit form via AJAX
        function confirmSubmit() {
            // Check if any required fields are empty
            var form = document.getElementById("registerStoreForm");
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
                confirmButtonText: "Yes, submit!"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form via AJAX
                    $.ajax({
                        url: 'form_submit.php', // Update this with the URL to your PHP script
                        type: 'POST',
                        data: $('#registerStoreForm').serialize(), // Serialize form data
                        success: function(response) {
                            // Handle success response
                            Swal.fire({
                                title: 'Success!',
                                text: /*  response,  */ 'Automatically redirecting to the List page...',
                                icon: 'success',
                                timer: 2000,
                                timerProgressBar: true
                            }).then(() => {
                                window.location.href = 'list.php?page=register_store';
                            });
                        },
                        error: function(xhr, status, error) {
                            // Handle error response
                            Swal.fire({
                                title: 'Error!',
                                text: 'Failed to insert data into the database.',
                                icon: 'error'
                            });
                        }
                    });
                }
            });
        }
    </script>
</head>

<body>
    <form id="registerStoreForm" method="POST">
        <input type="hidden" name="form_type" value="registerStoreForm">
        <table class="table table-borderless table-hover" style="width: 700px;">
            <tr>
                <th>Store Name:</th>
                <td><input type="text" class="form-control" name="st_name" /></td>
            </tr>
            <tr>
                <th>Address:</th>
                <td><input type="text" class="form-control" name="st_addr" /></td>
            </tr>
            <tr>
                <th>Tel:</th>
                <td><input type="text" class="form-control" name="st_tel" /></td>
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