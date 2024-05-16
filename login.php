<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <?php include('header.php') ?>
</head>
<style>
body {
    margin-top: 0;
}

.gradient-custom {
    /* fallback for old browsers */
    background: #9F9FDC;

    /* Chrome 10-25, Safari 5.1-6 */
    background: -webkit-linear-gradient(to right, rgba(178, 178, 227, 1), rgba(228, 228, 238, 1));

    /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
    background: linear-gradient(to right, rgba(178, 178, 227, 1), rgba(228, 228, 238, 1));
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
                                <i class="fa-regular fa-circle-user fa-5x p-2"></i>
                                <h2 class="fw-bold mb-2 text-uppercase">Login</h2>
                                <p class="text-dark-50 mb-5">Please enter your login and password!</p>

                                <form id="loginForm" action="login_process.php" method="post">

                                    <form id="loginForm" action="login_process.php" method="post">
                                        <div class="input-group input-group-lg mb-3">
                                            <span class="input-group-text" id="inputGroup-sizing-lg"
                                                style="background-color:#9F9FDC; color:black;">
                                                <i class="fa-solid fa-user"></i>
                                            </span>
                                            <input aria-label="Large" class="form-control xl" placeholder="Username"
                                                type="text" name="typeUserX" id="typeUserX"
                                                autocomplete="current-username">
                                        </div>


                                        <div class="input-group input-group-lg">
                                            <div class="input-group input-group-lg mb-3">
                                                <span class="input-group-text" id="inputGroup-sizing-lg"
                                                    style="background-color:#9F9FDC; color:black;">
                                                    <i class="fa-solid fa-lock"></i>
                                                </span>
                                                <input aria-label="Large" class="form-control xl" placeholder="Password"
                                                    type="password" name="typePasswordX" id="typePasswordX"
                                                    autocomplete="current-password">

                                            </div>
                                        </div>

                                        <br>
                                        <p class="small mb-5 pb-lg-2"><a class="text-dark-50"
                                                href="forget_pass.php">Forgot
                                                password?</a>
                                        </p>

                                        <button data-mdb-button-init data-mdb-ripple-init
                                            class="btn btn-dark btn-lg px-5" type="submit">Login</button>
                                    </form>



                                    <div class="d-flex justify-content-center text-center mt-4 pt-1">
                                        <a href="#!" class="text-dark"><i class="fab fa-facebook-f fa-lg"></i></a>
                                        <a href="#!" class="text-dark"><i
                                                class="fab fa-twitter fa-lg mx-4 px-2"></i></a>
                                        <a href="#!" class="text-dark"><i class="fab fa-google fa-lg"></i></a>
                                    </div>
                            </div>

                            <div>
                                <p class="mb-0" style="font-size: 15px;"> © 2024 SAMT: R＆D SECTION, All Rights
                                    Reserved.</a>
                                </p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');

    loginForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the form from submitting in the traditional way

        const formData = new FormData(loginForm);

        fetch('login_process.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Login Successful',
                        text: 'Redirecting...',
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        window.location.href = 'index.php';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Login Failed',
                        text: data.message || 'Invalid username or password!',
                        showConfirmButton: true
                    });
                }
            })
            .catch(error => {
                //console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred. Please try again.',
                    showConfirmButton: true
                });
            });
    });
});
</script>