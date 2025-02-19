
function calculateTotalSale() {
    const disType = document.getElementById('disType').value;
    const totalSale = parseFloat(document.getElementById('total_sale').value) || 0;
    const qtyValueNum = parseFloat(document.getElementById('qtyValueNum').value) || 1; // ป้องกันการหารด้วย 0
    const inputDiscount = parseFloat(document.getElementById('inputDiscount').value) || 0;
    let newTotalSale = 0; // กำหนดค่าเริ่มต้น

    $('#inputDiscount').prop('hidden', false);

    $('#selectedCalculateTotalSale').prop('hidden', false);

    if (disType == '1') {
        // คำนวณสำหรับ 'ต่อชิ้น'
        newTotalSale = ((totalSale / qtyValueNum) - inputDiscount) * qtyValueNum;
    } else if (disType == '2') {
        // คำนวณสำหรับ 'ทั้งหมด'
        newTotalSale = totalSale - inputDiscount;
    } else if (disType == '3') {
        // คำนวณสำหรับ 'เปอร์เซ็น (%)'
        newTotalSale = totalSale - ((inputDiscount / 100) * totalSale);
    } else {
        newTotalSale = totalSale;
        $('#inputDiscount').val('');
        $('#inputDiscount').prop('hidden', true);
    }

    // อัปเดตค่า total_sale
    document.getElementById('total_sale_dis').value = newTotalSale.toFixed(2); // แสดงเป็นจำนวนทศนิยม 2 ตำแหน่ง
}

function resetInput() {
    var inputs = document.querySelectorAll(
        "#product, #selectedProductName, #myInput, #selectedProductUnit, #colorInput, #sizeInput, #handInput, #total_price, #selectedProductCost,  #selectedProductCostVat"
    );


    // Reset the value of the input fields
    inputs.forEach(function (input) {
        input.value = "";
        input.classList.remove('valid-input-red');
    });

    // Reset the quantity value to 0
    $('#qtyValueNum').val('');
    $('#qtyValueText').prop('hidden', true);
}

$(document).ready(function () {
    const $form = $('#myForm');
    const $radios = $('input[name="flexRadioDefault"]');
    const $inputs = $form.find('input:not([type="radio"]), button');

    function checkRadios() {
        const checked = $radios.is(':checked');

        $inputs.prop('disabled', !checked);
    }

    $radios.on('change', checkRadios);

    checkRadios(); // Initial check on page load
});

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

// Add event listener to the dropdown menu
stockToOption.addEventListener('change', function () {
    // Check if the selected option is "Other"
    if (stockToOption.value == 'Other') {
        // If "Other" is selected, show the input field
        otherInput.style.display = 'block';
    } else {
        // If any other option is selected, hide the input field
        otherInput.style.display = 'none';
    }
});

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



// Function to update quantity input based on product, color, or size changes
$('#product, #colorInput, #sizeInput, #handInput').on('change', function () {
    updateQuantityInput();
});

$('#qtyValueNum').on('change', function () {
    calculateTotalSale();
});

$('#store').on('change', updateQuantityInput);

