<?php
// checkout.php - Final step before payment.

require_once 'includes/header.php';
require_once 'config/db_connect.php';

// --- SECURITY GUARD 1: USER AUTHENTICATION ---
// If the user is not logged in, redirect them to the login page.
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Please log in to proceed to checkout.";
    header("Location: login.php");
    exit();
}

// --- WORKFLOW GUARD 2: EMPTY CART CHECK ---
// If the cart is empty, redirect them to the products page.
if (empty($_SESSION['cart'])) {
    header("Location: products.php");
    exit();
}

// --- DATA FETCHING (Same logic as cart.php) ---
$cart_items = $_SESSION['cart'];
$products = [];
$grand_total = 0.00;

$product_ids = array_keys($cart_items);
$placeholders = implode(',', array_fill(0, count($product_ids), '?'));
$sql = "SELECT product_id, product_name, price FROM products WHERE product_id IN ($placeholders)";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $types = str_repeat('i', count($product_ids));
    $stmt->bind_param($types, ...$product_ids);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $products[$row['product_id']] = $row;
    }
    $stmt->close();
}
?>

<!-- =============================================== -->
<!--           START: CHECKOUT CONTENT               -->
<!-- =============================================== -->

<h2 class="text-center mb-4">Checkout</h2>

<div class="row g-5">

    <!-- Left Column: Shipping Details Form -->
    <div class="col-md-7">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Shipping Information</h4>
                <!-- This form will eventually post to our payment handler -->
                <form action="payment_handler.php" method="POST">
                    <div class="mb-3">
                        <label for="shipping_address" class="form-label">Full Shipping Address</label>
                        <textarea class="form-control" id="shipping_address" name="shipping_address" rows="4" required placeholder="Enter your street, city, state, and pin code..."></textarea>
                    </div>

                    <!-- We will add the payment button inside this form later -->

                </form>
            </div>
        </div>
    </div>

    <!-- Right Column: Order Summary -->
    <div class="col-md-5">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Your Order Summary</h4>
                <ul class="list-group list-group-flush">
                    <?php foreach ($cart_items as $product_id => $quantity): ?>
                        <?php
                        $product = $products[$product_id];
                        $subtotal = $product['price'] * $quantity;
                        $grand_total += $subtotal;
                        ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo htmlspecialchars($product['product_name']); ?> (x<?php echo $quantity; ?>)
                            <span>₹<?php echo number_format($subtotal, 2); ?></span>
                        </li>
                    <?php endforeach; ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center fw-bold fs-5">
                        <span>Grand Total</span>
                        <span>₹<?php echo number_format($grand_total, 2); ?></span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="d-grid mt-4">
            <!-- This button will be part of the form above in the final version -->
            <button type="submit" class="btn btn-primary btn-lg" form="shippingForm">Proceed to Payment</button>
        </div>
    </div>
</div>

<!-- =============================================== -->
<!--            END: CHECKOUT CONTENT                -->
<!-- =============================================== -->

<?php
$conn->close();
require_once 'includes/footer.php';
?>