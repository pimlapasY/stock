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
        margin-top: 0 !important;
    }

    .gradient-custom {
        position: absolute;
        /* fallback for old browsers */
        background: #9F9FDC;

        /* Chrome 10-25, Safari 5.1-6 */
        background: -webkit-linear-gradient(to right, rgba(178, 178, 227, 1), rgba(228, 228, 238, 1));

        /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
        background: linear-gradient(to right, rgba(178, 178, 227, 1), rgba(228, 228, 238, 1));
    }
</style>
<!-- x'mas style -->
<style>
    /* ปรับ card ให้อยู่ในลำดับชั้นที่สูงกว่า */
    .card {
        height: fit-content;
        position: relative;
        /* ต้องใช้ relative เพื่อให้ z-index มีผล */
        z-index: 10;
        /* ระบุ z-index ให้อยู่เหนือหิมะ */
    }

    body {
        margin: 0;
        overflow: hidden;
        background: linear-gradient(to right, rgb(170, 170, 228), rgba(228, 228, 238, 1));
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    #snow-container {
        position: relative;
        width: 100%;
        height: 100%;
        overflow: hidden;
        z-index: 0;

    }

    .snowflake {
        position: absolute;
        color: white;
        font-size: 20px;
        opacity: 0.8;
    }

    .snow-build-up {
        position: absolute;
        bottom: 0;
        width: 100%;
        background: white;
        transition: all 0.1s;
    }
</style>

