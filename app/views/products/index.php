<?php
$title = "Products - " . SITE_NAME;
ob_start();
?>

<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Products</h2>
            <form method="GET" action="<?php echo BASE_URL; ?>/products/search" class="d-flex">
                <input type="text" name="q" class="form-control me-2" placeholder="Search products..." value="<?php echo $_GET['q'] ?? ''; ?>">
                <button type="submit" class="btn btn-outline-primary">Search</button>
            </form>
        </div>

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="<?php echo BASE_URL; ?>/products" class="row g-3">
                    <div class="col-md-4">
                        <label for="category" class="form-label">Category</label>
                        <select name="category" id="category" class="form-select">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>" <?php echo ($selectedCategory == $category['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="price" class="form-label">Price Range</label>
                        <select name="price" id="price" class="form-select">
                            <option value="">All Prices</option>
                            <option value="below500" <?php echo ($selectedPrice == 'below500') ? 'selected' : ''; ?>>Below $500</option>
                            <option value="500-1000" <?php echo ($selectedPrice == '500-1000') ? 'selected' : ''; ?>>$500 - $1000</option>
                            <option value="1000plus" <?php echo ($selectedPrice == '1000plus') ? 'selected' : ''; ?>>$1000+</option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">Apply Filters</button>
                        <a href="<?php echo BASE_URL; ?>/products" class="btn btn-outline-secondary">Clear Filters</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <?php foreach ($products as $product): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100">
                        <?php
                        $screenshots = json_decode($product['screenshots'] ?? '[]', true);
                        $image = !empty($screenshots) ? SITE_URL . '/' . $screenshots[0] : BASE_URL . '/assets/images/placeholder.svg';
                        ?>
                        <img src="<?php echo $image; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['title']); ?>" style="height: 200px; object-fit: cover; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?php echo htmlspecialchars($product['title']); ?></h5>
                            <p class="card-text"><?php echo substr(htmlspecialchars($product['description']), 0, 100) . '...'; ?></p>
                            <p class="text-muted small"><?php echo htmlspecialchars($product['category_name']); ?></p>
                            <div class="mt-auto">
                                <p class="text-primary fw-bold">$<?php echo number_format($product['price'], 2); ?></p>
                                <a href="<?php echo BASE_URL; ?>/products/<?php echo $product['id']; ?>" class="btn btn-primary">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ($totalPages > 1): ?>
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php
                    $queryParams = [];
                    if (!empty($selectedCategory)) $queryParams[] = "category=" . urlencode($selectedCategory);
                    if (!empty($selectedPrice)) $queryParams[] = "price=" . urlencode($selectedPrice);
                    $queryString = !empty($queryParams) ? '&' . implode('&', $queryParams) : '';
                    ?>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo $i == $currentPage ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i . $queryString; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>