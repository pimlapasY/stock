<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Import CSV</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- นำเข้า excel csv -->
                <form action="list_product_import.php" method="post" enctype="multipart/form-data">
                    <label for="csvFile">Choose CSV file:</label>
                    <input type="file" name="csvFile" class="form-control" id="csvFile" accept=".csv" required><br>
                    <button name="import" class="btn btn-primary" onclick="showLoading()">
                        Import
                        CSV
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Preview Modal -->
<div id="previewModal" class="modal modal-xl modal fade" style="display:none; width:100%;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">
                    <i class="fa-solid fa-inbox fa-lg"></i> Preview Changes
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="margin: 50px;">
                <!--  <div class="d-flex justify-content-start w-50">
                        <p>PR CODE : </p>&nbsp;
                        <p><?php echo date('Ymd'); ?></p>
                    </div> -->
                <div class="d-flex justify-content-start w-50">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1"><b>Date</b></span>
                        <input class="form-control" type="date" value="" name="date_create" id="currentDate"
                            aria-describedby="basic-addon1" />
                    </div>
                    <script>
                        document.getElementById('currentDate').valueAsDate = new Date();
                    </script>

                </div>
                <div class="d-flex justify-content-end">
                    <h2 id="total">Total</h2>
                </div>
                <div class="d-flex justify-content-center">
                    <table class="table table-hover table-bordered table-sm" style="width: 100%;">
                        <!-- Table content -->
                        <thead class="text-center">
                            <tr class="table-light" style="vertical-align: middle;">
                                <th><?php echo $num; ?></th>
                                <th><?php echo $productCode; ?></th>
                                <th><?php echo $productName; ?></th>
                                <th><?php echo $options1_label; ?></th>
                                <th><?php echo $options2_label; ?></th>
                                <th><?php echo $options3_label; ?></th>
                                <th><?php echo $costPrice; ?></th>
                                <th><?php echo $costPrice . '(Vat%)'; ?></th>
                                <th><?php echo $qty; ?></th>
                                <th class="text-center" colspan="1">Store</th>
                                <th>Total Price</th>
                            </tr>
                        </thead>
                        <tbody class="modal-body" id="previewBody">
                            <!-- Preview content will be inserted here -->
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-start m-3" id="checkRadio">
                    <div class="form-check me-4">
                        <input class="form-check-input" type="radio" name="reason" id="flexRadioDefault1" value="1"
                            checked>
                        <label class="form-check-label" for="flexRadioDefault1">Purchased</label>
                    </div>
                    <div class="form-check me-4">
                        <input class="form-check-input" type="radio" name="reason" id="flexRadioDefault2" value="2">
                        <label class="form-check-label" for="flexRadioDefault2">Returned</label>
                    </div>
                </div>

                <div class="d-flex justify-content-start mt-3">
                    <textarea class="form-control w-50" name="memo" placeholder="memo"></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <input id="statusType" hidden>
                <button type="button" class="btn btn-secondary modal-footer" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="confirmStockInButton" onclick="confirmButton(1)"
                    hidden>Stock In</button>
                <button type="button" class="btn btn-success" id="confirmPRButton" onclick="confirmButton(2)" hidden>Add
                    PR</button>
            </div>
        </div>
    </div>
</div>