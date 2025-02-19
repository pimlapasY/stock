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
    <title>Mail Management</title>
</head>

<body>
    <?php include('navbar.php'); ?>
    <div class="table-responsive">
        <div class="container-fluid pt-5 col-12 mt-5">
            <div class="d-flex justify-content-start m-5">
                <h1 id="head_list">
                    <?php
                    echo '<i class="fa-solid fa-envelopes-bulk fa-xl"></i> ' . 'Mail';
                    ?>
                </h1>
            </div>
            <hr>
            <div class="d-flex align-self-end  align-items-center mb-2">
                <!--  <a class="btn btn-primary btn-rounded" id="exchange" style="white-space: nowrap;"><i
                    class="fa-solid fa-right-left"></i> PR Exchange</a>&nbsp; -->
                <a class="btn btn-primary" id="saveFile" style="white-space: nowrap;">
                    <i class="fa-solid fa-paperclip"></i>
                    PDF</a>&nbsp;
                <a class="btn btn-warning" id="sendMail" style="white-space: nowrap;">
                    <i class="fa-solid fa-envelope"></i>
                    Send Email</a>&nbsp;
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
            </div>
            <table class="table table-hover mx-auto table-sm" id="pr-table">
                <thead class="text-center table-info" style="text-transform: uppercase;">
                    <tr>
                        <th><input class="form-check-input" type="checkbox" id="select-all"></th>
                        <th class="text-center"><?php echo $status; ?></th>
                        <th><?php echo $prCode; ?></th>
                        <th><?php echo $mgCode; ?></th>
                        <th><?php echo $product; ?></th>
                        <th><?php echo $options1_label; ?></th>
                        <th><?php echo $options2_label; ?></th>
                        <th><?php echo $options3_label; ?></th>
                        <th><?php echo $qty; ?></th>
                        <th><?php echo $soldDate; ?></th>
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
        </div>
        <!-- jQuery -->
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>

    <script src="./mail_mnm.js"></script>
</body>

</html>