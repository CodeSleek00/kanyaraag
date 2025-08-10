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
    <title>Women's Collection</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px 0;
            background-color: #ff6b81;
            color: white;
            border-radius: 8px;
        }
        .products-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 25px;
            padding: 20px;
        }
        .product-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .product-card:hover {
            transform: translateY(-5px);
        }
        .product-image-container {
            height: 250px;
            overflow: hidden;
        }
        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        .product-card:hover .product-image {
            transform: scale(1.05);
        }
        .product-info {
            padding: 15px;
        }
        .product-title {
            font-size: 18px;
            margin: 0 0 10px 0;
            color: #333;
        }
        .product-description {
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .price-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .original-price {
            text-decoration: line-through;
            color: #999;
            font-size: 14px;
        }
        .discount-price {
            color: #e63946;
            font-weight: bold;
            font-size: 18px;
        }
        .no-products {
            text-align: center;
            grid-column: 1 / -1;
            padding: 50px;
            color: #666;
        }
        .add-product-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .add-product-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Women's Fashion Collection</h1>
        <a href="product_page.php" class="add-product-btn">Add New Product</a>
    </div>
    
    <div class="products-container">
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="product-card">
                    <div class="product-image-container">
                        <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="product-image">
                    </div>
                    <div class="product-info">
                        <h3 class="product-title"><?php echo htmlspecialchars($row['name']); ?></h3>
                        <p class="product-description"><?php echo htmlspecialchars($row['description']); ?></p>
                        <div class="price-container">
                            <?php if ($row['discount_price'] > 0): ?>
                                <span class="original-price">₹<?php echo number_format($row['original_price'], 2); ?></span>
                                <span class="discount-price">₹<?php echo number_format($row['discount_price'], 2); ?></span>
                            <?php else: ?>
                                <span class="discount-price">₹<?php echo number_format($row['original_price'], 2); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-products">
                <h2>No products found in women's collection</h2>
                <p>Add products using the product page to see them here</p>
                <a href="product_page.php" class="add-product-btn">Add Product</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
<?php $conn->close(); ?>