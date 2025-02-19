<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Collection</title>
</head>

<body>
    <div class="container mt-5">
        <h1>Register Collection</h1>
        <form id="registerCollectionForm" method="POST">
            <input type="hidden" name="form_type" value="registerCollectionForm">
            <table class="table table-borderless table-hover" style="width: 700px;">
                <tr>
                    <th>Collection Name:</th>
                    <td><input type="text" class="form-control" name="col_name" id="col_name" required /></td>
                </tr>
                <tr>
                    <td colspan="2" class="text-end">
                        <button type="button" class="btn btn-success btn-lg" id="submitColBtn">
                            <i class="fa-solid fa-plus"></i> ADD
                        </button>
                    </td>
                </tr>
            </table>
        </form>
        <div id="responseMessage"></div>
    </div>

    <script>
        $(document).ready(function() {
            $('#submitColBtn').click(function() {
                var col_name = $('#col_name').val();

                // Validate the input
                if (!col_name) {
                    $('#responseMessage').html(
                        '<span style="color:red;">Collection name is required.</span>');
                    return;
                }

                // Send data using AJAX
                $.ajax({
                    url: 'insert_collection.php',
                    type: 'POST',
                    data: $('#registerCollectionForm').serialize(),
                    success: function(response) {
                        $('#responseMessage').html('<span style="color:green;">' +
                            'สำเร็จ! รายการคอลเลกชันของคุณถูกบันทึกเรียบร้อยแล้ว พร้อมลงทะเบียนรายการใหม่ได้ทันที' +
                            '</span>');


                        /*  $('#responseMessage').html('<span style="color:green;">' +
                             response +
                             '</span>'); */
                        $('#col_name').val(''); // Clear the form
                    },
                    error: function(xhr, status, error) {
                        $('#responseMessage').html(
                            '<span style="color:red;">An error occurred: ' + error +
                            '</span>');
                    }
                });
            });
        });
    </script>
</body>

</html>