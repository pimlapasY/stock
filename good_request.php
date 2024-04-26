<?php
include('connect.php');
include('header.php');

// Check if 'id' key is set in the session
if(isset($_SESSION['id'])) {
    $userId = $_SESSION['id']; // Retrieve the value of 'id' key
    //echo "User ID: " . $userId . "<br>";

    try {
        // Step 3: Write SQL Query
        $sql = "SELECT * FROM user WHERE u_userid = :id";
        
        // Step 4: Prepare the statement
        $stmt = $pdo->prepare($sql);
        
        // Step 5: Bind parameters
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        
        // Step 6: Execute the Query
        $stmt->execute();
        
        
        // Step 7: Fetch Data
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
    }catch(PDOException $e){
        echo "Error: " . $e->getMessage();
    }
}else {
        echo "Session ID not set.";
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Good request</title>
    <?php include('header.php') ?>
</head>


<body>

    <?php include('navbar.php') ?>
    <div class="container">
        <div class="card text-center m-5">
            <div class="card-header">
                <table class="table table-borderless">
                    <tr>
                        <th class="text-center" colspan="6">
                            <!-- <h3 class="card-title">SHIPPO ASAHI MOULDS(THAILAND) CO.,LTD.</h3> -->
                            <h4 class="card-title"><button type="button" class="btn btn-primary btn-lg w-50"
                                    data-mdb-ripple-init data-mdb-ripple-color="dark">
                                    <p5><?php echo $request ?></p5>
                                </button>
                            </h4>
                        </th>
                    </tr>
                    <tr>
                        <th colspan="3">
                            <h5><?php echo $mat_goods ?></h5>
                        </th>
                        <th class="text-end" colspan="3">
                            <h5><?php echo "Date: " . date("d-m-y") ;?></h5>
                        </th>
                    </tr>
                    <tr>
                        <th colspan="6">
                            <button type="button" class="btn btn-secondary btn-rounded btn-lg" data-mdb-ripple-init>
                                <?php echo $department. ' : '. $row['u_deparment'] ?>
                            </button>
                        </th>
                    </tr>
                </table>
            </div>
            <div class="card-body">
                <table class="table ">
                    <thead>
                        <tr class="text-center table-primary">
                            <th>No.</th>
                            <th><?php echo $product_code ?></th>
                            <th><?php echo $product_name?></th>
                            <th><?php echo $qty ?></th>
                            <th><?php echo $unit ?></th>
                            <th><?php echo $color ?></th>
                            <th><?php echo $target  ?></th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider table-divider-color">
                        <?php
$count_row = 5; // Number of rows
for ($i = 1; $i <= $count_row; $i++) {
    echo '<tr>';
    echo '<th scope="row">' . $i . '</th>';
    echo '<td><input type="text" class="form-control"></td>';
    echo '<td><input type="text" class="form-control" disabled></td>';
    echo '<td><input type="number" min="1" class="form-control" value="1"></td>';
    echo '<td><input type="text" min="1" class="form-control" value=""></td>';
    echo '<td>';
    echo '<select class="form-select" aria-label="Default select example">';
    echo '<option selected>' . $color . '</option>';
    echo '<option value="1">black</option>';
    echo '<option value="2">white</option>';
    echo '</select>';
    echo '</td>';
    echo '<td>';
    echo '<select class="form-select" aria-label="Default select example">';
    echo '<option selected>' . $target .'</option>';
    echo '<option value="1">';
    echo '<span class="badge rounded-pill badge-warning">For Sale</span>';
    echo '</option>';
    echo '<option value="2">';
    echo '<span class="badge rounded-pill badge-warning">For Customer</span>';
    echo '</option>';
    echo '</select>';
    echo '</td>';
    echo '</tr>';
}
?>


                    </tbody>
                    <!-- <th colspan="8">ได้ตรวจสอบจำนวน และรายละเอียดต่างๆเรียบร้อยแล้ว</th> -->
                </table>
                <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th colspan="3">
                            <td>
                                <div class="d-flex justify-content-end">
                                    <input class="form-check-input" type="checkbox" id="check_recheck" value=""
                                        aria-label="..." />
                                    <span class="rounded-pill badge-warning">
                                        <h6>ได้ตรวจสอบจำนวน
                                            และรายละเอียดต่างๆเรียบร้อยแล้ว</h6>
                                    </span>
                                </div>
                            </td>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="3">
                                <div class="input-group mb-3">
                                    <button class="btn btn-secondary btn-lg" type="button"><?php echo $REQUEST_NAME ?>
                                        :</button>
                                    <select class="form-select">
                                        <option selected>Select an option</option>
                                        <option value="1">Dear</option>
                                        <option value="2">Show</option>
                                    </select>
                                </div>
                            </th>
                            <th class="text-end">
                                <div class="input-group mb-3">
                                    <button class="btn btn-secondary btn-lg" type="button"><?php echo $APPROVED_BY ?>
                                        :</button>
                                    <select class="form-select">
                                        <option selected>Select an option</option>
                                        <option value="1">Dear</option>
                                        <option value="2">Show</option>
                                    </select>
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="3">
                                <div class="input-group mb-3">
                                    <button class="btn btn-secondary btn-lg" type="button"><?php echo $STORE_KEEPER ?>
                                        :</button>
                                    <select class="form-select">
                                        <option selected>Select an option</option>
                                        <option value="1">Dear</option>
                                        <option value="2">Show</option>
                                    </select>
                                </div>
                            </th>
                            <th class="text-end">
                                <div class="input-group mb-3">
                                    <button class="btn btn-secondary btn-lg"
                                        type="button"><?php echo $GOODS_RECEIVED_BY ?> :</button>
                                    <select class="form-select">
                                        <option selected>Select an option</option>
                                        <option value="1">Dear</option>
                                        <option value="2">Show</option>
                                    </select>
                                </div>
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="card-footer text-end">
                <button type="button" class="btn btn-success" data-mdb-ripple-init>Submit</button>
                <button type="button" class="btn btn-warning" data-mdb-ripple-init>Reset</button>
            </div>
        </div>
    </div>
</body>

</html>