

function loadData(store = null, month = formattedMonth, payment = 0, year = currentYear,
    prStatusSelected) {

    console.log('Payment: ' + ($('#paymentStatus').val()));
    console.log('PRStatus: ' + ($('#prStatusSelected').val()));
    // Define the URL for the AJAX request
    var url = "mail_mnm_fetch.php";
    // Define the data to be sent
    var data = {
        store: store,
        month: month,
        payment: payment,
        year: year,
        prStatusSelected: prStatusSelected
    };
    // Perform an AJAX request
    $.ajax({
        type: "POST",
        url: url,
        data: data,
        success: function (response) {
            // Replace the content of dataTable with the new data
            $('#dataTable').html(response);
        }
    });
}

// Function to get the active store based on the active tab
function getActiveStore() {
    var activeTabId = $('.tab.active').attr('id');
    var store = null; // Default store value

    // Determine store based on active tab ID
    if (activeTabId === 'samtTab') {
        store = 'samt';
    } else if (activeTabId === 'sakabaTab') {
        store = 'sakaba';
    }

    console.log(activeTabId); // Output the active tab ID to console for debugging

    // Return the store based on the active tab
    return getStoreFromTabId(store);
}

// Function to map tab ID to store
function getStoreFromTabId(tabId) {
    switch (tabId) {
        case 'samt':
            return 'samt';
        case 'sakaba':
            return 'sakaba';
        default:
            return null; // Return null for unknown or default case
    }
}

// Function to handle tab click events
function handleTabClick(tabId, store) {
    $('.tab').removeClass('active');
    $(tabId).addClass('active');
    var month = $('#months').val();
    var paymentStatus = $('#paymentStatus').val();
    var prStatusSelected = $('#prStatusSelected').val();
    var year = $('#years').val();
    loadData(store, month, paymentStatus, year, prStatusSelected);
}

document.addEventListener('DOMContentLoaded', function () {
    const selectAllCheckbox = document.getElementById('select-all');
    const dataTable = document.getElementById('dataTable');

    selectAllCheckbox.addEventListener('change', function () {
        const checkboxes = dataTable.querySelectorAll('.select-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = selectAllCheckbox.checked;
        });
    });

});

