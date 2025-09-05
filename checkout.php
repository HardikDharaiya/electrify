<?php
// checkout.php - Final step before payment (Corrected Logic)

require_once 'includes/header.php';
require_once 'config/db_connect.php';

// --- SECURITY GUARD 1: USER AUTHENTICATION ---
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Please log in to proceed to checkout.";
    header("Location: login.php");
    exit();
}

// --- HYBRID DATA FETCHING (Corrected Logic) ---
$cart_items = [];
$products = [];
$grand_total = 0.00;

if (isset($_SESSION['user_id'])) {
    // USER IS LOGGED IN: Fetch cart from the database.
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT p.product_id, p.product_name, p.price, c.quantity
            FROM cart c
            JOIN products p ON c.product_id = p.product_id
            WHERE c.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $products[$row['product_id']] = $row;
        $cart_items[$row['product_id']] = $row['quantity'];
    }
    $stmt->close();
} else {
    // This block is for guest checkout in the future, but the authentication guard
    // currently prevents guests from reaching this page. This logic is here for completeness.
    $session_cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
    if (!empty($session_cart)) {
        // (Logic to fetch guest cart details would go here if we allowed guest checkout)
        $cart_items = $session_cart;
    }
}

// --- WORKFLOW GUARD 2: EMPTY CART CHECK (Corrected Logic) ---
if (empty($cart_items)) {
    // Redirect if the resolved cart (from DB or session) is empty.
    header("Location: products.php");
    exit();
}
?>

<!-- =============================================== -->
<!--           START: CHECKOUT CONTENT               -->
<!-- The HTML part of this file does not need to change. -->
<!-- =============================================== -->

<h2 class="text-center mb-4">Checkout</h2>

<div class="row g-5">
    <div class="col-md-7">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Shipping Information</h4>
                <!-- The form's ID is added to be targeted by the payment button -->
                <form id="checkoutForm" action="payment_handler.php" method="POST">
                    <div class="mb-3">
                        <label for="shipping_address" class="form-label">Full Shipping Address</label>
                        <textarea class="form-control" id="shipping_address" name="shipping_address" rows="4" required placeholder="Enter your street, city, state, and pin code..."></textarea>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
            <!-- This button is now linked to the form via the 'form' attribute -->
            <button type="submit" class="btn btn-primary btn-lg" form="checkoutForm">Proceed to Payment</button>
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