<?php
$title = "My Orders - " . SITE_NAME;
ob_start();
?>

<div class="row">
    <div class="col-md-12">
        <h2>My Orders</h2>

        <?php if (empty($orders)): ?>
            <div class="alert alert-info">
                You haven't placed any orders yet. <a href="<?php echo BASE_URL; ?>/products">Start shopping</a>
            </div>
        <?php else: ?>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Items</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td>#<?php echo $order['id']; ?></td>
                                        <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                        <td>$<?php echo number_format($order['total'], 2); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $order['status'] == 'completed' ? 'success' : 'warning'; ?>">
                                                <?php echo ucfirst($order['status']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo $order['item_count']; ?> item(s)</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>