<?php
// product_detail.php - Displays details for a single product.

require_once 'includes/header.php';
require_once 'config/db_connect.php';

// -- 1. VALIDATE THE PRODUCT ID FROM THE URL --
// We check if an 'id' was passed in the URL and if it's a valid integer.
// This is our first line of defense.
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($product_id <= 0) {
    // If the ID is invalid, we can show an error or redirect.
    // For now, we'll display a simple message and stop the script.
    echo "<div class='alert alert-danger'>Invalid product ID provided.</div>";
    require_once 'includes/footer.php';
    die();
}

// -- 2. FETCH PRODUCT DATA USING A PREPARED STATEMENT (SECURITY!) --
// We use a '?' as a placeholder for the product ID to prevent SQL injection.
$sql = "SELECT * FROM products WHERE product_id = ?";

// Prepare the statement
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    // Handle potential errors with the SQL query itself
    die("Error preparing statement: " . $conn->error);
}

// Bind the integer parameter ($product_id) to the placeholder. 'i' means integer.
$stmt->bind_param("i", $product_id);

// Execute the prepared statement
$stmt->execute();

// Get the result of the query
$result = $stmt->get_result();

// Fetch the single product row as an associative array
$product = $result->fetch_assoc();

// Close the statement
$stmt->close();
?>

<!-- =============================================== -->
<!--        START: PRODUCT DETAIL CONTENT            -->
<!-- =============================================== -->

<div class="container my-5">
    <?php if ($product): // Check if a product was found ?>
        <div class="row">
            <div class="col-md-6">
                <?php
                    // Use the same dynamic image logic as the products page
                    if (!empty($product['image_url'])) {
                        $image_path = 'assets/images/products/' . htmlspecialchars($product['image_url']);
                    } else {
                        $image_path = 'https://via.placeholder.com/600x450.png/F7F2FA/0C0420?text=Electrify';
                    }
                ?>
                <img src="<?php echo $image_path; ?>" class="img-fluid rounded" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
            </div>
            <div class="col-md-6">
                <h1 class="display-5"><?php echo htmlspecialchars($product['product_name']); ?></h1>
                <p class="text-muted">SKU: <?php echo htmlspecialchars($product['sku']); ?></p>
                <h2 class="my-3" style="color: var(--color-primary-action);">₹<?php echo htmlspecialchars($product['price']); ?></h2>
                
                <p class="lead"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                
                <div class="d-grid gap-2 d-md-block mt-4">
                    <!-- We will add functionality to this button later -->
                    <button class="btn btn-primary btn-lg" type="button">Add to Cart</button>
                    <a href="products.php" class="btn btn-secondary btn-lg" type="button">Back to Products</a>
                </div>
            </div>
        </div>
    <?php else: // If no product was found with that ID ?>
        <div class="alert alert-warning text-center" role="alert">
            <h2>Product Not Found</h2>
            <p>Sorry, the product you are looking for does not exist.</p>
            <a href="products.php" class="btn btn-primary">Return to Collection</a>
        </div>
    <?php endif; ?>
</div>

<!-- =============================================== -->
<!--         END: PRODUCT DETAIL CONTENT             -->
<!-- =============================================== -->

<?php
$conn->close();
require_once 'includes/footer.php';
?>