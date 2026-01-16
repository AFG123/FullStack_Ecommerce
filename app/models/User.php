<?php
require_once 'Model.php';

class User extends Model {
    protected $table = 'users';

    public function authenticate($identifier, $password) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = ? OR name = ? LIMIT 1");
        $stmt->execute([$identifier, $identifier]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function create($data) {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        return $this->insert($data);
    }

    public function updatePassword($id, $password) {
        return $this->update($id, ['password' => password_hash($password, PASSWORD_DEFAULT)]);
    }

    public function getPurchaseHistory($userId) {
        $sql = "SELECT p.title, p.price, o.created_at, o.status, up.download_count
                FROM user_purchases up
                JOIN products p ON up.product_id = p.id
                JOIN orders o ON up.order_id = o.id
                WHERE up.user_id = ?
                ORDER BY o.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
}
?>