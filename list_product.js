
var previewData = []; // Array to store preview data
const now = new Date(); // Get the current date

function exportToCSV() {
    // Get the current date and time formatted as YYYY-MM-DD_HH-MM-SS
    const now = new Date();
    const formattedDate = now.toISOString().split('T')[0] + "_" + now.toTimeString().split('')[0].replace(/:/g, '-');

    // CSV headers
    let csvContent =
        "No,ID Code,Product ID,Collection,Name,Hands,Color,Size,Cost Price,Sale Price,All Qty, Stock In(+), Take Out(-)\n";

    // Get all rows from the table body
    const rows = document.querySelectorAll("table tbody tr");
    rows.forEach(function (row, index) {
        const cells = row.querySelectorAll("td");
        const rowData = [
            index + 1, // No
            row.getAttribute('data-id') || '', // ID Code (from data-id attribute of <tr>)
            cells[1].innerText, // Product ID
            cells[2].innerText, // Collection
            cells[3].innerText, // Name
            cells[4].innerText || '', // Hands
            cells[5].innerText || '', // Color
            cells[6].innerText || '', // Size
            cells[7].innerText.replace(/,/g, ''), // Cost Price
            cells[8].innerText.replace(/,/g, ''), // Sale Price
            cells[10].innerText, // All Qty
        ];
        csvContent += rowData.join(",") + "\n"; // Add row to CSV content
    });

    // Create a blob and trigger download with UTF-8 encoding
    const blob = new Blob([`\ufeff${csvContent}`], {
        type: 'text/csv;charset=utf-8;'
    });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);

    link.setAttribute('href', url);
    link.setAttribute('download', `product_list_${formattedDate}.csv`);
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}



// Function to toggle input field visibility and update preview
function toggleInput(checkbox, reason) {
    var inputId = checkbox.id.replace('checkbox_', 'input_'); // Get the corresponding input field ID
    var inputField = document.getElementById(inputId);
    if (checkbox.checked) {
        inputField.type = 'number'; // Change input type to number
        inputField.style.display = 'block'; // Show the input field

        // Remove any existing event listener to avoid duplicates
        inputField.removeEventListener('input', handleInputChange);
        inputField.addEventListener('input', handleInputChange);

        //updatePreview(inputId); // Update the preview with the reason
    } else {
        inputField.style.display = 'none'; // Hide the input field
        inputField.value = ''; // Clear input value

        // Remove the product from previewData
        var productId = inputId.replace('input_', ''); // Get the product ID from inputId
        previewData = previewData.filter(item => item.p_product_id !== productId);

        // Update the preview display
    }
    updatePreviewDisplay();

}

// Function to handle input change
function handleInputChange(event) {
    var inputId = event.target.id;
    updatePreview(inputId);
}