<body>
    <!-- <div id="snow-container">
        <canvas id="snow-build-up">
        </canvas>
    </div> -->
    <section class="position-absolute">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-50">
                <div class="col-10">
                    <div class="card w-100 mx-auto bg-light text-dark" style="border-radius: 1rem;">
                        <div class="card-body text-center">
                            <div class="">
                                <i class="fa-regular fa-circle-user fa-5x p-2"></i>
                                <h2 class="fw-bold mb-2 text-uppercase">Login</h2>
                                <p class="text-dark-50">Please enter your login and password!</p>

                                <form id="loginForm" action="login_process.php" method="post">

                                    <form id="loginForm" action="login_process.php" method="post">
                                        <div class="input-group  mb-3">
                                            <span class="input-group-text" id="inputGroup-sizing-lg"
                                                style="background-color:#9F9FDC; color:black;">
                                                <i class="fa-solid fa-user"></i>
                                            </span>
                                            <input aria-label="Large" class="form-control xl" placeholder="Username"
                                                type="text" name="typeUserX" id="typeUserX"
                                                autocomplete="current-username">
                                        </div>


                                        <div class="input-group ">
                                            <div class="input-group  mb-3">
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
                                        <!-- <p class="small"><a class="text-dark-50" href="forget_pass.php">Forgot
                                                password?</a>
                                        </p> -->

                                        <button data-mdb-button-init data-mdb-ripple-init class="btn btn-dark px-5"
                                            type="submit">Login</button>
                                    </form>

                                    <p class="mb-0 mt-5" style="font-size: 0.8rem;"> © 2024 SAMT: R＆D SECTION, All
                                        Rights
                                        Reserved.</a>
                                    </p>

                                    <!--  <eiv class="d-flex justify-content-center text-center mt-4 pt-1">
                                        <a href="#!" class="text-dark"><i class="fab fa-facebook-f fa-lg"></i></a>
                                        <a href="#!" class="text-dark"><i
                                                class="fab fa-twitter fa-lg mx-4 px-2"></i></a>
                                        <a href="#!" class="text-dark"><i class="fab fa-google fa-lg"></i></a>
                                    </eiv> -->
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
    /* x'mas js */
    const container = document.getElementById('snow-container');
    const snowBuildUpCanvas = document.getElementById('snow-build-up');
    const ctx = snowBuildUpCanvas.getContext('2d');
    const snowflakes = [];

    snowBuildUpCanvas.width = container.offsetWidth;
    snowBuildUpCanvas.height = container.offsetHeight;

    const snowLevels = new Array(snowBuildUpCanvas.width).fill(0);
    const maxSnowDepth = 80; // Limit snow depth to 100px

    const snowflakeCharacters = ['❄', '*', '❉', '❃', '❅'];

    let snowflakeCounter = 0; // Counter to throttle snowflake creation
    let snowflakeCreationRate;
    let isMelting = false; // Flag for melting phase

    // Determine creation rate based on screen size
    if (window.innerWidth > 768) {
        snowflakeCreationRate = 1; // More snowflakes on desktop
    } else {
        snowflakeCreationRate = 8; // Fewer snowflakes on mobile
    }

    let lastFrameTime = performance.now();

    function createSnowflake() {
        const snowflake = {
            x: Math.random() * snowBuildUpCanvas.width,
            y: -10,
            size: Math.random() * 20 + 10, // Adjust size to make the symbols more visible
            speed: Math.random() * 2 + 1,
            opacity: 1, // Initial opacity
            character: snowflakeCharacters[Math.floor(Math.random() * snowflakeCharacters
                .length)] // Random snowflake character
        };
        snowflakes.push(snowflake);
    }

    function smoothAccumulation() {
        // Smooth out the snow accumulation over time by averaging snow levels
        for (let i = 1; i < snowLevels.length - 1; i++) {
            snowLevels[i] = (snowLevels[i - 1] + snowLevels[i] + snowLevels[i + 1]) / 3;
        }
    }

    function drawSnowflakes(deltaTime) {
        ctx.clearRect(0, 0, snowBuildUpCanvas.width, snowBuildUpCanvas.height);

        // Draw buildup
        ctx.fillStyle = 'white';
        for (let x = 0; x < snowLevels.length; x++) {
            ctx.fillRect(x, snowBuildUpCanvas.height - snowLevels[x], 1, snowLevels[x]);
        }

        // Draw falling snowflakes
        ctx.fillStyle = 'white';
        snowflakes.forEach((snowflake, index) => {
            ctx.globalAlpha = snowflake.opacity; // Apply opacity to the snowflake
            ctx.font = `${snowflake.size}px sans-serif`; // Set the font size for the snowflake character
            ctx.fillText(snowflake.character, snowflake.x, snowflake.y); // Draw the snowflake character
            ctx.globalAlpha = 1; // Reset alpha

            if (!isMelting) {
                snowflake.y += snowflake.speed * deltaTime * 60; // Adjust speed by deltaTime
            } else {
                snowflake.speed *= 0.98; // Slow down snowflake
                snowflake.opacity *= 0.98; // Fade out snowflake
            }

            // If snowflake reaches the bottom, accumulate snow
            if (
                snowflake.y + snowflake.size / 2 >=
                snowBuildUpCanvas.height - snowLevels[Math.floor(snowflake.x)]
            ) {
                if (!isMelting) {
                    const snowflakeX = Math.floor(snowflake.x);
                    const snowflakeSize = snowflake.size / 2;

                    // Accumulate snow more smoothly across a range with a weighted falloff effect
                    const accumulationWidth = Math.ceil(snowflakeSize * 2);
                    for (let i = -accumulationWidth; i <= accumulationWidth; i++) {
                        const xIndex = Math.min(Math.max(snowflakeX + i, 0), snowLevels.length - 1);
                        const distance = Math.abs(i); // The distance from the center of the snowflake
                        const falloff = Math.exp(-distance / 5); // Gaussian falloff (smooth transition)

                        // Apply the falloff to the accumulation to smooth the edges
                        if (snowLevels[xIndex] < maxSnowDepth) {
                            snowLevels[xIndex] += snowflake.size / 4 *
                                falloff; // Quicker accumulation with smoothing
                        }
                    }

                    // Remove snowflake once it lands
                    snowflakes.splice(index, 1);
                }
            }
        });

        // Apply smoothing after every update
        smoothAccumulation();
    }

    function meltSnow() {
        let allMelted = true;
        for (let i = 0; i < snowLevels.length; i++) {
            if (snowLevels[i] > 0) {
                const meltRate = Math.random() * 0.3 + 0.1; // Varying melt rates for uneven melting
                snowLevels[i] -= meltRate; // Melt snow gradually
                if (snowLevels[i] < 0) snowLevels[i] = 0; // Prevent negative values
                allMelted = false;
            }
        }

        // Introduce additional random offsets to simulate flow and uneven melting
        for (let i = 0; i < snowLevels.length; i++) {
            if (i > 0 && i < snowLevels.length - 1) {
                const leftNeighbor = snowLevels[i - 1];
                const rightNeighbor = snowLevels[i + 1];
                const average = (leftNeighbor + rightNeighbor) / 2;

                // Adjust current level slightly toward the average of neighbors
                const adjustment = (average - snowLevels[i]) * 0.2;
                snowLevels[i] += adjustment;
            }
        }

        return allMelted;
    }

    function checkFullSnow() {
        return snowLevels.every(level => level >= maxSnowDepth);
    }

    function animate() {
        const currentTime = performance.now();
        const deltaTime = (currentTime - lastFrameTime) / 1000; // Time elapsed in seconds
        lastFrameTime = currentTime;

        if (!isMelting) {
            if (checkFullSnow()) {
                isMelting = true; // Start melting phase
            } else if (snowflakeCounter % snowflakeCreationRate === 0) {
                createSnowflake();
            }
            snowflakeCounter++;
        } else {
            if (meltSnow()) {
                // Reset state when snow is fully melted
                snowflakes.length = 0; // Clear all snowflakes
                snowLevels.fill(0); // Reset snow levels
                isMelting = false;
            }
        }

        drawSnowflakes(deltaTime);
        requestAnimationFrame(animate);
    }

    animate();
</script>
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