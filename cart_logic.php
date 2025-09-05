<?php
// cart_logic.php - Handles all shopping cart operations.

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize the cart if it doesn't exist.
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Check if the request is a POST request.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // --- ACTION: ADD TO CART ---
    if (isset($_POST['add_to_cart'])) {
        $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;

        if ($product_id > 0 && $quantity > 0) {
            if (isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id] += $quantity;
                $_SESSION['success_message'] = "Product quantity updated in your cart!";
            } else {
                $_SESSION['cart'][$product_id] = $quantity;
                $_SESSION['success_message'] = "Product added to your cart!";
            }
        } else {
            $_SESSION['error_message'] = "Invalid product data provided.";
        }
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }

    // --- ACTION: UPDATE CART QUANTITIES ---
    if (isset($_POST['update_cart'])) {
        $quantities = $_POST['quantities'];
        foreach ($quantities as $product_id => $quantity) {
            $product_id = (int)$product_id;
            $quantity = (int)$quantity;

            if ($quantity > 0 && isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id] = $quantity;
            } else {
                // If quantity is 0 or less, remove the item.
                unset($_SESSION['cart'][$product_id]);
            }
        }
        $_SESSION['success_message'] = "Your cart has been updated.";
        header("Location: cart.php");
        exit();
    }

    // --- ACTION: REMOVE FROM CART ---
    if (isset($_POST['remove_from_cart'])) {
        $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
        if ($product_id > 0 && isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
            $_SESSION['success_message'] = "Product removed from your cart.";
        }
        header("Location: cart.php");
        exit();
    }
} else {
    // If not a POST request, redirect to the main products page.
    header("Location: products.php");
    exit();
}
