<?php
session_start();
require_once 'config/database.php';

$order_id = $_GET['order_id'] ?? '';
$order_number = $_GET['order_number'] ?? '';

if (empty($order_id) || empty($order_number)) {
    header('Location: index.php');
    exit();
}

// Get order details
$stmt = $pdo->prepare("SELECT * FROM orders WHERE order_id = ? AND order_number = ?");
$stmt->execute([$order_id, $order_number]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success - Kanyaraag</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .success-container {
            max-width: 600px;
            margin: 100px auto;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            text-align: center;
        }
        .success-icon {
            font-size: 4rem;
            color: #27ae60;
            margin-bottom: 1rem;
        }
        .order-details {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 5px;
            margin: 1rem 0;
            text-align: left;
        }
        .order-details h3 {
            margin-bottom: 1rem;
            color: #333;
        }
        .order-details p {
            margin-bottom: 0.5rem;
            color: #666;
        }
        .order-details strong {
            color: #333;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <nav>
            <div class="nav-container">
                <div class="logo">
                    <h1><a href="index.php" style="text-decoration: none; color: #e74c3c;">Kanyaraag</a></h1>
                </div>
                <div class="nav-links">
                    <a href="index.php">Continue Shopping</a>
                </div>
            </div>
        </nav>
    </header>

    <div class="success-container">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        
        <h1>Order Placed Successfully!</h1>
        <p>Thank you for your purchase. Your order has been confirmed.</p>
        
        <div class="order-details">
            <h3>Order Information</h3>
            <p><strong>Order Number:</strong> <?php echo $order['order_number']; ?></p>
            <p><strong>Order Date:</strong> <?php echo date('F j, Y', strtotime($order['created_at'])); ?></p>
            <p><strong>Total Amount:</strong> ₹<?php echo number_format($order['total_amount']); ?></p>
            <p><strong>Status:</strong> <span style="color: #f39c12;"><?php echo ucfirst($order['status']); ?></span></p>
            <p><strong>Payment Status:</strong> <span style="color: #e74c3c;"><?php echo ucfirst($order['payment_status']); ?></span></p>
        </div>
        
        <div style="margin: 2rem 0;">
            <h3>What's Next?</h3>
            <p>You will receive an email confirmation shortly. Our team will process your order and update you on the delivery status.</p>
        </div>
        
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <a href="index.php" class="cta-button">
                <i class="fas fa-shopping-cart"></i> Continue Shopping
            </a>
            <a href="admin/login.php" class="cta-button" style="background: #3498db;">
                <i class="fas fa-user"></i> Track Order
            </a>
        </div>
    </div>

    <script>
        // Clear cart from localStorage
        localStorage.removeItem('cart');
    </script>
</body>
</html> 