<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History</title>
</head>
<style>
.my_a {
    cursor: pointer;
    background-color: none;
    border-radius: none;
    padding: none;
}

.my_a:hover {
    padding-top: 2px;
    padding-bottom: 2px;
    color: black;
}
</style>
<!-- Add this CSS for printing -->
<style media="print">
/* Hide all elements except the modal */
body>*:not(.modal) {
    display: none !important;
}

.print_none {
    display: none !important;
}

/* Adjust modal styles for printing */
.modal {
    position: static !important;
    display: block !important;
    overflow: visible !important;
    width: auto !important;
    margin: 0 !important;
    border: none !important;
    box-shadow: none !important;
}

/* Adjust modal content styles for printing */
.modal-content {
    overflow: visible !important;
    border: none !important;
    box-shadow: none !important;
}

/* Hide unnecessary elements */
.modal-header .btn-primary {
    border: 0 !important;
    display: none !important;
    margin: none;
}

/* Hide the close button in modal header */
.modal-header .btn-close {
    display: none !important;
}

.btn-outline-danger .btn-outline-success {
    border: 0px !important;
}
</style>

<body>
    <?php include('navbar.php') ?>
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn btn-primary" onclick="printModalContent()">
                        <i class="fa-solid fa-print"></i> PRINT
                    </button>
                    <!-- Add this script for printing -->
                    <script>
                    function printModalContent() {
                        // Hide the print button before printing
                        $('.modal-header .btn-primary').hide();

                        // Open print dialog
                        window.print();

                        // Show the print button after printing is done
                        $('.modal-header .btn-primary').show();
                    }
                    </script>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-header">
                    <table class="table table-borderless">
                        <tr>
                            <th class="text-center" colspan="6">
                                <h3 class="card-title">
                                    <button type="button" class="btn btn-outline-secondary btn-lg w-50"
                                        data-mdb-ripple-init data-mdb-ripple-color="dark">SHIPPO ASAHI MOULDS(THAILAND)
                                        CO.,LTD.</button>
                                </h3><br>
                                <h3 class="card-title" style="text-transform: uppercase;"><?php echo $request ?></h3>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="3">
                                <h5><?php echo $mat_goods ?></h5>
                            </th>
                            <th class="text-end" colspan="3">
                                <a><?php echo "DATE: "  ;?> <a id="dateR"></a></a>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="6">
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-outline-secondary btn-rounded"
                                        data-mdb-ripple-init>
                                        <?php echo $department  ?> : <a id="department"></a>
                                    </button>
                                    <button type="button" id="btn-status" class="btn btn-rounded" data-mdb-ripple-init>
                                        <?php echo $status  ?> : <a id="status"></a>
                                    </button>
                                </div>
                            </th>
                        </tr>
                    </table>
                </div>
                <div class="modal-body" id="modalBody">
                    <div class="card-body">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr class="table-light uppercase" style="text-align:center; white-space: nowrap;">
                                    <th>No.</th>
                                    <th><?php echo $product_code ?></th>
                                    <th><?php echo $description ?></th>
                                    <th><?php echo $qty ?></th>
                                    <th><?php echo $unit ?></th>
                                    <th><?php echo $purpose  ?></th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider table-divider-color" id="table-body-modal">
                            </tbody>

                        </table>
                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th colspan="3">
                                        <div class="input-group mb-3">
                                            <button class="btn btn-outline-secondary" type="button" id="rq_name"
                                                data-mdb-ripple-init data-mdb-ripple-color="dark">
                                                <?php echo $REQUEST_NAME ?>
                                                :</button>
                                            <input class="form-control" id="REQname" aria-describedby="rq_name"
                                                readonly />
                                        </div>
                                    </th>
                                    <th class="text-end">
                                        <div class="input-group mb-3">
                                            <button class="btn btn-outline-secondary" type="button" id="ap_by"
                                                data-mdb-ripple-init data-mdb-ripple-color="dark">
                                                <?php echo $APPROVED_BY ?>
                                                :</button>
                                            <input class="form-control" id="ap_by_modal" aria-describedby="ap_by"
                                                readonly />
                                        </div>
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="3">
                                        <div class="input-group mb-3">
                                            <button class="btn btn-outline-secondary" type="button" id="st_keeper"
                                                data-mdb-ripple-init data-mdb-ripple-color="dark">
                                                <?php echo $STORE_KEEPER ?>
                                                :</button>
                                            <input class="form-control" id="RKname" aria-describedby="st_keeper"
                                                readonly />
                                        </div>
                                    </th>
                                    <th class="text-end">
                                        <div class="input-group mb-3">
                                            <button class="btn btn-outline-secondary" type="button" id="gr_by"
                                                data-mdb-ripple-init data-mdb-ripple-color="dark">
                                                <?php echo $GOODS_RECEIVED_BY ?> :</button>
                                            <input class="form-control" id="RECname" aria-describedby="gr_by"
                                                readonly />
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="modal-footer print_none">
                    <button type="button" class="btn btn-secondary modal-footer" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="container" style="margin-top: 150px;">
        <div class="d-flex justify-content-between m-3" style="align-items: center;">
            <div class="d-flex justify-content-end w-100">
                <div class="input-group p-3">
                    <input id="searchInput" type="search" class="form-control rounded" placeholder="Search"
                        aria-label="Search" aria-describedby="search-addon" />
                    <button type="button" class="btn btn-primary" data-mdb-ripple-init
                        onclick="loadData(1, $('#searchInput').val())">Search</button>
                </div>
            </div>
        </div>

        <table class="table table-hover align-middle mb-0 bg-white table-bordered"
            style="text-align:center; white-space: nowrap;">
            <thead class="bg-light">
                <tr class="uppercase">
                    <th><?php echo '#' ?></th>
                    <th><?php echo $code ?></th>
                    <th><?php echo $purpose ?></th>
                    <th><?php echo $amount ?></th>
                    <th><?php echo $date ?></th>
                    <th><?php echo $time ?></th>
                    <th colspan="2"><?php echo $status ?></th>
                </tr>
            </thead>
            <tbody class="table-group-divider table-divider-color text-uppercase-first" id="tableBody">
                <!-- Table data will be inserted here -->
            </tbody>
        </table>
        <br>
        <div class="d-flex justify-content-end">
            <nav aria-label="Page navigation example text-end">
                <ul class="pagination" id="pagination">
                    <!-- Pagination links will be inserted here -->
                </ul>
            </nav>
        </div>
    </div>
    </div>
