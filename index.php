<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACANA STOCK</title>
    <?php include('header.php') ?>
    <?php include('connect.php') ?>
</head>

<body>

    <?php include('./navbar.php') ?>

    <div class="container">
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

        <table class="table table-hover align-middle mb-0 text-center">
            <thead class="table-dark">
                <tr>
                    <th><?php echo '#' ?></th>
                    <th><?php echo $product_code ?></th>
                    <th><?php echo $product_name ?></th>
                    <th><?php echo $qty ?></th>
                    <th><?php echo $color ?></th>
                    <th><?php echo $size ?></th>
                    <th><?php echo $hands ?></th>
                    <th><?php echo $unit ?></th>
                    <th><?php echo $status ?></th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <!-- Table data will be inserted here -->
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
    <footer class="bg-body-tertiary text-center text-lg-start">
        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
            © 2024 SAMT:
            <a class="text-body">R＆D SECTION, All Rights Reserved.</a>
        </div>
    </footer>
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