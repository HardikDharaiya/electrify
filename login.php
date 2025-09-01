<?php
// login.php - User login form (Cleaned)

// The session_start() call has been removed from this file.
// Our header.php now handles starting the session for all pages.

require_once 'includes/header.php';
?>

<!-- =============================================== -->
<!--               START: LOGIN FORM                 -->
<!-- =============================================== -->

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-5">

        <?php
        // --- DISPLAY FEEDBACK MESSAGES ---
        // This code will still work perfectly because header.php has already started the session.
        if (isset($_SESSION['success_message'])) {
            echo '<div class="alert alert-success" role="alert">' . $_SESSION['success_message'] . '</div>';
            unset($_SESSION['success_message']);
        }

        if (isset($_SESSION['error_message'])) {
            echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error_message'] . '</div>';
            unset($_SESSION['error_message']);
        }
        ?>

        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h2 class="text-center mb-4">Login to Your Account</h2>
                
                <form id="loginForm" action="handle_login.php" method="POST">
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">Login</button>
                    </div>

                </form>

                <div class="text-center mt-4">
                    <p class="text-muted">Don't have an account? <a href="register.php">Register here</a></p>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- =============================================== -->
<!--                END: LOGIN FORM                  -->
<!-- =============================================== -->

<?php
require_once 'includes/footer.php';
?>