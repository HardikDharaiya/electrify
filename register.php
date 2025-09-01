<?php
// register.php - User registration form (Cleaned)

// The session_start() call has been removed from this file.
// Our header.php now handles starting the session for all pages.

require_once 'includes/header.php';
?>

<!-- =============================================== -->
<!--            START: REGISTRATION FORM             -->
<!-- =============================================== -->

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">

        <?php
        // --- DISPLAY FEEDBACK MESSAGES ---
        // This code will still work perfectly because header.php has already started the session.
        if (isset($_SESSION['error_message'])) {
            echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error_message'] . '</div>';
            unset($_SESSION['error_message']);
        }
        ?>

        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h2 class="text-center mb-4">Create an Account</h2>
                
                <form id="registrationForm" action="handle_register.php" method="POST" novalidate>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required minlength="8">
                    </div>

                    <div class="mb-4">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required minlength="8" equalTo="#password">
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">Register</button>
                    </div>

                </form>

                <div class="text-center mt-4">
                    <p class="text-muted">Already have an account? <a href="login.php">Login here</a></p>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- =============================================== -->
<!--             END: REGISTRATION FORM              -->
<!-- =============================================== -->

<?php
require_once 'includes/footer.php';
?>