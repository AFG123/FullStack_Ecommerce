<?php
$title = "Manage Products - Admin";
ob_start();
?>

<?php if (isset($_GET['message'])): ?>
<div class="alert alert-info">
    <?php echo htmlspecialchars($_GET['message']); ?>
</div>
<?php endif; ?>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Products</h2>
            <a href="<?php echo BASE_URL; ?>/admin/products/create" class="btn btn-primary">Add Product</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Price</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Stock Remaining</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?php echo $product['id']; ?></td>
                                <td><?php echo htmlspecialchars($product['title']); ?></td>
                                <td>$<?php echo number_format($product['price'], 2); ?></td>
                                <td><?php echo htmlspecialchars($product['category_name']); ?></td>
                                <td><?php echo ucfirst($product['status']); ?></td>
                                <td><?php echo $product['stock_limit']; ?></td>
                                <td>
                                    <a href="<?php echo BASE_URL; ?>/admin/products/edit/<?php echo $product['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="<?php echo BASE_URL; ?>/admin/products/delete/<?php echo $product['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
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