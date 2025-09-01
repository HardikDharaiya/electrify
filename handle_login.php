<?php
// handle_login.php - Processes user login credentials

// 1. Start the session to manage user state.
session_start();

// 2. Include the database connection.
require_once 'config/db_connect.php';

// 3. Check if the form was submitted using the POST method.
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 4. Retrieve and sanitize form data.
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    // 5. SERVER-SIDE VALIDATION
    if (empty($email) || empty($password)) {
        $_SESSION['error_message'] = "Both email and password are required.";
        header("Location: login.php");
        exit();
    }

    // 6. Fetch user from the database by email using a prepared statement.
    $sql = "SELECT user_id, first_name, password_hash, role FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        // User found, now verify the password.
        $user = $result->fetch_assoc();

        // 7. Securely verify the password against the stored hash.
        if (password_verify($password, $user['password_hash'])) {
            // Password is correct!
            
            // 8. Regenerate the session ID to prevent session fixation. CRITICAL!
            session_regenerate_id(true);

            // 9. Store user data in the session to mark them as logged in.
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_first_name'] = $user['first_name'];
            $_SESSION['user_role'] = $user['role'];

            // 10. Redirect to the main page (or a dashboard in the future).
            header("Location: index.php");
            exit();

        } else {
            // Password is not correct.
            $_SESSION['error_message'] = "Invalid email or password.";
            header("Location: login.php");
            exit();
        }

    } else {
        // No user found with that email.
        $_SESSION['error_message'] = "Invalid email or password.";
        header("Location: login.php");
        exit();
    }
    
    $stmt->close();
    $conn->close();

} else {
    // If not a POST request, redirect to the login page.
    header("Location: login.php");
    exit();
}
?>