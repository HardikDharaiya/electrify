<?php
// cart.php - Displays the cart from DB (logged-in) or session (guest).

require_once 'includes/header.php';
require_once 'config/db_connect.php';

$cart_items = [];
$products = [];
$grand_total = 0.00;

// --- INTELLIGENT CART FETCHING LOGIC ---
if (isset($_SESSION['user_id'])) {
    // USER IS LOGGED IN: Fetch cart from the database.
    $user_id = $_SESSION['user_id'];

    // This is an efficient JOIN query. It gets all products in the user's cart
    // and their details from the products table in a single database call.
    $sql = "SELECT p.product_id, p.product_name, p.price, p.image_url, p.stock_quantity, c.quantity
            FROM cart c
            JOIN products p ON c.product_id = p.product_id
            WHERE c.user_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        // We populate two arrays: one for product details, one for quantities.
        $products[$row['product_id']] = $row;
        $cart_items[$row['product_id']] = $row['quantity'];
    }
    $stmt->close();
} else {
    // USER IS A GUEST: Fetch cart from the session.
    $session_cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
    if (!empty($session_cart)) {
        $product_ids = array_keys($session_cart);
        $placeholders = implode(',', array_fill(0, count($product_ids), '?'));

        $sql = "SELECT product_id, product_name, price, image_url, stock_quantity FROM products WHERE product_id IN ($placeholders)";
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
            // We still use the session to get the quantities for guests.
            $cart_items = $session_cart;
        }
    }
}
// --- END OF FETCHING LOGIC ---
?>

<!-- =============================================== -->
<!--            START: CART VIEW CONTENT             -->
<!-- (The rest of this file remains the same, as the arrays are populated in the same structure) -->
<!-- =============================================== -->

<h2 class="text-center mb-4">Your Shopping Cart</h2>

<?php
if (isset($_SESSION['success_message'])) {
    echo '<div class="alert alert-success" role="alert">' . $_SESSION['success_message'] . '</div>';
    unset($_SESSION['success_message']);
}
?>

<?php if (!empty($cart_items) && !empty($products)): ?>
    <form action="cart_logic.php" method="POST">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col" colspan="2">Product</th>
                        <th scope="col" class="text-center">Price</th>
                        <th scope="col" class="text-center" style="width: 150px;">Quantity</th>
                        <th scope="col" class="text-end">Subtotal</th>
                        <th scope="col" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $product_id => $quantity): ?>
                        <?php
                        $product = $products[$product_id];
                        $subtotal = $product['price'] * $quantity;
                        $grand_total += $subtotal;

                        if (!empty($product['image_url'])) {
                            $image_path = 'assets/images/products/' . htmlspecialchars($product['image_url']);
                        } else {
                            $image_path = 'https://via.placeholder.com/100x100.png/F7F2FA/0C0420?text=Image';
                        }
                        ?>
                        <tr>
                            <td style="width: 100px;"><img src="<?php echo $image_path; ?>" class="img-fluid rounded" alt="<?php echo htmlspecialchars($product['product_name']); ?>"></td>
                            <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                            <td class="text-center">₹<?php echo number_format($product['price'], 2); ?></td>
                            <td class="text-center"><input type="number" class="form-control text-center" name="quantities[<?php echo $product_id; ?>]" value="<?php echo $quantity; ?>" min="1" max="<?php echo $product['stock_quantity']; ?>"></td>
                            <td class="text-end">₹<?php echo number_format($subtotal, 2); ?></td>
                            <td class="text-center">
                                <form action="cart_logic.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                    <button type="submit" name="remove_from_cart" class="btn btn-sm btn-danger">&times;</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="row mt-4">
            <div class="col text-start"><button type="submit" name="update_cart" class="btn btn-secondary">Update Cart</button></div>
            <div class="col text-end">
                <div class="card d-inline-block" style="width: 300px;">
                    <div class="card-body">
                        <h5 class="card-title">Cart Total</h5>
                        <h3 class="fw-bold">₹<?php echo number_format($grand_total, 2); ?></h3>
                        <div class="d-grid mt-3"><a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a></div>
                    </div>
                </div>
            </div>
        </div>
    </form>
<?php else: ?>
    <div class="alert alert-info text-center">
        <p class="fs-4">Your shopping cart is empty.</p>
        <a href="products.php" class="btn btn-secondary">Continue Shopping</a>
    </div>
<?php endif; ?>

<!-- =============================================== -->
<!--             END: CART VIEW CONTENT              -->
<!-- =============================================== -->

<?php
$conn->close();
require_once 'includes/footer.php';
?>