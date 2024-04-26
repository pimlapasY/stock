<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php include('header.php') ?>
</head>
<style>
.gradient-custom {
    /* fallback for old browsers */
    background: #6a11cb;

    /* Chrome 10-25, Safari 5.1-6 */
    background: -webkit-linear-gradient(to right, rgba(138, 209, 228, 1), rgba(37, 150, 190));

    /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
    background: linear-gradient(to right, rgba(138, 209, 228, 1), rgba(37, 150, 190))
}
</style>

<body>
    <section class="vh-100 gradient-custom">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-light text-dark" style="border-radius: 1rem;">
                        <div class="card-body p-5 text-center">
                            <div class="mb-md-5 mt-md-4 pb-5">
                                <i class="fa-solid fa-lock fa-3x m-3"></i>
                                <h5 class="fw-bold mb-2 text-uppercase">Reset
                                    password</h5>
                                <p class="text-dark-50 mb-5">You reset your password in here!</p>



                                <form id="resetPassForm" method="post">
                                    <!-- action="reset_process.php" -->
                                    <!-- <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"
                                                style="background-color:#9fc5e8; color:black;">
                                                <i class="fa-solid fa-user"></i>
                                            </div>
                                        </div>
                                        <input aria-label="Large" type="text" class="form-control"
                                            placeholder="Username" name="typeUserX" id="typeUserX"
                                            autocomplete="current-username">
                                    </div> -->

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"
                                                style="background-color:#9fc5e8; color:black;">
                                                <i class="fa-solid fa-envelope"></i>
                                            </div>
                                        </div>
                                        <input aria-label="Large" type="Email" class="form-control" placeholder="Email"
                                            name="typeEmailX" id="typeEmailX" autocomplete="current-email">
                                    </div>

                                    <br>
                                    <p class="small mb-5 pb-lg-2"><a class="text-dark-50" href="login.php">back to
                                            Login</a>
                                    </p>

                                    <button data-mdb-button-init data-mdb-ripple-init
                                        class="btn btn-primary btn-lg px-5" type="submit">Reset</button>
                                </form>



                                <!-- <div class="d-flex justify-content-center text-center mt-4 pt-1">
                                    <a href="#!" class="text-dark"><i class="fab fa-facebook-f fa-lg"></i></a>
                                    <a href="#!" class="text-dark"><i class="fab fa-twitter fa-lg mx-4 px-2"></i></a>
                                    <a href="#!" class="text-dark"><i class="fab fa-google fa-lg"></i></a>
                                </div> -->

                            </div>

                            <!-- <div>
                                <p class="mb-0">Don't have an account? <a href="#!" class="text-white-50 fw-bold">Sign
                                        Up</a>
                                </p>
                            </div> -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>