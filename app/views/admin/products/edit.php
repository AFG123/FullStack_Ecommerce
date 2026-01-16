<?php
$title = "Edit Product - Admin";
ob_start();
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3>Edit Product</h3>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="title" class="form-label">Product Title</label>
                            <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($product['title']); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?php echo $product['price']; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($product['description']); ?></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-control" id="category_id" name="category_id" required>
                                <option value="">Select Category</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category['id']; ?>" <?php echo $category['id'] == $product['category_id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($category['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="active" <?php echo $product['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                                <option value="inactive" <?php echo $product['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="stock_limit" class="form-label">Stock Limit</label>
                            <input type="number" class="form-control" id="stock_limit" name="stock_limit" value="<?php echo $product['stock_limit']; ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="screenshots" class="form-label">Product Images (leave empty to keep current)</label>
                        <input type="file" class="form-control" id="screenshots" name="screenshots[]" multiple accept="image/*">
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="<?php echo BASE_URL; ?>/admin/products" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
?>