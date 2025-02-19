<?php 
include('connect.php'); 
$currentDay = date('d');
$currentMonth = date('m');
$currentYear = date('Y');

// Check if the current month is December
if ($currentMonth == 12) {
    $nextMonth = 1;  // January
    $nextYear = $currentYear + 1;
} else {
    $nextMonth = $currentMonth + 1;
    $nextYear = $currentYear;
}

// Format next month to always be two digits
$nextMonth = str_pad($nextMonth, 2, '0', STR_PAD_LEFT);
 
    // Fetch product names from the database
    $stmt_code = $pdo->query("SELECT DISTINCT p_product_code FROM product");
    $productNames_code = $stmt_code->fetchAll(PDO::FETCH_COLUMN); 
    // Fetch product names from the database
    $stmt_color = $pdo->query("SELECT DISTINCT p_color FROM product");
    $productNames_color = $stmt_color->fetchAll(PDO::FETCH_COLUMN);       
    // Fetch product names from the database
    $stmt_size = $pdo->query("SELECT DISTINCT p_size FROM product");
    $productNames_size = $stmt_size->fetchAll(PDO::FETCH_COLUMN);    
    // Fetch product names from the database
    $stmt_hands = $pdo->query("SELECT DISTINCT p_hands FROM product");
    $productNames_hands = $stmt_hands->fetchAll(PDO::FETCH_COLUMN);  
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    <title>PR Exchange</title>

</head>

