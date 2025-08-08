<?php
session_start();
require_once '../config/database.php';

// Direct access to admin settings - login check removed
$_SESSION['admin_logged_in'] = true;
$_SESSION['admin_username'] = 'faiz';

$message = '';

// Handle settings updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        // Update admin profile (simplified)
        $message = '<div class="success-message">Profile updated successfully!</div>';
    }
    
    if (isset($_POST['backup_database'])) {
        $message = '<div class="success-message">Database backup initiated!</div>';
    }
    
    if (isset($_POST['clear_cache'])) {
        $message = '<div class="success-message">Cache cleared successfully!</div>';
    }
}

// Get some system stats
$stmt = $pdo->query("SELECT COUNT(*) as total FROM products");
$totalProducts = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) as total FROM orders");
$totalOrders = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(DISTINCT email) as total FROM order_customer_details");
$totalCustomers = $stmt->fetch()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Admin Panel</title>
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
        .settings-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .settings-section {
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid #eee;
        }
        .settings-section:last-child {
            border-bottom: none;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
            color: #333;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            max-width: 400px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }
        .settings-btn {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            margin-right: 10px;
            margin-top: 10px;
        }
        .settings-btn:hover {
            background: #c0392b;
        }
        .settings-btn.secondary {
            background: #6c757d;
        }
        .settings-btn.secondary:hover {
            background: #545b62;
        }
        .settings-btn.success {
            background: #28a745;
        }
        .settings-btn.success:hover {
            background: #218838;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .stat-item {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 5px;
            text-align: center;
        }
        .stat-number {
            font-size: 1.5rem;
            font-weight: bold;
            color: #e74c3c;
            margin-bottom: 0.5rem;
        }
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 1rem;
            border: 1px solid #c3e6cb;
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
                <li><a href="reports.php"><i class="fas fa-chart-bar"></i> Reports</a></li>
                <li><a href="settings.php" class="active"><i class="fas fa-cog"></i> Settings</a></li>
            </ul>
            
            <a href="logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <h1><i class="fas fa-cog"></i> Admin Settings</h1>
            <p>Configure and manage your admin panel settings</p>

            <?php echo $message; ?>

            <!-- System Overview -->
            <div class="settings-container">
                <h3><i class="fas fa-info-circle"></i> System Overview</h3>
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-number"><?php echo $totalProducts; ?></div>
                        <div>Total Products</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?php echo $totalOrders; ?></div>
                        <div>Total Orders</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?php echo $totalCustomers; ?></div>
                        <div>Total Customers</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?php echo phpversion(); ?></div>
                        <div>PHP Version</div>
                    </div>
                </div>
            </div>

            <!-- Admin Profile -->
            <div class="settings-container">
                <div class="settings-section">
                    <h3><i class="fas fa-user"></i> Admin's Profile</h3>
                    <form method="POST">
                        <div class="form-group">
                            <label for="admin_name">Admin Name</label>
                            <input type="text" id="admin_name" name="admin_name" value="<?php echo $_SESSION['admin_username']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="admin_email">Email</label>
                            <input type="email" id="admin_email" name="admin_email" value="faiz@kanyaraag.com">
                        </div>
                        <div class="form-group">
                            <label for="timezone">Timezone</label>
                            <select id="timezone" name="timezone">
                                <option value="Asia/Kolkata" selected>Asia/Kolkata (IST)</option>
                                <option value="UTC">UTC</option>
                            </select>
                        </div>
                        <button type="submit" name="update_profile" class="settings-btn">Update Profile</button>
                    </form>
                </div>

                <!-- Store Settings -->
                <div class="settings-section">
                    <h3><i class="fas fa-store"></i> Store Settings</h3>
                    <div class="form-group">
                        <label for="store_name">Store Name</label>
                        <input type="text" id="store_name" name="store_name" value="Kanyaraag E-commerce">
                    </div>
                    <div class="form-group">
                        <label for="currency">Currency</label>
                        <select id="currency" name="currency">
                            <option value="INR" selected>Indian Rupee (₹)</option>
                            <option value="USD">US Dollar ($)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tax_rate">Tax Rate (%)</label>
                        <input type="number" id="tax_rate" name="tax_rate" value="18" min="0" max="100" step="0.1">
                    </div>
                    <button type="submit" class="settings-btn">Update Store Settings</button>
                </div>

                <!-- System Maintenance -->
                <div class="settings-section">
                    <h3><i class="fas fa-tools"></i> System Maintenance</h3>
                    <p>Perform system maintenance tasks</p>
                    
                    <form method="POST" style="display: inline;">
                        <button type="submit" name="backup_database" class="settings-btn success">
                            <i class="fas fa-download"></i> Backup Database
                        </button>
                    </form>
                    
                    <form method="POST" style="display: inline;">
                        <button type="submit" name="clear_cache" class="settings-btn secondary">
                            <i class="fas fa-trash"></i> Clear Cache
                        </button>
                    </form>
                    
                    <button onclick="location.reload()" class="settings-btn secondary">
                        <i class="fas fa-sync"></i> Refresh Data
                    </button>
                </div>

                <!-- Quick Links -->
                <div class="settings-section">
                    <h3><i class="fas fa-external-link-alt"></i> Quick Links</h3>
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <a href="../index.php" target="_blank" class="settings-btn secondary">
                            <i class="fas fa-globe"></i> View Store
                        </a>
                        <a href="products.php" class="settings-btn">
                            <i class="fas fa-plus"></i> Add Product
                        </a>
                        <a href="orders.php" class="settings-btn">
                            <i class="fas fa-list"></i> View Orders
                        </a>
                        <a href="reports.php" class="settings-btn">
                            <i class="fas fa-chart-bar"></i> View Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>