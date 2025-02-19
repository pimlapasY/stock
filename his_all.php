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
    <?php include('navbar.php'); ?>
    <div class="table-responsive">
        <div class="container-fluid pt-5 mt-5 col-12">
            <div class="d-flex justify-content-start p-2">
                <h1 id="head_list">
                    <?php
                    echo ' <i class="fa-solid fa-laptop-medical fa-lg"></i> ' . $history;
                    ?>
                </h1>
            </div>
            <hr>
            <div class="d-flex justify-content-between">
                <div class="d-flex justify-content-start">
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
                                <i class="fa-solid fa-store fa-lg"></i> Other Store
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="d-flex justify-content-end">
                    <ul id="pagination" class="pagination">
                        <!-- Pagination links will be loaded here by AJAX -->
                    </ul>
                </div>
            </div>
            <div class="">
                <table class="table table-hover mx-auto table-sm">
                    <thead class="text-center table-primary" style="text-transform: uppercase;">
                        <tr>
                            <th>#</th>
                            <th><?php echo $soldDate; ?></th>
                            <th><?php echo $store; ?></th>
                            <th><?php echo $code; ?></th>
                            <!-- <th>Stock out</th> -->
                            <th><?php echo $product; ?></th>
                            <!-- <th>Receipt date</th> -->
                            <!-- <th>Supplier</th> -->
                            <th><?php echo $options1_label; ?></th>
                            <th><?php echo $options2_label; ?></th>
                            <th><?php echo $options3_label; ?></th>
                            <th><?php echo $qty; ?></th>
                            <th><?php echo $customer; ?></th>
                            <th><?php echo $paidBy; ?></th>
                            <th><?php echo $payment; ?></th>
                            <th><?php echo $delivery; ?></th>
                            <th><?php echo 'User'; ?></th>
                            <th><?php echo $prPo; ?></th>
                            <!-- <th>PO status</th> -->
                            <th><?php echo $memo; ?></th>
                        </tr>
                    </thead>
                    <tbody id="dataTable" class="table-group-divider table-divider-color">
                        <!-- Data will be loaded here via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
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
                            updatePagination(result.totalPages, currentPage, store);
                        }
                        console.log(result.totalPages);

                    }
                });
            }

            function updatePagination(totalPages, currentPage, store) {
                var pagination = '';
                for (var i = 1; i <= totalPages; i++) {
                    pagination += "<li class='page-item " + (currentPage == i ? 'active' : '') +
                        "'><a class='page-link' href='#' data-page='" + i + "' data-store='" + store + "'>" + i +
                        "</a></li>";
                }
                $('#pagination').html(pagination);
            }

            $(document).on('click', '.page-link', function(e) {
                e.preventDefault();
                var page = $(this).data('page');
                var store = $(this).data('store');
                loadData(store, page);
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