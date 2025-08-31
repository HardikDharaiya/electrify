<?php
// index.php - The Home Page (Royal Orchid Design)

require_once 'includes/header.php';
require_once 'config/db_connect.php';
?>

<!-- =============================================== -->
<!--            START: HOME PAGE CONTENT             -->
<!-- =============================================== -->

<div class="text-center py-5">
    <h1 class="display-5 fw-bold">Precision Engineered Electronics</h1>
    <!-- 
    MODIFICATION:
    - Removed the inline `style` attribute. The color is now correctly inherited from the body style in style.css.
    - Updated the text to match the sophisticated new brand identity.
    -->
    <p class="fs-4 col-md-8 mx-auto">Discover a curated selection of high-quality components for the discerning engineer and innovator.</p>
    <a href="products.php" class="btn btn-primary btn-lg mt-3" type="button">Explore Our Collection</a>
</div>


<div class="row text-center mt-5 mb-4">
    <div class="col">
        <h2>Shop by Category</h2>
    </div>
</div>


<div class="row g-4">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body text-center p-4 d-flex flex-column">
                <h5 class="card-title mb-3">Passive Components</h5>
                <p class="card-text">Essential resistors, capacitors, and inductors. The foundational building blocks for any circuit.</p>
                <!-- NOTE: The .btn-secondary class will now automatically apply our new "outline" style. -->
                <a href="#" class="btn btn-secondary mt-auto">View Products</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body text-center p-4 d-flex flex-column">
                <h5 class="card-title mb-3">Active Components</h5>
                <p class="card-text">The power of innovation. Explore our range of diodes, transistors, and integrated circuits.</p>
                <a href="#" class="btn btn-secondary mt-auto">View Products</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body text-center p-4 d-flex flex-column">
                <h5 class="card-title mb-3">Tools & Accessories</h5>
                <p class="card-text">Precision tools for the professional. High-quality soldering irons, multimeters, and more.</p>
                <a href="#" class="btn btn-secondary mt-auto">View Products</a>
            </div>
        </div>
    </div>
</div>

<!-- =============================================== -->
<!--             END: HOME PAGE CONTENT              -->
<!-- =============================================== -->

<?php
require_once 'includes/footer.php';
?>