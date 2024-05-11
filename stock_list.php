<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACANA STOCK</title>
    <?php include('connect.php') ?>
</head>

<body>
    <?php include('./navbar.php') ?>
    <div class="container" style="margin-top: 150px;">
        <div class="d-flex justify-content-between m-3" style="align-items: center;">
            <div class="d-flex justify-content-start w-50" style="gap: 10px;">
                <button type="button" id="all-stock" class="btn btn-primary btn-rounded" data-mdb-ripple-init>ALL
                    STOCK</button>
                <!-- <button type="button" id="normal" class="btn btn-success btn-rounded"
                    data-mdb-ripple-init><?php echo $nomal ?></button> -->
                <button type="button" id="out-of-stock" class="btn btn-danger btn-rounded"
                    data-mdb-ripple-init><?php echo $soldOut ?></button>
                <!-- <button type="button" id="near-end" class="btn btn-warning btn-rounded"
                    data-mdb-ripple-init><?php echo $lessStock ?></button> -->
            </div>
            <div class="d-flex justify-content-end w-100">
                <div class="input-group p-3">
                    <input id="searchInput" type="search" class="form-control rounded" placeholder="Search"
                        aria-label="Search" aria-describedby="search-addon" />
                    <button type="button" class="btn btn-primary" data-mdb-ripple-init
                        onclick="loadData(1, $('#searchInput').val())">Search</button>
                </div>
            </div>
        </div>


        <table class="table table-bordered table-hover">
            <thead class="table-primary text-center">
                <tr style="vertical-align: middle;">
                    <th rowspan="2">No</th>
                    <th rowspan="2">Collection</th>
                    <th rowspan="2">Name</th>
                    <th rowspan="2">Hands</th>
                    <th rowspan="2">Color</th>
                    <th rowspan="2">Size</th>
                    <th rowspan="2">Cost price</th>
                    <th rowspan="2">Sale price</th>
                    <th rowspan="2">All Qty</th>
                    <th class="text-center" colspan="2">Store</th>
                </tr>
                <tr>
                    <th>SAMT</th>
                    <th>SAKABA</th>
                </tr>
            </thead>
            <tbody>
                <?php
            // Assuming $pdo holds the database connection
            // Prepare and execute SELECT query
            $stmt = $pdo->prepare("SELECT 
                            s_collection, 
                            s_product_name, 
                            s_hands, 
                            s_color, 
                            s_size, 
                            s_cost_price, 
                            s_sale_price, 
                            SUM(s_qty) AS total_qty 
                      FROM stock 
                      WHERE s_location IN ('SAMT', 'SAKABA') 
                      GROUP BY s_collection, s_product_name, s_hands, s_color, s_size, s_cost_price, s_sale_price");
            $stmt->execute();

            // Fetch all rows as an associative array
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($products as $index => $product) {
                echo "<tr>";
                echo "<td>" . ($index + 1) . "</td>"; // Display No starting from 1
                echo "<td>" . $product['s_collection'] . "</td>";
                echo "<td>" . $product['s_product_name'] . "</td>";
                echo "<td>" . $product['s_hands'] . "</td>";
                echo "<td>" . $product['s_color'] . "</td>";
                echo "<td>" . $product['s_size'] . "</td>";
                echo "<td>" . $product['s_cost_price'] . "</td>";
                echo "<td>" . $product['s_sale_price'] . "</td>";
                echo "<td>" . $product['total_qty'] . "</td>";
                echo "<td class='text-center' style='vertical-align: middle;'>";
                echo "<input class='form-check-input' type='checkbox' value='' id='samt" . ($index+1) . "' />";
                echo "</td>";
                echo "<td class='text-center' style='vertical-align: middle;'>";
                echo "<input class='form-check-input' type='checkbox' value='' id='sakaba" . ($index+1) . "' />";
                echo "</td>";
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
        <br>
        <div class="d-flex justify-content-end">
            <nav aria-label="Page navigation example text-end">
                <ul class="pagination" id="pagination">
                    <!-- Pagination links will be inserted here -->
                </ul>
            </nav>
        </div>
    </div>
</body>

</html>

<script>
$(document).ready(function() {
    // Initial page load
    loadData(1, '');

    // Search input change event
    $('#searchInput').on('input', function() {
        loadData(1, $(this).val());
    });

    // Pagination click event
    $(document).on('click', '.pagination-link', function() {
        loadData($(this).data('page'), $('#searchInput').val());
    });

    // Button click event
    $(".btn").click(function() {
        var buttonId = $(this).attr('id'); // Get the id of the clicked button
        var actionValue;

        // Assign values based on the button id
        switch (buttonId) {
            case "all-stock":
                actionValue = 1;
                break;
            case "normal":
                actionValue = 2;
                break;
            case "out-of-stock":
                actionValue = 3;
                break;
            case "near-end":
                actionValue = 4;
                break;
            default:
                actionValue = buttonId; // If the id doesn't match, send the id as action
                break;
        }

        // Prepare data to send
        var dataToSend = {
            action: actionValue
        };

        // Send AJAX request
        $.ajax({
            url: 'fetch_stocklist.php',
            method: 'POST',
            data: dataToSend,
            dataType: 'json',
            success: function(response) {
                // Update table body
                $('#tableBody').html(response.table_data);

                // Update pagination
                $('#pagination').html(response.pagination);
            },
            error: function(error) {
                // Handle error here
                console.log('Error:', error);
            }
        });
    });
});

function loadData(page, search) {
    $.ajax({
        url: 'fetch_stocklist.php',
        method: 'GET',
        data: {
            page: page,
            search: search
        },
        dataType: 'json',
        success: function(response) {
            // Update table body
            $('#tableBody').html(response.table_data);

            // Update pagination
            $('#pagination').html(response.pagination);
        },
        error: function(error) {
            // Handle error here
            console.log('Error:', error);
        }
    });
}
</script>