<?php
require_once 'config/database.php';
require_once 'classes/Product.php';

$db = (new Database())->connect();
$product = new Product($db);

// Get featured products for home page
$featured_products = $product->getFeaturedProducts('home', 8);
$categories = $product->getAllCategories();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Ecommerce Store</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="container">
        <!-- Hero Banner -->
        <section class="hero-banner">
            <div class="hero-content">
                <h1>Welcome to Our Store</h1>
                <p>Discover amazing products at great prices</p>
                <a href="category.php?slug=all" class="btn btn-primary">Shop Now</a>
            </div>
        </section>

        <!-- Featured Products -->
        <section class="featured-products">
            <h2>Featured Products</h2>
            <div class="product-grid">
                <?php foreach ($featured_products as $product): ?>
                    <?php include 'partials/product-card.php'; ?>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Categories -->
        <section class="categories">
            <h2>Shop by Category</h2>
            <div class="category-grid">
                <?php foreach ($categories as $category): ?>
                    <a href="category.php?slug=<?= $category['slug'] ?>" class="category-card">
                        <img src="<?= $category['image_url'] ?>" alt="<?= $category['name'] ?>">
                        <h3><?= $category['name'] ?></h3>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>
    <script src="assets/js/main.js"></script>
</body>
</html>