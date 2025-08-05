<?php
session_start();
require_once 'config/database.php';

// Generate session ID if not exists
if (!isset($_SESSION['cart_session_id'])) {
    $_SESSION['cart_session_id'] = uniqid('cart_', true);
}

$session_id = $_SESSION['cart_session_id'];

// Handle cart actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add_to_cart') {
        $product_id = $_POST['product_id'] ?? 0;
        $quantity = $_POST['quantity'] ?? 1;
        
        // Get product details
        $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ? AND status = 'active'");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($product) {
            $price = $product['discount_price'] ?: $product['regular_price'];
            
            // Check if item already in cart
            $stmt = $pdo->prepare("SELECT * FROM cart WHERE session_id = ? AND product_id = ?");
            $stmt->execute([$session_id, $product_id]);
            $existing_item = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($existing_item) {
                // Update quantity
                $new_quantity = $existing_item['quantity'] + $quantity;
                $stmt = $pdo->prepare("UPDATE cart SET quantity = ?, updated_at = NOW() WHERE cart_id = ?");
                $stmt->execute([$new_quantity, $existing_item['cart_id']]);
            } else {
                // Add new item
                $stmt = $pdo->prepare("INSERT INTO cart (session_id, product_id, product_name, quantity, unit_price, regular_price, discount_percentage) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$session_id, $product_id, $product['name'], $quantity, $price, $product['regular_price'], $product['discount_percentage']]);
            }
        }
        
        header('Location: cart.php');
        exit();
    }
    
    if ($action === 'update_quantity') {
        $cart_id = $_POST['cart_id'] ?? 0;
        $quantity = $_POST['quantity'] ?? 1;
        
        if ($quantity <= 0) {
            // Remove item
            $stmt = $pdo->prepare("DELETE FROM cart WHERE cart_id = ? AND session_id = ?");
            $stmt->execute([$cart_id, $session_id]);
        } else {
            // Update quantity
            $stmt = $pdo->prepare("UPDATE cart SET quantity = ?, updated_at = NOW() WHERE cart_id = ? AND session_id = ?");
            $stmt->execute([$quantity, $cart_id, $session_id]);
        }
        
        header('Location: cart.php');
        exit();
    }
    
    if ($action === 'remove_item') {
        $cart_id = $_POST['cart_id'] ?? 0;
        
        $stmt = $pdo->prepare("DELETE FROM cart WHERE cart_id = ? AND session_id = ?");
        $stmt->execute([$cart_id, $session_id]);
        
        header('Location: cart.php');
        exit();
    }
}

