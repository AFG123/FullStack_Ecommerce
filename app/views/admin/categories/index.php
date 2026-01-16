<?php
$title = "Manage Categories - Admin";
ob_start();
?>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Categories</h2>
            <a href="<?php echo BASE_URL; ?>/admin/categories/create" class="btn btn-primary">Add Category</a>
        </div>
    </div>
</div>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>
<?php if (isset($success)): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category): ?>
                            <tr>
                                <td><?php echo $category['id']; ?></td>
                                <td><?php echo htmlspecialchars($category['name']); ?></td>
                                <td>
                                    <a href="<?php echo BASE_URL; ?>/admin/categories/edit/<?php echo $category['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="<?php echo BASE_URL; ?>/admin/categories/delete/<?php echo $category['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
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