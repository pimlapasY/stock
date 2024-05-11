<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Good request</title>
</head>

<body>
    <?php include('navbar.php') ?>
    <div class="container">
        <div class="input-group p-3">
            <input id="searchInput" type="search" class="form-control rounded" placeholder="Search" aria-label="Search"
                aria-describedby="search-addon" />
            <button type="button" class="btn btn-primary" data-mdb-ripple-init
                onclick="loadData(1, $('#searchInput').val())"><i class="fa-solid fa-plus"></i></button>
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