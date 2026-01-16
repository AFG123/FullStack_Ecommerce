<?php
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/core/Database.php';

// Autoload classes
spl_autoload_register(function ($className) {
    $paths = [
        __DIR__ . '/../app/controllers/' . $className . '.php',
        __DIR__ . '/../app/models/' . $className . '.php',
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Simple router
$request = $_SERVER['REQUEST_URI'];
$request = urldecode($request);
$request = str_replace('/Vorcas Tech Project/public', '', $request);
$request = explode('?', $request)[0];
$request = trim($request, '/');
$parts = explode('/', $request);
$controller = $parts[0] ?: 'home';
$action = $parts[1] ?? 'index';
$id = $parts[2] ?? null;

// Set session name based on controller
if ($controller == 'admin') {
    session_name('admin_session');
} else {
    session_name('user_session');
}

// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Route to controllers
try {
    switch ($controller) {
        case '':
        case 'home':
            $controllerObj = new HomeController();
            $controllerObj->index();
            break;

        case 'login':
            $controllerObj = new AuthController();
            $controllerObj->login();
            break;

        case 'signup':
            $controllerObj = new AuthController();
            $controllerObj->signup();
            break;

        case 'forgot-password':
            $controllerObj = new AuthController();
            $controllerObj->forgotPassword();
            break;

        case 'logout':
            $controllerObj = new AuthController();
            $controllerObj->logout();
            break;

        case 'profile':
            $controllerObj = new AuthController();
            $controllerObj->profile();
            break;

        case 'products':
            $controllerObj = new ProductController();
            if ($action == 'search') {
                $controllerObj->search();
            } elseif (is_numeric($action)) {
                $controllerObj->show($action);
            } else {
                $controllerObj->index();
            }
            break;

        case 'orders':
            $controllerObj = new OrderController();
            if ($action == 'create') {
                $controllerObj->create();
            } elseif ($action == 'download' && $id) {
                $controllerObj->download($id);
            } else {
                $controllerObj->index();
            }
            break;

        case 'support':
            $controllerObj = new SupportController();
            if ($action == 'contact') {
                $controllerObj->contact();
            } elseif ($action == 'faq') {
                $controllerObj->faq();
            }
            break;

        case 'admin':
            $controllerObj = new AdminController();
            if ($action == 'login') {
                $controllerObj->login();
            } elseif ($action == 'logout') {
                $controllerObj->logout();
            } elseif ($action == 'products') {
                if (isset($parts[2]) && $parts[2] == 'create') {
                    $controllerObj->createProduct();
                } elseif (isset($parts[2]) && $parts[2] == 'edit' && isset($parts[3])) {
                    $controllerObj->editProduct($parts[3]);
                } elseif (isset($parts[2]) && $parts[2] == 'delete' && isset($parts[3])) {
                    $controllerObj->deleteProduct($parts[3]);
                } else {
                    $controllerObj->products();
                }
            } elseif ($action == 'categories') {
                if (isset($parts[2]) && $parts[2] == 'create') {
                    $controllerObj->createCategory();
                } elseif (isset($parts[2]) && $parts[2] == 'edit' && isset($parts[3])) {
                    $controllerObj->editCategory($parts[3]);
                } elseif (isset($parts[2]) && $parts[2] == 'delete' && isset($parts[3])) {
                    $controllerObj->deleteCategory($parts[3]);
                } else {
                    $controllerObj->categories();
                }
            } elseif ($action == 'orders') {
                if (isset($parts[2]) && $parts[2] == 'update' && isset($parts[3])) {
                    $controllerObj->updateOrderStatus($parts[3]);
                } elseif (isset($parts[2]) && $parts[2] == 'delete' && isset($parts[3])) {
                    $controllerObj->deleteOrder($parts[3]);
                } else {
                    $controllerObj->orders();
                }
            } elseif ($action == 'users') {
                if (isset($parts[2]) && $parts[2] == 'toggle' && isset($parts[3])) {
                    $controllerObj->toggleUserStatus($parts[3]);
                } else {
                    $controllerObj->users();
                }
            } elseif ($action == 'sales') {
                $controllerObj->sales();
            } else {
                $controllerObj->index();
            }
            break;

        default:
            http_response_code(404);
            echo "Page not found";
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo "Internal server error: " . $e->getMessage();
}
?>
