<?php 
include('header.php'); 
include('connect.php');
?>

<head>
    <script>
    document.title += " | Saiko 0.1";
    </script>
</head>
<style>
::-webkit-scrollbar {
    width: 10px;
    height: 100%;
}


/* Track */
::-webkit-scrollbar-track {
    /* background: #ffffff; */
}

/* Handle */
::-webkit-scrollbar-thumb {
    background: rgba(226, 222, 234, 1);
    /* ให้สีมีความโปร่งใสบางส่วนเพื่อให้เห็นตำแหน่ง scrollbar */
    transition: background 0.3s ease-in-out;
    /* เพิ่มการเปลี่ยนแปลงความโปร่งใสแบบนุ่มนวล */
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
    background: rgba(85, 85, 85, 0.3);
    /* ค่อยๆ เปลี่ยนเป็นสีที่เข้มขึ้นเมื่อ hover */
}

* {
    font-family: 'Noto Sans Thai Looped', sans-serif;

}

.dropdown-item.active,
.dropdown-item:active {
    background-color: #fff !important;
}

#myBtn {
    display: none;
    /* Hidden by default */
    position: fixed;
    /* Fixed/sticky position */
    bottom: 20px;
    /* Place the button at the bottom of the page */
    right: 30px;
    /* Place the button 30px from the right */
    z-index: 1051;
    /* ตั้งให้สูงกว่า navbar */
    /* Make sure it does not overlap */
    border: none;
    /* Remove borders */
    outline: none;
    /* Remove outline */
    background-color: #e7e7f0;
    /* Set a background color */
    color: black;
    /* Text color */
    cursor: pointer;
    /* Add a mouse pointer on hover */
    padding: 15px;
    /* Some padding */
    /* Rounded corners */
    font-size: 20px;
    /* Increase font size */
}

#myBtn:hover {
    background-color: #555;
    /* Add a dark-grey background on hover */
    color: white;
}

.profile-icon {
    width: 40px;
    /* ขนาดรูปภาพ */
    height: 40px;
    border-radius: 50%;
    /* ทำให้เป็นวงกลม */
    object-fit: cover;
    /* ให้รูปภาพครอบคลุมพื้นที่ */
    margin-right: 8px;
    /* ระยะห่างระหว่างรูปภาพและข้อความ */
    vertical-align: middle;
    border: 2px solid #ffff;
}

