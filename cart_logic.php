<?php
// cart_logic.php - Handles both session and database cart operations.

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/db_connect.php';

// Check if a user is logged in. This determines where we store the cart.
$is_logged_in = isset($_SESSION['user_id']);
$user_id = $is_logged_in ? $_SESSION['user_id'] : null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // --- ACTION: ADD TO CART ---
    if (isset($_POST['add_to_cart'])) {
        $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;

        if ($product_id > 0 && $quantity > 0) {
            // ** LOGIC BRANCH **
            if ($is_logged_in) {
                // LOGGED-IN USER: Store in the database.
                $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)
                        ON DUPLICATE KEY UPDATE quantity = quantity + ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iiii", $user_id, $product_id, $quantity, $quantity);
                $stmt->execute();
                $stmt->close();
                $_SESSION['success_message'] = "Product saved to your account cart!";
            } else {
                // GUEST USER: Store in the session.
                if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
                if (isset($_SESSION['cart'][$product_id])) {
                    $_SESSION['cart'][$product_id] += $quantity;
                } else {
                    $_SESSION['cart'][$product_id] = $quantity;
                }
                $_SESSION['success_message'] = "Product added to your session cart!";
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
        // ** LOGIC BRANCH **
        if ($is_logged_in) {
            // LOGGED-IN USER: Update database
            foreach ($quantities as $product_id => $quantity) {
                $product_id = (int)$product_id;
                $quantity = (int)$quantity;
                if ($quantity > 0) {
                    $sql = "UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("iii", $quantity, $user_id, $product_id);
                    $stmt->execute();
                } else {
                    $sql = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ii", $user_id, $product_id);
                    $stmt->execute();
                }
            }
        } else {
            // GUEST USER: Update session
            foreach ($quantities as $product_id => $quantity) {
                if ((int)$quantity > 0) {
                    $_SESSION['cart'][(int)$product_id] = (int)$quantity;
                } else {
                    unset($_SESSION['cart'][(int)$product_id]);
                }
            }
        }
        $_SESSION['success_message'] = "Your cart has been updated.";
        header("Location: cart.php");
        exit();
    }

    // --- ACTION: REMOVE FROM CART ---
    if (isset($_POST['remove_from_cart'])) {
        $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
        if ($product_id > 0) {
            // ** LOGIC BRANCH **
            if ($is_logged_in) {
                // LOGGED-IN USER: Delete from database
                $sql = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $user_id, $product_id);
                $stmt->execute();
            } else {
                // GUEST USER: Delete from session
                unset($_SESSION['cart'][$product_id]);
            }
            $_SESSION['success_message'] = "Product removed from your cart.";
        }
        header("Location: cart.php");
        exit();
    }
}
