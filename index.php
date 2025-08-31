<?php
// index.php - The Home Page

// We include the header file. This contains the opening HTML, head section, and navigation bar.
// Using require_once ensures the file is included only once, preventing potential errors.
require_once 'includes/header.php';

// We also include our database connection file to make it available, though we aren't using it on this page yet.
require_once 'config/db_connect.php';
?>

<!-- =============================================== -->
<!--            START: HOME PAGE CONTENT             -->
<!-- =============================================== -->

<div class="p-5 mb-4 bg-light rounded-3">
    <div class="container-fluid py-5">
        <h1 class="display-5 fw-bold">Welcome to Electrify</h1>
        <p class="col-md-8 fs-4">Your one-stop shop for high-quality electronic components. From resistors to microcontrollers, we have everything you need for your next project.</p>
        <a href="products.php" class="btn btn-primary btn-lg" type="button">Shop All Products</a>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <h2>Featured Categories</h2>
        <hr>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Passive Components</h5>
                <p class="card-text">Resistors, capacitors, inductors, and more. The essential building blocks for any circuit.</p>
                <a href="#" class="btn btn-secondary">View Products</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Active Components</h5>
                <p class="card-text">Diodes, transistors, and integrated circuits that power your innovative designs.</p>
                <a href="#" class="btn btn-secondary">View Products</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Tools & Accessories</h5>
                <p class="card-text">High-quality soldering irons, multimeters, and everything else you need to build and test.</p>
                <a href="#" class="btn btn-secondary">View Products</a>
            </div>
        </div>
    </div>
</div>


<!-- =============================================== -->
<!--             END: HOME PAGE CONTENT              -->
<!-- =============================================== -->

<?php
// We include the footer file. This contains the closing body/html tags and our JavaScript links.
require_once 'includes/footer.php';
?>