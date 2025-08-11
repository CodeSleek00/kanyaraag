<?php
require_once '../database_connect/db_connect.php';
session_start();

if (!isset($_GET['id'])) {
    echo "Invalid request";
    exit();
}

$product_id = intval($_GET['id']);
$sql = "SELECT * FROM products WHERE id = $product_id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "Product not found.";
    exit();
}

$product = $result->fetch_assoc();
$price = ($product['discount_price'] > 0 ? $product['discount_price'] : $product['original_price']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Buy Now - <?php echo $product['name']; ?></title>
    <style>
        body { font-family: Arial; background: #f8f9fa; padding: 20px; }
        .product { display: flex; gap: 20px; background: white; padding: 20px; border-radius: 8px; }
        img { width: 200px; height: 200px; object-fit: cover; }
        .btn { padding: 10px 15px; background: green; color: white; border-radius: 4px; text-decoration: none; }
    </style>
</head>
<body>

<h1>Buy Now</h1>

<div class="product">
    <img src="<?php echo $product['image_path']; ?>">
    <div>
        <h2><?php echo $product['name']; ?></h2>
        <p>Price: ₹<?php echo number_format($price, 2); ?></p>
        <p><?php echo $product['description']; ?></p>
        <a href="checkout.php?buy_now_id=<?php echo $product['id']; ?>" class="btn">Proceed to Payment</a>
    </div>
</div>

</body>
</html>
