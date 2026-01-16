<?php
require_once 'Model.php';

class Product extends Model {
    protected $table = 'products';

    public function getWithCategory($limit = '', $offset = '', $status = 'active', $categoryId = '', $priceRange = '') {
        $sql = "SELECT p.*, c.name as category_name
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id";
        $params = [];
        $where = [];

        if ($status !== '') {
            $where[] = "p.status = ?";
            $params[] = $status;
        }

        if ($categoryId !== '') {
            $where[] = "p.category_id = ?";
            $params[] = $categoryId;
        }

        if ($priceRange !== '') {
            switch ($priceRange) {
                case 'below500':
                    $where[] = "p.price < 500";
                    break;
                case '500-1000':
                    $where[] = "p.price BETWEEN 500 AND 1000";
                    break;
                case '1000plus':
                    $where[] = "p.price > 1000";
                    break;
            }
        }

        if (!empty($where)) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }

        $sql .= " ORDER BY p.created_at DESC";

        if ($limit) {
            $sql .= " LIMIT $limit";
            if ($offset) {
                $sql .= " OFFSET $offset";
            }
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function search($query, $category = '', $priceMin = '', $priceMax = '') {
        $sql = "SELECT p.*, c.name as category_name
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.status = 'active' AND (p.title LIKE ? OR p.description LIKE ?)";
        $params = ["%$query%", "%$query%"];

        if ($category) {
            $sql .= " AND c.name = ?";
            $params[] = $category;
        }

        if ($priceMin !== '') {
            $sql .= " AND p.price >= ?";
            $params[] = $priceMin;
        }

        if ($priceMax !== '') {
            $sql .= " AND p.price <= ?";
            $params[] = $priceMax;
        }

        $sql .= " ORDER BY p.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getRelated($categoryId, $excludeId, $limit = 4) {
        $sql = "SELECT * FROM products
                WHERE category_id = ? AND id != ? AND status = 'active'
                ORDER BY created_at DESC LIMIT $limit";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$categoryId, $excludeId]);
        return $stmt->fetchAll();
    }

    public function getLowStock() {
        $sql = "SELECT * FROM products WHERE stock_limit <= 3 AND stock_limit > 0 AND status = 'active'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countWithFilters($status = 'active', $categoryId = '', $priceRange = '') {
        $sql = "SELECT COUNT(*) as count FROM products p";
        $params = [];
        $where = [];

        if ($status !== '') {
            $where[] = "p.status = ?";
            $params[] = $status;
        }

        if ($categoryId !== '') {
            $where[] = "p.category_id = ?";
            $params[] = $categoryId;
        }

        if ($priceRange !== '') {
            switch ($priceRange) {
                case 'below500':
                    $where[] = "p.price < 500";
                    break;
                case '500-1000':
                    $where[] = "p.price BETWEEN 500 AND 1000";
                    break;
                case '1000plus':
                    $where[] = "p.price > 1000";
                    break;
            }
        }

        if (!empty($where)) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch()['count'];
    }
}
?>