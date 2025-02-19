
$(document).ready(function () {

    // เมื่อโหลดหน้า ให้เช็คสถานะ checkbox ที่ถูกเลือก
    $('input[name="selected_ids[]"]:checked').each(function () {
        $('#input_' + $(this).val()).show(); // แสดง input
    });

    // เมื่อคลิกที่ checkbox "เลือกทั้งหมด"
    $('#checkAll').on('change', function () {
        const isChecked = $(this).prop('checked');

        // เลือก/ยกเลิกการเลือกทุก checkbox
        $('input[name="selected_ids[]"]').prop('checked', isChecked);

        // แสดง/ซ่อน input ทั้งหมดตามสถานะ
        if (isChecked) {
            // ถ้าเลือก "เลือกทั้งหมด" ให้แสดงทุก input
            $('input[name="selected_ids[]"]').each(function () {
                $('#input_' + $(this).val()).show();
            });
        } else {
            // ถ้ายกเลิก "เลือกทั้งหมด" ให้ซ่อนทุก input
            $('input[name="selected_ids[]"]').each(function () {
                $('#input_' + $(this).val()).hide();
            });
        }
    });

    // ตรวจสอบสถานะของ checkbox ย่อยเพื่อปรับ "เลือกทั้งหมด"
    $('input[name="selected_ids[]"]').on('change', function () {
        // แสดง/ซ่อน input ตามสถานะของ checkbox แต่ละตัว
        const inputId = '#input_' + $(this).val();
        if ($(this).prop('checked')) {
            $(inputId).show();
        } else {
            $(inputId).hide();
        }

        // ปรับสถานะ "เลือกทั้งหมด"
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

    $('.info-icon').click(function (e) {
        e.preventDefault(); // Prevent the default link behavior

        var productId = $(this).data('product-id'); // Get the product ID

        // Make an AJAX request to fetch details
        $.ajax({
            url: 'ajax_GET/get_sub_stock_details.php',
            type: 'POST',
            data: {
                p_product_id: productId
            },
            success: function (data) {
                var details = JSON.parse(data);
                // Build the table rows for the modal
                $('#modalOtherStockLabel').html(` ${details[0].p_product_code} 
                            ${details[0].p_product_name}
                            ${details[0].p_hands} 
                            ${details[0].p_color} 
                            ${details[0].p_size}`);
                let rows = `
                    <tr class="text-center table-info">
                       <th >Location</th>
                       <th>QTY</th>
                    </tr>
                `;

                var num = 0;
                var total = 0;
                $.each(details, function (index, item) {
                    num++
                    rows += `
                    <tr>
                        <td class="text-primary"> ${item.sub_name}</td>
                         <td class="text-end">${item.sub_qty}</td>
                    </tr>
                    
                `;
                    total += parseInt(item.sub_qty);
                });

                rows += `
                     <td>Total: </td>
                    <td class="text-end">${total}</td>
                `;
                // Update the modal content
                $('#stockModal tbody').html(
                    rows); // Ensure #stockModal is the <tbody> or relevant container

                // Show the modal
                $('#previewStockModal').modal('show');
            },
            error: function () {
                alert('Error loading sub stock details.');
            }
        });
    });




    // When the "Submit" button is clicked
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
        } else if (selectedValue == 'out to') {
            customerName = '';
            paidOption = '';
            storeID = $('#stockToOption').val();
        } else if (selectedValue == 'sale sample') {
            customerName = $('#cusname').val();
            paidOption = '';
            storeID = '1';
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
                        // Close the modal
                        $('#previewModal').modal('hide');
                        // Optionally, reload the page or update the table
                        location.reload();
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

    // ฟังก์ชันเพื่อจัดการแสดง/ซ่อน input เมื่อ checkbox เปลี่ยนสถานะ
    window.toggleInput = function (checkbox) {
        const productId = $(checkbox).val(); // รับค่าจาก checkbox
        const inputField = $('#input_' + productId); // เลือก input ที่เกี่ยวข้อง

        if (checkbox.checked) {
            inputField.show(); // แสดง input ถ้า checkbox ถูกเลือก
        } else {
            inputField.hide(); // ซ่อน input ถ้า checkbox ไม่ถูกเลือก
        }
    };
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
                                                <input class="form-control qty-input" min="1" type="number" value="${detail.qty}" data-product-id="${detail.p_product_id}" readonly>
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

    // Add event listener for Enter key on search input
    document.getElementById("searchInput").addEventListener("keydown", function (event) {
        if (event.key === "Enter") {
            searchTable();
        }
    });

    function searchTable() {
        // Get the value from the search input
        const input = document.getElementById("searchInput").value.toLowerCase();

        // Get the table and rows
        const table = document.getElementById("productTable");
        const rows = table.getElementsByTagName("tr");

        // Loop through all rows (except the header) and hide those that don't match the search query
        for (let i = 1; i < rows.length; i++) {
            const cells = rows[i].getElementsByTagName("td");
            let rowContainsSearchTerm = false;

            // Loop through each cell in the row
            for (let j = 0; j < cells.length; j++) {
                // Remove any previous highlight
                const cellText = cells[j].innerText;
                cells[j].innerHTML = cellText;

                if (cellText.toLowerCase().includes(input)) {
                    rowContainsSearchTerm = true;
                    // Highlight the matching term
                    const regex = new RegExp(`(${input})`, 'gi');
                    cells[j].innerHTML = cellText.replace(regex, "<span class='highlight'>$1</span>");
                }
            }

            // Show or hide the row based on the search
            if (rowContainsSearchTerm) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }
        }
    }
});
