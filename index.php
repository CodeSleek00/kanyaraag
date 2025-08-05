<?php
session_start();
require_once 'config/database.php';

// Generate session ID if not exists
if (!isset($_SESSION['cart_session_id'])) {
    $_SESSION['cart_session_id'] = uniqid('cart_', true);
}

// Get cart count
$session_id = $_SESSION['cart_session_id'];
$stmt = $pdo->prepare("SELECT SUM(quantity) as total_items FROM cart WHERE session_id = ?");
$stmt->execute([$session_id]);
$cart_count = $stmt->fetch(PDO::FETCH_ASSOC)['total_items'] ?? 0;

// Fetch all active products
$stmt = $pdo->query("SELECT * FROM products WHERE status = 'active' ORDER BY created_at DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kanyaraag - Online Shopping</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header>
        <nav>
            <div class="nav-container">
                <div class="logo">
                    <h1>Kanyaraag</h1>
                </div>
                <div class="nav-links">
                    <a href="index.php">Home</a>
                    <a href="#products">Products</a>
                    <a href="#about">About</a>
                    <a href="#contact">Contact</a>
                </div>
                <div class="cart-icon">
                    <a href="cart.php" style="color: inherit; text-decoration: none;">
                        <i class="fas fa-shopping-cart"></i>
                        <span id="cart-count"><?php echo $cart_count; ?></span>
                    </a>
                </div>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Welcome to Kanyaraag</h1>
            <p>Discover amazing products at great prices</p>
            <a href="#products" class="cta-button">Shop Now</a>
        </div>
    </section>

    <!-- Products Section -->
    <section id="products" class="products">
        <div class="container">
            <h2>Our Products</h2>
            <div class="products-grid">
                <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <div class="product-image">
                        <img src="assets/images/<?php echo $product['image'] ?: 'default.jpg'; ?>" alt="<?php echo $product['name']; ?>">
                        <?php if ($product['discount_percentage'] > 0): ?>
                            <div class="discount-badge">-<?php echo $product['discount_percentage']; ?>%</div>
                        <?php endif; ?>
                    </div>
                    <div class="product-info">
                        <h3><?php echo $product['name']; ?></h3>
                        <p><?php echo $product['description']; ?></p>
                        <div class="product-price">
                            <?php if ($product['discount_price']): ?>
                                <span class="regular-price">₹<?php echo number_format($product['regular_price']); ?></span>
                                <span class="discount-price">₹<?php echo number_format($product['discount_price']); ?></span>
                            <?php else: ?>
                                <span class="current-price">₹<?php echo number_format($product['regular_price']); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="product-stock">Stock: <?php echo $product['stock']; ?></div>
                        <form method="POST" action="cart.php" style="margin: 0;">
                            <input type="hidden" name="action" value="add_to_cart">
                            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="add-to-cart">
                                <i class="fas fa-cart-plus"></i> Add to Cart
                            </button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about">
        <div class="container">
            <h2>About Kanyaraag</h2>
            <p>We are committed to providing the best shopping experience with quality products and excellent customer service.</p>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact">
        <div class="container">
            <h2>Contact Us</h2>
            <div class="contact-info">
                <p><i class="fas fa-phone"></i> +91 1234567890</p>
                <p><i class="fas fa-envelope"></i> info@kanyaraag.com</p>
                <p><i class="fas fa-map-marker-alt"></i> Mumbai, Maharashtra, India</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2024 Kanyaraag. All rights reserved.</p>
        </div>
    </footer>
</body>
</html> 