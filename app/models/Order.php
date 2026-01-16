<?php
require_once 'Model.php';

class Order extends Model {
    protected $table = 'orders';

    public function createOrder($userId, $items, $total, $couponId = null, $discount = 0) {
        $this->db->beginTransaction();
        try {
            // Create order
            $orderId = $this->insert([
                'user_id' => $userId,
                'total' => $total,
                'coupon_id' => $couponId,
                'discount' => $discount,
                'status' => 'pending' // Changed from 'completed'
            ]);

            // Add order items
            foreach ($items as $item) {
                $this->db->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)")
                    ->execute([$orderId, $item['id'], $item['quantity'], $item['price']]);

                // Add to user purchases
                $this->db->prepare("INSERT INTO user_purchases (user_id, product_id, order_id) VALUES (?, ?, ?)")
                    ->execute([$userId, $item['id'], $orderId]);
            }

            // Update coupon usage if used
            if ($couponId) {
                $this->db->prepare("UPDATE coupons SET used_count = used_count + 1 WHERE id = ?")
                    ->execute([$couponId]);
            }

            $this->db->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function getUserOrders($userId) {
        $sql = "SELECT o.*, COUNT(oi.id) as item_count
                FROM orders o
                LEFT JOIN order_items oi ON o.id = oi.order_id
                WHERE o.user_id = ?
                GROUP BY o.id
                ORDER BY o.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
}
?>