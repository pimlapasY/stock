<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History</title>
    <!-- Include jQuery (ensure it's included in your project) -->
</head>
<style>
.custom-bg-warning {
    background-color: #fff3cd !important;
    /* Overriding bootstrap warning */
}

.custom-bg-light-pink {
    background-color: #FFF0F5 !important;
}
</style>

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
            <tbody id="dataTable" class="table-group-divider table-divider-color">
                <!-- Data will be loaded here via AJAX -->
            </tbody>
        </table>
        <ul id="pagination" class="pagination">
            <!-- Pagination links will be loaded here by AJAX -->
        </ul>

    </div>
    <script>
    $(document).ready(function() {
        function loadData(store = null, currentPage = 1) {
            var url = "his_fetch.php";
            var data = {
                store: store,
                page: currentPage
            };

            $.ajax({
                type: "POST",
                url: url,
                data: data,
                success: function(response) {
                    var result = JSON.parse(response);
                    if (result.error) {
                        alert(result.error);
                    } else {
                        $('#dataTable').html(result.tableRows);
                        updatePagination(result.pagination, currentPage);
                    }
                }
            });
        }

        function updatePagination(totalPages, currentPage) {
            var pagination = '';
            for (var i = 1; i <= totalPages; i++) {
                pagination += "<li class='page-item " + (currentPage == i ? 'active' : '') +
                    "'><a class='page-link' href='#' data-page='" + i + "'>" + i + "</a></li>";
            }
            $('#pagination').html(pagination);
        }

        $(document).on('click', '.page-link', function(e) {
            e.preventDefault();
            var page = $(this).data('page');
            var activeTab = $('.tab.active').data('store');
            loadData(activeTab, page);
        });

        function handleTabClick(tabId, store = null) {
            $('.tab').removeClass('active');
            $(tabId).addClass('active');
            loadData(store);
        }

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

        // Load all data when the page loads
        handleTabClick('#productTab');
    });
    </script>
</body>

</html>