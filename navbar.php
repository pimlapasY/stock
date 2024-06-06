<?php 
include('header.php'); 
include('connect.php');
?>

<style>
/* width */
::-webkit-scrollbar {
    width: 10px;
}

/* Track */
::-webkit-scrollbar-track {
    background: #f1f1f1;
}

/* Handle */
::-webkit-scrollbar-thumb {
    background: #888;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
    background: #555;
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
    z-index: 99;
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
    border-radius: 50px;
    /* Rounded corners */
    font-size: 18px;
    /* Increase font size */
}

#myBtn:hover {
    background-color: #555;
    /* Add a dark-grey background on hover */
    color: white;
}

.dropdown-item {
    text-transform: capitalize;
}

.navbar-nav .dropdown:hover .dropdown-menu {
    display: block;
}

.nav-color {
    background: linear-gradient(to right, #cdcdd6, #e4e4ee, #e7e7f0);
}

.position-left {
    left: 0;
    /* Position the dropdown menu to the left */
}


.navbar-nav .nav-link {
    text-transform: capitalize;
    /* color: white; */
}

.font-bold {
    font-weight: bold;
}


.navbar-nav .nav-item .nav-link.active {
    /*  color: antiquewhite; */
    font-weight: bold;
    border-bottom: 2px solid;
    /* Add this line to add underline */
}

.nav-link:hover {
    border-bottom: 1px solid;
}

.navbar-nav .nav-item {
    /* color: white; */
    /* กำหนดสีข้อความเป็นสีขาว */
}
</style>
<!-- Navbar -->
<?php
// Set the current page based on the PHP_SELF server variable
$currentPage = basename($_SERVER['PHP_SELF']);



if ($_SESSION["lang"] == "en" || !isset($_SESSION["lang"])) {
    include("lang/lang_en.php");
} else {
    include("lang/lang_th.php");
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
<div class="collapse" id="navbarToggleExternalContent">
    <div class="bg-dark p-4">
        <h5 class="text-white h4">Collapsed content</h5>
        <span class="text-muted">Toggleable via the navbar brand.</span>
    </div>
</div>
<!-- Navbar -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg navbar-light fixed-top nav-color">
    <!-- Container wrapper -->
    <div class="container-fluid">
        <!-- Collapsible wrapper -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <a class="navbar-brand" href="index.php">
                <i class="fa-solid fa-chart-line fa-lg"></i>&nbsp;
                <!--  <img src="img/icon-stock6.png" alt="Logo" style="width: 50px;" class="d-inline-block align-text-top"> -->
                HOME
            </a>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php echo ($currentPage == 'stock_list.php' || $currentPage == 'stock_samt.php' || $currentPage == 'stock_sakaba.php') ? 'active' : ''; ?>"
                        href="#" id="navbar1" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-box"></i> <?php echo $stockList ?>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbar1">
                        <li><a class="dropdown-item <?php echo ($currentPage == 'stock_list.php') ? 'active' : ''; ?>"
                                href="stock_list.php">All</a></li>
                        <li><a class="dropdown-item <?php echo ($currentPage == 'stock_samt.php') ? 'active' : ''; ?>"
                                href="stock_samt.php">SAMT</a></li>
                        <li><a class="dropdown-item <?php echo ($currentPage == 'stock_sakaba.php') ? 'active' : ''; ?>"
                                href="stock_sakaba.php">SAKABA</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php echo ($currentPage == 'currently_taken.php' || $currentPage == 'currently_samt.php' || $currentPage == 'currently_sakaba.php') ? 'active' : ''; ?>"
                        href="#" id="navbar2" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-database"></i> <?php echo 'Currently Taken' ?>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbar1">
                        <li><a class="dropdown-item  <?php echo ($currentPage == 'currently_taken.php') ? 'active' : ''; ?>"
                                href="currently_taken.php">All</a></li>
                        <li><a class="dropdown-item <?php echo ($currentPage == 'currently_samt.php') ? 'active' : ''; ?>"
                                href="currently_samt.php">SAMT</a></li>
                        <li><a class="dropdown-item <?php echo ($currentPage == 'currently_sakaba.php') ? 'active' : ''; ?>"
                                href="currently_sakaba.php">SAKABA</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php echo ($currentPage == 'his_all.php' || $currentPage =='his_samt.php' || $currentPage == 'his_sakaba.php') ? 'active' : ''; ?>"
                        href="#" id="navbar3" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-laptop-medical"></i> <?php echo $history ?>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbar3">
                        <li><a class="dropdown-item  <?php echo ($currentPage == 'his_all.php') ? 'active' : ''; ?>"
                                href="his_all.php">All</a></li>
                        <li><a class="dropdown-item  <?php echo ($currentPage =='his_samt.php') ? 'active' : ''; ?>"
                                href="his_samt.php">SAMT</a></li>
                        <li><a class="dropdown-item  <?php echo ($currentPage == 'his_sakaba.php') ? 'active' : ''; ?>"
                                href="his_sakaba.php">SAKABA</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php echo ($currentPage == 'pr_create.php' || $currentPage == 'pr_management.php' || $currentPage == 'pr_history.php') ? 'active' : ''; ?>"
                        href="#" id="navbar4" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-clipboard-list"></i>
                        <?php echo 'PR' ?>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbar4">
                        <li>
                            <a class="dropdown-item <?php echo ($currentPage == 'pr_create.php') ? 'active' : ''; ?>"
                                href="pr_create.php"><?php echo $pr_add ?></a>
                        </li>
                        <li>
                            <a class="dropdown-item <?php echo ($currentPage == 'pr_management.php') ? 'active' : ''; ?>"
                                href="pr_management.php"><?php echo $pr_manage ?></a>
                        </li>
                        <li>
                            <a class="dropdown-item <?php echo ($currentPage == 'pr_history.php') ? 'active' : ''; ?>"
                                href="pr_history.php"><?php echo 'PR History' ?></a>
                        </li>
                    </ul>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php echo ($currentPage == 'good_request.php' || $currentPage == 'g_history.php') ? 'active' : ''; ?>"
                        href="#" id="navbar5" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-check-to-slot"></i>
                        <?php echo $request ?>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbar5">
                        <li><a class="dropdown-item <?php echo ($currentPage == 'good_request.php') ? 'active' : ''; ?>"
                                href="good_request.php"><?php echo $request ?></a></li>
                        <li><a class="dropdown-item <?php echo ($currentPage == 'g_history.php') ? 'active' : ''; ?>"
                                id="dropdownItem2" href="g_history.php"><?php echo $history ?></a>
                        </li>
                    </ul>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php echo ($currentPage == 'stock_in.php' || $currentPage == 'stock_in_his.php') ? 'active' : ''; ?>"
                        href="#" id="navbar6" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-inbox"></i> <?php echo $stock_in ?>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbar6">
                        <li><a class="dropdown-item <?php echo ($currentPage == 'stock_in.php') ? 'active' : ''; ?>"
                                href="stock_in.php"><?php echo $stock_in ?></a></li>
                        <li><a class="dropdown-item <?php echo ($currentPage == 'stock_in_his.php') ? 'active' : ''; ?>"
                                id="dropdownItem2" href="stock_in_his.php"><?php echo 'StockIn History' ?></a>
                        </li>
                    </ul>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($currentPage == 'stock_out.php') ? 'active' : ''; ?>"
                        href="stock_out.php" id="nav7">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i> <?php echo $stock_out ?>
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($currentPage == 'register.php') ? 'active' : ''; ?>"
                        href="register.php" id="navbar8" role="button">
                        <i class="fa-solid fa-circle-plus"></i>
                        <?php echo $register ?>
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($currentPage == 'list.php') ? 'active' : ''; ?>" href="list.php"
                        id="navbar9" role="button">
                        <i class="fa-solid fa-list"></i>
                        <?php echo $list ?>
                    </a>
                </li>
            </ul>
            <!-- Left links -->
        </div>
        <!-- Left links -->

        <!-- Collapsible wrapper -->

        <!-- Right elements -->
        <div class="d-flex justify-content-end align-items-center">
            <div class="p-2">
                <button type="button" class="btn btn-tertiary" data-mdb-ripple-init data-mdb-ripple-color="light">
                    <a class="link link-primary link-opacity-25-hover" id="jp" href="lang/change_lang.php?lang=TH">
                        <i class="flag flag-th"></i>TH
                    </a>
                    &emsp;<label>|</label>&emsp;
                    <a class="link link-primary link-opacity-25-hover" id="en" href="lang/change_lang.php?lang=en">
                        <i class="flag flag-us"></i>EN
                    </a>
                </button>
            </div>
            <div class="p-2">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a href="#" data-mdb-dropdown-init
                            class="nav-link dropdown-toggle <?php echo ($currentPage == 'form_proflie.php') ? 'active' : ''; ?>"
                            id=" navbar_user" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-user-gear"></i>&nbsp;<?php echo $row['u_username'] ?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbar_user">
                            <li>
                                <a class="dropdown-item <?php echo ($currentPage == 'form_proflie.php') ? 'active' : ''; ?>"
                                    href="form_proflie.php">My profile</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">Settings</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="p-2">
                <!-- Avatar -->
                <a href="#!" class="btn btn-danger" id="logoutBtn" type="button" data-mdb-ripple-init
                    style="border-radius: 50px;">
                    <!-- <button id="logoutBtn" type="button" class="btn btn-danger" data-mdb-ripple-init
                        data-mdb-ripple-color="dark" style="width: 150px;"> --><i
                        class="fa-solid fa-right-from-bracket"></i>
                    Logout
                </a>
                &nbsp;
            </div>
            <!-- </button> -->
        </div>
    </div>
    <button onclick="topFunction()" id="myBtn" title="Go to top"><i class="fa-solid fa-arrow-up"></i> Top</button>
</nav>
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
            confirmButtonColor: '#e06666',
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
            // Handle successful form submission
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
$(document).ready(function() {
    $('.nav-item.dropdown').hover(function() {
        $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(500);
    }, function() {
        $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(500);
    });
    $('.dropdown-toggle').click(function() {
        $(this).toggleClass('font-bold'); // Toggle class to make font bold
    });
});

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