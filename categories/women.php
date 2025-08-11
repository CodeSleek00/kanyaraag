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
            padding: 0;
            background-color: #f8f9fa;
        }

        /* Header */
        .header {
            background-color: white;
            padding: 15px 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .back-btn {
            text-decoration: none;
            color: #333;
            font-size: 20px;
            margin-right: 15px;
            background: #eee;
            padding: 5px 10px;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .back-btn:hover {
            background: #ddd;
        }
        .header-title {
            flex: 1;
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            color: #333;
            margin-right: 35px;
        }

        /* Product Grid */
        .products-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 15px;
            padding: 20px;
        }

        @media (min-width: 768px) {
            .products-container {
                grid-template-columns: repeat(4, 1fr); /* Desktop: 4 per row */
                gap: 25px;
            }
        }

        .product-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            position: relative;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .product-card:hover {
            transform: translateY(-5px);
        }
        .product-image-container {
            height: 200px;
            overflow: hidden;
            position: relative;
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

        /* Discount Badge */
        .discount-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #e63946;
            color: white;
            padding: 6px 10px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .product-info {
            padding: 15px;
            flex: 1;
        }
        .product-title {
            font-size: 16px;
            margin: 0 0 5px 0;
            color: #333;
        }
        .product-description {
            font-size: 13px;
            color: #666;
            margin-bottom: 10px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .price-container {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .original-price {
            text-decoration: line-through;
            color: #999;
            font-size: 13px;
        }
        .discount-price {
            color: #e63946;
            font-weight: bold;
            font-size: 16px;
        }

        /* Buttons */
        .product-actions {
            display: flex;
            gap: 5px;
            padding: 10px 15px 15px 15px;
        }
        .btn {
            flex: 1;
            text-align: center;
            padding: 8px 0;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.3s;
        }
        .buy-now {
            background-color: #ff6600;
            color: white;
        }
        .buy-now:hover {
            background-color: #e65c00;
        }
        .add-cart {
            background-color: #4CAF50;
            color: white;
        }
        .add-cart:hover {
            background-color: #45a049;
        }

        /* No products */
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

    <!-- Header -->
    <div class="header">
        <a href="javascript:history.back()" class="back-btn">←</a>
        <div class="header-title">Kanyaraag</div>
    </div>

    <div class="products-container">
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="product-card">
                    <div class="product-image-container">
                        <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="product-image">
                        <?php if ($row['discount_price'] > 0 && $row['original_price'] > $row['discount_price']): ?>
                            <?php 
                                $discountPercent = round((($row['original_price'] - $row['discount_price']) / $row['original_price']) * 100);
                            ?>
                            <div class="discount-badge"><?php echo $discountPercent; ?>% OFF</div>
                        <?php endif; ?>
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
                    <div class="product-actions">
                        <a href="buy_now.php?id=<?php echo $row['id']; ?>" class="btn buy-now">Buy Now</a>
                        <a href="add_to_cart.php?id=<?php echo $row['id']; ?>" class="btn add-cart">Add to Cart</a>
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