$(document).ready(function () {
    ///////////////////////////////////////////////////////
    var currentDate = new Date();
    ///////////////////////
    // ตั้งค่า default เดือนเป็นเดือนปัจจุบัน
    var currentMonth = new Date().getMonth() + 1; // getMonth() คืนค่าเป็น 0-11, เราจึงต้องบวก 1
    var formattedMonth = currentMonth < 10 ? '0' + currentMonth :
        currentMonth; // ถ้าเดือนน้อยกว่า 10 ให้เพิ่ม 0 ข้างหน้า



    var nextMonth = new Date().getMonth() + 2; // getMonth() คืนค่าเป็น 0-11, เราจึงต้องบวก 1
    var formattedNextMonth = nextMonth < 10 ? '0' + nextMonth :
        nextMonth; // ถ้าเดือนน้อยกว่า 10 ให้เพิ่ม 0 ข้างหน้า

    $('#months').val(formattedMonth);

    /////////////////////////////////
    var currentYear = new Date().getFullYear(); // Get current year
    console.log(currentYear);
    $('#years').val(currentYear); // Set the value of #years to the current year

    $('#years').change(function () {
        var selectedYear = $(this).val();
        var paymentStatus = $('#paymentStatus').val();
        var prStatusSelected = $('#prStatusSelected').val();
        var selectedMonth = $('#months').val();
        var activeStore = getActiveStore(); // Get the active store from the active tab
        loadData(activeStore, selectedMonth, paymentStatus, selectedYear, prStatusSelected);
    });

    $('#prStatusSelected').change(function () {
        var prStatusSelected = $(this).val();
        var selectedYear = $('#years').val();
        var paymentStatus = $('#paymentStatus').val();
        var selectedMonth = $('#months').val();
        var activeStore = getActiveStore(); // Get the active store from the active tab
        loadData(activeStore, selectedMonth, paymentStatus, selectedYear, prStatusSelected);
    });
    //////////////////////////////////
    // Load data with default values on page load
    var paymentStatus = $('#paymentStatus').val();
    var prStatusSelected = $('#prStatusSelected').val();
    /* 
            function alertMothChange() {
                $('#months').val(formattedNextMonth);
            } */
    // Update data when the month is changed
    $('#months').change(function () {
        var selectedYear = $('#years').val();
        var selectedMonth = $(this).val();
        var paymentStatusSelected = $('#paymentStatus').val();
        var prStatus = $('#prStatusSelected').val();
        var activeStore = getActiveStore(); // Get the active store from the active tab
        loadData(activeStore, selectedMonth, paymentStatus, selectedYear, prStatusSelected);
    });

    // Update data when the payment status is changed
    $('#paymentStatus').change(function () {
        var selectedYear = $('#years').val();
        var paymentStatus = $(this).val();
        var prStatusSelected = $('#prStatusSelected').val();

        var selectedMonth = $('#months').val();
        var activeStore = getActiveStore(); // Get the active store from the active tab
        loadData(activeStore, selectedMonth, paymentStatus, selectedYear, prStatusSelected);
    });

    // Load all data when the page loads
    handleTabClick('#productTab');
    //////////////////////////////////////////////////////

    // Set up click event handlers for each tab
    $('#productTab').click(function (e) {
        e.preventDefault();
        handleTabClick('#productTab');
    });

    $('#samtTab').click(function (e) {
        e.preventDefault();
        handleTabClick('#samtTab', 'samt');
    });

    $('#sakabaTab').click(function (e) {
        e.preventDefault();
        handleTabClick('#sakabaTab', 'sakaba');
    });
    ///////////////////////////////////////
});

