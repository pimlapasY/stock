<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACANA STOCK</title>
</head>

<body>
    <div class="d-flex flex-wrap">
        <?php include('navbar.php');
     
     try {
        // Fetch store IDs and names from the database
        $stmt_store = $pdo->query("SELECT st_id, st_name FROM store WHERE st_id != 1");
        $store_options = $stmt_store->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    
 
    
    ?>
        <div class="container pt-5 col-10">
            <div class="d-flex justify-content-start">
                <ul class="nav nav-tabs">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <?php 
                echo '<a class="nav-link' . ($currentPage == 'stock_list.php' ? ' active' : '') . '"href="stock_list.php" id="productTab"
                    style="font-size: 20px;">';
                ?>
                            <i class="fa-solid fa-box fa-lg"></i> All Store</a>
                        </li>
                        <!-- Line break after the first list item -->
                        <?php
                echo '<a class="nav-link' . ($currentPage == 'stock_samt.php' ? ' active' : '') . '" href="stock_samt.php" id="samtTab" style="font-size: 20px;">';
                ?>
                        <i class="fa-solid fa-store fa-lg"></i> SAMT Store</a>
                        </li>
                        <!-- Line break after the first list item -->
                        <li class="nav-item">
                            <?php 
                echo '<a class="nav-link' . ($currentPage == 'stock_other.php' ? ' active' : '') . '" href="stock_other.php" id="supplierTab" style="font-size: 20px;">';               
                ?>
                            <i class="fa-solid fa-store fa-lg"></i> Other Store</a>
                        </li>
                    </ul>
                </ul>
            </div>

            <div class="d-flex justify-content-between m-3" style="align-items: center;">
                <div class="d-flex justify-content-start">
                    <a href="register.php" class="btn btn-success"><i class="fa-solid fa-plus"></i> NEW</a>&nbsp;
                    <button type="button" class="btn btn-outline-warning preview-btn-stockOut">
                        <i class="fa-solid fa-cart-flatbed"></i> Stock
                        out</button>&nbsp;
                    <button class="btn btn-outline-info preview-btn-pr">
                        <i class="fa-solid fa-clipboard-list"></i> add
                        PR</button>
                </div>
                <div class="d-flex justify-content-end w-50">
                    <div class="input-group p-3">
                        <input id="searchInput" type="search" class="form-control rounded" placeholder="Search"
                            aria-label="Search" aria-describedby="search-addon" />
                        <button type="button" class="btn btn-primary" data-mdb-ripple-init
                            onclick="loadData(1, $('#searchInput').val())" disabled>Search</button>
                    </div>
                </div>
            </div>
            <div class="table-responsive">

                <table class="table table-bordered table-hover">
                    <thead class="table-info text-center">
                        <tr style="vertical-align: middle;">
                            <th rowspan="2">No</th>
                            <th rowspan="2">Code</th>
                            <th rowspan="2">Collection</th>
                            <th rowspan="2">Name</th>
                            <th rowspan="2">Hands</th>
                            <th rowspan="2">Color</th>
                            <th rowspan="2">Size</th>
                            <th rowspan="2">Cost price</th>
                            <th rowspan="2">Sale price</th>
                            <th rowspan="2">Samt Qty</th>
                            <th rowspan="2">Other stock</th>
                            <th rowspan="2"><i class="fa-solid fa-list-check"></i></th>
                            <!--  <th class="text-center" colspan="2">Store</th> -->
                        </tr>
                        <!-- <tr>
                    <th>SAMT</th>
                    <th>SAKABA</th>
                </tr> -->
                    </thead>
                    <tbody class="table-group-divider table-divider-color">
                        <?php
         $stmt = $pdo->prepare("SELECT p.*, SUM(sub.sub_qty) AS total_sub_qty, s.s_qty, s.s_return_date
         FROM product p 
         LEFT JOIN stock s ON s.s_product_id = p.p_product_id 
         LEFT JOIN sub_stock sub ON sub.sub_product_id = p.p_product_id 
         GROUP BY p.p_product_code, p.p_product_name, p.p_hands, p.p_color, FIELD(p.p_size, 'SS', 'S', 'M', 'L', 'XL', 'XXL'), p.p_unit, p.p_collection, p.p_cost_price, p.p_sale_price
         HAVING s.s_qty != 0;");
           // Execute the statement
           $stmt->execute();
           $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

           // Loop through products and output each row
           foreach ($products as $index => $product) {
            $difference = $product['s_qty'] - $product['total_sub_qty'];
            // Check if s_return_date exists and is within 2 days from today
            $returnDate = $product['s_return_date'];
            if ($returnDate != null) {
                $returnDateTime = new DateTime($returnDate);
                $currentDateTime = new DateTime();
                $interval = $currentDateTime->diff($returnDateTime);
                $daysDiff = $interval->days;               
                // If return date is within 2 days from today, mark the row as red
                $rowColor = ($daysDiff <= 2) ? "class='table-warning'" : '';
            } else {
                $rowColor = '';
            }
            
            // กำหนดสีของข้อความและพื้นหลังตามค่าของ difference
            $textColor = ($difference <= 0) ? "red" : "green";
            $backgroundColor = "#E5F9E5";

            
           echo "<tr $rowColor data-id='" . htmlspecialchars($product['p_product_id']) . "'>";
           /* echo "<td>".$product['s_return_date']."</td>"; */
           echo "<td>" . ($index + 1) . "</td>"; // Display No starting from 1
           echo "<td>" . htmlspecialchars($product['p_product_code']) . "</td>";
           echo "<td>" . htmlspecialchars($product['p_collection']) . "</td>";
           echo "<td>" . htmlspecialchars($product['p_product_name']) . "</td>";
           echo "<td>" . htmlspecialchars($product['p_hands']) . "</td>";
           echo "<td>" . htmlspecialchars($product['p_color']) . "</td>";
           echo "<td>" . htmlspecialchars($product['p_size']) . "</td>";
           echo "<td class='text-end'>" . number_format($product['p_cost_price']) . "</td>";
           echo "<td class='text-end'>" . number_format($product['p_sale_price']) . "</td>";
           echo "<td class='text-end' style='color: $textColor; background: $backgroundColor;'>" . htmlspecialchars($difference) . "</td>";
           echo "<td class='text-center'>" . 
           ($product['total_sub_qty'] == null ? '' : 
           '<a href="#" class="info-icon text-info" data-product-id="' . htmlspecialchars($product['p_product_id']) . '">
               <i class="fa-solid fa-list-ul fa-xl"></i>
           </a>') . 
           "</td>";
           
                  echo "<td class='text-center' style='vertical-align: middle;'>";
           echo '<div class="input-group"><div class="input-group-text">';
           echo "<input class='form-check-input' type='checkbox'  name='selected_ids[]' value='".htmlspecialchars($product['p_product_id'])."' id='checkbox_" . htmlspecialchars($product['p_product_id']) . "' onchange='toggleInput(this)' /> <br>";
           echo "</div><input class='form-control' min='1' max='" . $difference . "' type='number' id='input_" . htmlspecialchars($product['p_product_id']) . "' style='display: none;' /> </div>";
           echo "</td>";
           echo "</tr>";
           
        }        
        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header text-bg-light">
                        <h5 class="modal-title" id="previewModalLabel">
                            Product Details(SAMT)
                            <span class="badge" id="addTitle"></span>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" id="updateForm" hidden>
                        <div class="d-flex justify-content-start w-50">
                            <div class="input-group ms-3 mb-3">
                                <span class="input-group-text" id="basic-addon1"><b>Date</b></span>
                                <input class="form-control" type="date" value="" name="date_create" id="currentDate"
                                    aria-describedby="basic-addon1" />
                            </div>
                            <script>
                            document.getElementById('currentDate').valueAsDate = new Date();
                            </script>
                        </div>
                        <!-- Default radio -->
                        <div id="previewStockOut" style="display: none;" class="p-3">
                            <h5 class="modal-title">Stock out for: <span class="text-danger">*require</span></h5>
                            <div class="form-check me-3 ms-3">
                                <input class="form-check-input" type="radio" name="flexRadioDefault"
                                    id="flexRadioDefault1" value="sale" required>
                                <label class="form-check-label" for="flexRadioDefault1"><?php echo $sale ?></label>
                            </div>
                            <div id="saleDetails">
                                <div class="input-group mb-3 ms-5 w-75">
                                    <button class="btn btn-dark" type="paidOption"><?php echo $paid_by ?></button>
                                    <select class="form-select" id="paidOption">
                                        <option disabled selected><?php echo $choose ?>...</option>
                                        <option value="1">Cash</option>
                                        <option value="2">QR</option>
                                        <option value="3">Shopify</option>
                                    </select>
                                </div>
                                <div class="input-group mb-4 ms-5 w-75">
                                    <span class="input-group-text  text-bg-dark"
                                        id="basic-addon1"><?php echo $cus_name ?></span>
                                    <input type="text" class="form-control" aria-label="Username" id="cusname"
                                        aria-describedby="basic-addon1" />
                                </div>
                            </div>
                            <div class="form-check me-3 ms-3">
                                <input class="form-check-input" type="radio" name="flexRadioDefault"
                                    id="flexRadioDefault2" value="out to">
                                <label class="form-check-label" for="flexRadioDefault2"> <?php echo $take_out ?></label>
                            </div>
                            <div class="input-group mb-3 ms-5 w-75 mb-4" id="takeOutDetails">
                                <button class="btn btn-dark" type="button">To</button>
                                <select class="form-select" id="stockToOption">
                                    <option value="" disabled selected>Select a store</option>
                                    <?php foreach ($store_options as $store_option): ?>
                                    <option value="<?php echo htmlspecialchars($store_option['st_id']); ?>">
                                        <?php echo htmlspecialchars($store_option['st_id']) . ' - ' . htmlspecialchars($store_option['st_name']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-check ms-3">
                                <input class="form-check-input" type="radio" name="flexRadioDefault"
                                    id="flexRadioDefault3" value="sale sample">
                                <label class="form-check-label" for="flexRadioDefault3">
                                    <?php echo 'Sale sample' ?></label>
                            </div>
                        </div>
                        <div class="ms-3">
                            <label for="memo">Memo: </label>
                            <textarea class="form-control w-75" name="memo" id="memo">
                        </textarea>
                        </div>
                        <br>
                        <table class="table table-bordered table-hover">
                            <thead class="table-dark text-center">
                                <tr style="vertical-align: middle;">
                                    <th>#</th>
                                    <th>Code</th>
                                    <th>Collection</th>
                                    <th>Name</th>
                                    <th>Hands</th>
                                    <th>Color</th>
                                    <th>Size</th>
                                    <th>Sale price</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody id='productDetails'>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal"
                            aria-label="Close">Close</button>
                        <button type="button" class="btn btn-success" id="submitProductDetails">Submit</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal info other stock -->
        <div class="modal fade" id="previewStockModal" tabindex="-1" role="dialog" aria-labelledby="previewProductLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header text-bg-light">
                        <h5 class="modal-title" id="previewProductLabel">
                            Other Stock
                            <span class="badge" id="addTitle"></span>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <table class="table table-bordered" id="stockModal">
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<script>
function formatPrice(price) {
    // แปลงราคาสำหรับการแสดงลูกน้ำ
    return parseFloat(price).toLocaleString('en-US', {
        style: 'currency',
        currency: 'THB'
    }); // สำหรับสกุลเงินไทย

}

$(document).ready(function() {
    $('.info-icon').click(function(e) {
        e.preventDefault(); // Prevent the default link behavior

        var productId = $(this).data('product-id'); // Get the product ID

        // Make an AJAX request to fetch details
        $.ajax({
            url: 'ajax_GET/get_sub_stock_details.php',
            type: 'POST',
            data: {
                p_product_id: productId
            },
            success: function(data) {
                var details = JSON.parse(data);
                // Build the table rows for the modal
                let rows = `
                    <tr class="table-secondary">
                        <th colspan='3' class="">Product: ${details[0].p_product_code} 
                            ${details[0].p_product_name}
                            ${details[0].p_hands} 
                            ${details[0].p_color} 
                            ${details[0].p_size}
                        </th>
                    </tr>
                    <tr class="text-center">
                       <th colspan='2'>Location</th>
                       <th>QTY</th>
                    </tr>
                `;

                var num = 0;
                var total = 0;
                $.each(details, function(index, item) {
                    num++
                    rows += `
                    <tr>
                        <td>${num}</td>
                        <td class="text-primary">${item.sub_name}</td>
                         <td class="text-end">${item.sub_qty}</td>
                    </tr>
                    
                `;
                    total += parseInt(item.sub_qty);
                });

                rows += `
                     <td colspan="2">Total: </td>
                    <td class="text-end">${total}</td>
                `;
                // Update the modal content
                $('#stockModal tbody').html(
                    rows); // Ensure #stockModal is the <tbody> or relevant container

                // Show the modal
                $('#previewStockModal').modal('show');
            },
            error: function() {
                alert('Error loading sub stock details.');
            }
        });
    });

    // Hide all details sections initially
    $('#saleDetails').hide();
    $('#takeOutDetails').hide();
    // Event listener for radio button changes
    $('input[name="flexRadioDefault"]').change(function() {
        // Check which radio button is selected
        if ($('#flexRadioDefault1').is(':checked')) {
            $('#saleDetails').show();
            $('#takeOutDetails').hide();
        } else if ($('#flexRadioDefault2').is(':checked')) {
            $('#saleDetails').hide();
            $('#takeOutDetails').show();
        } else if ($('#flexRadioDefault3').is(':checked')) {
            $('#saleDetails').hide();
            $('#takeOutDetails').hide();
        }
    });


    // When the "Submit" button is clicked
    $('#submitProductDetails').click(function() {
        // Gather the data from the table
        const productDetails = [];
        var checkStatus = ''; // Get the selected radio button value
        const currentDate = $('#currentDate').val(); // Get the date input value
        var updateForm = $('#updateForm').val();
        var typeStatus = '';
        var storeID = '1';
        // Check which radio button is selected
        if ($('#flexRadioDefault1').is(':checked')) {
            checkStatus = ($('#flexRadioDefault1').val()) +
                ',' + ($('#paidOption').val()) +
                ',' + ($('#cusname').val());
            typeStatus = '1';
        } else if ($('#flexRadioDefault2').is(':checked')) {
            checkStatus = 'out to,' + ($('#stockToOption').val());
            typeStatus = '2';
            storeID = $('#stockToOption').val();
        } else if ($('#flexRadioDefault3').is(':checked')) {
            checkStatus = 'sale sample';
            typeStatus = '3';
        }

        $('#productDetails tr').each(function() {
            const row = $(this);
            const qtyInput = row.find('.qty-input');
            const productId = qtyInput.data('product-id');
            console.log(productId);
            const qty = qtyInput.val();

            if (productId && qty) {
                productDetails.push({
                    productId: productId,
                    qty: qty
                });
            }
        });


        // Send an AJAX request to the server
        $.ajax({
            url: 'ajax_POST/update_product_details.php',
            type: 'POST',
            data: JSON.stringify({
                products: productDetails,
                status: checkStatus,
                typeStatus: typeStatus,
                date: currentDate,
                updateForm: updateForm,
                storeID: storeID // Include the date in the request
            }),
            contentType: 'application/json',
            success: function(response) {
                // Handle the server response
                console.log(response);

                Swal.fire({
                    title: 'Success',
                    text: 'Product details updated successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Close the modal
                        $('#previewModal').modal('hide');
                        // Optionally, reload the page or update the table
                        location.reload();
                    }
                });
            },
            error: function(error) {
                console.error(error);

                Swal.fire({
                    title: 'Error',
                    text: 'Failed to update product details.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    });
    // เมื่อโหลดหน้า ให้เช็คสถานะ checkbox ที่ถูกเลือก
    $('input[name="selected_ids[]"]:checked').each(function() {
        $('#input_' + $(this).val()).show(); // แสดง input
    });

    // ฟังก์ชันเพื่อจัดการแสดง/ซ่อน input เมื่อ checkbox เปลี่ยนสถานะ
    window.toggleInput = function(checkbox) {
        const productId = $(checkbox).val(); // รับค่าจาก checkbox
        const inputField = $('#input_' + productId); // เลือก input ที่เกี่ยวข้อง

        if (checkbox.checked) {
            inputField.show(); // แสดง input ถ้า checkbox ถูกเลือก
        } else {
            inputField.hide(); // ซ่อน input ถ้า checkbox ไม่ถูกเลือก
        }
    };
    // เมื่อคลิกปุ่ม Preview
    $('.preview-btn-stockOut').click(function() {
        previewProduct(1)
    });
    $('.preview-btn-pr').click(function() {
        previewProduct(2)
    });

    function previewProduct(statusBtn) {
        var colorText = '';

        if (statusBtn == 1) {
            $('#addTitle').text('Stock Out');
            $('#addTitle').removeClass('bg-info text-white').addClass('bg-warning text-white');
            $('#previewStockOut').show();
            colorText = 'text-danger';
            $('#updateForm').val(1);
        } else if (statusBtn == 2) {
            $('#addTitle').text('PR Create');
            $('#addTitle').removeClass('bg-warning text-white').addClass('bg-info text-white');
            $('#previewStockOut').hide();
            colorText = 'text-success';
            $('#updateForm').val(2);
        }


        // รับค่าจาก checkbox ที่เลือก
        const selectedIds = $('input[name="selected_ids[]"]:checked').map(function() {
            return $(this).val();
        }).get();

        // สร้างอ็อบเจ็กต์เพื่อเก็บข้อมูล product_id และ qty
        const productData = selectedIds.map(id => {
            return {
                id: id,
                qty: $('#input_' + id).val() // ดึงค่า qty จาก input field
            };
        });


        if (selectedIds.length === 0) {
            Swal.fire({
                title: 'No Data',
                text: 'No items selected.',
                icon: 'info',
                confirmButtonText: 'OK'
            });
            return;
        }


        // ส่ง request ไปที่เซิร์ฟเวอร์เพื่อดึงข้อมูลผลิตภัณฑ์
        $.ajax({
            url: 'ajax_GET/get_product_details.php', // URL ของไฟล์ PHP ที่จะดึงข้อมูล
            type: 'POST',
            data: {
                products: productData // ส่งข้อมูลผลิตภัณฑ์รวมทั้ง qty
            },
            success: function(data) {
                const details = JSON.parse(data);

                let productDetails = '';
                let allTotal = 0; // ตั้งค่าตัวแปร `allTotal` เป็น 0
                let number = 1; // ตั้งค่าตัวแปร `number` เป็น 1

                details.forEach(function(detail) {
                    const totalPrice = detail.qty * detail.p_sale_price;
                    allTotal += totalPrice; // Sum the total prices

                    productDetails += `
                            <tr>
                                <td>${number}</td>
                                <td>${detail.p_collection}</td>
                                <td>${detail.p_product_code}</td>
                                <td>${detail.p_product_name}</td>
                                <td>${detail.p_hands}</td>
                                <td>${detail.p_color}</td>
                                <td>${detail.p_size}</td>
                                <td class='text-end'>${formatPrice(detail.p_sale_price)}</td> <!-- Display price in formatted way -->
                                <td class='text-end ${colorText}'>
                                    <input class="form-control qty-input" min="1" type="number" value="${detail.qty}" data-product-id="${detail.p_product_id}" readonly>
                                </td> <!-- Display qty value -->
                                <td class='text-end ${colorText}' data-product-id="${detail.p_product_id}">${formatPrice(totalPrice)}</td> <!-- Display total price -->
                            </tr>
                        `;

                    if ((statusBtn == 1) && (Number(detail.qty) > (Number(detail.s_qty) -
                            Number(detail.sub_qty)))) {
                        console.log(detail.qty + '/' + detail.s_qty);
                        productDetails += `
                <tr>
                    <td colspan="10">
                        <div class="alert alert-danger" role="alert">
                            Quantity exceeds available stock! Stock is: ${detail.s_qty-detail.sub_qty}
                        </div>
                    </td>
                </tr>
                `;
                    }

                    number++;
                });

                // แสดงผลรวมทั้งหมด
                const totalRow = `
                <tr>
                    <td colspan="8" class="text-end">Total:</td>
                    <td colspan="2" class="text-end ${colorText}" id="totalAmount">${formatPrice(allTotal)}</td>
                </tr>
                `;
                $('#productDetails').html(productDetails + totalRow); // แสดงข้อมูลใน Modal
                $('#previewModal').modal('show'); // เปิด Modal

                // เพิ่ม event listener สำหรับการเปลี่ยนแปลงค่า qty
                $('.qty-input').on('change', function() {
                    updateTotals();
                });
            },
            error: function() {
                $('#productDetails').html(
                    '<tr><td colspan="7">Error loading product details.</td></tr>');
                $('#previewModal').modal('show');
            }
        });
    }
    // ฟังก์ชันในการคำนวณผลรวมใหม่เมื่อ qty เปลี่ยน
    function updateTotals() {
        let allTotal = 0;

        // คำนวณผลรวมใหม่
        $('#productDetails tr').each(function() {
            const qtyInput = $(this).find('.qty-input');
            const productId = qtyInput.data('product-id');
            const qty = parseFloat(qtyInput.val()) || 0;
            const price = parseFloat($(this).find('td').eq(7).text().replace(/[^0-9.-]+/g, "")) ||
                0; // ดึงราคาจากตาราง
            const totalPrice = qty * price;
            allTotal += totalPrice;
            $(this).find('td').eq(9).text(formatPrice(totalPrice)); // อัปเดตค่าผลรวมในตาราง
        });

        // อัปเดตแถวผลรวมทั้งหมด
        $('#totalAmount').text(formatPrice(allTotal));
    }

});
</script>