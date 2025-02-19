
function submitPR() {
    var productID = $('#product_id').val();
    var productCode = $('#product').val();
    var productName = $('#selectedProductName').val();
    var prCode = $('#prCode').val();
    var prQty = $('#qtyValueNum').val();

    var data = {
        productID: productID,
        productCode: productCode,
        prCode: prCode,
        productName: productName,
        prQty: prQty
    };

    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to create PR?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, submit it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'pr_create_submit.php',
                method: 'POST',
                data: data,
                success: function (response) {
                    // Handle success response
                    Swal.fire('Success!', 'Form submitted successfully', 'success').then((
                        result) => {
                        if (result.isConfirmed) {
                            window.location.href =
                                'pr_management.php'; // Redirect to pr_management.php
                        }
                    });
                },
                error: function (xhr, status, error) {
                    // Handle error response
                    Swal.fire('Error!', 'Failed to submit form', 'error');
                }
            });
        } else {
            console.log('cancel');
        }
    });
}

$('#product').on('input', function () {
    var selectedProductCode = $(this).val();
    console.log('Selected product code:', selectedProductCode);

    // ส่ง AJAX request เพื่อดึงข้อมูล product
    $.ajax({
        url: 'ajax_GET/get_product_name.php',
        method: 'POST',
        data: {
            product_code: selectedProductCode
        },
        success: function (response) {
            // แยกข้อมูลที่ได้จาก PHP
            var parts = response.split('|');
            var productName = parts[0] || ''; // จัดการค่าว่าง
            var productUnit = parts[1] || '';
            var productOption1 = parts[2] || '';
            var productOption2 = parts[3] || '';
            var productOption3 = parts[4] || '';

            // อัปเดตค่าใน input fields
            $('#selectedProductName').val(productName);
            $('#selectedProductUnit').val(productUnit);
            $('#handInput').val(productOption1);
            $('#colorInput').val(productOption2);
            $('#sizeInput').val(productOption3);
        },
        error: function () {
            console.error('Failed to fetch product data');
        }
    });
});


$('#product, #colorInput, #sizeInput, #handInput, #qtyValueNum').on('change', function () {
    updateQuantityInput();
});

function updateQuantityInput() {
    var selectedProductCode = $('#product').val();
    var selectedColor = $('#colorInput').val() || '';
    var selectedSize = $('#sizeInput').val() || '';
    var selectedHand = $('#handInput').val() || '';
    var total_price = $('#total_price').val();
    var qtyValueNum = $('#qtyValueNum'); // Assuming the element has an ID of 'qtyValueNum'
    var productCost = parseFloat($('#selectedProductCost').val());

    // AJAX call to get the stock quantity and product cost of the selected product, color, size, and hand
    $.ajax({
        url: 'ajax_GET/get_product_pr.php',
        method: 'POST',
        data: {
            product_code: selectedProductCode,
            color: selectedColor,
            size: selectedSize,
            hand: selectedHand
        },
        success: function (response) {
            var data = JSON.parse(response);
            if (data.error1) {
                $('#alertFillData').prop('hidden', false);
                $('#alertSuccess').prop('hidden', true);
                $('#alertError').prop('hidden', true);
                $('#createPR').prop('disabled', true);
            } else if (data.error2) {
                $('#alertError').prop('hidden', false);
                $('#alertSuccess').prop('hidden', true);
                $('#alertFillData').prop('hidden', true);
                $('#createPR').prop('disabled', true);
            }


            var productID = data.p_product_id;
            var stockQuantity = parseInt(data.s_qty);
            var productCost = parseFloat(data.p_cost_price);
            var productQTY = parseInt(data.p_qty);
            var vat = parseFloat(data.p_vat);

            console.log('s_qty = ' + stockQuantity);
            console.log('p_cost_price = ' + productCost);
            console.log('p_qty = ' + productQTY);
            console.log('productID = ' + productID);


            if (stockQuantity > 0 || productQTY == 0) {
                $('#createPR').prop('disabled', false);
                $('#alertError').prop('hidden', true);
                $('#alertFillData').prop('hidden', true);
                $('#alertSuccess').prop('hidden', false);


                qtyValueNum.prop('readonly', false);


                //var totalPrice = (stockQuantity * productCost).toFixed(2);
                var totalPrice = (qtyValueNum.val() * productCost).toFixed(2);

                // คำนวณ VAT
                var vat = parseFloat(totalPrice * vat / 100); // คำนวณ 7% ของ totalPrice
                var totalWithVat = parseFloat(totalPrice) + vat; // รวมราคาสินค้ากับ VAT

                // แสดงผลใน input field
                $('#TotalVat').val((totalWithVat).toFixed(2));
                $('#total_price').val(totalPrice);
                $('#selectedProductCost').val(productCost);
                $('#product_id').val(productID);
                if (stockQuantity > 0) {
                    $('#currentQTY').val(stockQuantity);
                    $('#totalQTY').val(stockQuantity + parseInt($('#qtyValueNum').val()));
                } else {
                    $('#currentQTY').val();
                    $('#totalQTY').val(stockQuantity + parseInt($('#qtyValueNum').val()));

                }
            }
            /* else if (productQTY == 0) {
                           qtyInput.html('<b style="color: red;">out of stock</b>');
                           incrementButton.prop('disabled', true);
                           $('#selectedProductCost').val(productCost);
                           $('#product_id').val(productID);
                       } */

            // Handle stock quantity and product cost
            /*    if (stockQuantity <= 0) {
                   qtyInput.html('<b style="color: red;">Sold out</b>');
                   incrementButton.prop('disabled', true);
               } else {
                   qtyInput.text(stockQuantity);
                   incrementButton.prop('disabled', false);
               } */

            // Set the max attribute of the quantity span to the stock quantity
            //qtyInput.attr('max', stockQuantity);
        },
        error: function () {
            console.error('Error fetching stock quantity and product cost.');
        }
    });
}

function resetInput() {
    var inputs = document.querySelectorAll(
        " #currentQTY, #memo, #totalQTY, #product_id, #product, #selectedProductName, #myInput, #selectedProductUnit, #colorInput, #sizeInput, #handInput, #total_price, #selectedProductCost"
    );


    // Reset the value of the input fields
    inputs.forEach(function (input) {
        input.value = "";
    });

    // Reset the quantity value to 0
    $('#qtyValueNum').val(1);

}
