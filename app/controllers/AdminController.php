<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Category.php';

class AdminController extends Controller {
    private $productModel;
    private $orderModel;
    private $userModel;
    private $categoryModel;
    private $db;

    public function __construct() {
        $this->productModel = new Product();
        $this->orderModel = new Order();
        $this->userModel = new User();
        $this->categoryModel = new Category();
        $this->db = Database::getInstance()->getConnection();
    }

    protected function render($view, $data = []) {
        extract($data);
        $viewPath = __DIR__ . '/../views/' . $view . '.php';
        if (file_exists($viewPath)) {
            require_once $viewPath;
            require_once __DIR__ . '/../views/layouts/admin.php';
        } else {
            die("View $view not found");
        }
    }

    protected function requireAdmin() {
        if (!$this->isAdmin()) {
            $this->redirect('/admin/login');
        }
    }

    public function index() {
        $this->requireAdmin();

        $stats = [
            'total_products' => $this->productModel->count(),
            'total_orders' => $this->orderModel->count(),
            'total_users' => $this->userModel->count(['role' => 'user']),
            'low_stock_products' => count($this->productModel->getLowStock())
        ];

        $recentOrders = $this->db->query("
            SELECT o.*, u.name
            FROM orders o
            LEFT JOIN users u ON o.user_id = u.id
            ORDER BY o.created_at DESC
            LIMIT 5
        ")->fetchAll();

        $lowStockProducts = $this->productModel->getLowStock();

        $recentUsers = $this->db->query("SELECT name, email, created_at FROM users WHERE role = 'user' ORDER BY created_at DESC LIMIT 5")->fetchAll();
        $topProducts = $this->db->query("SELECT title, price, status FROM products ORDER BY created_at DESC LIMIT 5")->fetchAll();

        $this->render('admin/index', [
            'stats' => $stats,
            'recentOrders' => $recentOrders,
            'lowStockProducts' => $lowStockProducts,
            'recentUsers' => $recentUsers,
            'topProducts' => $topProducts
        ]);
    }

    public function login() {
        if ($this->isAdmin()) {
            $this->redirect('/admin');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = $this->userModel->authenticate($email, $password);
            if ($user && in_array($user['role'], ['admin', 'editor'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                session_regenerate_id(true);
                $this->redirect('/admin');
            } else {
                $error = "Invalid admin credentials";
                $this->render('auth/admin_login', ['error' => $error]);
                return;
            }
        }

        $this->render('auth/admin_login');
    }

    public function logout() {
        session_destroy();
        $this->redirect('/admin/login');
    }

    public function products() {
        $this->requireAdmin();

        $products = $this->productModel->getWithCategory('', '', ''); // Include all products
        $this->render('admin/products/index', ['products' => $products]);
    }

    public function createProduct() {
        $this->requireAdmin();

        $db = Database::getInstance()->getConnection();
        $categories = $db->query("SELECT * FROM categories")->fetchAll();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? 0;
            $categoryId = $_POST['category_id'] ?? 0;
            $stockLimit = $_POST['stock_limit'] ?? 0;

            // Handle file upload
            $filePath = '';
            if (isset($_FILES['product_file']) && $_FILES['product_file']['error'] == 0) {
                $uploadDir = dirname(__DIR__, 4) . '/storage/products/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

                $fileName = time() . '_' . basename($_FILES['product_file']['name']);
                $filePath = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['product_file']['tmp_name'], $filePath)) {
                    $filePath = str_replace(dirname(__DIR__, 4) . '/', '', $filePath);
                }
            }

            // Handle screenshots
            $screenshots = [];
            if (isset($_FILES['screenshots'])) {
                $uploadDir = dirname(__DIR__, 4) . '/storage/uploads/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

                foreach ($_FILES['screenshots']['tmp_name'] as $key => $tmpName) {
                    if ($_FILES['screenshots']['error'][$key] == 0) {
                        $fileName = time() . '_' . basename($_FILES['screenshots']['name'][$key]);
                        $destPath = $uploadDir . $fileName;
                        if (move_uploaded_file($tmpName, $destPath)) {
                            $screenshots[] = str_replace(dirname(__DIR__, 4) . '/', '', $destPath);
                        }
                    }
                }
            }

            $this->productModel->insert([
                'title' => $title,
                'description' => $description,
                'price' => $price,
                'category_id' => $categoryId,
                'file_path' => $filePath,
                'screenshots' => json_encode($screenshots),
                'stock_limit' => $stockLimit
            ]);

            $this->redirect('/admin/products');
        }

        $this->render('admin/products/create', ['categories' => $categories]);
    }

