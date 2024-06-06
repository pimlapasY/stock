<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History</title>
    <!-- Include jQuery (ensure it's included in your project) -->
</head>

<body>
    <?php include('navbar.php') ?>
    <div class="container-fluid">
        <div class="d-flex justify-content-start p-2">
            <h1 id="head_list">
                <?php
                echo ' <i class="fa-solid fa-laptop-medical fa-lg"></i> ' . $history;
                ?>
            </h1>
        </div>
        <div class="d-flex justify-content-start p-4">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link tab" href="#" id="productTab" style="font-size: 20px;">
                        <i class="fa-solid fa-box fa-lg"></i> All Store
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link tab" href="#" id="samtTab" style="font-size: 20px;">
                        <i class="fa-solid fa-store fa-lg"></i> SAMT Store
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link tab" href="#" id="sakabaTab" style="font-size: 20px;">
                        <i class="fa-solid fa-store fa-lg"></i> SAKABA Store
                    </a>
                </li>
            </ul>
        </div>
        <table class="table table-hover mx-auto">
            <thead class="text-center table-secondary" style="text-transform: uppercase;">
                <tr>
                    <th>#</th>
                    <th>store</th>
                    <th>CODE</th>
                    <th>product</th>
                    <th>size</th>
                    <th>color</th>
                    <th>hand</th>
                    <th>qty</th>
                    <th>Sold date</th>
                    <th>customer</th>
                    <th>paid by</th>
                    <th>payment</th>
                    <th>Delivery</th>
                    <th>PR/PO</th>
                    <th>Memo</th>
                </tr>
            </thead>
            <tbody id="dataTable">
                <!-- Data will be loaded here via AJAX -->
            </tbody>
        </table>
    </div>

    <script>
    $(document).ready(function() {
        // Function to load data based on button clicked
        function loadData(store = null) {
            // Define the URL for the AJAX request
            var url = "his_fetch.php";
            // Define the data to be sent
            var data = {
                store: store
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


        // Function to handle tab click events
        function handleTabClick(tabId, store) {
            $('.tab').removeClass('active');
            $(tabId).addClass('active');
            loadData(store);
        }

        // Load all data when the page loads
        handleTabClick('#samtTab', 'samt');

        // Set up click event handlers for each tab
        $('#productTab').click(function(e) {
            e.preventDefault();
            handleTabClick('#productTab');
        });

        $('#samtTab').click(function(e) {
            e.preventDefault();
            handleTabClick('#samtTab', 'samt');
        });

        $('#sakabaTab').click(function(e) {
            e.preventDefault();
            handleTabClick('#sakabaTab', 'sakaba');
        });
    });
    </script>
</body>

</html>