function updateQuantityInput() {
    var store = $('#store').val();
    var selectedProductCode = $('#product').val();
    var selectedColor = $('#colorInput').val() ?? '';
    var selectedSize = $('#sizeInput').val() ?? '';
    var selectedHand = $('#handInput').val() ?? '';
    var qtyInputText = $('#qtyValueText');
    var qtyValueNum = $('#qtyValueNum');
    // AJAX call to get the stock quantity and product cost of the selected product
    $.ajax({
        url: 'ajax_GET/get_stock_quantity.php',
        method: 'POST',
        data: {
            product_code: selectedProductCode,
            color: selectedColor,
            size: selectedSize,
            hand: selectedHand,
            store: store
        },
        success: function (response) {
            var data = JSON.parse(response);

            // ตรวจสอบ error หากไม่มีข้อมูลที่ตรงกันในฐานข้อมูล
            if (data.error1) {
                $('#alertFillData').prop('hidden', false);
                $('#alertSuccess').prop('hidden', true);
                $('#alertError').prop('hidden', true);
                $('#submitStockOutBtn').prop('hidden', true);
                qtyInputText.prop('hidden', true);
                return;
            } else if (data.error2) {
                $('#alertError').prop('hidden', false);
                $('#alertSuccess').prop('hidden', true);
                $('#alertFillData').prop('hidden', true);
                $('#submitStockOutBtn').prop('hidden', true);
                qtyInputText.prop('hidden', true);
                resetInput();
                return;
            } else if (data.error3) {
                $('#alertFillData').prop('hidden', false);
                $('#alertSuccess').prop('hidden', true);
                $('#alertError').prop('hidden', true);
                $('#submitStockOutBtn').prop('hidden', true);
                qtyInputText.prop('hidden', true);
                return;
            };

            var productID = data.p_product_id;
            var stockQuantity = store == '1' ? parseInt(data.stock_quantity) : parseInt(data.sub_qty);
            var productCost = parseFloat(data.p_cost_price);
            var productQTY = parseInt(data.p_qty);
            /* costvat */
            var salePrice = parseFloat(data.p_sale_price);
            var costPrice = parseFloat(data.p_cost_price);
            var vatRate = parseFloat(data.p_vat);

            /* if (!isNaN(costPrice) && !isNaN(vatRate)) {
                var Vat = (costPrice * vatRate) / 100;
                var productCostVat = (costPrice + Vat).toFixed(2); // ทศนิยม 2 ตำแหน่ง
            } else {
                var productCostVat = 0; // หรือค่าที่เหมาะสม
            } */

            if (!isNaN(salePrice) && !isNaN(vatRate)) {
                var Vat = (salePrice * vatRate) / 100;
                var salePriceVat = (salePrice + Vat).toFixed(2); // ทศนิยม 2 ตำแหน่ง
            } else {
                var salePriceVat = 0; // หรือค่าที่เหมาะสม
            }
            /* costvat */

            // แสดงผลลัพธ์ตามเงื่อนไขสต็อก
            if (stockQuantity > 0) {
                $('#alertSuccess').prop('hidden', false);
                $('#submitStockOutBtn').prop('hidden', false);
                $('#alertFillData').prop('hidden', true);
                $('#alertError').prop('hidden', true);
                qtyInputText.prop('hidden', true);

                qtyValueNum.val(stockQuantity).prop('disabled', false);
                var totalSalePrice = (stockQuantity * salePrice).toFixed(2).toLocaleString();
                var totalSalePriceVat = (stockQuantity * salePriceVat).toFixed(2).toLocaleString();
                $('#total_sale_vat').val(totalSalePriceVat);
                $('#total_sale').val(totalSalePrice);
                /* $('#selectedProductCost').val(productCost);
                $('#selectedProductCostVat').val(productCostVat); */
                $('#selectedProductSale').val(salePrice);
                $('#selectedProductSaleVat').val(salePriceVat);
                $('#product_id').val(productID);

            } else if (productQTY === 0) {
                qtyInputText.prop('hidden', false);
                $('#alertSuccess').prop('hidden', true);
                $('#alertFillData').prop('hidden', true);
                $('#alertError').prop('hidden', true);
                $('#submitStockOutBtn').prop('hidden', true);
                $('#selectedProductSale').val(salePrice);
                /* $('#selectedProductCost').val(productCost); */
                $('#product_id').val(productID);
            }

            // กำหนด max ของจำนวนสต็อกให้เป็น stockQuantity
            qtyValueNum.attr('max', stockQuantity);
        },
        error: function () {
            console.error('Error fetching stock quantity and product cost.');
        }
    });
}


// Function to decrement quantity value
/* function decrementQty() {
    var qtyValue = parseInt($('#qtyValue').text());
    if (qtyValue > 0) {
        $('#qtyValue').text(qtyValue - 1);
        updateTotalPrice(); // Call updateTotalPrice after decrementing quantity
    }
} */


// Function to increment quantity value
/* function incrementQty() {
    var qtyValue = parseInt($('#qtyValue').text());
    var maxQuantity = parseInt($('#qtyValue').attr('max'));
    if (qtyValue < maxQuantity) {
        $('#qtyValue').text(qtyValue + 1);
        updateTotalPrice(); // Call updateTotalPrice after incrementing quantity
    }
} */

// Function to update total price
function updateTotalPrice() {
    var qtyValueNum = $('#qtyValueNum').val();
    var productSale = parseFloat($('#selectedProductSale').val()); // Get the product cost
    var totalSalePrice = (qtyValueNum * productSale).toFixed(2);
    var productSaleVat = parseFloat($('#selectedProductSaleVat').val()); // Get the product cost
    var totalPriceSaleVat = (qtyValueNum * productSaleVat).toFixed(2);

    $('#total_sale').val(totalSalePrice); // Update the total price element
    $('#total_sale_vat').val(totalPriceSaleVat); // Update the total price element
}


