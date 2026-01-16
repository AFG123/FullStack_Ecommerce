<?php
$title = "Sales Summary - Admin";
ob_start();
?>

<div class="row mb-4">
    <div class="col-12">
        <div class="admin-header text-center">
            <h1 class="display-4 fw-bold mb-2">
                <i class="fas fa-chart-line me-3"></i>Sales Summary
            </h1>
            <p class="lead">Comprehensive sales analytics and performance metrics.</p>
        </div>
    </div>
</div>

<!-- Summary Statistics -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card stats-card admin-card h-100">
            <div class="card-body text-center">
                <i class="fas fa-box fa-2x mb-2"></i>
                <h3 class="card-title"><?php echo number_format($summary['total_products_sold']); ?></h3>
                <p class="card-text">Total Products Sold</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stats-card admin-card h-100">
            <div class="card-body text-center">
                <i class="fas fa-dollar-sign fa-2x mb-2"></i>
                <h3 class="card-title">$<?php echo number_format($summary['total_revenue'], 2); ?></h3>
                <p class="card-text">Total Revenue</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stats-card admin-card h-100">
            <div class="card-body text-center">
                <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                <h3 class="card-title"><?php echo number_format($summary['total_orders']); ?></h3>
                <p class="card-text">Completed Orders</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stats-card admin-card h-100">
            <div class="card-body text-center">
                <i class="fas fa-calculator fa-2x mb-2"></i>
                <h3 class="card-title">$<?php echo number_format($summary['average_order_value'], 2); ?></h3>
                <p class="card-text">Avg Order Value</p>
            </div>
        </div>
    </div>
</div>

<!-- Sales Chart -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card admin-card">
            <div class="card-header sales-header-bg">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Top 10 Products Sales Chart
                </h5>
            </div>
            <div class="card-body sales-chart-container">
                <canvas id="salesChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Product Sales Table -->
    <div class="col-lg-8 mb-4">
        <div class="card admin-card">
            <div class="card-header sales-header-bg">
                <h5 class="mb-0">
                    <i class="fas fa-table me-2"></i>Product Sales Details
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead style="background-color: #495057; color: #171515;">
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Sales Count</th>
                                <th>Units Sold</th>
                                <th>Total Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($productSales as $product): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($product['title']); ?></td>
                                    <td>$<?php echo number_format($product['price'], 2); ?></td>
                                    <td>
                                        <span class="badge bg-primary"><?php echo $product['total_sales']; ?></span>
                                    </td>
                                    <td><?php echo $product['total_quantity']; ?></td>
                                    <td class="fw-bold text-success">$<?php echo number_format($product['total_revenue'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Selling Products -->
    <div class="col-lg-4 mb-4">
        <div class="card admin-card">
            <div class="card-header sales-header-bg">
                <h5 class="mb-0">
                    <i class="fas fa-trophy me-2"></i>Top Selling Products
                </h5>
            </div>
            <div class="card-body" style="background-color: #343a40; color: white;">
                <?php if (empty($topSellingProducts)): ?>
                    <div class="text-center text-light">
                        <i class="fas fa-info-circle fa-2x mb-2"></i>
                        <p>No sales data available yet.</p>
                    </div>
                <?php else: ?>
                    <div class="list-group list-group-flush" style="background-color: transparent;">
                        <?php foreach ($topSellingProducts as $index => $product): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center" style="background-color: #495057; border-color: #6c757d; color: white;">
                                <div>
                                    <span class="badge bg-warning text-dark me-2">#<?php echo $index + 1; ?></span>
                                    <strong><?php echo htmlspecialchars(substr($product['title'], 0, 25)); ?><?php echo strlen($product['title']) > 25 ? '...' : ''; ?></strong>
                                    <br>
                                    <small class="text-light"><?php echo $product['total_sales']; ?> sales • <?php echo $product['total_quantity']; ?> units</small>
                                </div>
                                <span class="badge bg-success">$<?php echo number_format($product['total_revenue'], 0); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('salesChart').getContext('2d');

    const chartData = <?php echo json_encode($chartData); ?>;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartData.labels,
            datasets: [{
                label: 'Sales Count',
                data: chartData.sales,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                yAxisID: 'y'
            }, {
                label: 'Revenue ($)',
                data: chartData.revenue,
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    ticks: {
                        color: 'white'
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    }
                },
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Sales Count',
                        color: 'white'
                    },
                    ticks: {
                        color: 'white'
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Revenue ($)',
                        color: 'white'
                    },
                    ticks: {
                        color: 'white'
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        color: 'white'
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            if (context.datasetIndex === 0) {
                                return 'Sales: ' + context.parsed.y;
                            } else {
                                return 'Revenue: $' + context.parsed.y.toFixed(2);
                            }
                        }
                    }
                }
            }
        }
    });
});
</script>

<?php
$content = ob_get_clean();
?>