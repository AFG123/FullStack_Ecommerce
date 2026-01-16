

<?php
$title = "Home - " . SITE_NAME;
ob_start();
// Pick a hero image (could randomize or rotate if desired)
$heroImage = BASE_URL . '/assets/images/burgess-milner-OYYE4g-I5ZQ-unsplash.jpg';
?>
<div class="hero-section hero-full" style="background-image: url('<?php echo $heroImage; ?>');">
    <div class="hero-overlay-card">
        <h1 class="display-4 fw-bold">Stylish Clothes Collection</h1>
        <p class="lead">Discover the latest trends in fashion</p>
        <a href="<?php echo BASE_URL; ?>/products" class="btn btn-primary btn-lg">Shop Now</a>
    </div>
</div>

<section class="py-5 featured-section">
    <div class="container">
        <h2 class="section-title">Featured Products</h2>
        <div id="featuredCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php
                $chunks = array_chunk($featuredProducts, 3);
                foreach ($chunks as $index => $chunk):
                ?>
                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                    <div class="row">
                        <?php foreach ($chunk as $product): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card product-card h-100">
                                <?php
                                $screenshots = json_decode($product['screenshots'] ?? '[]', true);
                                $image = !empty($screenshots) ? SITE_URL . '/' . $screenshots[0] : BASE_URL . '/assets/images/placeholder.svg';
                                ?>
                                <img src="<?php echo $image; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['title']); ?>" style="height: 200px; object-fit: cover; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?php echo htmlspecialchars($product['title']); ?></h5>
                                    <p class="card-text"><?php echo substr(htmlspecialchars($product['description']), 0, 100) . '...'; ?></p>
                                    <div class="mt-auto">
                                        <p class="text-primary fw-bold">$<?php echo number_format($product['price'], 2); ?></p>
                                        <a href="<?php echo BASE_URL; ?>/products/<?php echo $product['id']; ?>" class="btn btn-primary">View Details</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#featuredCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#featuredCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
        <div class="text-center mt-4">
            <a href="<?php echo BASE_URL; ?>/products" class="btn btn-outline-primary">View All Products</a>
        </div>
    </div>
</section>

<section class="py-5 best-sellers-section">
    <div class="container">
        <h2 class="section-title">Best Sellers</h2>
        <div id="bestSellersCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
            <div class="carousel-inner">
                <?php
                $chunks = array_chunk($bestSellers ?? [], 4);
                foreach ($chunks as $index => $chunk):
                ?>
                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                    <div class="row">
                        <?php foreach ($chunk as $product): ?>
                        <div class="col-md-3 mb-4">
                            <div class="card product-card h-100">
                                <?php
                                $screenshots = json_decode($product['screenshots'] ?? '[]', true);
                                $image = !empty($screenshots) ? SITE_URL . '/' . $screenshots[0] : BASE_URL . '/assets/images/placeholder.svg';
                                ?>
                                <img src="<?php echo $image; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['title']); ?>" style="height: 200px; object-fit: cover; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?php echo htmlspecialchars($product['title']); ?></h5>
                                    <p class="card-text"><?php echo substr(htmlspecialchars($product['description']), 0, 100) . '...'; ?></p>
                                    <div class="mt-auto">
                                        <p class="text-primary fw-bold">$<?php echo number_format($product['price'], 2); ?></p>
                                        <a href="<?php echo BASE_URL; ?>/products/<?php echo $product['id']; ?>" class="btn btn-primary">View Details</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#bestSellersCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#bestSellersCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
</section>

<section class="py-5 why-choose-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 class="section-title">Why Choose Us?</h2>
                <div class="row text-center mt-5">
                    <div class="col-md-4 mb-4">
                        <div class="category-card p-4">
                            <i class="fas fa-palette fa-3x text-primary mb-3"></i>
                            <h4>Stylish Designs</h4>
                            <p>Discover the latest fashion trends and styles.</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="category-card p-4">
                            <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                            <h4>Secure Payment</h4>
                            <p>Safe and secure checkout process.</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="category-card p-4">
                            <i class="fas fa-headset fa-3x text-primary mb-3"></i>
                            <h4>24/7 Support</h4>
                            <p>Our support team is here to help you anytime.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<section class="py-5 testimonials-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 class="section-title">What Our Customers Says</h2>
                <div id="testimonialsCarousel" class="carousel slide mt-5" data-bs-ride="carousel" data-bs-interval="4000">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <div class="row justify-content-center">
                                <div class="col-md-6 mb-4">
                                    <div class="testimonial-card">
                                        <p>"Great platform! Found exactly what I needed for my business. The quality is outstanding and the delivery was super fast."</p>
                                        <cite>- John Doe, Entrepreneur</cite>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="row justify-content-center">
                                <div class="col-md-6 mb-4">
                                    <div class="testimonial-card">
                                        <p>"Fast delivery and excellent support. The customer service team is always ready to help with any questions."</p>
                                        <cite>- Jane Smith, Designer</cite>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="row justify-content-center">
                                <div class="col-md-6 mb-4">
                                    <div class="testimonial-card">
                                        <p>"Highly recommend for digital products. The templates I purchased exceeded my expectations in terms of quality and usability."</p>
                                        <cite>- Bob Johnson, Developer</cite>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="row justify-content-center">
                                <div class="col-md-6 mb-4">
                                    <div class="testimonial-card">
                                        <p>"Amazing collection of resources. I've saved so much time and money by using the products from this platform."</p>
                                        <cite>- Sarah Wilson, Marketing Manager</cite>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="row justify-content-center">
                                <div class="col-md-6 mb-4">
                                    <div class="testimonial-card">
                                        <p>"The best investment I've made for my creative projects. Professional quality at affordable prices."</p>
                                        <cite>- Mike Chen, Photographer</cite>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="row justify-content-center">
                                <div class="col-md-6 mb-4">
                                    <div class="testimonial-card">
                                        <p>"Outstanding customer experience from start to finish. Will definitely be back for more products!"</p>
                                        <cite>- Lisa Brown, Small Business Owner</cite>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#testimonialsCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#testimonialsCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                    <div class="carousel-indicators mt-4">
                        <button type="button" data-bs-target="#testimonialsCarousel" data-bs-slide-to="0" class="active" aria-current="true"></button>
                        <button type="button" data-bs-target="#testimonialsCarousel" data-bs-slide-to="1"></button>
                        <button type="button" data-bs-target="#testimonialsCarousel" data-bs-slide-to="2"></button>
                        <button type="button" data-bs-target="#testimonialsCarousel" data-bs-slide-to="3"></button>
                        <button type="button" data-bs-target="#testimonialsCarousel" data-bs-slide-to="4"></button>
                        <button type="button" data-bs-target="#testimonialsCarousel" data-bs-slide-to="5"></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>