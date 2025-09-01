<?php
// products.php - Displays all products from the database

require_once 'includes/header.php';
require_once 'config/db_connect.php';

$sql = "SELECT * FROM products ORDER BY created_at DESC";
$result = $conn->query($sql);

?>

<!-- =============================================== -->
<!--         START: PRODUCT LISTING CONTENT          -->
<!-- =============================================== -->

<div class="row text-center mb-4">
    <div class="col">
        <h2>Our Collection</h2>
        <p class="fs-5" style="color: var(--color-text-secondary);">Browse our full catalog of precision engineered components.</p>
    </div>
</div>

<div class="row g-4 row-cols-1 row-cols-md-3 row-cols-lg-4">
    <?php
    if ($result && $result->num_rows > 0) {
        while ($product = $result->fetch_assoc()) {
    ?>
            <div class="col">
                <div class="card h-100">
                    <?php
                        if (!empty($product['image_url'])) {
                            $image_path = 'assets/images/products/' . htmlspecialchars($product['image_url']);
                        } else {
                            $image_path = 'https://via.placeholder.com/400x300.png/F7F2FA/0C0420?text=Electrify';
                        }
                    ?>
                    <img src="<?php echo $image_path; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?php echo htmlspecialchars($product['product_name']); ?></h5>
                        <p class="card-text text-muted">SKU: <?php echo htmlspecialchars($product['sku']); ?></p>
                        <!-- MODIFICATION: Changed currency symbol from $ to ₹ -->
                        <h4 class="mt-2" style="color: var(--color-primary-action);">₹<?php echo htmlspecialchars($product['price']); ?></h4>
                        <a href="product_detail.php?id=<?php echo $product['product_id']; ?>" class="btn btn-secondary mt-auto">View Details</a>
                    </div>
                </div>
            </div>
    <?php
        } // End of while loop
    } else {
    ?>
        <div class="col-12">
            <div class="alert alert-info text-center" role="alert">
                There are currently no products available. Please check back later!
            </div>
        </div>
    <?php
    } // End of if/else block
    ?>
</div>

<!-- =============================================== -->
<!--          END: PRODUCT LISTING CONTENT           -->
<!-- =============================================== -->

<?php
$conn->close();
require_once 'includes/footer.php';
?>