$(document).ready(function () {
    function handleSelectedUpdate(action, status, successMessage, errorMessage) {
        var selectedIds = [];
        $('input[name="selected_ids[]"]:checked').each(function () {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length > 0) {
            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to update the status for ${selectedIds.length} selected items.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, update it!',
                cancelButtonText: 'No, cancel!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'pr_update_status.php',
                        type: 'POST',
                        data: {
                            ids: selectedIds,
                            status: status
                        },
                        success: function (response) {
                            Swal.fire({
                                title: 'Success',
                                text: successMessage,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function () {
                            Swal.fire({
                                title: 'Error',
                                text: errorMessage,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        } else {
            Swal.fire({
                title: 'No Data',
                text: 'No items selected.',
                icon: 'info',
                confirmButtonText: 'OK'
            });
        }
    }

    $('#purchaseSelected').click(function () {
        handleSelectedUpdate(
            'purchaseSelected',
            1,
            'Purchase request status updated successfully.',
            'Error updating purchase request status. Please try again.'
        );
    });

    $('#deliveredSelected').click(function () {
        handleSelectedUpdate(
            'deliveredSelected',
            2,
            'Delivered request status updated successfully.',
            'Error updating delivery request status. Please try again.'
        );
    });

    $('#stockinSelected').click(function () {
        handleSelectedUpdate(
            'stockinSelected',
            3,
            'StockIn request status updated successfully.',
            'Error updating StockIn request status. Please try again.'
        );
    });

    //////////////////////////////////
    $('#submitFormExchange').click(function () {
        // Get data from both forms
        var prCode = $('#prCodeInput').val(); // Example: Fetch PR Code from first form
        var pdCode = $('#productCode').val(); // Example: Fetch PR Code from first form
        var pdName = $('#productName').val();
        var size = $('#size').val();
        var color = $('#color').val();
        var hand = $('#hand').val();
        var qty = $('#qty').val();
        var prMgcode = $('#mgCode').val();
        var prDate = $('#dateAddedInput').val();
        var prID = $('#prID').val();
        var prStatusID = $('#prStatusID').val();


        // AJAX request to send data to server-side script for insertion
        $.ajax({
            url: 'pr_submit_exchange.php', // Replace with your server-side script URL
            method: 'POST',
            data: {
                pr_code: prCode,
                size: size,
                color: color,
                hand: hand,
                qty: qty,
                pdCode: pdCode,
                pdName: pdName,
                prMgcode,
                prDate,
                prID,
                prStatusID
            },
            success: function (response) {
                // Handle success response (if needed)
                console.log('Data inserted successfully');
                var jsonResponse = JSON.parse(response);

                // Check if the response indicates success or error
                if (jsonResponse.hasOwnProperty('success')) {
                    // Handle success case
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: jsonResponse.success,
                        showConfirmButton: true
                    }).then(function () {
                        // Additional actions if needed
                        console.log(response);
                        openEditModal(prID);

                        $('#alertExchange').removeClass('alert-danger').addClass(
                            'alert-success').html(
                                'Successfully Exchange please <a id="closeModalButton" data-bs-dismiss="modal" style="cursor: pointer;"><i class="fa-solid fa-rotate"></i> Refresh</a>'
                            ).prop('hidden', false);

                        // Attach a click event to the refresh button
                        $('#closeModalButton').click(function () {
                            location.reload(); // Refresh the page
                        });

                        $('#closeModalButton').prop('disabled', false);
                    });
                } else if (jsonResponse.hasOwnProperty('error')) {
                    // Handle error case
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: jsonResponse.error,
                        showConfirmButton: true
                    }).then(function () {
                        $('#alertExchange').removeClass('alert-success').addClass(
                            'alert-danger').prop('hidden', false).html(
                                ' Product not found, please check your fill or <a href="register.php">Register Here</a>'
                            ); // Show the alert
                        return; // Exit the function
                    });
                }
                //alert(response);
                // Optionally, clear form fields or reset form state
            },
            error: function (xhr, status, error) {
                // Handle error response
                console.error('Error inserting data:', error);
                alert('Error inserting data');
            }
        });
    });
});


$('#sendMail').click(function () {
    // เก็บ ID ที่เลือกไว้ใน array
    var selectedIds = [];
    $('input[name="selected_ids[]"]:checked').each(function () {
        selectedIds.push($(this).val());
    });


    // ตรวจสอบว่ามี ID ที่ถูกเลือกหรือไม่
    if (selectedIds.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'No selection',
            text: 'Please select at least one row to send mail',
        });
        return;
    }

    // แสดง Swal Loading ระหว่างรอการส่งข้อมูล
    Swal.fire({
        title: 'Please wait',
        text: 'Sending email...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });

    // ทำการส่งข้อมูลไปยัง mail_po_send.php
    $.ajax({
        url: 'ajax_POST/mail_po_send.php',
        type: 'POST',
        data: {
            selectedIds: selectedIds
        },
        dataType: 'json', // ระบุประเภทของข้อมูลที่คาดว่าจะได้รับกลับ
        success: function (response) {
            Swal.close(); // ปิด Loading

            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Email Sent',
                    text: response.message,
                }).then(() => {
                    // Reload the page after the alert is closed
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message,
                });
            }
        },
        error: function (xhr, status, error) {
            Swal.close(); // ปิด Loading

            console.error('Failed to send email:', error);
            Swal.fire({
                icon: 'error',
                title: 'Failed',
                text: `An error occurred: ${xhr.responseText || error}`,
            });
        },
    });
});

