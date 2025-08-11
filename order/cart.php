<?php
session_start();
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Cart</title>
    <style>
        body { font-family: Arial; background: #f8f9fa; padding: 20px; }
        h1 { text-align: center; }
        table { width: 100%; border-collapse: collapse; background: white; }
        th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: center; }
        img { width: 70px; height: 70px; object-fit: cover; }
        .btn { padding: 8px 12px; background: red; color: white; text-decoration: none; border-radius: 4px; }
        .checkout-btn { background: green; }
    </style>
</head>
<body>

<h1>🛒 My Cart</h1>

<?php if (count($_SESSION['cart']) > 0): ?>
<table>
    <tr>
        <th>Image</th>
        <th>Name</th>
        <th>Price</th>
        <th>Qty</th>
        <th>Total</th>
        <th>Action</th>
    </tr>
    <?php 
    $grand_total = 0;
    foreach ($_SESSION['cart'] as $index => $item): 
        $total = $item['price'] * $item['qty'];
        $grand_total += $total;
    ?>
    <tr>
        <td><img src="<?php echo $item['image']; ?>"></td>
        <td><?php echo $item['name']; ?></td>
        <td>₹<?php echo number_format($item['price'], 2); ?></td>
        <td><?php echo $item['qty']; ?></td>
        <td>₹<?php echo number_format($total, 2); ?></td>
        <td><a class="btn" href="remove_from_cart.php?index=<?php echo $index; ?>">Remove</a></td>
    </tr>
    <?php endforeach; ?>
</table>

<h2>Total Amount: ₹<?php echo number_format($grand_total, 2); ?></h2>
<a href="checkout.php" class="btn checkout-btn">Proceed to Checkout</a>

<?php else: ?>
<h2>Your cart is empty.</h2>
<?php endif; ?>

</body>
</html>