// Get the radio buttons
var saleRadio = document.getElementById('flexRadioDefault1');
var takeOutRadio = document.getElementById('flexRadioDefault2');
var saleSampleRadio = document.getElementById('flexRadioDefault3');

// Get the select container
var selectContainerSale = document.getElementById('selectContainerSale');
var selectContainerTakeOut = document.getElementById('selectContainerTakeOut');
// Function to toggle select container visibility
function toggleSelectContainer() {
    if (takeOutRadio.checked) {
        selectContainerTakeOut.style.display = 'table-cell'; // Show select container
    } else {
        selectContainerTakeOut.style.display = 'none'; // Hide select container
    }

    if (saleRadio.checked) {
        selectContainerSale.style.display = 'table-cell'; // Show select container
    } else {
        selectContainerSale.style.display = 'none'; // Hide select container
    }

    if (saleSampleRadio.checked) {
        selectContainerSale.style.display = 'none'; // Hide select container
        selectContainerTakeOut.style.display = 'none'; // Hide select container
    }
}

// Call the function initially
toggleSelectContainer();

// Add event listener to the radio buttons
saleRadio.addEventListener('change', toggleSelectContainer);
takeOutRadio.addEventListener('change', toggleSelectContainer);
saleSampleRadio.addEventListener('change', toggleSelectContainer);
var paidOption = $('#paidOption').val();

function submitStockOut() {

    // Get the radio inputs
    var saleRadio = document.getElementById("flexRadioDefault1");
    var takeOutRadio = document.getElementById("flexRadioDefault2");
    var saleSampleRadio = document.getElementById('flexRadioDefault3');
    var paidOption = $('#paidOption').val();
    var store = '';
    var stockToOption = $('#stockToOption').val();
    var customerName = $('#cusname').val(); // Get the value of the customer name input
    var date = $('#dateStockOut').val(); // Get the value of the customer name input
    var otherInput = $('#otherInput').val();
    var discountType = $('#disType').val();
    var discountPrice = $('#inputDiscount').val();
    var totalSaleDis = $('#total_sale_dis').val();
    var totalSaleVat = $('#total_sale_vat').val();
    var reasons_submit; // Define reasons_submit variable
    // Check if the sale radio is checked
    if (saleRadio.checked) {

        if (paidOption === '' || customerName === '') {
            Swal.fire({
                title: 'ERROR',
                text: "Please select a payment method \nor enter the customer's name.",
                icon: 'warning',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
            return false;
        }
        // Update reasons_submit for 'sale' reason
        reasons_submit = 'sale';
        store = $('#store').val();
    }
    // Check if the take out radio is checked
    else if (takeOutRadio.checked) {
        reasons_submit = 'out to'; // Update reasons_submit for 'take_out' reason
        store = stockToOption;
    } else if (saleSampleRadio.checked) {
        reasons_submit = 'sale sample';
        //default samt == 1
        store = 1;
    } else {
        Swal.fire({
            title: 'ERROR',
            text: 'Please select stock out to',
            icon: 'error',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
        return false;
    }
    /* สำหรับเช็คดรอปดาว */



    var mgCode = $('#MG_code').val();
    var productID = $('#product_id').val();
    /*  var productCode = $('#product').val();
     var productName = $('#selectedProductName').val();
     var productCost = $('#selectedProductCost').val();
     var productTotal = $('#total_price').val(); */
    var memo = $('#memo').val();
    var qtyValueNum = $('#qtyValueNum').val();
    console.log(reasons_submit, qtyValueNum);

    var data = {
        mgCode: mgCode,
        productID: productID,
        memo: memo,
        qtyValue: qtyValueNum,
        reasons: reasons_submit,
        date: date,
        store: store,
        discountType: discountType,
        discountPrice: discountPrice,
        totalSaleDis: totalSaleDis,
        totalSaleVat: totalSaleVat,
        customerName: customerName,
        paidOption: paidOption
    };


    if (productID != '' && qtyValueNum != 0) {
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
                    url: 'stock_out_submit.php',
                    method: 'POST',
                    data: data,
                    success: function (response) {
                        // จัดการข้อมูลหลังจากที่ส่งไปยังเซิร์ฟเวอร์สำเร็จ
                        // Handle success response
                        Swal.fire('Success!', 'Form submitted successfully', 'success')
                            .then((result) => {
                                location.reload();
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
            text: 'Please stock in or add QTY of product',
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
