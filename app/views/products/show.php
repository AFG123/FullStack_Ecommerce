<?php
$title = htmlspecialchars($product['title']) . " - " . SITE_NAME;
ob_start();
?>

<div class="container my-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>/">Home</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>/products">Products</a></li>
                            <li class="breadcrumb-item active"><?php echo htmlspecialchars($product['title']); ?></li>
                        </ol>
                    </nav>

                    <h1 class="display-5 fw-bold text-primary mb-3"><?php echo htmlspecialchars($product['title']); ?></h1>
                    <p class="text-muted fs-5 mb-4"><?php echo htmlspecialchars($product['category_name']); ?></p>

                    <?php if (!empty($screenshots)): ?>
                        <div id="productCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
                            <div class="carousel-inner rounded">
                                <?php foreach ($screenshots as $index => $screenshot): ?>
                                    <div class="carousel-item <?php echo $index == 0 ? 'active' : ''; ?>">
                                        <img src="<?php echo SITE_URL; ?>/<?php echo $screenshot; ?>" class="d-block w-100" alt="Screenshot <?php echo $index + 1; ?>" style="height: 400px; object-fit: cover; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon bg-dark rounded-circle p-2"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon bg-dark rounded-circle p-2"></span>
                            </button>
                        </div>
                    <?php else: ?>
                        <img src="<?php echo BASE_URL; ?>/assets/images/placeholder.svg" class="img-fluid mb-4 rounded" alt="No images available" style="height: 400px; object-fit: cover; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                    <?php endif; ?>

                    <div class="mb-4">
                        <h4 class="fw-bold text-dark mb-3">Product Description</h4>
                        <p class="lead"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                    </div>

                    <div class="row align-items-center mb-4">
                        <div class="col-md-6">
                            <h3 class="text-success fw-bold">$<?php echo number_format($product['price'], 2); ?></h3>
                            <small class="text-muted">Instant download after purchase</small>
                        </div>
                        <div class="col-md-6 text-end">
                            <?php if (isset($_SESSION['user_id']) && (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin')): ?>
                                <form method="POST" action="<?php echo BASE_URL; ?>/orders/create" class="d-inline">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <input type="number" name="quantity" value="1" min="1" class="form-control d-inline-block w-auto me-2" style="width: 80px;">
                                    <button type="submit" class="btn btn-success">Order Now</button>
                                </form>
                            <?php else: ?>
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#loginModal">Order Now</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <?php if (!empty($related)): ?>
                <div class="card shadow border-0 mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Related Products</h5>
                    </div>
                    <div class="card-body">
                        <?php foreach ($related as $relProduct): ?>
                            <div class="mb-3 p-3 border rounded">
                                    <a href="<?php echo BASE_URL; ?>/products/<?php echo $relProduct['id']; ?>" class="text-decoration-none">
                                    <h6 class="text-dark"><?php echo htmlspecialchars($relProduct['title']); ?></h6>
                                    <p class="text-primary fw-bold mb-0">$<?php echo number_format($relProduct['price'], 2); ?></p>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="card shadow border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">Why Buy From Us?</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Instant Download</li>
                        <li class="mb-2"><i class="fas fa-shield-alt text-success me-2"></i> Secure Payment</li>
                        <li class="mb-2"><i class="fas fa-headset text-success me-2"></i> 24/7 Support</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Login Required</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p>You need to be logged in to place an order.</p>
                <a href="/Vorcas Tech Project/public/login" class="btn btn-primary me-2">Login</a>
                <a href="/Vorcas Tech Project/public/signup" class="btn btn-outline-primary">Sign Up</a>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>