/// ฟังก์ชั่นการดาวโหลด pdf ให้มีการอัพเดทวันที่ปัจจุบันและทำการบันทึกไฟล์ลงในเครื่องได้
$('#saveFile').click(function save() {
    var selectedIds = [];
    $('input[name="selected_ids[]"]:checked').each(function () {
        selectedIds.push($(this).val());
    });

    if (selectedIds.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'No selection',
            text: 'Please select at least one row to export.',
        });
        return;
    }

    // แสดง Swal Loading สำหรับการอัปเดตฐานข้อมูล
    Swal.fire({
        title: 'Please wait',
        text: 'Updating database...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // อัปเดตฐานข้อมูลก่อน
    $.ajax({
        url: 'ajax_POST/mail_pr_download.php', // ไฟล์ PHP ที่ใช้ในการอัปเดตฐานข้อมูล
        type: 'POST',
        data: { selectedIds: selectedIds }, // ส่ง array ของ selectedIds
        success: function (response) {
            console.log('Database update successful:', response);

            // แสดง Swal Loading สำหรับการสร้าง PDF
            Swal.update({
                title: 'Please wait',
                text: 'Generating your PDF...',
            });

            // สร้าง div ชั่วคราวเพื่อรวมเฉพาะแถวที่เลือก
            const selectedTableContainer = document.createElement('div');
            selectedTableContainer.style.padding = '10px';
            selectedTableContainer.style.backgroundColor = 'white';
            selectedTableContainer.style.position = 'absolute'; // ซ่อน div
            selectedTableContainer.style.top = '-9999px'; // ย้ายออกนอกหน้าจอ

            // คัดลอก Header จากตาราง โดยละเว้นคอลัมน์แรก
            const header = document.querySelector('#pr-table thead').cloneNode(true);
            $(header).find('th:nth-child(1), th:nth-child(2)').remove(); // ลบคอลัมน์ที่หนึ่งและสองออกจาก Header
            const table = document.createElement('table');
            table.style.width = '100%';
            table.style.borderCollapse = 'collapse';
            table.appendChild(header);

            // เพิ่มเฉพาะแถวที่เลือก โดยละเว้นคอลัมน์แรก
            $('input[name="selected_ids[]"]:checked').each(function () {
                const row = $(this).closest('tr').clone(); // คัดลอกแถวที่เกี่ยวข้อง
                $(row).find('td:nth-child(1), td:nth-child(2)').remove(); // ลบคอลัมน์ที่หนึ่งและสองออกจากแถว
                table.appendChild(row[0]); // เพิ่มแถวลงใน table
            });

            selectedTableContainer.appendChild(table); // เพิ่ม table ลงใน div ชั่วคราว
            document.body.appendChild(selectedTableContainer); // เพิ่ม div ชั่วคราวใน DOM เพื่อ render

            // ใช้ html2canvas กับ div ชั่วคราว
            html2canvas(selectedTableContainer, { scale: 2 }).then(canvas => {
                // ลบ div ชั่วคราว
                document.body.removeChild(selectedTableContainer);

                // สร้างภาพจาก HTML
                const imgData = canvas.toDataURL('image/png');

                // ใช้ jsPDF ในโหมด Landscape (แนวนอน)
                const { jsPDF } = window.jspdf; // เรียกใช้ jsPDF
                const pdf = new jsPDF('l', 'mm', 'a4'); // 'l' คือ Landscape, ขนาด A4
                const pdfWidth = pdf.internal.pageSize.getWidth();
                const pdfHeight = (canvas.height * pdfWidth) / canvas.width;

                pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
                pdf.save('selected_items.pdf'); // ดาวน์โหลดไฟล์ PDF

                // ปิด Swal เมื่อสำเร็จ
                Swal.close();
                // Reload หน้าเว็บ
                location.reload();
            }).catch(function (error) {
                console.error('Error rendering PDF:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'An error occurred while creating the PDF!',
                });
            });
        },
        error: function (xhr, status, error) {
            console.error('Database update failed:', error);
            Swal.fire({
                icon: 'error',
                title: 'Update Failed',
                text: 'An error occurred while updating the database.',
            });
        }
    });
});

