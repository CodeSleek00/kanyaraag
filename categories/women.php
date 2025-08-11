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
   
</head>
<body>
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