// Function to update the preview
function updatePreview(inputId) {
    if (inputId) {
        var productId = inputId.replace('input_', ''); // Get the product ID from inputId
        var inputField = document.getElementById(inputId);
        var qty = inputField.value; // Get the quantity from input field

        // Validate quantity input
        /* if (!qty || isNaN(qty) || qty < 0) {
            console.error('Invalid quantity input');
        return;
        } */

        // Send AJAX request to fetch product data
        $.ajax({
            type: 'POST',
            url: 'list_get_product_info.php', // URL to handle AJAX request
            data: {
                productId: productId
            }, // Send product ID as data
            success: function (response) {
                try {
                    var productData = JSON.parse(response);

                    // Update previewData or create new entry
                    var productIndex = previewData.findIndex(item => item.p_product_id === productId);
                    if (productIndex !== -1) {
                        previewData[productIndex].s_qty = qty;
                    } else {

                        // Create a new entry in previewData
                        previewData.push({
                            p_product_id: productId,
                            p_product_code: productData.p_product_code,
                            p_qty: productData
                                .stock_qty, // Ensure this field exists in your fetched data
                            s_qty: qty,
                            p_collection: productData.p_collection,
                            p_product_name: productData.p_product_name,
                            p_hands: productData.p_hands,
                            p_color: productData.p_color,
                            p_size: productData.p_size,
                            p_cost_price: productData.p_cost_price,
                            p_sale_price: productData.p_sale_price,
                            p_vat: productData.p_vat,
                            // Add more properties as needed
                            //p_reason: reason // Add reason to the new entry
                        });
                    }
                    // Update the preview display
                    updatePreviewDisplay();
                } catch (e) {
                    console.error('Error parsing response:', e);
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX request error:', error);
            }
        });
    }
}


// Function to update the preview display
function updatePreviewDisplay() {
    var previewBody = document.getElementById('previewBody');
    previewBody.innerHTML = ''; // Clear existing content

    previewData.forEach(function (product, index) {
        var cost_price = product.p_cost_price;
        var cost_vat = parseFloat(cost_price) * parseFloat(product.p_vat) / 100 + parseFloat(cost_price);

        var row = document.createElement('tr');
        row.innerHTML = `
        <td style="display: none;">${product.p_product_id}</td> <!-- Hidden to send ID for update -->
        <td>${index + 1}</td>
        <td>${product.p_product_code}</td>
        <td>${product.p_product_name}</td>
        ${product.p_hands ? `<td>${product.p_hands}</td>` : `<td></td>`}
        ${product.p_color ? `<td>${product.p_color}</td>` : `<td></td>`}
        ${product.p_size ? `<td>${product.p_size}</td>` : `<td></td>`}
        <td class="text-end">${Number(cost_price).toLocaleString()}</td>
        <td class="text-end">${Number(cost_vat).toFixed(2).toLocaleString()}</td>
        <td style="width:200px; color: red; text-align: center;">${product.p_qty}</td>
        <td style="width:200px; color: green; text-align: center;">
            +${product.s_qty} (${parseInt(product.p_qty) + parseInt(product.s_qty)})
        </td>
        <td class='text-end'>${(parseFloat(product.s_qty) * parseFloat(cost_vat)).toFixed(2).toLocaleString()}</td>
        `;
        previewBody.appendChild(row);
    });
}



function openPreviewModal(status) {
    // Check if previewData is defined and has length
    var totalRows = previewData ? previewData.length : 0;
    $('#statusType').val(status);
    // Handle based on status
    if (status == 1) {
        $('#confirmPRButton').prop('hidden', true); // To show the button
        $('#confirmStockInButton').prop('hidden', false); // To show the button
        $('#modal-title').html('<i class="fa-solid fa-inbox fa-lg"></i> Preview Stock In');
        updatePreview();
        // Recalculate total rows after update
        totalRows = previewData.length;
    } else if (status == 2) {
        $('#confirmStockInButton').prop('hidden', true); // To show the button
        $('#confirmPRButton').prop('hidden', false); // To show the button
        $('#modal-title').html('<i class="fa-solid fa-inbox fa-lg"></i> Preview PR');
        // You can add specific logic for status 2 here
        updatePreview();
        // Recalculate total rows after update
        totalRows = previewData.length;
    }

    // If no data, show alert and do not show the modal
    if (totalRows === 0) {
        Swal.fire({
            title: 'No Data',
            text: 'There is no data to update.',
            icon: 'info',
            confirmButtonText: 'OK'
        });
        return; // Prevent modal from showing if no data
    }

    // Show the preview modal
    $('#previewModal').modal('show');

    // Update the total count display in the modal
    document.getElementById('total').innerText = 'Total: ' + totalRows;
}

// Function to handle confirm button logic
function confirmButton(status) {
    // Display confirmation dialog with SweetAlert
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to update stock?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, update it!',
        cancelButtonText: 'Cancel',
    }).then((result) => {
        if (result.isConfirmed) {
            var selectedReason = document.querySelector('input[name="reason"]:checked').value;
            var dateCreate = document.querySelector('#currentDate').value;
            var memo = document.querySelector('textarea[name="memo"]').value;

            // Perform AJAX call if the user confirms the update
            $.ajax({
                type: 'POST',
                url: 'list_insert_stock_qty.php', // URL of the PHP script to update stock
                data: {
                    previewData: previewData, // The preview data to be sent
                    reason: selectedReason, // Reason selected by the user
                    date_create: dateCreate, // Current date
                    memo: memo, // Memo input
                    status: status // Send the status value as well
                },
                success: function (response) {
                    console.log(response);

                    // Show success message
                    Swal.fire({
                        title: 'Updated!',
                        text: 'Stock has been updated successfully.',
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonText: '<i class="fa-solid fa-folder-plus"></i> on this page',
                        cancelButtonText: '<i class="fa-solid fa-arrow-right-to-bracket"></i> History StockIn',
                        confirmButtonColor: '#28a745', // Custom color for confirm button
                        cancelButtonColor: 'orange' // Custom color for cancel button
                    }).then((result) => {
                        // Redirect or reload based on the user's choice
                        if (!result.isConfirmed) {
                            window.location.href = 'stock_in_his.php';
                        } else {
                            location.reload();
                        }
                    });
                },
                error: function (xhr, status, error) {
                    Swal.fire({
                        title: 'Error',
                        icon: 'error',
                    });
                    console.error(error);
                }
            });
        }
    });
}