<body>
    <div class="d-flex flex-wrap">
        <?php include('navbar.php'); ?>
        <div class="container-fluid pt-5 col-10">
            <div class="d-flex justify-content-start m-5">
                <h1 id="head_list">
                    <?php
                echo '<i class="fa-solid fa-right-left"></i> ' . 'PR Exchange';
                ?>
                </h1>
            </div>
            <hr>
            <div class="d-flex align-self-center mb-2">
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
            <div class="d-flex align-self-end  align-items-center mb-2">
                <!--  <a class="btn btn-primary btn-rounded" id="exchange" style="white-space: nowrap;"><i
                    class="fa-solid fa-right-left"></i> PR Exchange</a>&nbsp; -->
                <select class="form-select" id="months" name="months">
                    <option value="month">Months</option>
                    <option value="01">01 - Jan</option>
                    <option value="02">02 - Feb</option>
                    <option value="03">03 - Mar</option>
                    <option value="04">04 - Apr</option>
                    <option value="05">05 - May</option>
                    <option value="06">06 - Jun</option>
                    <option value="07">07 - Jul</option>
                    <option value="08">08 - Aug</option>
                    <option value="09">09 - Sep</option>
                    <option value="10">10 - Oct</option>
                    <option value="11">11 - Nov</option>
                    <option value="12">12 - Dec</option>
                </select>&nbsp;
                <select class="form-select" id="years" name="year">
                    <option value="years">Years</option>
                    <option value="2023">2023</option>
                    <option value="2024">2024</option>
                    <option value="2025">2025</option>
                    <option value="2026">2026</option>
                    <option value="2027">2027</option>
                </select>&nbsp;
                <select class="form-select" id="paymentStatus" name="paymentStatus">
                    <option value="all">All payment</option>
                    <option value="1">- Pending Payment</option>
                    <option value="2">- Payment Successful</option>
                </select>&nbsp;
                <select class="form-select" id="prStatusSelected" name="prStatusSelected">
                    <option value="all">All status</option>
                    <option value="99">- Pending</option>
                    <option value="2">- Delivered</option>
                </select>
                <!-- ปุ่ม Exchange (ที่ถูกซ่อนโดย exchangeForm) -->
                <button class="btn-outline-primary btn ms-2" style="white-space: nowrap;" id="exchangeBtn"
                    onclick="exchangeForm()">
                    <i class="fa-solid fa-right-left"></i>
                    Exchange
                </button>
                <!-- ปุ่ม Back (ซ่อนโดยค่าเริ่มต้น) -->
                <a class="btn-dark btn ms-2" style="white-space: nowrap; display:none;" id="backBtn"
                    onclick="showTable()">
                    <i class="fa-solid fa-arrow-left-long"></i>
                    Back
                </a>
            </div>
            <table class="table table-hover mx-auto table-sm" id="pr-table">
                <thead class="text-center table-info" style="text-transform: uppercase;">
                    <tr>
                        <th><input class="form-check-input" type="checkbox" id="select-all"></th>
                        <th><?php echo $prCode; ?></th>
                        <th><?php echo $store; ?></th>
                        <th><?php echo $mgCode; ?></th>
                        <th><?php echo $product; ?></th>
                        <th><?php echo $options1_label; ?></th>
                        <th><?php echo $options2_label; ?></th>
                        <th><?php echo $options3_label; ?></th>
                        <th><?php echo $qty; ?></th>
                        <th><?php echo $soldDate; ?></th>
                        <th><?php echo $customer; ?></th>
                        <th><?php echo $paidBy; ?></th>
                        <th><?php echo $payment; ?></th>
                        <th><?php echo $prStatus; ?></th>
                        <!-- <th>Delivery</th> -->
                        <th><?php echo $memo; ?></th>
                    </tr>
                </thead>
                <tbody id="dataTable" class="table-group-divider table-divider-color">
                    <!-- Data will be loaded here via AJAX -->
                </tbody>
            </table>

            <div id="editForm" style="display: none;">
                <form id="formEdit" method="POST">
                    <input type="hidden" name="action" value="update_select">
                    <div id="hiddenInputsContainer"></div>
                    <table class="table table-hover mx-auto table-sm" id="pr-table">
                        <thead class="text-center table-warning" style="text-transform: uppercase;">
                            <tr>
                                <th>#</th>
                                <th><?php echo $prCode; ?></th>
                                <th><?php echo $store; ?></th>
                                <th><?php echo $mgCode; ?></th>
                                <th><?php echo $product; ?></th>
                                <th><?php echo $options1_label; ?></th>
                                <th><?php echo $options2_label; ?></th>
                                <th><?php echo $options3_label; ?></th>
                                <th><?php echo $qty; ?></th>
                                <th><?php echo $soldDate; ?></th>
                            </tr>
                        </thead>
                        <tbody id="dataTable-selected" class="table-group-divider table-divider-color">
                            <!-- Data will be loaded here via AJAX -->
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end align-items-baseline">
                        <button type="submit" class="btn btn-success" id="submitExchange" disabled>submit</button>
                    </div>
                </form>
            </div>

            <!-- Modal for Editing Data -->
            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header text-bg-light">
                            <h5 class="modal-title" id="editModalLabel">Edit Modal Title</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" id="modal-body-content">
                            <div class="row">
                                <div class="d-flex justify-content-lg-start">
                                    <label for="prStatus" class="form-label">PR Status:&nbsp;</label>
                                    <h5 id="prStatus"></h5>
                                    <input id="prStatusID" hidden> </input>
                                </div>
                                <div class="alert alert-danger" role="alert" id="alertExchange" hidden>
                                </div><br>
                                <div class="d-flex justify-content-lg-end">
                                    <button class="btn btn-outline-secondary" onclick="editProduct()"><i
                                            class="fa-solid fa-pen-to-square"></i>
                                        EDIT</button> &nbsp;
                                    <input class="form-control" id="productID" hidden>
                                    <input class="form-control" id="prID" hidden>
                                </div>
                            </div>

                            <div class="row ms-4 me-4">
                                <div class="col-md-6">
                                    <form id="editForm">
                                        <div class="mb-3">
                                            <label for="prCodeInput" class="form-label">PR Code:</label>
                                            <input type="text" class="form-control" id="prCodeInput" disabled>
                                        </div>
                                        <div class="mb-3">
                                            <label for="dateAddedInput" class="form-label">PR Date:</label>
                                            <input type="text" class="form-control" id="dateAddedInput" disabled>
                                        </div>
                                        <div class="mb-3">
                                            <label for="mgCode" class="form-label">MG Code:</label>
                                            <input type="text" class="form-control" id="mgCode" disabled>
                                        </div>
                                        <div class="mb-3">
                                            <label for="productCode" class="form-label">Product Code:</label>
                                            <input type="text" class="form-control" id="productCode" disabled>
                                        </div>
                                        <div class="mb-3">
                                            <label for="productName" class="form-label">Product Name:</label>
                                            <input type="text" class="form-control" id="productName" disabled>
                                        </div>
                                    </form>
                                </div>

                                <div class="col-md-6">
                                    <form id="editForm">
                                        <div class="mb-3">
                                            <label for="size" class="form-label"><?php echo $options1_label; ?></label>
                                            <input type="search" class="form-control" id="size" list="product_size"
                                                disabled>
                                            <datalist id="product_size">
                                                <?php foreach ($productNames_size as $productName_size): ?>
                                                <option value="<?php echo $productName_size; ?>">
                                                    <?php endforeach; ?>
                                            </datalist>
                                        </div>
                                        <div class="mb-3">
                                            <label for="color" class="form-label"><?php echo $options2_label; ?></label>
                                            <input type="search" class="form-control" id="color"
                                                list="product_names_color" disabled>
                                            <datalist id="product_names_color">
                                                <?php foreach ($productNames_color as $productName_color): ?>
                                                <option value="<?php echo $productName_color; ?>">
                                                    <?php endforeach; ?>
                                            </datalist>
                                        </div>
                                        <div class="mb-3">
                                            <label for="hand" class="form-label"><?php echo $options3_label; ?></label>
                                            <input type="search" class="form-control" id="hand" list="product_hand"
                                                disabled>
                                            <datalist id="product_hand">
                                                <?php foreach ($productNames_hands as $productName_hand): ?>
                                                <option value="<?php echo $productName_hand; ?>">
                                                    <?php endforeach; ?>
                                            </datalist>
                                        </div>
                                        <div class="mb-3">
                                            <label for="qty" class="form-label">Qty:</label>
                                            <input type="number" class="form-control" id="qty" min="1" disabled>
                                        </div>
                                        <div class="mb-3">
                                            <label for="soldDate" class="form-label">Sold Date:</label>
                                            <input type="text" class="form-control" id="soldDate" disabled>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="d-flex flex-column m-4">
                                <button type="button" class="btn  btn-primary" id="submitFormExchange">
                                    <i class="fa-solid fa-right-left"></i>
                                    Exchange
                                </button>&nbsp;
                                <button type="button" class="btn  btn-warning" id="delivered">
                                    <i class="fa-solid fa-truck-ramp-box"></i>
                                    Delivered
                                </button>&nbsp;
                                <button type="button" class="btn  btn-info" id="stockin">
                                    <i class="fa-solid fa-box"></i>
                                    Stockin
                                </button>
                            </div>
                            <!-- Fetched data will be displayed here -->
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-danger" onclick="deletePr()">
                                <i class="fa-solid fa-trash-can"></i> Delete
                            </button>
                            <button type="button" class="btn btn-success" id="closeModalButton" data-bs-dismiss="modal"
                                disabled><i class="fa-solid fa-rotate"></i> Refresh</button>
                            <!-- Add additional buttons as needed -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- EXCHANGE MODAL FOR PR MANAGE-->
        </div>
        <!-- jQuery -->
    </div>


    <script src="pr_exchange.js"></script>
</body>

</html>