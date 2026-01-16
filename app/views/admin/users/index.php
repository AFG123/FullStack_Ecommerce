<?php
$title = "Manage Users - Admin";
ob_start();
?>

<div class="row mb-4">
    <div class="col-md-12">
        <h2>Users</h2>
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
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo $user['id']; ?></td>
                                <td><?php echo htmlspecialchars($user['name']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo ucfirst($user['status']); ?></td>
                                <td>
                                    <a href="<?php echo BASE_URL; ?>/admin/users/toggle/<?php echo $user['id']; ?>" class="btn btn-sm btn-warning">
                                        <?php echo $user['status'] == 'active' ? 'Block' : 'Unblock'; ?>
                                    </a>
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