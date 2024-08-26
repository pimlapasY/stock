<?php
include('connect.php'); // Include your connection script
session_start();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History</title>
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

    /* Add this CSS for printing */
    @media print {

        /* Hide all elements except the modal */
        body>*:not(.modal) {
            display: none !important;
            margin: 0 !important;
            padding: 0 !important;
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

        /* Set modal body to vertical */
        .modal-body {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

    }
    </style>
</head>

<body>
    <div class="d-flex flex-wrap">
        <?php include('navbar.php') ?>
        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn btn-primary" onclick="printModalContent()">
                            <i class="fa-solid fa-print"></i> PRINT
                        </button>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-header">
                        <table class="table table-borderless">
                            <tr>
                                <th class="text-center" colspan="6">
                                    <h3 class="card-title">
                                        <button type="button" class="btn btn-outline-secondary btn-lg w-50"
                                            data-mdb-ripple-init data-mdb-ripple-color="dark">SHIPPO ASAHI
                                            MOULDS(THAILAND)
                                            CO.,LTD.</button>
                                    </h3><br>
                                    <h3 class="card-title" style="text-transform: uppercase;"><?php echo $request ?>
                                    </h3>
                                </th>
                            </tr>
                            <tr>
                                <th colspan="3">
                                    <h5><?php echo $mat_goods ?></h5>
                                </th>
                                <th class="text-end" colspan="3">
                                    <a><?php echo "DATE: "; ?><a id="dateR"></a></a>
                                </th>
                            </tr>
                            <tr>
                                <th colspan="6">
                                    <div class="d-flex justify-content-between">
                                        <button type="button" class="btn btn-outline-secondary btn-rounded"
                                            data-mdb-ripple-init>
                                            <?php echo $department ?> : <a id="department"></a>
                                        </button>
                                        <button type="button" id="btn-status" class="btn btn-rounded"
                                            data-mdb-ripple-init>
                                            <?php echo $status ?> : <a id="status"></a>
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
                                        <th><?php echo $purpose ?></th>
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
                                                    <?php echo $REQUEST_NAME ?> :
                                                </button>
                                                <input class="form-control" id="REQname" aria-describedby="rq_name"
                                                    readonly />
                                            </div>
                                        </th>
                                        <th class="text-end">
                                            <div class="input-group mb-3">
                                                <button class="btn btn-outline-secondary" type="button" id="ap_by"
                                                    data-mdb-ripple-init data-mdb-ripple-color="dark">
                                                    <?php echo $APPROVED_BY ?> :
                                                </button>
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
                                                    <?php echo $STORE_KEEPER ?> :
                                                </button>
                                                <input class="form-control" id="RKname" aria-describedby="st_keeper"
                                                    readonly />
                                            </div>
                                        </th>
                                        <th class="text-end">
                                            <div class="input-group mb-3">
                                                <button class="btn btn-outline-secondary" type="button" id="gr_by"
                                                    data-mdb-ripple-init data-mdb-ripple-color="dark">
                                                    <?php echo $GOODS_RECEIVED_BY ?> :
                                                </button>
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
                        <button type="button" class="btn btn-secondary modal-footer"
                            data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="container pt-5 col-10">
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

            <?php if($_SESSION['role'] == 'admin'){?>
            <div class="d-flex justify-content-end p-3">
                <button id="updateSelected" class="btn btn-success rounded-8"><i
                        class="fa-solid fa-check-to-slot"></i>&nbsp;
                    approve</button>&nbsp;
                <button id="disapproveSelected" class="btn btn-danger rounded-8"><i
                        class="fa-solid fa-square-xmark"></i>&nbsp;
                    Disapproved</button>
            </div>
            <?php } ?>

            <table class="table table-hover align-middle mb-0" style="text-align:center; white-space: nowrap;">
                <!-- table-bordered -->
                <thead class="table-primary">
                    <tr class="uppercase">

                        <th colspan="2" class="text-center">
                            <?php if($_SESSION['role'] == 'admin'){?>
                            <button id="selectAll" class="btn btn-light">
                                <i class="fa-solid fa-check-double"></i>
                            </button>&nbsp;
                            <?php } ?>
                        </th>

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
    <script>
    $(document).ready(function() {
        loadData(1, '');

        $('#searchInput').on('input', function() {
            loadData(1, $(this).val());
        });

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
                $('#tableBody').html(response.table_data);
                $('#pagination').html(response.pagination);
            },
            error: function(error) {
                console.log('Error:', error);
            }
        });
    }

    $(document).on('click', '.deleteButton', function() {
        var reqno = $(this).data('reqno');
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
                deleteData(reqno); // Pass an array of reqno to deleteData function
            }
        });
    });

    function deleteData(reqno) {
        console.log(reqno);
        $.ajax({
            url: 'delete_good_quest.php',
            method: 'POST',
            data: {
                reqno: reqno // Pass the array of reqnos
            },
            dataType: 'json',
            //response ต้องถูกส่งมาเป็น reqno ถึงจะ delete สำเร็จ
            success: function(response) {
                console.log(response);
                Swal.fire({
                    title: 'Deleted!',
                    text: 'The records have been deleted.',
                    icon: 'success'
                }).then(() => {
                    loadData(1, $('#searchInput').val());
                });
                /* if (response.deleted) {
                   
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Failed to delete the records.',
                        icon: 'error'
                    });
                } */
            },
            error: function(xhr, status, error) {
                console.log('XHR status:', xhr.status);
                console.log('Error:', error);
                var errorMessage = '';

                // Check if the error is due to server-side processing
                if (xhr.status === 500) {
                    errorMessage = 'Server error. Please try again later.';
                } else {
                    errorMessage = 'An error occurred while processing your request. Please try again.';
                }

                Swal.fire({
                    title: 'Error!',
                    text: errorMessage,
                    icon: 'error'
                });
            }
        });
    }


    $(document).on('click', 'a.modal-link', function(e) {
        e.preventDefault();
        var reqno = $(this).data('reqno');
        $.ajax({
            url: 'fetch_modal_request.php',
            method: 'POST',
            data: {
                reqno: reqno
            },
            dataType: 'json',
            success: function(data) {
                showModalWithData(data);
            },
            error: function(error) {
                console.log('Error:', error);
            }
        });
    });

    function showModalWithData(data) {
        $('#modalTitle').html(data.title);
        $('#table-body-modal').html(data.data_table);
        $('#myModal').modal('show');
        $('#department').text(data.dataDep);
        $('#dateR').text(data.dataDate);
        $('#RKname').val(data.rk_username);
        $('#RECname').val(data.rec_username);
        $('#REQname').val(data.req_username);

        let statusClass = '';
        let statusText = '';

        if (data.status == null) {
            statusClass = 'btn-secondary';
            statusText = 'รออนุมัติ';
            $('#ap_by_modal').val('Pending for approval');
        } else if (data.status === '99') {
            statusClass = 'btn-outline-danger';
            statusText = 'ไม่อนุมัติ';
            $('#ap_by_modal').val('Disapproved');
        } else {
            statusClass = 'btn-outline-success';
            statusText = 'อนุมัติ';
            $('#ap_by_modal').val(data.prove_name);
        }

        $('#btn-status').removeClass('btn-secondary btn-outline-danger btn-outline-success').addClass(statusClass);
        $('#status').text(statusText);
    }

    function printModalContent() {
        $('.modal-header .btn-primary').hide();
        window.print();
        $('.modal-header .btn-primary').show();
    }
    </script>
