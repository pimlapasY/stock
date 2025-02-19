
$(document).ready(function () {
    //เรียกข้อมูล body ของ table
    fetchProducts();

    $("#selectedSearchBy").on("change", function () {
        if ($(this).val() === "0") {
            $("#option1").val("").prop("disabled", true);
            $("#option2").val("").prop("disabled", true);
            $("#option3").val("").prop("disabled", true);
            $("#productCodeSearch").val("");
            $("#productNameSearch").val("");
        }
        updateSelectedProducts();
        fetchProducts();
    });
    // ตรวจสอบการเปลี่ยนแปลงของฟิลด์การค้นหา
    $("#productCodeSearch, #productNameSearch, #option1, #option2, #option3").on("input", function () {
        // เก็บค่า selections ก่อนที่จะ fetch ใหม่
        updateSelectedProducts();
        fetchProducts();
    });


    function fetchProducts() {
        let searchBy = $("#selectedSearchBy").val();
        let productCode = $("#productCodeSearch").val();
        let productName = $("#productNameSearch").val();
        let option1 = $("#option1").prop("disabled") ? "" : $("#option1").val();
        let option2 = $("#option2").prop("disabled") ? "" : $("#option2").val();
        let option3 = $("#option3").prop("disabled") ? "" : $("#option3").val();

        let selectedProducts = JSON.parse(localStorage.getItem("selectedProducts")) || {};

        $.ajax({
            url: "stock_samt_fetch.php",
            type: "POST",
            dataType: "json",
            data: { searchBy, productCode, productName, option1, option2, option3 },
            success: function (data) {
                let tableBody = $("#tableBody");
                tableBody.empty();

                data.forEach((product) => {
                    let rowClass = product.rowColor ? `class='${product.rowColor}'` : "";
                    let isChecked = selectedProducts.hasOwnProperty(product.id) ? "checked" : "";
                    let storedValue = selectedProducts[product.id] || "";
                    let inputValue = storedValue ? `value='${storedValue}'` : "";
                    let inputDisplay = isChecked ? "block" : "none";

                    let checkbox = `<div class="input-group">
                                <div class="input-group-text">
                                    <input class='form-check-input checkbox-select' type='checkbox' 
                                           name='selected_ids[]' value='${product.id}' 
                                           id='checkbox_${product.id}' ${isChecked} 
                                           onchange='toggleInput(this)' /> 
                                </div>
                                <input class='form-control quantity-input' 
                                       min='1' max='${product.difference}' 
                                       type='number' id='input_${product.id}' 
                                       ${inputValue} 
                                       style='display: ${inputDisplay}; width: 70px;' />
                            </div>`;

                    let detailsButton = product.total_sub_qty
                        ? `<a href="#" class="info-icon btn btn-info" data-product-id="${product.id}">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </a>`
                        : "";

                    let row = `<tr ${rowClass} data-id='${product.id}'>
                            <td class='text-center' style='vertical-align: middle;'>${checkbox}</td>
                            <td>${product.index || ""}</td>
                            <td>${product.code || ""}</td>
                            <td>${product.collection || ""}</td>
                            <td>${product.name || ""}</td>
                            <td>${product.hands || ""}</td>
                            <td>${product.color || ""}</td>
                            <td>${product.size || ""}</td>
                            <td class='text-end'>${product.cost_price || ""}</td>
                            <td class='text-end'>${product.sale_price || ""}</td>
                            <td class='text-end'>${product.vat_price || ""}</td>
                            <td class='text-end' style='color: ${product.textColor}; background: ${product.backgroundColor};'>
                                ${product.difference || "0"}
                            </td>
                            <td class='text-center'>${detailsButton}</td>
                        </tr>`;

                    tableBody.append(row);
                });

                // เรียก restoreSelections หลังจากสร้างตารางใหม่
                restoreSelections();
            },
            error: function (xhr, status, error) {
                console.error("Error fetching data:", error);
            }
        });
    }

    function updateSelectedProducts() {
        let selectedProducts = JSON.parse(localStorage.getItem("selectedProducts")) || {};
        $(".checkbox-select").each(function () {
            let productId = $(this).val();
            if ($(this).is(":checked")) {
                let quantity = $("#input_" + productId).val();
                selectedProducts[productId] = quantity || "";
            }
        });
        localStorage.setItem("selectedProducts", JSON.stringify(selectedProducts));
    }

    function restoreSelections() {
        let selectedProducts = JSON.parse(localStorage.getItem("selectedProducts")) || {};
        Object.keys(selectedProducts).forEach(productId => {
            let checkbox = $(`#checkbox_${productId}`);
            let input = $(`#input_${productId}`);
            if (checkbox.length) {
                checkbox.prop("checked", true);
                input.val(selectedProducts[productId]).show();
            }
        });
    }

    window.toggleInput = function (checkbox) {
        const productId = $(checkbox).val();
        const inputField = $('#input_' + productId);
        if (checkbox.checked) {
            inputField.show().focus();
        } else {
            inputField.val('').hide();
            // ลบค่าออกจาก localStorage เมื่อยกเลิกการเลือก
            let selectedProducts = JSON.parse(localStorage.getItem("selectedProducts")) || {};
            delete selectedProducts[productId];
            localStorage.setItem("selectedProducts", JSON.stringify(selectedProducts));
        }
    };
    /* --------------------------------------------------------------------------- */
    function loadOptions(productCode, productName) {
        $.ajax({
            url: "stock_samt_product_options.php",
            type: "POST",
            data: {
                code: productCode,
                name: productName
            },
            dataType: "json",
            success: function (data) {
                console.log("🔹 Data received:", data); // 🔥 ตรวจสอบ JSON ที่ได้รับ

                function updateDatalist(selector, options) {
                    $(selector).empty(); // ล้างค่าก่อนเติมใหม่

                    // ใช้ Set เพื่อลบค่าที่ซ้ำกัน
                    let uniqueOptions = [...new Set(options)];

                    if (uniqueOptions.length > 0) {
                        uniqueOptions.forEach(function (item) {
                            $(selector).append("<option value='" + item + "'></option>");
                        });
                        $("input[list='" + selector.replace("#", "") + "']").prop("disabled",
                            false);
                    } else {
                        // ✅ รีเซ็ตค่า และปิดใช้งาน input
                        $("input[list='" + selector.replace("#", "") + "']")
                            .val('')
                            .prop("disabled", true);
                    }
                }

                updateDatalist("#product_option1", data.option1 || []);
                updateDatalist("#product_option2", data.option2 || []);
                updateDatalist("#product_option3", data.option3 || []);
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error: " + status + " - " + error);
            }
        });
    }

    $("#productCodeSearch").on("change", function () {
        let productCode = $(this).val();
        loadOptions(productCode, "");
    });

    $("#productNameSearch").on("change", function () {
        let productName = $(this).val();
        loadOptions("", productName);
    });

    /* ---------------------------------------------------------------------- */
    // เมื่อโหลดหน้า ให้เช็คสถานะ checkbox ที่ถูกเลือก
    $('input[name="selected_ids[]"]:checked').each(function () {
        $('#input_' + $(this).val()).show(); // แสดง input
    });

    // เมื่อคลิกที่ checkbox "เลือกทั้งหมด"
    $('#checkAll').on('change', function () {
        const isChecked = $(this).prop('checked');
        const checkboxes = $('input[name="selected_ids[]"]');

        checkboxes.prop('checked', isChecked);

        checkboxes.each(function () {
            const inputId = '#input_' + $(this).val();
            $(inputId).toggle(isChecked);
        });

        // จัดการ localStorage
        if (isChecked) {
            let selectedProducts = {};
            checkboxes.each(function () {
                selectedProducts[$(this).val()] = true;
            });
            localStorage.setItem("selectedProducts", JSON.stringify(selectedProducts));
        } else {
            localStorage.removeItem("selectedProducts");
        }
    });

    // ตรวจสอบสถานะของ checkbox ย่อยเพื่อปรับ "เลือกทั้งหมด"
    $('input[name="selected_ids[]"]').on('change', function () {
        const productId = $(this).val();
        const inputId = '#input_' + productId;
        const isChecked = $(this).prop('checked');

        $(inputId).toggle(isChecked);

        // จัดการ localStorage
        let selectedProducts = JSON.parse(localStorage.getItem("selectedProducts")) || {};
        if (isChecked) {
            selectedProducts[productId] = true;
        } else {
            delete selectedProducts[productId];
        }
        localStorage.setItem("selectedProducts", JSON.stringify(selectedProducts));

        $('#checkAll').prop(
            'checked',
            $('input[name="selected_ids[]"]').length === $('input[name="selected_ids[]"]:checked').length
        );
    });
    // Hide all details sections initially
    $('#saleDetails').hide();
    $('#takeOutDetails').hide();

    // Event listener for radio button changes
    $('input[name="flexRadioDefault"]').change(function () {
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


    /* ************************************************************************************** */
    // submit function อัพเดทการสต็อคออก/การสร้างใบ PR
    $('#submitProductDetails').click(function () {
        // Gather the data from the table
        const productDetails = [];
        var checkStatus = ''; // Get the selected radio button value
        const currentDate = $('#currentDate').val(); // Get the date input value
        var updateForm = $('#updateForm').val();
        var typeStatus = '';
        var storeID = '1';
        var paidOption = '';
        var customerName = '';
        var memo = $('#memo').val();

        let selectedValue = ''; // กำหนดค่า selectedValue เป็นตัวแปรแบบ let

        $('#productDetails tr').each(function () {
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

        // ตรวจสอบว่า radio ที่มี name="flexRadioDefault" ถูกเลือกหรือไม่
        // ดึงข้อมูลจาก radio button ที่มี name="flexRadioDefault"
        selectedValue = $('input[name="flexRadioDefault"]:checked').val();
        console.log("Selected Radio Value: " + selectedValue);


        if (selectedValue == 'sale') {
            customerName = $('#cusname').val();
            paidOption = $('#paidOption').val();
            storeID = '1';
            typeStatus = '1';
        } else if (selectedValue == 'out to') {
            customerName = '';
            paidOption = '';
            storeID = $('#stockToOption').val();
            typeStatus = '2'
        } else if (selectedValue == 'sale sample') {
            customerName = $('#cusname').val();
            paidOption = '';
            storeID = '1';
            typeStatus = '3'
        } else {
            if (updateForm == '1') {
                Swal.fire({
                    title: 'Warning',
                    text: 'Please select Stock out for.',
                    icon: 'warning',
                    confirmButtonText: '<i class="fa-solid fa-check"></i> OK',
                    confirmButtonColor: 'gray'
                });
                return; // หยุดการทำงานหากไม่มีการเลือก
            }
        }

        // แสดง Loading
        Swal.fire({
            title: 'Processing...',
            html: 'Please wait a moment',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
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
                dateSelect: currentDate,
                updateForm: updateForm,
                storeID: storeID, // Include the date in the request
                paidOption: paidOption,
                customerName: customerName,
                selectedValue: selectedValue,
                memo: memo
            }),
            contentType: 'application/json',
            success: function (response) {
                // Handle the server response
                console.log(response);


                Swal.fire({
                    title: 'Success',
                    text: 'Product details updated successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // ล้างค่า checkbox ทั้งหมด
                        $('input[name="selected_ids[]"]').prop('checked', false);
                        $('#checkAll').prop('checked', false);

                        // ซ่อน inputs ทั้งหมด
                        $('input[name="selected_ids[]"]').each(function () {
                            $('#input_' + $(this).val()).hide();
                        });

                        // ล้างค่าใน localStorage
                        localStorage.removeItem("selectedProducts");

                        // ปิด modal
                        $('#previewModal').modal('hide');

                        if (typeStatus == '1' || typeStatus == '3') {
                            // เปลี่ยนจาก location.reload() เป็นการ redirect ไปยัง currently_samt.php
                            window.location.href = 'currently_samt.php';
                        } else if (typeStatus == '2') {
                            window.location.href = 'take_out_his.php';
                        }
                    }
                });
            },
            error: function (error) {
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
    /* ****************************************************************************************** */
    // เมื่อคลิกปุ่ม Preview
    $('.preview-btn-stockOut').click(function () {
        previewProduct(1)
    });
    $('.preview-btn-pr').click(function () {
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
        const selectedIds = $('input[name="selected_ids[]"]:checked').map(function () {
            return $(this).val();
        }).get();

        // ตรวจสอบ input fields
        let hasEmptyQty = false; // ตัวแปรเช็คว่ามี qty ว่างหรือไม่
        const productData = selectedIds.map(id => {
            const qty = $('#input_' + id).val(); // ดึงค่า qty จาก input field
            if (!qty) { // ถ้า qty ไม่มีค่า
                hasEmptyQty = true;
            }
            return {
                id: id,
                qty: qty
            };
        }).filter(item => item.qty); // กรองรายการที่ไม่มี qty ออก

        // แสดงการแจ้งเตือนและหยุดกระบวนการหากมี qty ที่ว่าง
        if (hasEmptyQty) {
            Swal.fire({
                title: 'Warning',
                text: 'Please enter the quantity for all selected items.',
                icon: 'warning',
                confirmButtonText: '<i class="fa-solid fa-check"></i> OK',
                confirmButtonColor: 'gray'
            }).then(() => {
                return; // หยุดการทำงานหลังจากผู้ใช้กด OK
            });
        } else {
            // ดำเนินการต่อเมื่อข้อมูลถูกต้อง
            console.log(productData);
        }


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
            success: function (data) {
                const details = JSON.parse(data);

                let productDetails = '';
                let allTotal = 0; // ตั้งค่าตัวแปร `allTotal` เป็น 0
                let number = 1; // ตั้งค่าตัวแปร `number` เป็น 1
                var totalPrice = 0;
                var vat = 0;

                details.forEach(function (detail) {

                    if (statusBtn == 1) { //Stock out สร้างสินค้าออก
                        $('#priceLabel').text('Sale Price');
                        $('#priceVatLabel').text('Sale Price(Vat%)');
                        $('#totalVatLabel').text('Total Sale(Vat%)');

                        vat = parseFloat((detail.p_sale_price * detail.p_vat) / 100);
                        totalPrice = detail.qty * (parseFloat(detail.p_sale_price) + vat);
                        allTotal += totalPrice; // Sum the total prices
                        var totalSaleVat = parseFloat(vat) + parseFloat(detail.p_sale_price);

                        productDetails += `
                                        <tr>
                                            <td>${number}</td>
                                            <td>${detail.p_product_code}</td>
                                            <td>${detail.p_product_name}</td>
                                            <td>${detail.p_hands}</td>
                                            <td>${detail.p_color}</td>
                                            <td>${detail.p_size}</td>
                                            <td class='text-end'>${formatPrice(detail.p_sale_price)}</td> <!-- Display price in formatted way -->
                                            <td class='text-end'>${formatPrice(totalSaleVat)}</td> <!-- Display price in formatted way -->
                                            <td class='text-end ${colorText}'>
                                                <input style="width:80px" class="form-control qty-input" min="1" type="number" value="${detail.qty}" data-product-id="${detail.p_product_id}" readonly>
                                            </td> <!-- Display qty value -->
                                            <td class='text-end ${colorText}' data-product-id="${detail.p_product_id}">${formatPrice(totalPrice)}</td> <!-- Display total price -->
                                        </tr>
                                    `;
                    } else { //PR Create สร้างใบ pr
                        $('#priceLabel').text('Cost Price');
                        $('#priceVatLabel').text('Cost Price(Vat%)');
                        $('#totalVatLabel').text('Total Cost(Vat%)');

                        vat = parseFloat((detail.p_cost_price * detail.p_vat) / 100);
                        totalPrice = detail.qty * (parseFloat(detail.p_cost_price) + vat);
                        allTotal += totalPrice; // Sum the total prices
                        var totalCostVat = parseFloat(vat) + parseFloat(detail.p_cost_price);

                        productDetails += `
                        <tr>
                            <td>${number}</td>
                            <td>${detail.p_product_code}</td>
                            <td>${detail.p_product_name}</td>
                            <td>${detail.p_hands}</td>
                            <td>${detail.p_color}</td>
                            <td>${detail.p_size}</td>
                            <td class='text-end'>${formatPrice(detail.p_cost_price)}</td> <!-- Display price in formatted way -->
                            <td class='text-end'>${formatPrice(totalCostVat)}</td> <!-- Display price in formatted way -->
                            <td class='text-end ${colorText}'>
                                <input class="form-control qty-input" min="1" type="number" value="${detail.qty}" data-product-id="${detail.p_product_id}" readonly>
                            </td> <!-- Display qty value -->
                            <td class='text-end ${colorText}' data-product-id="${detail.p_product_id}">${formatPrice(totalPrice)}</td> <!-- Display total price -->
                        </tr>
                    `;
                    }

                    if ((statusBtn == 1) && (Number(detail.qty) > (Number(detail.s_qty) -
                        Number(detail.sub_qty)))) {
                        console.log(detail.qty + '/' + detail.s_qty);
                        productDetails += `
                                        <tr>
                                            <td colspan="10">
                                                <div class="alert alert-danger" role="alert">
                                                    Quantity exceeds available stock! Stock is: ${detail.s_qty - detail.sub_qty}
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
                $('.qty-input').on('change', function () {
                    updateTotals();
                });
            },
            error: function () {
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
        $('#productDetails tr').each(function () {
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


    function formatPrice(price) {
        // แปลงราคาสำหรับการแสดงลูกน้ำ
        return parseFloat(price).toLocaleString('en-US', {
            style: 'currency',
            currency: 'THB'
        }); // สำหรับสกุลเงินไทย

    }

    /* --------------------------------------------------------------- */
    //ดู substock store อื่นๆ
    $(document).on('click', '.info-icon', function (e) {
        e.preventDefault(); // ป้องกัน default link behavior

        var productId = $(this).data('product-id'); // ดึงค่า Product ID

        // ส่ง AJAX เพื่อดึงข้อมูลรายละเอียดสินค้า
        $.ajax({
            url: 'ajax_GET/get_sub_stock_details.php',
            type: 'POST',
            data: { p_product_id: productId },
            success: function (data) {
                var details = JSON.parse(data);
                if (details.length === 0) {
                    alert("No stock details available.");
                    return;
                }

                // สร้างชื่อสินค้าใน Modal
                $('#previewProductLabel').html(`
                    <b>${details[0].p_product_code}</b> - 
                    ${details[0].p_product_name} 
                    ${details[0].p_hands}  
                    ${details[0].p_color} 
                    ${details[0].p_size}
                `);



                // สร้างตารางข้อมูล
                let rows = `
                <tr class="text-center table-info">
                   <th>No.</th>
                   <th>Location</th>
                   <th>QTY</th>
                </tr>
            `;

                var total = 0;
                var numRow = 1;
                $.each(details, function (index, item) {
                    rows += `
                <tr>
                   <td class="text-primary">${numRow}</td>
                    <td class="text-primary">${item.sub_name}</td>
                    <td class="text-end">${item.sub_qty}</td>
                </tr>`;
                    total += parseInt(item.sub_qty);
                    numRow++;
                });

                // แสดงยอดรวมสินค้า
                rows += `
                <tr>
                    <td colspan="2"><strong>Total:</strong></td>
                    <td class="text-end"><strong>${total}</strong></td>
                </tr>
            `;

                // อัปเดตข้อมูลลงใน Modal
                $('#stockModal tbody').html(rows);

                // เปิด Modal
                $('#previewStockModal').modal('show');
            },
            error: function () {
                alert('Error loading sub stock details.');
            }
        });
    });


});
