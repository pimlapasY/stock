<?php include('navbar.php') ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock In</title>
</head>

<body>
    <div class="container-fluid" style="margin-top: 150px;">
        <div class="card text-center m-5">
            <div class="card-header">
                <table class="table table-borderless">
                    <tr>
                        <th class="text-start" colspan="6">
                            <h1>
                                <i class="fa-solid fa-inbox fa-xl"></i> Stock In
                            </h1>
                        </th>
                    </tr>
                    <tr>
                        <td class="text-start" style="width: 150px; text-transform: uppercase;">
                            <!-- Large -->
                            <span class="badge bg-info text-dark">
                                <a class="nav-link link-light" href="list.php">
                                    <i class="fa-solid fa-plus"></i>&nbsp; <?php echo $stockList ?></a>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-start" style="text-transform: uppercase;">
                            <span class="badge bg-info text-dark">
                                <?php echo $stock_in ?>
                            </span>
                            <!-- Default checkbox -->
                        </td>

                        <td class="d-flex justify-content-start" style="vertical-align: middle;">
                            <!-- Default radio -->
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="check_purchased" name="check_stockIn"
                                    required>
                                <label class="form-check-label" for="check_purchased">
                                    <?php echo 'Purchased (Storage)' ?>
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="check_returned" name="check_stockIn">
                                <label class="form-check-label" for="check_returned">
                                    <?php echo 'Returned' ?>
                                </label>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr class="text-center table-info">
                            <th>No.</th>
                            <th><?php echo 'MG CODE' ?></th>
                            <th><?php echo $product_code ?></th>
                            <th><?php echo $product_name?></th>
                            <th><?php echo $unit ?></th>
                            <th><?php echo $color ?></th>
                            <th><?php echo $hands ?></th>
                            <th><?php echo $size  ?></th>
                            <th><?php echo $qty ?></th>
                            <th><?php echo 'Total price'  ?></th>
                            <th><?php echo 'Memo'  ?></th>
                            <th><?php echo $reset  ?></th>
                        </tr>
                    </thead>

                </table>
            </div>
            <div class="card-footer text-end">
                <button type="button" class="btn btn-success btn-lg" data-mdb-ripple-init><i
                        class="fa-solid fa-floppy-disk"></i> Submit</button>
            </div>
        </div>
    </div>
</body>

</html>