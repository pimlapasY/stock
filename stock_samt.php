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
    <div class="container-fluid" style="margin-top: 150px;">
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

        <table class="table table-hover align-middle mb-0 text-center table-bordered">
            <thead class="table-dark">
                <tr>
                    <th><?php echo '#' ?></th>
                    <th><?php echo $product_code ?></th>
                    <th><?php echo $stock_in ?></th>
                    <th><?php echo $product_name ?></th>
                    <th><?php echo 'Purchased or Returned' ?></th>
                    <th><?php echo 'Receipt date' ?></th>
                    <th><?php echo 'PR status' ?></th>
                    <th><?php echo 'PO status' ?></th>
                    <th><?php echo 'Supplier' ?></th>
                    <th><?php echo $size ?></th>
                    <th><?php echo $color ?></th>
                    <th><?php echo $hands ?></th>
                    <th><?php echo $qty ?></th>
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
</body>

</html>