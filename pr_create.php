<?php include('navbar.php') ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    <title>PR Management</title>
</head>

<body>
    <div class="container-fluid">
        <div class="d-flex justify-content-start p-2">
            <h1 id="head_list">
                <?php
                echo '<i class="fa-solid fa-file-circle-plus fa-xl"></i> ' . $pr_add;
                ?>
            </h1>
        </div>
    </div>
</body>

</html>