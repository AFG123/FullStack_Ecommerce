<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? SITE_NAME; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>/assets/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="<?php echo BASE_URL; ?>/"><?php echo SITE_NAME; ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>/">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>/products">Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>/support/faq">FAQ</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>/support/contact">Contact</a></li>
                </ul>
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['user_id']) && (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin')): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <?php echo $_SESSION['user_name']; ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/profile">Profile</a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/orders">My Orders</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/logout">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>/login">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>/signup">Sign Up</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container mt-4" style="padding-top: 70px;">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <?php echo $content; ?>
    </main>

    <footer class="bg-primary text-white text-center py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><?php echo SITE_NAME; ?></h5>
                    <p>Your trusted source for digital products.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p>&copy; 2025 <?php echo SITE_NAME; ?>. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets/js/script.js"></script>
</body>
</html>