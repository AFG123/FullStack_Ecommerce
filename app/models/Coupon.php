<?php
require_once 'Model.php';

class Coupon extends Model {
    protected $table = 'coupons';

    public function validateCoupon($code) {
        $coupon = $this->findAll(['code' => $code]);
        if (!$coupon) return false;

        $coupon = $coupon[0];
        $now = date('Y-m-d');

        // Check expiry
        if ($coupon['expiry_date'] && $coupon['expiry_date'] < $now) {
            return false;
        }

        // Check usage limit
        if ($coupon['usage_limit'] > 0 && $coupon['used_count'] >= $coupon['usage_limit']) {
            return false;
        }

        return $coupon;
    }

    public function applyDiscount($coupon, $total) {
        if ($coupon['type'] == 'flat') {
            return min($coupon['value'], $total);
        } else {
            return ($total * $coupon['value']) / 100;
        }
    }
}
?>