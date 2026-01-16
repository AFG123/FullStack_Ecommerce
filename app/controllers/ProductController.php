<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/models/Product.php';

class ProductController extends Controller {
    private $productModel;

    public function __construct() {
        $this->productModel = new Product();
    }

    public function index() {
        $page = $_GET['page'] ?? 1;
        $limit = 12;
        $offset = ($page - 1) * $limit;

        $categoryId = $_GET['category'] ?? '';
        $priceRange = $_GET['price'] ?? '';

        $products = $this->productModel->getWithCategory($limit, $offset, 'active', $categoryId, $priceRange);
        $total = $this->productModel->countWithFilters('active', $categoryId, $priceRange);
        $totalPages = ceil($total / $limit);

        // Get all categories for filter dropdown
        $db = Database::getInstance()->getConnection();
        $categories = $db->query("SELECT * FROM categories ORDER BY name")->fetchAll();

        $this->render('products/index', [
            'products' => $products,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'categories' => $categories,
            'selectedCategory' => $categoryId,
            'selectedPrice' => $priceRange
        ]);
    }

    public function show($id) {
        $product = $this->productModel->find($id);
        if (!$product || $product['status'] != 'active') {
            $this->redirect('/products');
        }

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT name FROM categories WHERE id = ?");
        $stmt->execute([$product['category_id']]);
        $category = $stmt->fetch();
        $product['category_name'] = $category ? $category['name'] : 'Uncategorized';

        $related = $this->productModel->getRelated($product['category_id'], $id);
        $screenshots = json_decode($product['screenshots'] ?? '[]', true);

        $this->render('products/show', [
            'product' => $product,
            'related' => $related,
            'screenshots' => $screenshots
        ]);
    }

    public function search() {
        $query = $_GET['q'] ?? '';
        $category = $_GET['category'] ?? '';
        $priceMin = $_GET['price_min'] ?? '';
        $priceMax = $_GET['price_max'] ?? '';

        $products = [];
        if (!empty($query)) {
            $products = $this->productModel->search($query, $category, $priceMin, $priceMax);
        }

        $this->render('products/search', [
            'products' => $products,
            'query' => $query,
            'category' => $category,
            'priceMin' => $priceMin,
            'priceMax' => $priceMax
        ]);
    }
}
?>