</body>

</html>

<script>
$("#selectAll").click(function() {
    let isChecked = $(this).data("checked") || false;
    $(".checkbox").prop('checked', !isChecked);
    $(this).data("checked", !isChecked);
});

$(document).ready(function() {
    $('#updateSelected').on('click', function() {
        var selectedCheckboxes = $('.checkbox:checked');

        if (selectedCheckboxes.length > 0) {
            // Count the number of selected rows
            var selectedRowCount = selectedCheckboxes.length;

            // Show SweetAlert confirmation
            Swal.fire({
                title: 'Confirm to Approve',
                text: selectedRowCount + ' selected',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: 'green',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Proceed with the update
                    var reqNos = [];
                    selectedCheckboxes.each(function() {
                        reqNos.push($(this).data('reqno'));
                    });

                    $.ajax({
                        url: 'request_update.php',
                        method: 'POST',
                        data: {
                            reqnos: reqNos,
                            prove_username: <?php echo $_SESSION['id']; ?>
                        },
                        success: function(response) {
                            var result = JSON.parse(response);
                            if (result.status === 'success') {
                                Swal.fire({
                                    title: 'Success',
                                    text: 'Rows updated successfully!',
                                    icon: 'success'
                                }).then((result) => {
                                    // Reload the page after successful update
                                    location.reload();
                                });
                            } else {
                                console.error('Update failed: ' + result.message);
                            }
                        },
                        error: function(error) {
                            console.error('AJAX error: ', error);
                        }
                    });
                }
            });
        } else {
            Swal.fire({
                title: 'Can not update',
                text: 'Please select at least 1 item for approval',
                icon: 'error',
            })
            //alert('Please select at least one checkbox to update.');
        }
    });
});

$(document).ready(function() {
    $('#disapproveSelected').on('click', function() {
        var selectedCheckboxes = $('.checkbox:checked');

        if (selectedCheckboxes.length > 0) {
            // Count the number of selected rows
            var selectedRowCount = selectedCheckboxes.length;

            // Show SweetAlert confirmation
            Swal.fire({
                title: 'Confirm to Disapprove',
                text: selectedRowCount + ' selected',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'red',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Proceed with the update
                    var reqNos = [];
                    selectedCheckboxes.each(function() {
                        reqNos.push($(this).data('reqno'));
                    });

                    $.ajax({
                        url: 'request_update.php',
                        method: 'POST',
                        data: {
                            reqnos: reqNos,
                            prove_username: 99 // Change to disapprove
                        },
                        success: function(response) {
                            var result = JSON.parse(response);
                            if (result.status === 'success') {
                                Swal.fire({
                                    title: 'Success',
                                    text: 'Rows disapproved successfully!',
                                    icon: 'success'
                                }).then((result) => {
                                    // Reload the page after successful update
                                    location.reload();
                                });
                            } else {
                                console.error('Update failed: ' + result.message);
                            }
                        },
                        error: function(error) {
                            console.error('AJAX error: ', error);
                        }
                    });
                }
            });
        } else {
            Swal.fire({
                title: 'Can not update',
                text: 'Please select at least 1 item for disapprove',
                icon: 'error',
            })
            //alert('Please select at least one checkbox to disapprove.');
        }
    });
});
</script>