<style>
.nav-color {
    background: #03045e;
    /* fallback for old browsers */
    background: -webkit-linear-gradient(to right,
            #48cae4,
            /* #5b86e5, */
            /* #36d1dc */
            #0892fd,
            #03045e);
    /* Chrome 10-25, Safari 5.1-6 */
    background: linear-gradient(to right, #03045e, #0892fd, #48cae4);
    /* background: linear-gradient(to right, #5b86e5, #36d1dc); */

}

.position-left {
    left: 0;
    /* Position the dropdown menu to the left */
}


.navbar-nav .nav-link {
    color: white;
}

.navbar-nav .nav-item .nav-link.active {
    color: white;
    font-weight: bold;
    border-bottom: 2px solid white;
    /* Add this line to add underline */
}

.navbar-nav .nav-item {
    color: white;
    /* กำหนดสีข้อความเป็นสีขาว */
}
</style>
<!-- Navbar -->
<?php
// Set the current page based on the PHP_SELF server variable
$currentPage = basename($_SERVER['PHP_SELF']);

include('connect.php');

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

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-body-tertiary nav-color">
    <!-- Container wrapper -->
    <div class="container-fluid">

        <!-- Collapsible wrapper -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Navbar brand -->
            <a class="navbar-brand mt-2 mt-lg-0" href="#">
                <!-- <img src="https://mdbcdn.b-cdn.net/img/logo/mdb-transaprent-noshadows.webp" height="15" alt="MDB Logo"
                    loading="lazy" /> -->
            </a>
            <!-- Left links -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($currentPage == 'index.php' || $currentPage == '') ? 'active' : ''; ?>"
                        href="./"><?php echo $stockList ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($currentPage == 'good_request.php') ? 'active' : ''; ?>"
                        href="good_request.php"><?php echo $request ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($currentPage == 'history.php') ? 'active' : ''; ?>"
                        href="history.php"><?php echo $history ?></a>
                </li>
            </ul>
            <!-- Left links -->
        </div>
        <!-- Collapsible wrapper -->

        <!-- Right elements -->
        <div class="d-flex align-items-center">
            <div class="p-2">
                <button type="button" class="btn btn-tertiary" data-mdb-ripple-init data-mdb-ripple-color="light">
                    <a class="link link-primary link-opacity-25-hover" id="jp"
                        href="lang/change_lang.php?lang=TH">TH</a>&emsp;<label>|</label>&emsp;
                    <a class="link link-primary link-opacity-25-hover" id="en"
                        href="lang/change_lang.php?lang=en">EN</a>
                </button>
            </div>
            <!-- Avatar -->
            <div class="dropdown p-3">
                <ul class="dropdown-menu" id="userDropdown" aria-labelledby="navbarDropdownMenuAvatar">
                    <li>
                        <a class="dropdown-item" href="#">My profile</a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">Settings</a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">Logout</a>
                    </li>
                </ul>
            </div>
            <a href="#" data-mdb-dropdown-init class="btn btn-light  dropdown-toggle" data-mdb-ripple-init>
                <!-- <a data-mdb-dropdown-init class="dropdown-toggle d-flex align-items-center hidden-arrow" href="#"
                        role="button"> -->
                <i class="fa-solid fa-user-gear"></i>&nbsp;<?php echo $row['u_username'] ?>
            </a>
            &nbsp;
            <a href="#!" class="btn btn-danger" id="logoutBtn" type="button" data-mdb-ripple-init>
                <!-- <button id="logoutBtn" type="button" class="btn btn-danger" data-mdb-ripple-init
                        data-mdb-ripple-color="dark" style="width: 150px;"> --><i
                    class="fa-solid fa-right-from-bracket"></i>
                Logout
            </a>
            <!-- </button> -->
        </div>
    </div>

    <!-- Right elements -->
    </div>
    <!-- Container wrapper -->
</nav>
<!-- Navbar -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Select the user icon
    var userIcon = document.querySelector('.dropdown-toggle');

    // Select the dropdown menu
    var userDropdown = document.getElementById('userDropdown');

    // Add click event listener to the user icon
    userIcon.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent default action

        // Toggle the dropdown menu's visibility
        if (userDropdown.style.display === 'block') {
            userDropdown.style.display = 'none';
        } else {
            userDropdown.style.display = 'block';
        }
    });

    // Close dropdown menu when clicking outside of it
    document.addEventListener('click', function(event) {
        if (!userDropdown.contains(event.target) && !userIcon.contains(event.target)) {
            userDropdown.style.display = 'none';
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {

    // Logout button click event
    const logoutBtn = document.getElementById('logoutBtn');

    logoutBtn.addEventListener('click', function() {

        Swal.fire({
            title: 'Are you sure?',
            text: 'You will be logged out!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
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
</script>