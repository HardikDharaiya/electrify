<?php
// includes/header.php (Session Safeguard Version)

// This is the professional way to manage sessions.
// We check if a session has NOT already been started before starting one.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electrify - Precision Engineered Electronics</title>
    
    <!-- (The rest of the file is identical) -->

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- "Royal Orchid" Custom Stylesheet -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <header class="header-custom sticky-top">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container">
                <a class="navbar-brand fw-bold" href="index.php">
                    <i class="bi bi-cpu-fill brand-icon"></i> Electrify
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto align-items-lg-center">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="products.php">Products</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="cart.php">
                                <i class="bi bi-cart"></i> Cart
                            </a>
                        </li>
                        
                        <?php if (isset($_SESSION['user_id'])): ?>
                            
                            <li class="nav-item">
                                <a class="nav-link" href="account.php">
                                    Welcome, <?php echo htmlspecialchars($_SESSION['user_first_name']); ?>
                                </a>
                            </li>
                             <li class="nav-item">
                                <a class="nav-link" href="logout.php">Logout</a>
                            </li>

                        <?php else: ?>

                            <li class="nav-item">
                                <a class="nav-link" href="login.php">Login</a>
                            </li>
                             <li class="nav-item">
                                <a class="btn btn-accent ms-lg-2" href="register.php">Register</a>
                            </li>

                        <?php endif; ?>

                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- This main container wraps all page-specific content -->
    <div class="container mt-5">