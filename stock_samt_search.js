$(document).ready(function () {
    filterTable(); // ✅ โหลดข้อมูลสินค้าทั้งหมดตอนเปิดหน้าเว็บ

    let selectedCheckboxes = {}; // เก็บค่า checkbox ที่ถูกเลือกไว้

    function saveCheckboxState() {
        selectedCheckboxes = {};
        $("input[name='selected_ids[]']:checked").each(function () {
            selectedCheckboxes[$(this).val()] = true;
        });
    }

    function restoreCheckboxState() {
        $("input[name='selected_ids[]']").each(function () {
            if (selectedCheckboxes[$(this).val()]) {
                $(this).prop("checked", true);
                $('#input_' + $(this).val()).show(); // แสดง input จำนวนที่เกี่ยวข้อง
            } else {
                $('#input_' + $(this).val()).hide(); // ซ่อน input ที่ไม่ถูกเลือก
            }
        });
    }

    function filterTable() {
        saveCheckboxState(); // บันทึกค่า checkbox ก่อนโหลดข้อมูลใหม่

        let option1 = $("#searchOption1").val();
        let option2 = $("#searchOption2").val();
        let option3 = $("#searchOption3").val();

        $.ajax({
            url: "fetch_filtered_products.php",
            type: "POST",
            data: {
                option1: option1,
                option2: option2,
                option3: option3
            },
            success: function (response) {
                console.log("🔹 Data received:", response); // ✅ ตรวจสอบข้อมูลที่ส่งกลับมา
                $("#productTableBody").html(response); // ✅ อัปเดตข้อมูลใน tbody
                restoreCheckboxState(); // คืนค่า checkbox ที่เลือกไว้
            },
            error: function () {
                console.error("❌ เกิดข้อผิดพลาดในการโหลดข้อมูล");
            }
        });
    }


    // กรองข้อมูลเมื่อพิมพ์ในช่อง datalist
    $("#searchOption1, #searchOption2, #searchOption3").on("input", function () {
        filterTable();
    });

    // ✅ อัปเดต checkbox และ input จำนวนที่เกี่ยวข้อง
    $(document).on("change", "input[name='selected_ids[]']", function () {
        let inputField = $('#input_' + $(this).val());
        if ($(this).prop("checked")) {
            inputField.show();
        } else {
            inputField.hide();
        }

        // อัปเดตสถานะ "เลือกทั้งหมด"
        $('#checkAll').prop(
            'checked',
            $('input[name="selected_ids[]"]').length === $('input[name="selected_ids[]"]:checked').length
        );
    });

    // ✅ อัปเดต "เลือกทั้งหมด"
    $(document).on("change", "#checkAll", function () {
        const isChecked = $(this).prop('checked');
        $("input[name='selected_ids[]']").prop('checked', isChecked);
        $("input[name='selected_ids[]']").each(function () {
            $('#input_' + $(this).val()).toggle(isChecked);
        });
    });
});
