<?php
// handle_register.php - Processes user registration data

// 1. Start a session to store messages.
session_start();

// 2. Include the database connection.
require_once 'config/db_connect.php';

// 3. Check if the form was submitted using the POST method.
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 4. Retrieve and sanitize form data.
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password']; // Don't trim password in case of leading/trailing spaces are intended.
    
    // 5. SERVER-SIDE VALIDATION
    $errors = [];
    if (empty($first_name)) { $errors[] = "First name is required."; }
    if (empty($last_name)) { $errors[] = "Last name is required."; }
    if (empty($email)) { 
        $errors[] = "Email is required."; 
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    if (empty($password)) { 
        $errors[] = "Password is required."; 
    } elseif (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }

    // If there are validation errors, redirect back with an error message.
    if (!empty($errors)) {
        $_SESSION['error_message'] = implode('<br>', $errors);
        header("Location: register.php");
        exit();
    }

    // 6. Check if email already exists using a prepared statement.
    $sql_check = "SELECT user_id FROM users WHERE email = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        // Email already exists.
        $_SESSION['error_message'] = "An account with this email address already exists.";
        header("Location: register.php");
        exit();
    }
    $stmt_check->close();

    // 7. Securely hash the password.
    // PASSWORD_BCRYPT is the current strong and recommended algorithm.
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    // 8. Insert the new user into the database using a prepared statement.
    $sql_insert = "INSERT INTO users (first_name, last_name, email, password_hash) VALUES (?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    
    // 'ssss' means we are binding four string parameters.
    $stmt_insert->bind_param("ssss", $first_name, $last_name, $email, $password_hash);

    // 9. Execute the statement and redirect.
    if ($stmt_insert->execute()) {
        // Success!
        $_SESSION['success_message'] = "Registration successful! You can now log in.";
        header("Location: login.php");
        exit();
    } else {
        // Database error.
        $_SESSION['error_message'] = "An error occurred during registration. Please try again.";
        header("Location: register.php");
        exit();
    }

    $stmt_insert->close();
    $conn->close();

} else {
    // If not a POST request, redirect to the registration page.
    header("Location: register.php");
    exit();
}
?>