</body>

</html>
<script>
$(document).ready(function() {
    // Initial page load
    loadData(1, '');

    // Search input change event
    $('#searchInput').on('input', function() {
        loadData(1, $(this).val());
    });

    // Pagination click event
    $(document).on('click', '.pagination-link', function() {
        loadData($(this).data('page'), $('#searchInput').val());
    });

});

function loadData(page, search) {
    $.ajax({
        url: 'fetch_goodRequest.php',
        method: 'GET',
        data: {
            page: page,
            search: search
        },
        dataType: 'json',
        success: function(response) {
            // Update table body
            $('#tableBody').html(response.table_data);

            // Update pagination
            $('#pagination').html(response.pagination);
        },
        error: function(error) {
            // Handle error here
            console.log('Error:', error);
        }
    });
}
$(document).on('click', '.deleteButton', function() {
    var reqno = $(this).data('reqno');

    // Display a confirmation dialog using SweetAlert
    Swal.fire({
        title: 'Are you sure?',
        text: 'You are about to delete this record. ' + reqno,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // If user confirms, proceed with deletion
            deleteData(reqno);
        }
    });
});

function deleteData(reqno) {
    $.ajax({
        url: 'delete.php',
        method: 'POST',
        data: {
            reqno: reqno
        },
        dataType: 'json',
        success: function(response) {
            if (response.deleted) {
                // Show success message
                Swal.fire({
                    title: 'Deleted!',
                    text: 'The record has been deleted.',
                    icon: 'success'
                }).then(() => {
                    loadData($(this).data('page'), $('#searchInput').val());

                    // Reload data or update UI as needed
                });
            } else {
                // Show error message if deletion fails
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to delete the record.',
                    icon: 'error'
                });
            }
        },
        error: function(error) {
            // Handle AJAX error
            console.log('Error:', error);
            // Show error message using SweetAlert
            Swal.fire({
                title: 'Error!',
                text: 'An error occurred while processing your request.',
                icon: 'error'
            });
        }
    });
}

$(document).on('click', 'a.modal-link', function(e) {
    e.preventDefault(); // Prevent the default behavior of the link
    var reqno = $(this).data('reqno'); // Get the value of data-reqno attribute

    // Fetch data via AJAX
    $.ajax({
        url: 'fetch_modal_request.php',
        method: 'POST',
        data: {
            reqno: reqno
        },
        dataType: 'json',
        success: function(data) {
            // Call showModalWithData function with the received data
            showModalWithData(data);
        },
        error: function(error) {
            // Handle AJAX error
            console.log('Error:', error);
            // Show error message (if needed)
        }
    });
});


function showModalWithData(data) {
    // Use data fetched via AJAX to populate the modal content
    // For example, update modal elements with data properties
    $('#modalTitle').html(data.title);
    $('#table-body-modal').html(data.data_table);
    // Show the modal
    $('#myModal').modal('show');
    $('#department').text(data.dataDep);
    $('#dateR').text(data.dataDate);
    $('#RKname').val(data.rk_username);
    $('#RECname').val(data.rec_username);
    $('#REQname').val(data.req_username);

    if (data.status === '99') {
        $('#btn-status').removeClass('btn-outline-danger btn-outline-success').addClass('btn-secondary');
        $('#status').text('รออนุมัติ');
        $('#ap_by_modal').val('Pending for approval');
    } else if (data.status === '1') {
        $('#btn-status').removeClass('btn-secondary btn-outline-success').addClass('btn-outline-danger');
        $('#status').text('ไม่อนุมัติ');
        $('#ap_by_modal').val('Disapproved');
    } else {
        $('#btn-status').removeClass('btn-secondary btn-outline-danger').addClass('btn-outline-success');
        $('#status').text('อนุมัติ');
        $('#ap_by_modal').val(data.status);
    }
}
</script>