// Get cart items
$stmt = $pdo->prepare("SELECT c.*, p.image FROM cart c LEFT JOIN products p ON c.product_id = p.product_id WHERE c.session_id = ? ORDER BY c.created_at DESC");
$stmt->execute([$session_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate totals
$subtotal = 0;
$total_discount = 0;
$total_items = 0;

foreach ($cart_items as $item) {
    $regular_total = $item['regular_price'] * $item['quantity'];
    $discounted_total = $item['unit_price'] * $item['quantity'];
    $item_discount = $regular_total - $discounted_total;
    
    $subtotal += $regular_total;
    $total_discount += $item_discount;
    $total_items += $item['quantity'];
}

$final_total = $subtotal - $total_discount;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Kanyaraag</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .cart-page {
            max-width: 1200px;
            margin: 100px auto 50px;
            padding: 0 20px;
        }
        .cart-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .cart-empty {
            text-align: center;
            padding: 3rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .cart-empty i {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 1rem;
        }
        .cart-items {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .cart-item {
            display: flex;
            align-items: center;
            padding: 1.5rem;
            border-bottom: 1px solid #eee;
        }
        .cart-item:last-child {
            border-bottom: none;
        }
        .item-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
            margin-right: 1rem;
        }
        .item-details {
            flex: 1;
        }
        .item-name {
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        .item-price {
            color: #e74c3c;
            font-weight: bold;
        }
        .item-regular-price {
            text-decoration: line-through;
            color: #999;
            font-size: 0.9rem;
            margin-right: 0.5rem;
        }
        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }
        .quantity-btn {
            background: #f8f9fa;
            border: 1px solid #ddd;
            width: 30px;
            height: 30px;
            border-radius: 3px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .quantity-input {
            width: 50px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 3px;
            padding: 5px;
        }
        .remove-btn {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 0.8rem;
        }
        .cart-summary {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1.5rem;
            margin-top: 1rem;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }
        .summary-total {
            border-top: 1px solid #eee;
            padding-top: 1rem;
            margin-top: 1rem;
            font-size: 1.2rem;
            font-weight: bold;
        }
        .checkout-btn {
            width: 100%;
            background: #27ae60;
            color: white;
            border: none;
            padding: 15px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            font-size: 1rem;
            margin-top: 1rem;
        }
        .checkout-btn:hover {
            background: #229954;
        }
        .continue-shopping {
            display: inline-block;
            background: #3498db;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 1rem;
        }
        .continue-shopping:hover {
            background: #2980b9;
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

    <div class="cart-page">
        <div class="cart-header">
            <h1><i class="fas fa-shopping-cart"></i> Shopping Cart</h1>
            <p><?php echo $total_items; ?> items in your cart</p>
        </div>

        <?php if (empty($cart_items)): ?>
            <div class="cart-empty">
                <i class="fas fa-shopping-cart"></i>
                <h2>Your cart is empty</h2>
                <p>Looks like you haven't added any items to your cart yet.</p>
                <a href="index.php" class="continue-shopping">
                    <i class="fas fa-arrow-left"></i> Continue Shopping
                </a>
            </div>
        <?php else: ?>
            <div class="cart-items">
                <?php foreach ($cart_items as $item): ?>
                    <div class="cart-item">
                        <img src="assets/images/<?php echo $item['image'] ?: 'default.jpg'; ?>" alt="<?php echo $item['product_name']; ?>" class="item-image">
                        
                        <div class="item-details">
                            <div class="item-name"><?php echo $item['product_name']; ?></div>
                            <div class="item-price">
                                <?php if ($item['discount_percentage'] > 0): ?>
                                    <span class="item-regular-price">₹<?php echo number_format($item['regular_price']); ?></span>
                                <?php endif; ?>
                                ₹<?php echo number_format($item['unit_price']); ?>
                            </div>
                            
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="update_quantity">
                                <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                <div class="quantity-controls">
                                    <button type="button" class="quantity-btn" onclick="updateQuantity(<?php echo $item['cart_id']; ?>, -1)">-</button>
                                    <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" class="quantity-input" onchange="this.form.submit()">
                                    <button type="button" class="quantity-btn" onclick="updateQuantity(<?php echo $item['cart_id']; ?>, 1)">+</button>
                                </div>
                            </form>
                        </div>
                        
                        <div style="text-align: right;">
                            <div style="font-weight: bold; margin-bottom: 0.5rem;">
                                ₹<?php echo number_format($item['unit_price'] * $item['quantity']); ?>
                            </div>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="remove_item">
                                <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                <button type="submit" class="remove-btn" onclick="return confirm('Remove this item from cart?')">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="cart-summary">
                <h3>Order Summary</h3>
                
                <div class="summary-row">
                    <span>Subtotal (<?php echo $total_items; ?> items):</span>
                    <span>₹<?php echo number_format($subtotal); ?></span>
                </div>
                
                <?php if ($total_discount > 0): ?>
                    <div class="summary-row" style="color: #27ae60;">
                        <span>Discount:</span>
                        <span>-₹<?php echo number_format($total_discount); ?></span>
                    </div>
                <?php endif; ?>
                
                <div class="summary-row summary-total">
                    <span>Total:</span>
                    <span>₹<?php echo number_format($final_total); ?></span>
                </div>
                
                <a href="checkout.php" class="checkout-btn">
                    <i class="fas fa-lock"></i> Proceed to Checkout
                </a>
                
                <a href="index.php" class="continue-shopping">
                    <i class="fas fa-arrow-left"></i> Continue Shopping
                </a>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function updateQuantity(cartId, change) {
            const input = event.target.parentNode.querySelector('input[name="quantity"]');
            const newQuantity = parseInt(input.value) + change;
            
            if (newQuantity >= 1) {
                input.value = newQuantity;
                input.form.submit();
            }
        }
    </script>
</body>
</html> 