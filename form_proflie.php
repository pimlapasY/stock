<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
</head>



<body>
    <div class="d-flex flex-wrap">

        <?php include('navbar.php'); ?>
        <div class="container col-10 pt-5">
            <div class="row gutters">
                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="account-settings">
                                <div class="user-profile text-center p-3">
                                    <div class="user-avatar text-center">
                                        <?php 
                                    if($row['u_department'] == 'RD'){
                                    echo '<img src="img/D_RD.png" style="width:50%">';
                                    }else{
                                    echo '<img src="img/D_RD.png" style="width:50%">';
                                    }
                                    ?>
                                    </div>
                                    <h5 class="user-code"><?php echo $row['u_usercode'] ?>
                                        <?php echo $row['u_username'] ?>
                                    </h5>
                                    <p class="user-email"><?php echo $row['u_email'] ?></p>
                                </div>
                                <div class="about p-3">
                                    <h5 class="text-center">Department</h5>
                                    <p><i class="fa-solid fa-briefcase"></i> <?php echo $row['u_department'] ?></p>
                                    <p><i class="fa-solid fa-key"></i> <?php echo $row['u_status'] ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="row gutters">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <h5 class="mb-2 text-primary"><i class="fa-solid fa-circle-info"></i> Personal
                                        Details
                                    </h5>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="fullName">Full Name</label>
                                        <input type="text" class="form-control" id="fullName"
                                            value="<?php echo $row['u_username'] ?>">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="eMail">Email</label>
                                        <input type="email" class="form-control" id="eMail"
                                            value="<?php echo $row['u_email'] ?>">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="phone">Phone</label>
                                        <input type="text" class="form-control" id="phone"
                                            value="<?php echo $row['u_phone'] ?>">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="lineID"><i class="fa-brands fa-line"></i> Line ID</label>
                                        <input type="text" class="form-control" id="lineID"
                                            value="<?php echo $row['u_lineid'] ?>">
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row gutters">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <h5 class="mt-3 mb-2 text-primary"><i class="fa-solid fa-location-dot"></i> Address
                                    </h5>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="Street">Street</label>
                                        <input type="name" class="form-control" id="Street" placeholder="Enter Street">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="ciTy">City</label>
                                        <input type="name" class="form-control" id="ciTy" placeholder="Enter City">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="sTate">State</label>
                                        <input type="text" class="form-control" id="sTate" placeholder="Enter State">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="zIp">Zip Code</label>
                                        <input type="text" class="form-control" id="zIp" placeholder="Zip Code">
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row gutters">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <div class="text-end">
                                        <button type="button" id="submit" name="submit" class="btn btn-primary"
                                            disabled>Update</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>