<?php
session_start();
require_once '../config/database.php';

// Direct access to admin reports - login check removed
$_SESSION['admin_logged_in'] = true;
$_SESSION['admin_username'] = 'faiz';

// Get sales data for charts
$stmt = $pdo->query("SELECT DATE(created_at) as date, COUNT(*) as orders, SUM(total_amount) as revenue 
                     FROM orders 
                     WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) 
                     GROUP BY DATE(created_at) 
                     ORDER BY date DESC");
$salesData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get top products
$stmt = $pdo->query("SELECT p.name, SUM(oi.quantity) as total_sold, SUM(oi.total_price) as revenue
                     FROM order_items oi 
                     JOIN products p ON oi.product_id = p.product_id 
                     GROUP BY oi.product_id 
                     ORDER BY total_sold DESC 
                     LIMIT 10");
$topProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get monthly statistics
$stmt = $pdo->query("SELECT 
                        COUNT(*) as total_orders,
                        SUM(total_amount) as total_revenue,
                        AVG(total_amount) as avg_order_value,
                        SUM(CASE WHEN payment_status = 'paid' THEN 1 ELSE 0 END) as paid_orders
                     FROM orders 
                     WHERE MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW())");
$monthlyStats = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Admin Panel</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            background: #2c3e50;
            color: white;
            padding: 1rem;
            position: relative;
        }
        .sidebar h2 {
            margin-bottom: 2rem;
            text-align: center;
            color: #e74c3c;
        }
        .sidebar-menu {
            list-style: none;
            padding: 0;
        }
        .sidebar-menu li {
            margin-bottom: 0.5rem;
        }
        .sidebar-menu a {
            display: block;
            padding: 12px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: #34495e;
        }
        .sidebar-menu i {
            margin-right: 10px;
        }
        .main-content {
            flex: 1;
            padding: 2rem;
            background: #f8f9fa;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-card i {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #e74c3c;
        }
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: #333;
        }
        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }
        .chart-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .reports-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }
        .top-products {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .product-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .product-item:last-child {
            border-bottom: none;
        }
        .logout-btn {
            position: absolute;
            bottom: 1rem;
            left: 1rem;
            right: 1rem;
            background: #e74c3c;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            display: block;
        }
        .logout-btn:hover {
            background: #c0392b;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2><i class="fas fa-tachometer-alt"></i> Admin Panel</h2>
            <ul class="sidebar-menu">
                <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="products.php"><i class="fas fa-box"></i> Products</a></li>
                <li><a href="orders.php"><i class="fas fa-shopping-cart"></i> Orders</a></li>
                <li><a href="customers.php"><i class="fas fa-users"></i> Customers</a></li>
                <li><a href="reports.php" class="active"><i class="fas fa-chart-bar"></i> Reports</a></li>
                <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
            </ul>
            
            <a href="logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <h1><i class="fas fa-chart-bar"></i> Sales Reports & Analytics</h1>
            <p>View detailed sales reports and business analytics</p>

            <!-- Monthly Statistics -->
            <div class="stats-grid">
                <div class="stat-card">
                    <i class="fas fa-shopping-cart"></i>
                    <div class="stat-number"><?php echo $monthlyStats['total_orders'] ?? 0; ?></div>
                    <div class="stat-label">Orders This Month</div>
                </div>
                
                <div class="stat-card">
                    <i class="fas fa-rupee-sign"></i>
                    <div class="stat-number">₹<?php echo number_format($monthlyStats['total_revenue'] ?? 0); ?></div>
                    <div class="stat-label">Revenue This Month</div>
                </div>
                
                <div class="stat-card">
                    <i class="fas fa-calculator"></i>
                    <div class="stat-number">₹<?php echo number_format($monthlyStats['avg_order_value'] ?? 0); ?></div>
                    <div class="stat-label">Average Order Value</div>
                </div>
                
                <div class="stat-card">
                    <i class="fas fa-check-circle"></i>
                    <div class="stat-number"><?php echo $monthlyStats['paid_orders'] ?? 0; ?></div>
                    <div class="stat-label">Paid Orders</div>
                </div>
            </div>

            <!-- Sales Chart -->
            <div class="chart-container">
                <h3><i class="fas fa-line-chart"></i> Sales Trend (Last 30 Days)</h3>
                <canvas id="salesChart" width="400" height="200"></canvas>
            </div>

            <!-- Reports Grid -->
            <div class="reports-grid">
                <!-- Top Products -->
                <div class="top-products">
                    <h3><i class="fas fa-star"></i> Top Selling Products</h3>
                    <?php if (empty($topProducts)): ?>
                        <p style="text-align: center; color: #666; padding: 2rem;">No sales data available.</p>
                    <?php else: ?>
                        <?php foreach ($topProducts as $product): ?>
                            <div class="product-item">
                                <div>
                                    <strong><?php echo htmlspecialchars($product['name']); ?></strong>
                                    <div style="color: #666; font-size: 0.9rem;">
                                        <?php echo $product['total_sold']; ?> units sold
                                    </div>
                                </div>
                                <div style="font-weight: bold; color: #27ae60;">
                                    ₹<?php echo number_format($product['revenue']); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Quick Actions -->
                <div class="top-products">
                    <h3><i class="fas fa-tools"></i> Quick Actions</h3>
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <a href="orders.php?status=pending" style="padding: 10px; background: #f39c12; color: white; text-decoration: none; border-radius: 5px; text-align: center;">
                            <i class="fas fa-clock"></i> View Pending Orders
                        </a>
                        <a href="products.php?action=add" style="padding: 10px; background: #27ae60; color: white; text-decoration: none; border-radius: 5px; text-align: center;">
                            <i class="fas fa-plus"></i> Add New Product
                        </a>
                        <a href="customers.php" style="padding: 10px; background: #3498db; color: white; text-decoration: none; border-radius: 5px; text-align: center;">
                            <i class="fas fa-users"></i> View All Customers
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Sales Chart
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesData = <?php echo json_encode(array_reverse($salesData)); ?>;
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: salesData.map(item => {
                    const date = new Date(item.date);
                    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                }),
                datasets: [{
                    label: 'Revenue (₹)',
                    data: salesData.map(item => item.revenue),
                    borderColor: '#e74c3c',
                    backgroundColor: 'rgba(231, 76, 60, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Orders',
                    data: salesData.map(item => item.orders),
                    borderColor: '#3498db',
                    backgroundColor: 'rgba(52, 152, 219, 0.1)',
                    tension: 0.4,
                    yAxisID: 'y1'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Revenue (₹)'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Orders'
                        },
                        grid: {
                            drawOnChartArea: false,
                        },
                    }
                }
            }
        });
    </script>
</body>
</html>