.nav-color {
    background: linear-gradient(to right, #e6e6ec, #f2f2f7, #f7f7fb);
    /* background-color: white; */
    width: 250px !important;
}

.nav-color-top {
    background: linear-gradient(to right, #e6e6ec, #f2f2f7, #f7f7fb);

}

.navbar-nav .nav-link .dropdown-item {
    text-transform: capitalize;

    /* color: white; */
}

.font-bold {
    font-weight: bold;
}

.font-nav {
    font-size: 1vh;
}

.navbar-nav .dropdown-menu {
    position: relative;
}

.dropdown-menu.show {
    display: contents;
}

.navbar-nav .nav-item {
    flex-basis: auto;
    display: flex;
    /*  color: antiquewhite; */
    /* Add this ine to add underline */
    white-space: nowrap;
    /* gap: 20px; */
    flex-direction: column;

}


.nav-link:hover {
    /* border-bottom: 1px solid; */
    font-weight: bold;

}

.font-weight-bold {
    font-weight: bold;
}

.toggle-arrow {
    cursor: pointer;
    font-size: 24px;
    position: fixed;
    top: 50%;
    left: 10px;
    transform: translateY(-50%);
    z-index: 1050;
    /* Higher than Offcanvas */
}

.nav-custom {
    /* Keeps the nav element fixed on the screen */
    width: 100%;
    /* Sets the width to fit the content with the maximum possible size */
    /* padding: 3vh; */
    /* Adds padding around the content */
    line-height: 35px;
    /* Sets the space between lines of text */
    /* box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.25); */
    /* Adds a shadow around the nav element */
    overflow-x: scroll;
    /* Allows horizontal scrolling if the content overflows */
    /* Removes any top margin with !important to override other styles */
    /* ค่า modal คือ 1050 */
    /* z-index: 1060; */

}
</style>
<!-- Navbar -->
<?php
// Set the current page based on the PHP_SELF server variable
$currentPage = basename($_SERVER['PHP_SELF']);



if ($_SESSION["lang"] == "en" || !isset($_SESSION["lang"])) {
    include("lang/lang_en.php");
    $activeLang = "activeEN";
} else {
    include("lang/lang_th.php");
    $activeLang = "activeTH";
}

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
<!-- <div class="collapse" id="navbarToggleExternalContent">
    <div class="bg-dark p-4">
        <h5 class="text-white h4">Collapsed content</h5>
        <span class="text-muted">Toggleable via the navbar brand.</span>
    </div>
</div> -->
<!-- Navbar -->
<!-- Container wrapper -->


<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top nav-color-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="#" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling"
            aria-controls="offcanvasScrolling">
            <i class="fa-solid fa-boxes-stacked"></i> &nbsp; ARCANA
        </a>
        <ul class="navbar-nav ms-3">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="index.php">Home</a>
            </li>
        </ul>
        <div class="collapse navbar-collapse justify-content-end align-content-center" id="navbarNavDropdown">
            <button type="button" class="btn btn-tertiary me-5">
                <a class="btn <?php echo $activeLang == 'activeTH' ? 'btn-secondary' : 'btn-link'  ?>" id="jp"
                    href="lang/change_lang.php?lang=TH">
                    <i class="flag flag-th"></i>TH
                </a>
                <a class="btn  <?php echo $activeLang == 'activeEN' ? 'btn-secondary' : 'btn-link'  ?> " id="en"
                    href="lang/change_lang.php?lang=en">
                    <i class="flag flag-us"></i>EN
                </a>
            </button>&nbsp;

            <?php if($_SESSION['role'] == 'Dev'  || $_SESSION['role'] == 'Test'){ ?>
            <ul class="navbar-nav me-5">
                <li class="nav-item text-primary">
                    <a class="btn btn-link">
                        <?php echo 'Test Mode'; ?>
                    </a>
                </li>
            </ul>&nbsp;
            <?php } ?>

            <ul class="navbar-nav me-5">
                <li class="nav-item text-primary">
                    <a class="nav-link <?php echo ($currentPage == 'form_proflie.php') ? 'active' : ''; ?>"
                        href="form_proflie.php" id="navbar_user" role="button" style="text-transform: uppercase;">
                        <?php
                        if(isset( $row['u_img'] )){
                            echo "<img class='profile-icon' src='img/" . htmlspecialchars($row['u_img']) . "' alt='Profile Image'>";
                        }else{
                            echo "<img class='profile-icon' src='img/D_RD.png' alt='Profile Image'>";
                        }
                        echo $row['u_username'] ?> !
                    </a>
                </li>
            </ul>&nbsp;

            <!-- Left links -->
            <a href="#" class="btn-outline-dark btn" id="logoutBtn" type="button" data-mdb-ripple-init
                style="display:block;">
                <i class="fa-solid fa-right-from-bracket"></i>
                <?php echo $logout ?>
            </a>
        </div>
    </div>
</nav>
<button onclick="topFunction()" id="myBtn" title="Go to top">
    <i class="fa-solid fa-arrow-up"></i> Top
</button>

<div class="d-flex align-items-center" style="height: 100vh; position:fixed">
    <button style="height: 200px;" class="btn btn-secondary" type="button" data-bs-toggle="offcanvas"
        data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling">
        <i class="fa-solid fa-angles-right fa-xl"></i>
    </button>
</div>
<!--  data-bs-backdrop="false" -->
<div class="offcanvas offcanvas-start nav-color" data-bs-scroll="true" tabindex="-1" id="offcanvasScrolling"
    aria-labelledby="offcanvasScrollingLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title"><i class="fa-solid fa-boxes-stacked"></i> ARCANA</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0">
        <div class="col-12">
            <div class="container-fluid mx-auto h-100 nav-custom">
                <!-- Collapsible wrapper -->
                <div id="navbarSupportedContent">
                    <!-- <div class="d-flex flex-column justify-content-center align-items-center">
                        <ul class="navbar-nav">
                            <li class="nav-item text-primary">
                                <a class="nav-link font-weight-bold <?php echo ($currentPage == 'form_proflie.php') ? ' active' : ''; ?>"
                                    href='form_proflie.php' id="navbar_user" role="button"
                                    style="text-transform: uppercase;">
                                    <?php echo $row['u_username'] ?>
                                </a>
                            </li>
                        </ul>
                        <button type="button" class="btn btn-tertiary">
                            <a class="btn <?php echo $activeLang == 'activeTH' ? 'btn-secondary' : 'btn-link'  ?> me-3"
                                id="jp" href="lang/change_lang.php?lang=TH">
                                <i class="flag flag-th"></i>TH
                            </a>
                            <a class="btn  <?php echo $activeLang == 'activeEN' ? 'btn-secondary' : 'btn-link'  ?> "
                                id="en" href="lang/change_lang.php?lang=en">
                                <i class="flag flag-us"></i>EN
                            </a>
                        </button>
                    </div> -->


                    <hr>
                    <a class="navbar-brand p-3 justify-content-center font-weight-bold" href="index.php">
                        <i class="fa-solid fa-chart-line fa-lg"></i>&nbsp;
                        <!--  <img src="img/icon-stock6.png" alt="Logo" style="width: 50px;" class="d-inline-block align-text-top"> -->
                        HOME
                    </a>
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle <?php echo ($currentPage == 'stock_list.php' || $currentPage == 'stock_samt.php' || $currentPage == 'stock_other.php') ? 'active font-weight-bold text-primary ' : ''; ?>"
                                href="#" id="navbar1" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-box"></i> <?php echo $stockList ?>
                            </a>
                            <ul class="dropdown-menu <?php echo ($currentPage == 'stock_list.php' || $currentPage == 'stock_samt.php' || $currentPage == 'stock_other.php') ? 'show' : ''; ?>"
                                aria-labelledby="navbar1">
                                <li><a class="dropdown-item <?php echo ($currentPage == 'stock_list.php') ? 'active' : ''; ?>"
                                        href="stock_list.php"><?php echo $all ?></a></li>
                                <li><a class="dropdown-item <?php echo ($currentPage == 'stock_samt.php') ? 'active' : ''; ?>"
                                        href="stock_samt.php">SAMT</a></li>
                                <li><a class="dropdown-item <?php echo ($currentPage == 'stock_other.php') ? 'active' : ''; ?>"
                                        href="stock_other.php"><?php echo $other ?></a></li>
                            </ul>
                        </li>
                    </ul>

                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle <?php echo ($currentPage == 'currently_taken.php' || $currentPage == 'currently_samt.php' || $currentPage == 'currently_other.php') ? 'active font-weight-bold text-primary' : ''; ?>"
                                href="#" id="navbar2" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-database"></i> <?php echo $cr_taken ?>
                            </a>
                            <ul class="dropdown-menu <?php echo ($currentPage == 'currently_taken.php' || $currentPage == 'currently_samt.php' || $currentPage == 'currently_other.php') ? 'show' : ''; ?>"
                                aria-labelledby="navbar2">
                                <li><a class="dropdown-item  <?php echo ($currentPage == 'currently_taken.php') ? 'active' : ''; ?>"
                                        href="currently_taken.php"><?php echo $all ?></a></li>
                                <li><a class="dropdown-item <?php echo ($currentPage == 'currently_samt.php') ? 'active' : ''; ?>"
                                        href="currently_samt.php">SAMT</a></li>
                                <li><a class="dropdown-item <?php echo ($currentPage == 'currently_other.php') ? 'active' : ''; ?>"
                                        href="currently_other.php"><?php echo $other ?></a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle <?php echo ($currentPage == 'pr_create.php' || $currentPage == 'pr_management.php' || $currentPage == 'pr_history.php' || $currentPage == 'pr_exchange.php') ? 'active font-weight-bold text-primary' : ''; ?>"
                                href="#" id="navbar4" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-file-circle-plus"></i>
                                <?php echo 'PR' ?>
                            </a>
                            <ul class="dropdown-menu <?php echo ($currentPage == 'pr_create.php' || $currentPage == 'pr_management.php' || $currentPage == 'pr_history.php' || $currentPage == 'pr_exchange.php') ? 'show' : ''; ?>"
                                aria-labelledby="navbar4">
                                <li><a class="dropdown-item <?php echo ($currentPage == 'pr_create.php') ? 'active' : ''; ?>"
                                        href="pr_create.php"><?php echo $pr_add ?></a></li>
                                <li><a class="dropdown-item <?php echo ($currentPage == 'pr_management.php') ? 'active' : ''; ?>"
                                        href="pr_management.php"><?php echo $pr_manage ?></a></li>
                                <li><a class="dropdown-item <?php echo ($currentPage == 'pr_exchange.php') ? 'active' : ''; ?>"
                                        href="pr_exchange.php">PR Exchange</a></li>
                                <li><a class="dropdown-item <?php echo ($currentPage == 'pr_history.php') ? 'active' : ''; ?>"
                                        href="pr_history.php"><?php echo 'PR History' ?></a></li>
                            </ul>
                        </li>
                    </ul>

                    <!-- <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($currentPage == 'his_all.php') ? 'active font-weight-bold text-primary' : ''; ?>"
                        href="his_all.php" id="nav3">
                        <i class="fa-solid fa-laptop-medical"></i> <?php echo  $history ?>
                    </a>
                </li>
            </ul> -->


                    <!-- <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php echo ($currentPage == 'good_request.php' || $currentPage == 'g_history.php') ? 'active font-weight-bold text-primary' : ''; ?>"
                        href="#" id="navbar5" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-check-to-slot"></i>
                        <?php echo $request ?>
                    </a>
                    <ul class="dropdown-menu <?php echo ($currentPage == 'good_request.php' || $currentPage == 'g_history.php') ? 'show' : ''; ?>"
                        aria-labelledby="navbar5">
                        <li><a class="dropdown-item <?php echo ($currentPage == 'good_request.php') ? 'active' : ''; ?>"
                                href="good_request.php"><?php echo $request ?></a></li>
                        <li><a class="dropdown-item <?php echo ($currentPage == 'g_history.php') ? 'active' : ''; ?>"
                                id="dropdownItem2" href="g_history.php"><?php echo $history ?></a></li>
                    </ul>
                </li>
            </ul> -->

                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle <?php echo ($currentPage == 'stock_in.php' || $currentPage == 'stock_in_his.php') ? 'active font-weight-bold text-primary' : ''; ?>"
                                href="#" id="navbar6" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-inbox"></i> <?php echo $stock_in ?>
                            </a>

                            <ul class="dropdown-menu <?php echo ($currentPage == 'stock_in.php' || $currentPage == 'stock_in_his.php') ? 'show' : ''; ?>"
                                id="dropdownMenu6" aria-labelledby="navbar6">
                                <li><a class="dropdown-item <?php echo ($currentPage == 'stock_in.php') ? 'active' : ''; ?>"
                                        href="stock_in.php"><?php echo $stock_in ?></a></li>
                                <li><a class="dropdown-item <?php echo ($currentPage == 'stock_in_his.php') ? 'active' : ''; ?>"
                                        href="stock_in_his.php"><?php echo 'StockIn History' ?></a></li>
                            </ul>
                        </li>
                    </ul>

                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle <?php echo ($currentPage == 'stock_out.php' || $currentPage == 'his_all.php') ? 'active font-weight-bold text-primary' : ''; ?>"
                                href="#" id="navbar7" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-money-bill-transfer"></i> <?php echo $stock_out ?>
                            </a>

                            <ul class="dropdown-menu <?php echo ($currentPage == 'stock_out.php' || $currentPage == 'his_all.php') ? 'show' : ''; ?>"
                                id="dropdownMenu7" aria-labelledby="navbar7">
                                <li><a class="dropdown-item <?php echo ($currentPage == 'stock_out.php') ? 'active' : ''; ?>"
                                        href="stock_out.php"><?php echo $stock_out ?></a></li>
                                <li><a class="dropdown-item <?php echo ($currentPage == 'his_all.php') ? 'active' : ''; ?>"
                                        href="his_all.php"> <?php echo $history ?></a>
                                </li>
                            </ul>
                        </li>
                    </ul>


                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($currentPage == 'register.php') ? 'active font-weight-bold text-primary' : ''; ?>"
                                href="register.php" id="navbar8" role="button">
                                <i class="fa-solid fa-circle-plus"></i>
                                <?php echo $register ?>
                            </a>
                        </li>
                    </ul>

                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($currentPage == 'list.php') ? 'active font-weight-bold text-primary' : ''; ?>"
                                href="list.php" id="navbar9" role="button">
                                <i class="fa-solid fa-list"></i>
                                <?php echo $list ?>
                            </a>
                        </li>
                    </ul>
                    <hr>

                </div>
                <!-- Left links -->

                <!-- Collapsible wrapper -->

                <!-- Right elements -->
            </div>
        </div>
    </div>

    <!-- Navbar -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Logout button click event
        const logoutBtn = document.getElementById('logoutBtn');
        logoutBtn.addEventListener('click', function() {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You will be logged out!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '',
                cancelButtonColor: 'gray',
                confirmButtonText: 'Yes, logout!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Call the logout function via AJAX
                    logoutUser();
                }
            });
        });

        // Logout function using AJAX
        function logoutUser() {
            // Create an AJAX request
            let xhr = new XMLHttpRequest();

            // Configure the request
            xhr.open('POST', 'logout.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

            // Send the request
            xhr.send();

            // Handle the response
            xhr.onload = function() {
                if (this.status == 200) {
                    let response = JSON.parse(this.responseText);

                    if (response.success) {
                        // Show success message for 3 seconds
                        Swal.fire({
                            icon: 'success',
                            title: 'Logout Successful',
                            showConfirmButton: false,
                            timer: 1000 // Display for 3 seconds
                        }).then(() => {
                            // Redirect to login page after alert is closed
                            window.location.href = 'login.php';
                        });
                    } else {
                        // Show error message
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Logout failed!',
                        });
                    }
                }
            };
        }

    });

    // Add event listener to the submit button
    $(document).ready(function() {
        $('button[type="submit"]').click(function(e) {
            e.preventDefault(); // Prevent default form submission

            // Show SweetAlert confirmation dialog
            swal({
                    title: "Are you sure?",
                    text: "Once submitted, you will not be able to edit this request!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willSubmit) => {
                    if (willSubmit) {
                        // If user confirms, proceed with form submission
                        submitForm();
                    } else {
                        // If user cancels, do nothing
                    }
                });
        });
    });

    // Function to submit the form
    function submitForm() {
        // Here you can submit the form using AJAX or any other method
        // Example AJAX submission:
        $.ajax({
            url: "submit_form.php", // Replace "submit_form.php" with your actual form submission URL
            method: "POST",
            data: {
                // Include your form data here
                r_department: "<?php echo $row['u_department']; ?>",
                r_product_code: "<?php echo $_POST['p_name' . $i]; ?>",
                r_qty: "<?php echo $_POST['qtyValue' . $i]; ?>",
                r_unit: "<?php echo $_POST['unit' . $i]; ?>",
                r_rec_date: "<?php echo date("Y-m-d"); ?>",
                r_rec_username: "<?php echo $row['u_username']; ?>"
            },
            success: function(response) {
                // You can show a success message or redirect the user
                console.log("Form submitted successfully!");
            },
            error: function(xhr, status, error) {
                // Handle errors
                console.error("Error submitting form:", error);
            }
        });
    }

    // JavaScript to show dropdown menu on hover
    /* $(document).ready(function() {
        $('.nav-item.dropdown').hover(function() {
            $(this).find('.').stop(true, true).delay(200).fadeIn(500);
        }, function() {
            $(this).find('.').stop(true, true).delay(200).fadeOut(500);
        });
        $('.dropdown-toggle').click(function() {
            $(this).toggleClass('font-bold'); // Toggle class to make font bold
        });
    }); */

    // Get the button:
    let mybutton = document.getElementById("myBtn");

    // When the user scrolls down 20px from the top of the document, show the button
    window.onscroll = function() {
        scrollFunction()
    };

    function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            mybutton.style.display = "block";
        } else {
            mybutton.style.display = "none";
        }
    }

    // When the user clicks on the button, scroll to the top of the document
    function topFunction() {
        document.body.scrollTop = 0; // For Safari
        document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
    }
    </script>
