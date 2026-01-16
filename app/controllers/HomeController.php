<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/models/Product.php';

class HomeController extends Controller {
    private $productModel;

    public function __construct() {
        $this->productModel = new Product();
    }

    public function index() {
        // Get latest 6 products as featured
        $featuredProducts = $this->productModel->getWithCategory(6);

        // Get top 8 best selling products
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("
            SELECT p.*, COALESCE(SUM(oi.quantity), 0) as total_sales
            FROM products p
            LEFT JOIN order_items oi ON p.id = oi.product_id
            WHERE p.status = 'active'
            GROUP BY p.id
            ORDER BY total_sales DESC
            LIMIT 8
        ");
        $stmt->execute();
        $bestSellers = $stmt->fetchAll();

        $this->render('home/index', ['featuredProducts' => $featuredProducts, 'bestSellers' => $bestSellers]);
    }
}
?>