    public function editProduct($id) {
        $this->requireAdmin();

        $product = $this->productModel->find($id);
        if (!$product) {
            $this->redirect('/admin/products');
        }

        $db = Database::getInstance()->getConnection();
        $categories = $db->query("SELECT * FROM categories")->fetchAll();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? 0;
            $categoryId = $_POST['category_id'] ?? 0;
            $status = $_POST['status'] ?? 'active';
            $stockLimit = $_POST['stock_limit'] ?? 0;

            $updateData = [
                'title' => $title,
                'description' => $description,
                'price' => $price,
                'category_id' => $categoryId,
                'status' => $status,
                'stock_limit' => $stockLimit
            ];

            // Handle file upload if new file
            if (isset($_FILES['product_file']) && $_FILES['product_file']['error'] == 0) {
                $uploadDir = dirname(__DIR__, 4) . '/storage/products/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

                $fileName = time() . '_' . basename($_FILES['product_file']['name']);
                $filePath = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['product_file']['tmp_name'], $filePath)) {
                    $updateData['file_path'] = str_replace(dirname(__DIR__, 4) . '/', '', $filePath);
                }
            }

            // Handle new screenshots
            if (!empty($_FILES['screenshots']['name'][0])) {
                $uploadDir = dirname(__DIR__, 4) . '/storage/uploads/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

                $existingScreenshots = json_decode($product['screenshots'] ?? '[]', true);
                foreach ($_FILES['screenshots']['tmp_name'] as $key => $tmpName) {
                    if ($_FILES['screenshots']['error'][$key] == 0) {
                        $fileName = time() . '_' . basename($_FILES['screenshots']['name'][$key]);
                        $destPath = $uploadDir . $fileName;
                        if (move_uploaded_file($tmpName, $destPath)) {
                            $existingScreenshots[] = str_replace(dirname(__DIR__, 4) . '/', '', $destPath);
                        }
                    }
                }
                $updateData['screenshots'] = json_encode($existingScreenshots);
            }

            $this->productModel->update($id, $updateData);
            $this->redirect('/admin/products');
        }

        $screenshots = json_decode($product['screenshots'] ?? '[]', true);
        $this->render('admin/products/edit', [
            'product' => $product,
            'categories' => $categories,
            'screenshots' => $screenshots
        ]);
    }

    public function deleteProduct($id) {
        $this->requireAdmin();

        // Check if product has any orders
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM order_items WHERE product_id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch();

        if ($result['count'] > 0) {
            // Product has orders, cannot delete. Set to inactive instead.
            $this->productModel->update($id, ['status' => 'inactive']);
            $this->redirect('/admin/products?message=Product has orders and cannot be deleted. It has been set to inactive.');
        } else {
            $this->productModel->delete($id);
            $this->redirect('/admin/products');
        }
    }

    public function orders() {
        $this->requireAdmin();

        $orders = $this->db->query("
            SELECT o.*, u.name as user_name, 
                   GROUP_CONCAT(CONCAT(p.title, ' (', oi.quantity, ')') SEPARATOR ', ') as products
            FROM orders o
            LEFT JOIN users u ON o.user_id = u.id
            LEFT JOIN order_items oi ON o.id = oi.order_id
            LEFT JOIN products p ON oi.product_id = p.id
            GROUP BY o.id
            ORDER BY o.created_at DESC
        ")->fetchAll();
        $this->render('admin/orders/index', ['orders' => $orders]);
    }

    public function updateOrderStatus($orderId) {
        $this->requireAdmin();

        $status = $_POST['status'] ?? '';
        $validStatuses = ['pending', 'shipped', 'delivered', 'completed', 'cancelled'];

        if (in_array($status, $validStatuses)) {
            $this->orderModel->update($orderId, ['status' => $status]);
        }

        $this->redirect('/admin/orders');
    }

    public function deleteOrder($id) {
        $this->requireAdmin();

        // Delete order_items first
        $this->db->prepare("DELETE FROM order_items WHERE order_id = ?")->execute([$id]);

        // Delete user_purchases
        $this->db->prepare("DELETE FROM user_purchases WHERE order_id = ?")->execute([$id]);

        // Delete order
        $this->db->prepare("DELETE FROM orders WHERE id = ?")->execute([$id]);

        $this->redirect('/admin/orders');
    }

    public function users() {
        $this->requireAdmin();

        $users = $this->userModel->findAll(['role' => 'user']);
        $this->render('admin/users/index', ['users' => $users]);
    }

    public function toggleUserStatus($id) {
        $this->requireAdmin();

        $user = $this->userModel->find($id);
        if ($user) {
            $newStatus = $user['status'] == 'active' ? 'blocked' : 'active';
            $this->userModel->update($id, ['status' => $newStatus]);
        }
        $this->redirect('/admin/users');
    }

    public function categories() {
        $this->requireAdmin();

        $categories = $this->categoryModel->getAll();
        $error = $_GET['error'] ?? null;
        $success = $_GET['success'] ?? null;
        $this->render('admin/categories/index', ['categories' => $categories, 'error' => $error, 'success' => $success]);
    }

    public function createCategory() {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            if ($name) {
                $this->categoryModel->insert(['name' => $name]);
                $this->redirect('/admin/categories?success=Category added successfully');
            }
        }

        $this->render('admin/categories/create');
    }

    public function editCategory($id) {
        $this->requireAdmin();

        $category = $this->categoryModel->find($id);
        if (!$category) {
            $this->redirect('/admin/categories');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            if ($name) {
                $this->categoryModel->update($id, ['name' => $name]);
                $this->redirect('/admin/categories?success=Category updated successfully');
            }
        }

        $this->render('admin/categories/edit', ['category' => $category]);
    }

    public function deleteCategory($id) {
        $this->requireAdmin();

        // Check if category has products
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM products WHERE category_id = ?");
        $stmt->execute([$id]);
        $productCount = $stmt->fetch()['count'];
        
        if ($productCount > 0) {
            // Cannot delete category with products
            $this->redirect('/admin/categories?error=Cannot delete category with existing products');
        }

        $this->categoryModel->delete($id);
        $this->redirect('/admin/categories?success=Category deleted successfully');
    }

    public function sales() {
        $this->requireAdmin();

        // Get sales data for each product
        $productSales = $this->db->query("
            SELECT 
                p.id,
                p.title,
                p.price,
                COUNT(oi.id) as total_sales,
                SUM(oi.quantity) as total_quantity,
                SUM(oi.price * oi.quantity) as total_revenue
            FROM products p
            LEFT JOIN order_items oi ON p.id = oi.product_id
            LEFT JOIN orders o ON oi.order_id = o.id AND o.status = 'completed'
            GROUP BY p.id, p.title, p.price
            ORDER BY total_sales DESC
        ")->fetchAll();

        // Get top selling products (limit 10)
        $topSellingProducts = array_slice($productSales, 0, 10);

        // Prepare data for chart (top 10 products)
        $chartData = [
            'labels' => [],
            'sales' => [],
            'revenue' => []
        ];

        foreach ($topSellingProducts as $product) {
            $chartData['labels'][] = htmlspecialchars(substr($product['title'], 0, 20)) . (strlen($product['title']) > 20 ? '...' : '');
            $chartData['sales'][] = (int)$product['total_sales'];
            $chartData['revenue'][] = (float)$product['total_revenue'];
        }

        // Calculate summary statistics
        $totalProductsSold = array_sum(array_column($productSales, 'total_quantity'));
        $totalRevenue = array_sum(array_column($productSales, 'total_revenue'));
        $totalOrders = $this->db->query("SELECT COUNT(*) as count FROM orders WHERE status = 'completed'")->fetch()['count'];

        $this->render('admin/sales/index', [
            'productSales' => $productSales,
            'topSellingProducts' => $topSellingProducts,
            'chartData' => $chartData,
            'summary' => [
                'total_products_sold' => $totalProductsSold,
                'total_revenue' => $totalRevenue,
                'total_orders' => $totalOrders,
                'average_order_value' => $totalOrders > 0 ? $totalRevenue / $totalOrders : 0
            ]
        ]);
    }
}
?>