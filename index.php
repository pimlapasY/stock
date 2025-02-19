<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php include('connect.php') ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
</head>
<style>
.card-body {
    display: flex;
    /* Center horizontally */
    align-items: center;
    /* Center vertically */
}

.card-body h5 {
    margin: 0;
    /* Remove default margin for better centering */

}

.chart-container {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    /* Two columns */
    gap: 20px;
    /* Space between charts */
}

.chart-item {
    width: 100%;
    height: auto;
}
</style>

<body>
    <?php include('navbar.php') ?>
    <div class="container-fluid mt-5 col-10 pt-5">

        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button"
                    role="tab" aria-controls="home" aria-selected="true">New Products (Report)</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button"
                    role="tab" aria-controls="profile" aria-selected="false" disabled>Dead Stock (Report)</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button"
                    role="tab" aria-controls="contact" aria-selected="false">Sales (Report)</button>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <?php include('assets/report1.php') ?>
            </div>
            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <?php include('assets/report2.php') ?>
            </div>
            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                <?php include('assets/report3.php') ?>
            </div>
        </div>
        <?php include('footer.php') ?>

    </div>
</body>

</html>