<?php
// product_detail.php - Displays details for a single product.

require_once 'includes/header.php';
require_once 'config/db_connect.php';

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($product_id <= 0) {
    echo "<div class='alert alert-danger'>Invalid product ID provided.</div>";
    require_once 'includes/footer.php';
    die();
}

$sql = "SELECT * FROM products WHERE product_id = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();
?>

<!-- =============================================== -->
<!--        START: PRODUCT DETAIL CONTENT            -->
<!-- =============================================== -->

<div class="container my-5">
    <?php
    // --- DISPLAY FEEDBACK MESSAGES from cart_logic.php ---
    if (isset($_SESSION['success_message'])) {
        echo '<div class="alert alert-success" role="alert">' . $_SESSION['success_message'] . '</div>';
        unset($_SESSION['success_message']);
    }
    if (isset($_SESSION['error_message'])) {
        echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error_message'] . '</div>';
        unset($_SESSION['error_message']);
    }
    ?>

    <?php if ($product): ?>
        <div class="row">
            <div class="col-md-6 mb-4">
                <?php
                if (!empty($product['image_url'])) {
                    $image_path = 'assets/images/products/' . htmlspecialchars($product['image_url']);
                } else {
                    $image_path = 'https://via.placeholder.com/600x450.png/F7F2FA/0C0420?text=Electrify';
                }
                ?>
                <img src="<?php echo $image_path; ?>" class="img-fluid rounded shadow-sm" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
            </div>
            <div class="col-md-6">
                <h1 class="display-5"><?php echo htmlspecialchars($product['product_name']); ?></h1>
                <p class="text-muted">SKU: <?php echo htmlspecialchars($product['sku']); ?></p>
                <h2 class="my-3" style="color: var(--color-primary-action);">₹<?php echo htmlspecialchars($product['price']); ?></h2>

                <p class="lead"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>

                <!-- 
                MODIFICATION: 
                - The "Add to Cart" button is now inside a form.
                - The form posts to our cart_logic.php script.
                - We include hidden inputs for product_id and a quantity selector.
                -->
                <form action="cart_logic.php" method="POST" class="mt-4">
                    <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="quantity" class="form-label">Quantity:</label>
                            <input type="number" class="form-control" name="quantity" id="quantity" value="1" min="1" max="<?php echo $product['stock_quantity']; ?>">
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-block">
                        <button type="submit" name="add_to_cart" class="btn btn-primary btn-lg">
                            <i class="bi bi-cart-plus-fill me-2"></i>Add to Cart
                        </button>
                        <a href="products.php" class="btn btn-secondary btn-lg">Back to Products</a>
                    </div>
                </form>

            </div>
        </div>
    <?php else: ?>
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