<?php
session_start();
require_once '../config/database.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

// Get statistics
$stmt = $pdo->query("SELECT COUNT(*) as total_products FROM products");
$totalProducts = $stmt->fetch(PDO::FETCH_ASSOC)['total_products'];

$stmt = $pdo->query("SELECT COUNT(*) as total_orders FROM orders");
$totalOrders = $stmt->fetch(PDO::FETCH_ASSOC)['total_orders'];

$stmt = $pdo->query("SELECT SUM(total_amount) as total_revenue FROM orders WHERE payment_status = 'paid'");
$totalRevenue = $stmt->fetch(PDO::FETCH_ASSOC)['total_revenue'] ?? 0;

$stmt = $pdo->query("SELECT COUNT(*) as pending_orders FROM orders WHERE status = 'pending'");
$pendingOrders = $stmt->fetch(PDO::FETCH_ASSOC)['pending_orders'];

// Get recent orders
$stmt = $pdo->query("SELECT o.*, ocd.first_name, ocd.last_name, ocd.email FROM orders o 
                     LEFT JOIN order_customer_details ocd ON o.order_id = ocd.order_id 
                     ORDER BY o.created_at DESC LIMIT 5");
$recentOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Kanyaraag</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
        }
        .stat-card.products i { color: #3498db; }
        .stat-card.orders i { color: #e74c3c; }
        .stat-card.revenue i { color: #27ae60; }
        .stat-card.pending i { color: #f39c12; }
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }
        .recent-orders {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .recent-orders h3 {
            margin-bottom: 1rem;
            color: #333;
        }
        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #eee;
        }
        .order-item:last-child {
            border-bottom: none;
        }
        .order-info h4 {
            margin: 0;
            color: #333;
        }
        .order-info p {
            margin: 0.5rem 0 0 0;
            color: #666;
            font-size: 0.9rem;
        }
        .order-status {
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 0.8rem;
            font-weight: bold;
        }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-processing { background: #d1ecf1; color: #0c5460; }
        .status-shipped { background: #d4edda; color: #155724; }
        .status-delivered { background: #d1e7dd; color: #0f5132; }
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
                <li><a href="dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="products.php"><i class="fas fa-box"></i> Products</a></li>
                <li><a href="orders.php"><i class="fas fa-shopping-cart"></i> Orders</a></li>
                <li><a href="customers.php"><i class="fas fa-users"></i> Customers</a></li>
                <li><a href="reports.php"><i class="fas fa-chart-bar"></i> Reports</a></li>
                <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
            </ul>
            
            <a href="logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <h1>Dashboard</h1>
            <p>Welcome back, <?php echo $_SESSION['admin_username']; ?>!</p>

            <!-- Statistics -->
            <div class="stats-grid">
                <div class="stat-card products">
                    <i class="fas fa-box"></i>
                    <div class="stat-number"><?php echo $totalProducts; ?></div>
                    <div class="stat-label">Total Products</div>
                </div>
                
                <div class="stat-card orders">
                    <i class="fas fa-shopping-cart"></i>
                    <div class="stat-number"><?php echo $totalOrders; ?></div>
                    <div class="stat-label">Total Orders</div>
                </div>
                
                <div class="stat-card revenue">
                    <i class="fas fa-rupee-sign"></i>
                    <div class="stat-number">₹<?php echo number_format($totalRevenue); ?></div>
                    <div class="stat-label">Total Revenue</div>
                </div>
                
                <div class="stat-card pending">
                    <i class="fas fa-clock"></i>
                    <div class="stat-number"><?php echo $pendingOrders; ?></div>
                    <div class="stat-label">Pending Orders</div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="recent-orders">
                <h3><i class="fas fa-list"></i> Recent Orders</h3>
                
                <?php if (empty($recentOrders)): ?>
                    <p style="text-align: center; color: #666;">No orders found.</p>
                <?php else: ?>
                    <?php foreach ($recentOrders as $order): ?>
                        <div class="order-item">
                            <div class="order-info">
                                <h4>Order #<?php echo $order['order_number']; ?></h4>
                                <p>
                                    <strong><?php echo $order['first_name'] . ' ' . $order['last_name']; ?></strong> - 
                                    <?php echo $order['email']; ?><br>
                                    <strong>Amount:</strong> ₹<?php echo number_format($order['total_amount']); ?> | 
                                    <strong>Date:</strong> <?php echo date('M j, Y', strtotime($order['created_at'])); ?>
                                </p>
                            </div>
                            <div>
                                <span class="order-status status-<?php echo $order['status']; ?>">
                                    <?php echo ucfirst($order['status']); ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                
                <div style="text-align: center; margin-top: 1rem;">
                    <a href="orders.php" class="admin-btn">View All Orders</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 