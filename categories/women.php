<?php
require_once '../database_connect/db_connect.php';

// Fetch products for women's page
$sql = "SELECT * FROM products WHERE page = 'women.php' ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Women's Collection | Kanyaraag</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #6d28d9;
            --primary-light: #8b5cf6;
            --primary-dark: #4c1d95;
            --accent-color: #ec4899;
            --text-dark: #1e293b;
            --text-medium: #475569;
            --text-light: #64748b;
            --bg-light: #f8fafc;
            --bg-white: #ffffff;
            --border-radius: 12px;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.12);
            --shadow-md: 0 4px 6px rgba(0,0,0,0.1);
            --shadow-lg: 0 10px 15px rgba(0,0,0,0.1);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--text-dark);
            background-color: var(--bg-light);
            line-height: 1.5;
        }

        /* Header */
        .header {
            background-color: var(--bg-white);
            padding: 16px 24px;
            box-shadow: var(--shadow-sm);
            position: sticky;
            top: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header-nav {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .back-btn {
            color: var(--text-medium);
            font-size: 20px;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: var(--transition);
        }

        .back-btn:hover {
            background-color: #f1f5f9;
            color: var(--primary-color);
        }

        .header-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--primary-dark);
        }

        .header-actions {
            display: flex;
            gap: 16px;
        }

        .header-icon {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: var(--text-medium);
            transition: var(--transition);
            position: relative;
        }

        .header-icon:hover {
            background-color: #f1f5f9;
            color: var(--primary-color);
        }

        .cart-count {
            position: absolute;
            top: -4px;
            right: -4px;
            background-color: var(--accent-color);
            color: white;
            font-size: 10px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        /* Filter and Sort */
        .filters {
            padding: 16px 24px;
            background-color: var(--bg-white);
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            gap: 16px;
            overflow-x: auto;
            scrollbar-width: none;
        }

        .filters::-webkit-scrollbar {
            display: none;
        }

        .filter-btn {
            padding: 8px 16px;
            border-radius: 20px;
            background-color: #f1f5f9;
            color: var(--text-medium);
            font-size: 14px;
            font-weight: 500;
            white-space: nowrap;
            border: none;
            cursor: pointer;
            transition: var(--transition);
        }

        .filter-btn.active {
            background-color: var(--primary-color);
            color: white;
        }

        .filter-btn:hover {
            background-color: #e2e8f0;
        }

        .filter-btn.active:hover {
            background-color: var(--primary-dark);
        }

        /* Main Content */
        .main-container {
            max-width: 1440px;
            margin: 0 auto;
            padding: 24px;
        }

        /* Product Grid */
        .products-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 24px;
        }

        @media (max-width: 768px) {
            .products-container {
                grid-template-columns: repeat(2, 1fr);
                gap: 16px;
            }
        }

        /* Product Card */
        .product-card {
            background: var(--bg-white);
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            position: relative;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .product-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-4px);
        }

        .product-image-container {
            height: 280px;
            overflow: hidden;
            position: relative;
        }

        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .product-card:hover .product-image {
            transform: scale(1.03);
        }

        /* Badges */
        .badge-container {
            position: absolute;
            top: 12px;
            left: 12px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            z-index: 2;
        }

        .badge {
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            color: white;
        }

        .badge-discount {
            background-color: var(--accent-color);
        }

        .badge-new {
            background-color: var(--primary-color);
        }

        .badge-sale {
            background-color: #10b981;
        }

        .wishlist-btn {
            position: absolute;
            top: 12px;
            right: 12px;
            width: 32px;
            height: 32px;
            background-color: var(--bg-white);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-medium);
            box-shadow: var(--shadow-sm);
            border: none;
            cursor: pointer;
            transition: var(--transition);
            z-index: 2;
        }

        .wishlist-btn:hover {
            color: var(--accent-color);
            transform: scale(1.1);
        }

        .wishlist-btn.active {
            color: var(--accent-color);
        }

        /* Product Info */
        .product-info {
            padding: 16px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .product-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--text-dark);
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-category {
            font-size: 12px;
            color: var(--text-light);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .product-description {
            font-size: 14px;
            color: var(--text-medium);
            margin-bottom: 12px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .price-container {
            margin-top: auto;
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .original-price {
            text-decoration: line-through;
            color: var(--text-light);
            font-size: 14px;
        }

        .discount-price {
            color: var(--primary-dark);
            font-weight: 700;
            font-size: 18px;
        }

        .rating-container {
            display: flex;
            align-items: center;
            gap: 4px;
            margin-top: 8px;
        }

        .rating-stars {
            color: #f59e0b;
            font-size: 14px;
        }

        .rating-count {
            font-size: 12px;
            color: var(--text-light);
        }

        /* Product Actions */
        .product-actions {
            padding: 0 16px 16px 16px;
            display: flex;
            gap: 12px;
        }

        .btn {
            flex: 1;
            text-align: center;
            padding: 10px 0;
            border-radius: var(--border-radius);
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: var(--transition);
            border: none;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: white;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
        }

        .btn-secondary:hover {
            background-color: #f5f3ff;
            transform: translateY(-2px);
        }

        /* Quick View Overlay */
        .quick-view-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
        }

        .quick-view-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .quick-view-container {
            background-color: white;
            border-radius: var(--border-radius);
            width: 90%;
            max-width: 900px;
            max-height: 90vh;
            overflow-y: auto;
            transform: translateY(20px);
            transition: var(--transition);
        }

        .quick-view-overlay.active .quick-view-container {
            transform: translateY(0);
        }

        /* Empty State */
        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 64px 24px;
        }

        .empty-state-icon {
            font-size: 48px;
            color: var(--text-light);
            margin-bottom: 16px;
        }

        .empty-state-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .empty-state-description {
            color: var(--text-medium);
            margin-bottom: 24px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn-add-product {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background-color: var(--primary-color);
            color: white;
            border-radius: var(--border-radius);
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
        }

        .btn-add-product:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }

        /* Footer */
        .footer {
            background-color: var(--text-dark);
            color: white;
            padding: 40px 24px;
            margin-top: 64px;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
        }

        .footer-column-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 16px;
            color: white;
        }

        .footer-links {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .footer-link {
            color: #cbd5e1;
            text-decoration: none;
            transition: var(--transition);
            font-size: 14px;
        }

        .footer-link:hover {
            color: white;
        }

        .social-links {
            display: flex;
            gap: 16px;
            margin-top: 16px;
        }

        .social-link {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: #334155;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            transition: var(--transition);
        }

        .social-link:hover {
            background-color: var(--primary-color);
            transform: translateY(-2px);
        }

        .copyright {
            text-align: center;
            padding-top: 40px;
            margin-top: 40px;
            border-top: 1px solid #334155;
            color: #94a3b8;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-nav">
            <a href="javascript:history.back()" class="back-btn">
                <i class="fas fa-chevron-left"></i>
            </a>
            <h1 class="header-title">Women's Collection</h1>
        </div>
        <div class="header-actions">
            <button class="header-icon">
                <i class="fas fa-search"></i>
            </button>
            <button class="header-icon">
                <i class="fas fa-heart"></i>
            </button>
            <a href="cart.php" class="header-icon">
                <i class="fas fa-shopping-bag"></i>
                <span class="cart-count">3</span>
            </a>
        </div>
    </header>

    <!-- Filters -->
    <div class="filters">
        <button class="filter-btn active">All</button>
        <button class="filter-btn">Dresses</button>
        <button class="filter-btn">Tops</button>
        <button class="filter-btn">Bottoms</button>
        <button class="filter-btn">Outerwear</button>
        <button class="filter-btn">Activewear</button>
        <button class="filter-btn">Accessories</button>
        <button class="filter-btn">Sale</button>
        <button class="filter-btn">New Arrivals</button>
    </div>

    <!-- Main Content -->
    <main class="main-container">
        <div class="products-container">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="product-card">
                        <div class="product-image-container">
                            <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="product-image">
                            <div class="badge-container">
                                <?php if ($row['discount_price'] > 0 && $row['original_price'] > $row['discount_price']): ?>
                                    <?php 
                                        $discountPercent = round((($row['original_price'] - $row['discount_price']) / $row['original_price']) * 100);
                                    ?>
                                    <div class="badge badge-discount"><?php echo $discountPercent; ?>% OFF</div>
                                <?php endif; ?>
                                <?php if (strtotime($row['created_at']) > strtotime('-7 days')): ?>
                                    <div class="badge badge-new">New</div>
                                <?php endif; ?>
                            </div>
                            <button class="wishlist-btn">
                                <i class="far fa-heart"></i>
                            </button>
                        </div>
                        <div class="product-info">
                            <span class="product-category"><?php echo htmlspecialchars($row['category'] ?? 'Clothing'); ?></span>
                            <h3 class="product-title"><?php echo htmlspecialchars($row['name']); ?></h3>
                            <p class="product-description"><?php echo htmlspecialchars($row['description']); ?></p>
                            <div class="price-container">
                                <?php if ($row['discount_price'] > 0): ?>
                                    <span class="discount-price">₹<?php echo number_format($row['discount_price'], 2); ?></span>
                                    <span class="original-price">₹<?php echo number_format($row['original_price'], 2); ?></span>
                                <?php else: ?>
                                    <span class="discount-price">₹<?php echo number_format($row['original_price'], 2); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="rating-container">
                                <div class="rating-stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                </div>
                                <span class="rating-count">(42)</span>
                            </div>
                        </div>
                        <div class="product-actions">
                            <button class="btn btn-secondary quick-view-btn">Quick View</button>
                            <a href="add_to_cart.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Add to Cart</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="far fa-folder-open"></i>
                    </div>
                    <h2 class="empty-state-title">No Products Found</h2>
                    <p class="empty-state-description">We couldn't find any products in the women's collection. Check back later or explore our other collections.</p>
                    <a href="product_page.php" class="btn-add-product">
                        <i class="fas fa-plus"></i> Add Product
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Quick View Modal -->
    <div class="quick-view-overlay">
        <div class="quick-view-container">
            <!-- Quick view content would be loaded here via JavaScript -->
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-column">
                <h3 class="footer-column-title">Shop</h3>
                <ul class="footer-links">
                    <li><a href="#" class="footer-link">Women's Collection</a></li>
                    <li><a href="#" class="footer-link">Men's Collection</a></li>
                    <li><a href="#" class="footer-link">Kids' Collection</a></li>
                    <li><a href="#" class="footer-link">New Arrivals</a></li>
                    <li><a href="#" class="footer-link">Sale Items</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3 class="footer-column-title">Help</h3>
                <ul class="footer-links">
                    <li><a href="#" class="footer-link">Customer Service</a></li>
                    <li><a href="#" class="footer-link">Track Order</a></li>
                    <li><a href="#" class="footer-link">Returns & Exchanges</a></li>
                    <li><a href="#" class="footer-link">Shipping Information</a></li>
                    <li><a href="#" class="footer-link">FAQ</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3 class="footer-column-title">About</h3>
                <ul class="footer-links">
                    <li><a href="#" class="footer-link">Our Story</a></li>
                    <li><a href="#" class="footer-link">Sustainability</a></li>
                    <li><a href="#" class="footer-link">Careers</a></li>
                    <li><a href="#" class="footer-link">Terms & Conditions</a></li>
                    <li><a href="#" class="footer-link">Privacy Policy</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3 class="footer-column-title">Connect</h3>
                <p style="color: #cbd5e1; font-size: 14px; margin-bottom: 16px;">Follow us on social media for updates and promotions.</p>
                <div class="social-links">
                    <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-pinterest"></i></a>
                </div>
            </div>
        </div>
        <div class="copyright">
            &copy; <?php echo date('Y'); ?> Kanyaraag. All rights reserved.
        </div>
    </footer>

    <script>
        // Quick view functionality
        document.querySelectorAll('.quick-view-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelector('.quick-view-overlay').classList.add('active');
                // Here you would typically load product details via AJAX
            });
        });

        document.querySelector('.quick-view-overlay').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('active');
            }
        });

        // Filter buttons
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                // Here you would filter products based on the selected category
            });
        });

        // Wishlist toggle
        document.querySelectorAll('.wishlist-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const icon = this.querySelector('i');
                this.classList.toggle('active');
                if (this.classList.contains('active')) {
                    icon.classList.replace('far', 'fas');
                } else {
                    icon.classList.replace('fas', 'far');
                }
            });
        });
    </script>
</body>
</html>
<?php $conn->close(); ?>