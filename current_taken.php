<?php include('navbar.php') ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Current Taken</title>
</head>
<!-- <style>
td {
    padding: 15px;
    /* ปรับค่าตามที่ต้องการ */
}

th {
    font-size: 15px;
    height: 70px;
    background-color: #E5F9E5;
}
</style> -->

<body>
    <div class="container">
        <h1><i class="fa-solid fa-database fa-xl"></i> Current Taken</h1><br>
        <a href="#" class="btn btn-success" id="allBtn">All</a>
        <a href="#" class="btn btn-warning" id="partSaleBtn">Part Sale</a>
    </div>
    <br>
    <div class="container-fluid">
        <table class="table table-hover mx-auto">
            <thead class="text-center table-secondary" style="text-transform: uppercase;">
                <th>#</th>
                <th>CODE</th>
                <!-- <th>Stock out</th> -->
                <th>product</th>
                <!-- <th>Receipt date</th> -->
                <th>Supplier</th>
                <th>size</th>
                <th>color</th>
                <th>hand</th>
                <th>qty</th>
                <th>Sold date</th>
                <th>customer</th>
                <th>piad by</th>
                <th>payment</th>
                <th>PR status</th>
                <th>PO status</th>
                <th>Delivery status</th>
                <th>Memo</th>
                <th>select</th>
            </thead>
            <tbody id="dataTable">


            </tbody>
        </table>
    </div>
</body>

</html>

<script>
$(document).ready(function() {
    // Function to load data based on button clicked
    function loadData(reasons = null) {
        // Define the URL for the AJAX request
        var url = "current_fetch_data.php";
        // Define the data to be sent
        var data = {
            reasons: reasons
        };
        // Perform an AJAX request
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            success: function(response) {
                // Replace the content of dataTable with the new data
                $('#dataTable').html(response);
            }
        });
    }

    // Load all data when the page loads
    loadData();

    // Event listener for all button
    $('#allBtn').click(function(e) {
        e.preventDefault();
        loadData();
    });

    // Event listener for part sale button
    $('#partSaleBtn').click(function(e) {
        e.preventDefault();
        loadData('sale');
    });
});
</script>