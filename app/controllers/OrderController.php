<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/models/Order.php';

class OrderController extends Controller {
    private $orderModel;

    public function __construct() {
        $this->orderModel = new Order();
    }

    public function create() {
        $this->requireLogin();

        // Prevent admin from ordering
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            $this->redirect('/');
        }

        $productId = $_POST['product_id'] ?? 0;
        $quantity = $_POST['quantity'] ?? 1;

        require_once __DIR__ . '/models/Product.php';
        $productModel = new Product();
        $product = $productModel->find($productId);

        if (!$product || $product['status'] != 'active') {
            $this->redirect('/products');
        }

        // Check stock availability
        if ($product['stock_limit'] < $quantity) {
            $this->redirect('/products/' . $productId . '?error=Insufficient stock');
        }

        $total = $product['price'] * $quantity;

        $orderId = $this->orderModel->createOrder($_SESSION['user_id'], [
            [
                'id' => $productId,
                'title' => $product['title'],
                'price' => $product['price'],
                'quantity' => $quantity
            ]
        ], $total, null, 0);

        if ($orderId) {
            // Decrease stock
            $productModel->update($productId, ['stock_limit' => $product['stock_limit'] - $quantity]);
            $this->redirect('/orders');
        } else {
            $this->redirect('/products/' . $productId);
        }
    }

    public function index() {
        $this->requireLogin();

        $orders = $this->orderModel->getUserOrders($_SESSION['user_id']);
        $this->render('orders/index', ['orders' => $orders]);
    }

    public function download($productId) {
        $this->requireLogin();

        // Check if user has purchased this product
        $sql = "SELECT p.file_path, up.download_count
                FROM user_purchases up
                JOIN products p ON up.product_id = p.id
                WHERE up.user_id = ? AND up.product_id = ?";
        $stmt = $this->orderModel->db->prepare($sql);
        $stmt->execute([$_SESSION['user_id'], $productId]);
        $purchase = $stmt->fetch();

        if ($purchase && file_exists($purchase['file_path'])) {
            // Update download count
            $this->orderModel->db->prepare("UPDATE user_purchases SET download_count = download_count + 1, last_download = NOW() WHERE user_id = ? AND product_id = ?")
                ->execute([$_SESSION['user_id'], $productId]);

            // Force download
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($purchase['file_path']) . '"');
            readfile($purchase['file_path']);
            exit;
        } else {
            $this->redirect('/profile');
        }
    }
}
?>