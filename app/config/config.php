<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'vendex');
define('DB_USER', 'root');
define('DB_PASS', '');

// Site configuration
define('SITE_NAME', 'Vendex');
define('SITE_URL', 'http://localhost/Vorcas Tech Project');
define('BASE_URL', SITE_URL . '/public');

// Email configuration (for notifications)
define('SMTP_HOST', 'smtp.example.com');
define('SMTP_USER', 'your-email@example.com');
define('SMTP_PASS', 'your-password');
define('SMTP_PORT', 587);

// Session configuration
session_name('vendex_session');
$sessionPath = __DIR__ . '/../storage/sessions';
if (!is_dir($sessionPath)) {
    mkdir($sessionPath, 0755, true);
}
ini_set('session.save_path', $sessionPath);
ini_set('session.cookie_lifetime', 86400); // 1 day
ini_set('session.gc_maxlifetime', 86400);
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 0); // localhost
session_set_cookie_params(86400, '/');

// Other constants
define('UPLOAD_DIR', __DIR__ . '/../../storage/uploads/');
// define('PRODUCT_FILES_DIR', __DIR__ . '/../../storage/products/'); // Removed, not used

// Start session
// Moved to index.php after determining session name
?>