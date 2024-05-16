<?php include('navbar.php') ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History</title>
</head>

<body>
    <?php include('./navbar.php') ?>
    <div class="container">
        <div class="d-flex justify-content-start p-2">
            <h1 id="head_list">
                <?php
            echo ' <i class="fa-solid fa-laptop-medical fa-lg"></i> '. $history; 
            ?>
            </h1>
        </div>
        <div class="d-flex justify-content-start p-4">
            <ul class="nav nav-tabs">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <?php 
                    echo ' <a class="nav-link' . ($currentPage == 'his_all.php' ? ' active' : '') . '"href="his_all.php" id="productTab"
                    style="font-size: 20px;">';
               
                ?>
                        <i class="fa-solid fa-box fa-lg"></i> All Store</a>
                    </li>
                    <!-- Line break after the first list item -->
                    <?php
                echo '<a class="nav-link' . ($currentPage == 'his_samt.php' ? ' active' : '') . '" href="his_samt.php" id="samtTab" style="font-size: 20px;">';
                ?>
                    <i class="fa-solid fa-store fa-lg"></i> SAMT Store</a>
                    </li>
                    <!-- Line break after the first list item -->
                    <li class="nav-item">
                        <?php 
               
                    echo '<a class="nav-link' . ($currentPage == 'his_sakaba.php' ? ' active' : '') . '" href="his_sakaba.php" id="supplierTab" style="font-size: 20px;">';
                
                ?>
                        <i class="fa-solid fa-store fa-lg"></i> SAKABA Store</a>
                    </li>
                </ul>
            </ul>
        </div>
        <div class="d-flex justify-content-between m-3" style="align-items: center;">
            <div class="d-flex justify-content-end w-100">
                <div class="input-group p-3">
                    <input id="searchInput" type="search" class="form-control rounded" placeholder="Search"
                        aria-label="Search" aria-describedby="search-addon" />
                    <button type="button" class="btn btn-primary" data-mdb-ripple-init
                        onclick="loadData(1, $('#searchInput').val())">Search</button>
                </div>
            </div>
        </div>


</body>

</html>