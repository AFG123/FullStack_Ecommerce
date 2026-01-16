<?php
$title = "Manage Orders - Admin";
ob_start();
?>

<div class="row mb-4">
    <div class="col-md-12">
        <h2>Orders</h2>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>User</th>
                            <th>Products</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?php echo $order['id']; ?></td>
                                <td><?php echo htmlspecialchars($order['user_name']); ?></td>
                                <td><?php echo htmlspecialchars($order['products']); ?></td>
                                <td>$<?php echo number_format($order['total'], 2); ?></td>
                                <td><?php echo ucfirst($order['status']); ?></td>
                                <td><?php echo date('Y-m-d', strtotime($order['created_at'])); ?></td>
                                <td>
                                    <form method="POST" action="<?php echo BASE_URL; ?>/admin/orders/delete/<?php echo $order['id']; ?>" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this order?')">
                                        <button type="submit" class="btn btn-sm btn-danger me-2">Delete</button>
                                    </form>
                                    <form method="POST" action="<?php echo BASE_URL; ?>/admin/orders/update/<?php echo $order['id']; ?>" class="d-inline">
                                        <select name="status" class="form-select form-select-sm d-inline w-auto me-1">
                                            <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="shipped" <?php echo $order['status'] == 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                            <option value="delivered" <?php echo $order['status'] == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                            <option value="completed" <?php echo $order['status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
                                            <option value="cancelled" <?php echo $order['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
?>