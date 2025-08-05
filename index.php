<?php
session_start();
require_once 'config/database.php';

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
                    <i class="fas fa-shopping-cart" onclick="toggleCart()"></i>
                    <span id="cart-count">0</span>
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
                        <button class="add-to-cart" onclick="addToCart(<?php echo $product['product_id']; ?>, '<?php echo addslashes($product['name']); ?>', <?php echo $product['discount_price'] ?: $product['regular_price']; ?>, <?php echo $product['regular_price']; ?>, <?php echo $product['discount_percentage']; ?>)">
                            Add to Cart
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Shopping Cart Sidebar -->
    <div id="cart-sidebar" class="cart-sidebar">
        <div class="cart-header">
            <h3>Shopping Cart</h3>
            <button onclick="toggleCart()" class="close-cart">&times;</button>
        </div>
        <div id="cart-items" class="cart-items">
            <!-- Cart items will be displayed here -->
        </div>
        <div class="cart-footer">
            <div class="cart-summary">
                <div class="cart-subtotal">
                    <span>Subtotal:</span>
                    <span>₹<span id="cart-subtotal">0</span></span>
                </div>
                <div class="cart-discount">
                    <span>Discount:</span>
                    <span>-₹<span id="cart-discount">0</span></span>
                </div>
                <div class="cart-total">
                    <strong>Total: ₹<span id="cart-total">0</span></strong>
                </div>
            </div>
            <button onclick="proceedToCheckout()" class="checkout-btn">Proceed to Checkout</button>
        </div>
    </div>

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

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2024 Kanyaraag. All rights reserved.</p>
        </div>
    </footer>

    <script src="assets/js/script.js"></script>
</body>
</html> 