//reset ข้อมูล
function resetInput() {
    var inputs = document.querySelectorAll(
        "#mgCodeInput, #currentQTY, #memo, #totalQTY, #product_id, #product, #selectedProductName, #myInput, #selectedProductUnit, #colorInput, #sizeInput, #handInput, #total_price, #selectedProductCost, #selectedProductCostVat,#total_priceVat "
    );

    $('#alertSuccess').prop('hidden', true);
    $('#alertFillData').prop('hidden', true);
    $('#alertError').prop('hidden', true);


    $('#decrementButton').removeClass('disabled');
    $('#incrementButton').removeClass('disabled');
    // Reset the value of the input fields
    inputs.forEach(function (input) {
        input.value = "";
        input.classList.remove('valid-input-red');
    });

    // Reset the quantity value to 0
    $('#qtyValueNum').val('');
}

//อื่นๆ
$(document).ready(function () {
    const $form = $('#myForm');
    const $radios = $('input[name="check_stockIn"]');
    const $inputs = $form.find('input:not([type="radio"]), button');

    function checkRadios() {
        const checked = $radios.is(':checked');

        $inputs.prop('disabled', !checked);
    }

    $radios.on('change', checkRadios);

    checkRadios(); // Initial check on page load
});

//กรณี return ข้อมูล อิงจาก mg code
$('#mgCodeInput').on('change', function () {
    var mgCode = $('#mgCodeInput').val();
    $('#check_returned').prop('checked', true); // This will check the checkbox
    $('#qtyValueNum').prop('readonly', true); // This will check the checkbox

    // Send an AJAX request to fetch the product name
    $.ajax({
        url: 'ajax_GET/get_product_name.php', // URL to your PHP script that fetches the product name
        method: 'POST',
        data: {
            mgCode: mgCode
        },
        success: function (response) {
            var data = JSON.parse(response);
            if (data.error) {
                // Handle error case
                Swal.fire({
                    title: 'ERROR',
                    text: data.error,
                    icon: 'error',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
                //alert(data.error);
                resetInput();
            } else {
                // Extract product name and unit from the response
                var productName = data.product_name;
                var productUnit = data.unit;
                var productCode = data.o_product_code;
                var productID = data.o_product_id;
                var productColor = data.color;
                var productHands = data.hands;
                var qtyOut = data.qtyOut;
                var qtyStock = data.qtyStock;
                var cost = data.cost;
                var color = data.color;
                var hand = data.hand;
                var size = data.size;


                // Update the value of the readonly input fields with the fetched product name and unit
                $('#selectedProductName').val(productName);
                $('#selectedProductUnit').val(productUnit);
                $('#product').val(productCode);
                $('#product_id').val(productID);
                $('#qtyValueNum').val(qtyOut);
                $('#decrementButton').addClass('disabled');
                $('#incrementButton').addClass('disabled');
                $('#selectedProductCost').val(cost);
                $('#total_price').val(cost * qtyOut);
                $('#colorInput').val(color);
                $('#handInput').val(hand);
                $('#sizeInput').val(size);
                $('#currentQTY').val(qtyStock);
                console.log(qtyStock);
                $('#totalQTY').val(parseInt(qtyStock) + parseInt(qtyOut));
            }
        }
    });

});

function validateInput(input) {
    // Check if the input has a value
    if (input.value.trim() !== '') {
        input.classList.remove('valid-input-red');
        // If the input has a value, add the valid-input class
        //input.classList.add('valid-input-green');
    } else {
        // If the input doesn't have a value, remove the valid-input class
        //input.classList.remove('valid-input-green');
        input.classList.add('valid-input-red');
    }
}




// Get references to the dropdown menu and the input field
const stockToOption = document.getElementById('stockToOption');
const otherInput = document.getElementById('otherInput');

// Function to format the input value with commas
function formatNumberWithCommas(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// Event listener to format the number input on keyup
document.addEventListener('DOMContentLoaded', (event) => {
    const inputElements = document.querySelectorAll(
        'input[type="text"][id="selectedProductCost"], #total_price');
    inputElements.forEach(input => {
        input.addEventListener('keyup', (event) => {
            let value = input.value.replace(/,/g, ''); // Remove existing commas
            if (!isNaN(value) && value.length > 0) {
                input.value = formatNumberWithCommas(value);
            }
        });
    });
});



$('#product').on('input', function () {
    // Get the selected product code from the input field
    var selectedProductCode = $(this).val();
    console.log('Selected product code:', selectedProductCode);

    // Send an AJAX request to fetch the product name
    $.ajax({
        url: 'ajax_GET/get_product_name.php', // URL to your PHP script that fetches the product name
        method: 'POST',
        data: {
            product_code: selectedProductCode
        },
        success: function (response) {
            // Split the response into product name and unit
            var parts = response.split('|');
            var productName = parts[0];
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

        }
    });
}); // Function to update quantity input based on product, color, or size changes
// Function to update quantity input based on product, color, or size changes
$('#product, #colorInput, #sizeInput, #handInput').on('change', function () {
    updateQuantityInput();
});


function updateQuantityInput() {
    var selectedProductCode = $('#product').val();
    var selectedColor = $('#colorInput').val() || '';
    var selectedSize = $('#sizeInput').val() || '';
    var selectedHand = $('#handInput').val() || '';

    var incrementButton = $('#incrementBtn');
    var total_price = $('#total_price').val();
    var qtyValueNum = $('#qtyValueNum'); // Assuming the element has an ID of 'qtyValueNum'
    var productCost = parseFloat($('#selectedProductCost').val());

    var getType = 'stockIn';

    // AJAX call to get the stock quantity and product cost of the selected product, color, size, and hand
    $.ajax({
        url: 'ajax_GET/get_stock_quantity.php',
        method: 'POST',
        data: {
            product_code: selectedProductCode,
            color: selectedColor,
            size: selectedSize,
            hand: selectedHand,
            getType: getType
        },
        success: function (response) {
            var data = JSON.parse(response);
            if (data.error1) {
                $('#alertFillData').prop('hidden', false);
                $('#alertSuccess').prop('hidden', true);
                $('#alertError').prop('hidden', true);
                return;
            } else if (data.error2) {
                $('#alertError').prop('hidden', false);
                $('#alertSuccess').prop('hidden', true);
                $('#alertFillData').prop('hidden', true);
                return;
            }

            var productID = data.p_product_id;
            var stockQuantity = parseInt(data.s_qty);
            var productCost = parseFloat(data.p_cost_price);
            var productQTY = parseInt(data.p_qty);
            var productVat = parseFloat(data.p_vat);
            var productCostVat = (parseFloat(productCost * productVat / 100) + productCost).toFixed(2);

            console.log('s_qty = ' + stockQuantity);
            console.log('p_cost_price = ' + productCost);
            console.log('p_qty = ' + productQTY);
            console.log('productID = ' + productID);


            if (stockQuantity > 0 || productQTY == 0) {
                $('#alertSuccess').prop('hidden', false);
                $('#successText').html('Stock: ' + stockQuantity);
                $('#alertFillData').prop('hidden', true);
                $('#alertError').prop('hidden', true);

                incrementButton.prop('disabled', false);
                //var totalPrice = (stockQuantity * productCost).toFixed(2);
                var totalPrice = (1 * productCost).toFixed(2);
                totalPrice = parseFloat(totalPrice); // Calculate the total price
                $('#total_price').val(totalPrice);
                $('#total_priceVat').val(((totalPrice * productVat / 100) + totalPrice).toFixed(2));
                $('#selectedProductCost').val(productCost);
                $('#product_id').val(productID);

                // Set currentQTY based on stockQuantity
                $('#currentQTY').val(stockQuantity > 0 ? stockQuantity : 0);
                $('#selectedProductCostVat').val(productCostVat);
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


var interval; // Variable to store the interval

// Function to decrement quantity value
/* function decrementQty() {
    var qtyValueNum = parseInt($('#qtyValueNum').val());
    if (qtyValueNum > 1) {
        $('#qtyValueNum').val(qtyValue - 1);
        updateTotalPrice(); // Call updateTotalPrice after decrementing quantity
    }
} */

// Function to increment quantity value
/* function incrementQty() {
    var qtyValueNum = parseInt($('#qtyValueNum').val());
    $('#qtyValueNum').val(qtyValue + 1);
    updateTotalPrice(); // Call updateTotalPrice after incrementing quantity
} */

// Start continuous decrement
/* function startDecrement() {
    interval = setInterval(decrementQty, 100);
} */

// Start continuous increment
/* function startIncrement() {
    interval = setInterval(incrementQty, 100);
} */

// Stop the continuous change
/* function stopChange() {
    clearInterval(interval);
} */

// Attach event handlers for increment and decrement buttons
/* $(document).ready(function() {
    $('#decrementButton').mousedown(startDecrement);
    $('#decrementButton').mouseup(stopChange);
    $('#decrementButton').mouseleave(stopChange); // Also stop when mouse leaves the button

    $('#incrementButton').mousedown(startIncrement);
    $('#incrementButton').mouseup(stopChange);
    $('#incrementButton').mouseleave(stopChange); // Also stop when mouse leaves the button
}); */



// Function to update total price
function updateTotalPrice() {
    var qtyValueNum = $('#qtyValueNum').val() || 0; // Convert to number and default to 0 if NaN
    var unitCost = $('#selectedProductCost').val() || 0; // Remove commas and convert
    var unitCostVat = $('#selectedProductCostVat').val() || 0; // Remove commas and convert
    var currentQTY = $('#currentQTY').val() || 0;
    // Calculate total costs based on quantity
    var totalCost = qtyValueNum * unitCost;
    var totalCostVat = qtyValueNum * unitCostVat;

    // Update the values in the input fields
    $('#total_price').val(totalCost.toFixed(2));
    $('#total_priceVat').val(totalCostVat.toFixed(2));
    $('#totalQTY').val(parseInt(qtyValueNum) + parseInt(currentQTY));
}


function submitStockin() {
    var productID = $('#product_id').val();
    var memo = $('#memo').val();
    var qtyValueNum = $('#qtyValueNum').val();
    var date = $('#dateStockIn').val();
    var currentQTY = $('#currentQTY').val();
    var mgCode = $('#mgCodeInput').val();

    let status = '';

    if ($('#check_purchased').is(':checked')) {
        status = 1;
    } else if ($('#check_returned').is(':checked')) {
        status = 2;
    }


    var data = {
        productID: productID,
        memo: memo,
        qtyValueNum: qtyValueNum,
        date: date,
        status: status,
        currentQTY: currentQTY,
        mgCode: mgCode
    };


    if (productID != '' && qtyValueNum != 0 && status != '') {
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to submit the form?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, submit it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'stock_in_submit.php',
                    method: 'POST',
                    data: data,
                    success: function (response) {
                        // จัดการข้อมูลหลังจากที่ส่งไปยังเซิร์ฟเวอร์สำเร็จ
                        // Handle success response
                        // Handle success response
                        Swal.fire('Success!', 'Form submitted successfully', 'success').then((
                            result) => {
                            if (result.isConfirmed) {
                                window.location.href =
                                    'stock_in_his.php'; // Redirect to stock_in_his.php
                            }
                        });
                    },
                    error: function (xhr, status, error) {
                        // จัดการข้อมูลหลังจากที่เกิดข้อผิดพลาดในการส่งข้อมูล
                        Swal.fire('Error!', 'Failed to submit form', 'error');

                    }

                });
            } else {
                console.log('cancel')
            }
        });
    } else if (qtyValueNum == '') {
        Swal.fire({
            title: 'ERROR',
            text: 'Please select stock in or add QTY of product',
            icon: 'error',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
    } else {
        Swal.fire({
            title: 'ERROR',
            text: 'Please fill to data or register of product',
            icon: 'error',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
    }

};
