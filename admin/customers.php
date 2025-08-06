<?php
session_start();
require_once '../config/database.php';

// Direct access to admin customers - login check removed
$_SESSION['admin_logged_in'] = true;
$_SESSION['admin_username'] = 'faiz';

// Get customer data from orders
$stmt = $pdo->query("SELECT DISTINCT ocd.*, COUNT(o.order_id) as total_orders, 
                             SUM(o.total_amount) as total_spent,
                             MAX(o.created_at) as last_order_date
                     FROM order_customer_details ocd 
                     LEFT JOIN orders o ON ocd.order_id = o.order_id 
                     GROUP BY ocd.email 
                     ORDER BY total_spent DESC");
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers - Admin Panel</title>
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
        .admin-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .admin-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        .admin-table th,
        .admin-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .admin-table th {
            background: #f8f9fa;
            font-weight: bold;
            color: #333;
        }
        .admin-table tr:hover {
            background: #f8f9fa;
        }
        .customer-info {
            display: flex;
            align-items: center;
        }
        .customer-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e74c3c;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            font-weight: bold;
        }
        .stats-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: bold;
        }
        .badge-orders {
            background: #d1ecf1;
            color: #0c5460;
        }
        .badge-spent {
            background: #d4edda;
            color: #155724;
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
        .search-box {
            margin-bottom: 1rem;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 300px;
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
                <li><a href="customers.php" class="active"><i class="fas fa-users"></i> Customers</a></li>
                <li><a href="reports.php"><i class="fas fa-chart-bar"></i> Reports</a></li>
                <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
            </ul>
            
            <a href="logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <h1><i class="fas fa-users"></i> Customer Management</h1>
            <p>Manage and view customer information</p>

            <div class="admin-container">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <h3>All Customers (<?php echo count($customers); ?>)</h3>
                    <input type="text" id="searchCustomers" placeholder="Search customers..." class="search-box">
                </div>

                <?php if (empty($customers)): ?>
                    <p style="text-align: center; color: #666; padding: 2rem;">No customers found.</p>
                <?php else: ?>
                    <table class="admin-table" id="customersTable">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Contact</th>
                                <th>Location</th>
                                <th>Total Orders</th>
                                <th>Total Spent</th>
                                <th>Last Order</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($customers as $customer): ?>
                                <tr>
                                    <td>
                                        <div class="customer-info">
                                            <div class="customer-avatar">
                                                <?php echo strtoupper(substr($customer['first_name'], 0, 1)); ?>
                                            </div>
                                            <div>
                                                <strong><?php echo htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']); ?></strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div><?php echo htmlspecialchars($customer['email']); ?></div>
                                        <div style="color: #666; font-size: 0.9rem;"><?php echo htmlspecialchars($customer['phone']); ?></div>
                                    </td>
                                    <td>
                                        <div><?php echo htmlspecialchars($customer['city'] . ', ' . $customer['state']); ?></div>
                                        <div style="color: #666; font-size: 0.9rem;"><?php echo htmlspecialchars($customer['pincode']); ?></div>
                                    </td>
                                    <td>
                                        <span class="stats-badge badge-orders">
                                            <?php echo $customer['total_orders']; ?> orders
                                        </span>
                                    </td>
                                    <td>
                                        <span class="stats-badge badge-spent">
                                            ₹<?php echo number_format($customer['total_spent']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php echo $customer['last_order_date'] ? date('M j, Y', strtotime($customer['last_order_date'])) : 'Never'; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Search functionality
        document.getElementById('searchCustomers').addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('#customersTable tbody tr');
            
            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    </script>
</body>
</html>