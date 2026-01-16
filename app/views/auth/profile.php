<?php
$title = "Profile - " . SITE_NAME;
ob_start();
?>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3>Edit Profile</h3>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="pincode" class="form-label">Pincode</label>
                            <input type="text" class="form-control" id="pincode" name="pincode" value="<?php echo htmlspecialchars($user['pincode'] ?? ''); ?>">
                        </div>
                    </div>
                    <hr>
                    <h5>Change Password (leave blank to keep current)</h5>
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" class="form-control" id="current_password" name="current_password">
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password">
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Purchase History</h5>
            </div>
            <div class="card-body">
                <?php if (empty($purchases)): ?>
                    <p class="text-muted">No purchases yet.</p>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($purchases as $purchase): ?>
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1"><?php echo htmlspecialchars($purchase['title']); ?></h6>
                                        <small class="text-muted"><?php echo date('M d, Y', strtotime($purchase['created_at'])); ?></small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-success"><?php echo $purchase['status']; ?></span>
                                        <br>
                                        <small><?php echo $purchase['download_count']; ?> downloads</small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>