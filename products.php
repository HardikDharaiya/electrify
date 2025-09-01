<?php
// products.php - Displays all products from the database

require_once 'includes/header.php';
require_once 'config/db_connect.php';

// -- 1. PREPARE THE DATABASE QUERY --
// We write the SQL query to select all columns (*) from the 'products' table.
// It's good practice to order the results, for example, by the date they were added.
$sql = "SELECT * FROM products ORDER BY created_at DESC";

// -- 2. EXECUTE THE QUERY --
// We use our connection object '$conn' to run the query.
// The result of the query (the set of products) is stored in the '$result' variable.
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

<div class="row g-4">
    <?php
    // -- 3. CHECK FOR RESULTS & LOOP THROUGH THEM --
    // We check if the query returned any rows (products).
    if ($result && $result->num_rows > 0) {
        // If yes, we loop through each row in the result set.
        // The fetch_assoc() method pulls one row at a time as an associative array.
        while ($product = $result->fetch_assoc()) {
            // For each product, we generate the HTML for a product card.
            // We use our "Royal Orchid" card design for perfect visual consistency.
            // We use htmlspecialchars() to prevent XSS attacks by sanitizing any data coming from the DB.
    ?>
            <div class="col-md-4 col-lg-3">
                <div class="card h-100">
                    <!-- In the future, we will replace the placeholder with a real product image -->
                    <img src="https://via.placeholder.com/400x300.png/F7F2FA/0C0420?text=Electrify" class="card-img-top" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?php echo htmlspecialchars($product['product_name']); ?></h5>
                        <p class="card-text text-muted">SKU: <?php echo htmlspecialchars($product['sku']); ?></p>
                        <h4 class="mt-2" style="color: var(--color-primary-action);">$<?php echo htmlspecialchars($product['price']); ?></h4>
                        <a href="product_detail.php?id=<?php echo $product['product_id']; ?>" class="btn btn-secondary mt-auto">View Details</a>
                    </div>
                </div>
            </div>
    <?php
        } // End of the while loop
    } else {
        // -- 4. HANDLE THE "NO PRODUCTS" CASE --
        // If the query returns no rows, we display a user-friendly message.
    ?>
        <div class="col-12">
            <div class="alert alert-info text-center" role="alert">
                There are currently no products available. Please check back later!
            </div>
        </div>
    <?php
    } // End of the if/else block
    ?>
</div>

<!-- =============================================== -->
<!--          END: PRODUCT LISTING CONTENT           -->
<!-- =============================================== -->

<?php
// Close the database connection once we are done with it.
$conn->close();

require_once 'includes/footer.php';
?>