</div>

<!-- <script>
const offcanvasScrolling = document.getElementById('offcanvasScrolling');
const bsOffcanvas = new bootstrap.Offcanvas(offcanvasScrolling);

// ตรวจสอบสถานะล่าสุดของ Offcanvas
if (localStorage.getItem('offcanvasState') === 'show') {
    bsOffcanvas.show();
} else {
    bsOffcanvas.hide();
}

// เก็บสถานะเมื่อเปิดหรือปิด Offcanvas
offcanvasScrolling.addEventListener('hidden.bs.offcanvas', () => {
    localStorage.setItem('offcanvasState', 'hide');
});

offcanvasScrolling.addEventListener('shown.bs.offcanvas', () => {
    localStorage.setItem('offcanvasState', 'show');
});
</script> -->

<script>
const offcanvasScrolling = document.getElementById('offcanvasScrolling');
const bsOffcanvas = new bootstrap.Offcanvas(offcanvasScrolling);

// ตรวจสอบสถานะล่าสุดของ Offcanvas และกำหนดค่าเริ่มต้นให้โชว์เมนู
if (localStorage.getItem('offcanvasState') === 'hide') {
    bsOffcanvas.hide();
} else {
    bsOffcanvas.show();
    localStorage.setItem('offcanvasState', 'show'); // ตั้งค่าเริ่มต้นให้โชว์เมนู
}

// เก็บสถานะเมื่อเปิดหรือปิด Offcanvas
offcanvasScrolling.addEventListener('hidden.bs.offcanvas', () => {
    localStorage.setItem('offcanvasState', 'hide');
});

offcanvasScrolling.addEventListener('shown.bs.offcanvas', () => {
    localStorage.setItem('offcanvasState', 'show');
});
</script>