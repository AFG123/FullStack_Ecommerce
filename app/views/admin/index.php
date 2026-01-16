<?php
$title = "Admin Dashboard - " . SITE_NAME;
ob_start();
?>

<div class="row mb-4">
    <div class="col-12">
        <div class="admin-header text-center">
            <h1 class="display-4 fw-bold mb-2">
                <i class="fas fa-tachometer-alt me-3"></i>Admin Dashboard
            </h1>
            <p class="lead">Welcome back! Here's an overview of your e-commerce platform.</p>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card stats-card admin-card h-100">
            <div class="card-body text-center">
                <i class="fas fa-box fa-2x mb-2"></i>
                <h3 class="card-title"><?php echo $stats['total_products']; ?></h3>
                <p class="card-text">Total Products</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stats-card admin-card h-100">
            <div class="card-body text-center">
                <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                <h3 class="card-title"><?php echo $stats['total_orders']; ?></h3>
                <p class="card-text">Total Orders</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stats-card admin-card h-100">
            <div class="card-body text-center">
                <i class="fas fa-users fa-2x mb-2"></i>
                <h3 class="card-title"><?php echo $stats['total_users']; ?></h3>
                <p class="card-text">Total Users</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stats-card admin-card h-100">
            <div class="card-body text-center">
                <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                <h3 class="card-title"><?php echo $stats['low_stock_products']; ?></h3>
                <p class="card-text">Low Stock Items</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mb-4">
        <div class="card admin-card">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-shopping-cart me-2"></i>Recent Orders
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Order ID</th>
                                <th>User</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentOrders as $order): ?>
                                <tr>
                                    <td>#<?php echo $order['id']; ?></td>
                                    <td><?php echo htmlspecialchars($order['name']); ?></td>
                                    <td>$<?php echo number_format($order['total'], 2); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $order['status'] == 'completed' ? 'success' : 'warning'; ?>">
                                            <?php echo ucfirst($order['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card admin-card">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>Low Stock Alert
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($lowStockProducts)): ?>
                    <div class="text-center text-success">
                        <i class="fas fa-check-circle fa-2x mb-2"></i>
                        <p class="mb-0">All products are well stocked!</p>
                    </div>
                <?php else: ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($lowStockProducts as $product): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?php echo htmlspecialchars($product['title']); ?>
                                <span class="badge bg-warning rounded-pill"><?php echo $product['stock_limit']; ?> left</span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card admin-card">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-users me-2"></i>Recent Users
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentUsers as $user): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card admin-card">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-box me-2"></i>Top Products
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($topProducts as $product): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($product['title']); ?></td>
                                    <td>$<?php echo number_format($product['price'], 2); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $product['status'] == 'active' ? 'success' : 'secondary'; ?>">
                                            <?php echo ucfirst($product['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
?>