<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Currently Taken (Other)</title>
</head>
<!-- <style>
td {
    padding: 15px;
    /* ปรับค่าตามที่ต้องการ */
}

th {
    font-size: 15px;
    height: 70px;
    background-color: #E5F9E5;
}
</style> -->
<style>
    .modal-body {
        overflow-y: auto;
    }

    .custom-modal-size {
        max-width: 70%;
        /* Adjust the width as needed */
    }

    .table-responsive {
        overflow-x: auto;
    }

    @media (max-width: 576px) {
        .table-responsive {
            overflow-x: auto;
        }
    }
</style>

<body>
    <?php include('navbar.php') ?>

    <div class="table-responsive">
        <div class="container-fluid mt-5  pt-5 col-12">
            <h1><i class="fa-solid fa-database fa-xl"></i> <?php echo $cr_taken . ' (' . $other . ') ' ?></h1><br>
            <hr>
            <div class="d-flex justify-content-end">
                <!-- <div class="mb-2">
                <a href="#" class="btn btn-primary" id="allBtn"><i class="fa-solid fa-bars"></i> All</a>
                <a href="#" class="btn btn-danger" id="partSaleBtn"><i class="fa-solid fa-file-invoice-dollar"></i> Part
                    Sale</a>
            </div> -->
                <div class="mb-2">
                    <a href="#" id="previewPRSelectedBtn" class="btn btn-outline-info "><i
                            class="fa-solid fa-file-lines"></i> PR
                        create</a>
                    <a href="#" id="previewReturnedSelectedBtn" class="btn btn-outline-warning"><i
                            class="fa-solid fa-right-left"></i> Returned</a>
                    <a href="#" id="completedBtn" class="btn btn-outline-success"><i
                            class="fa-solid fa-file-circle-check"></i>
                        Completed</a>
                </div>
            </div>
            <div class="">
                <table class="table table-hover mx-auto table-sm">
                    <thead class="text-center table-secondary" style="text-transform: uppercase;">
                        <th><input type="checkbox" id="checkAll" class="form-check-input"></th>
                        <th>#</th>
                        <th><?php echo $store; ?></th>
                        <th><?php echo $code; ?></th>
                        <!-- <th>Stock out</th> -->
                        <th><?php echo $product; ?></th>
                        <!-- <th>Receipt date</th> -->
                        <!-- <th>Supplier</th> -->
                        <th><?php echo $options1_label; ?></th>
                        <th><?php echo $options2_label; ?></th>
                        <th><?php echo $options3_label; ?></th>
                        <th><?php echo $qty; ?></th>
                        <th><?php echo $soldDate; ?></th>
                        <th><?php echo $customer; ?></th>
                        <th><?php echo $paidBy; ?></th>
                        <th><?php echo $payment; ?></th>
                        <th><?php echo $delivery; ?></th>
                        <th><?php echo $prPo; ?></th>
                        <!-- <th>PO status</th> -->
                        <th><?php echo $memo; ?></th>
                    </thead>
                    <tbody id="dataTable">
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <!------------------------ Preview Modal -------------------------------------------------------------------->
    <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl mx-auto">
            <div class="modal-content">
                <div class="modal-header text-center" id="previewModalHeader">
                    <h5 class="modal-title" id="previewModalLabel">Preview Selected Items</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <?php
                // กำหนดวันที่ปัจจุบันในรูปแบบ 'Y-m-d'
                $currentDate = date('Y-m-d');
                ?>
                <div class="modal-body">
                    <label for="newDate">Date: </label>
                    <input id="newDate" type="date" class="form-control w-50 ms-1"
                        value="<?php echo $currentDate; ?>"><br>
                    <label for="memo">Memo: </label>
                    <textarea id="memo" class="form-control w-50" placeholder="memo"></textarea>
                </div>
                <div class="modal-body" id="previewModalBody">
                    <!-- Details will be populated here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info" id="makePR" hidden>PR CREATE</button>
                    <button type="button" class="btn btn-warning" id="returnButton" hidden>RETURN</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap Modal -->
    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background-color: wheat;">
                    <h5 class="modal-title" id="updateModalLabel"><i class="fa-solid fa-pen-to-square fa-lg"></i>
                        Payment and
                        Delivery</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="update-form">
                    <div class="modal-body">
                        <div class="mb-3">
                            <input type="hidden" id="product-id" name="product-id">
                            <label for="mg-code" class="form-label"><i class="fa-solid fa-barcode"></i> MG CODE:</label>
                            <input class="form-control" type="text" id="mg-code" name="mg-code" disabled>
                        </div>
                        <!-- Hidden field to store product ID -->
                        <div class="mb-3">
                            <div class="mb-3">
                                <label for="o_payment" class="form-label"><i
                                        class="fa-solid fa-hand-holding-dollar"></i> Payment Status:</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="o_payment" id="paymentSuccess"
                                        value="1">
                                    <label class="form-check-label" for="paymentSuccess">Payment Successful</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="o_payment" id="paymentPending"
                                        value="2">
                                    <label class="form-check-label" for="paymentPending">Payment Pending</label>
                                </div>
                            </div>

                            <!-- Add more options as needed -->
                        </div>
                        <div class="mb-3">
                            <label for="o_delivery" class="form-label"><i class="fa-solid fa-cart-flatbed"></i> Delivery
                                Status:</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="o_delivery" id="deliveryDelivered"
                                    value="1">
                                <label class="form-check-label" for="deliveryDelivered">Successfully Delivered</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="o_delivery" id="deliveryNotDelivered"
                                    value="2">
                                <label class="form-check-label" for="deliveryNotDelivered">Delivery Pending</label>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" onclick="updateData()">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="./currently_taken.js"></script>

    <!------------------------ Preview Modal -------------------------------------------------------------------->
</body>

</html>
<script>
    // Function to load data based on button clicked
    function loadData(reasons = null) {
        // Define the URL for the AJAX request
        var url = "currently_other_fetch.php";
        // Define the data to be sent
        var data = {
            reasons: reasons
        };
        // Perform an AJAX request
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            success: function(response) {
                // Replace the content of dataTable with the new data
                $('#dataTable').html(response);
            }
        });
    }
</script>