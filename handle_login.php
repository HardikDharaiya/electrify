<?php
// handle_login.php - Processes login and syncs guest cart.

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $_SESSION['error_message'] = "Both email and password are required.";
        header("Location: login.php");
        exit();
    }

    $sql = "SELECT user_id, first_name, password_hash, role FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password_hash'])) {
            // Password is correct!

            // --- MODIFICATION: CART SYNCHRONIZATION ---

            // 1. Grab the guest cart from the current session BEFORE regenerating the ID.
            $guest_cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

            // 2. Regenerate the session ID to prevent session fixation. CRITICAL!
            session_regenerate_id(true);

            // 3. Store user data in the NEW, clean session.
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_first_name'] = $user['first_name'];
            $_SESSION['user_role'] = $user['role'];

            // 4. If the guest had items in their cart, merge them into the database cart.
            if (!empty($guest_cart)) {
                foreach ($guest_cart as $product_id => $quantity) {
                    $sql_sync = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)
                                 ON DUPLICATE KEY UPDATE quantity = quantity + ?";
                    $stmt_sync = $conn->prepare($sql_sync);
                    $stmt_sync->bind_param("iiii", $user['user_id'], $product_id, $quantity, $quantity);
                    $stmt_sync->execute();
                    $stmt_sync->close();
                }
            }
            // --- END OF MODIFICATION ---

            header("Location: index.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Invalid email or password.";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['error_message'] = "Invalid email or password.";
        header("Location: login.php");
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: login.php");
    exit();
}
