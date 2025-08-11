<?php
session_start();
require_once '../database_connect/db_connect.php';

if (isset($_GET['buy_now_id'])) {
    // Direct Buy Now checkout
    $id = intval($_GET['buy_now_id']);
    $sql = "SELECT * FROM products WHERE id = $id";
    $result = $conn->query($sql);
    $product = $result->fetch_assoc();
    $items = [[
        'name' => $product['name'],
        'price' => ($product['discount_price'] > 0 ? $product['discount_price'] : $product['original_price']),
        'qty' => 1
    ]];
} else {
    // Cart checkout
    $items = $_SESSION['cart'] ?? [];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f8f9fa; }
        table { width: 100%; border-collapse: collapse; background: white; }
        th, td { padding: 12px; border-bottom: 1px solid #ddd; }
    </style>
</head>
<body>

<h1>Checkout</h1>

<table>
    <tr>
        <th>Name</th>
        <th>Price</th>
        <th>Qty</th>
        <th>Total</th>
    </tr>
    <?php 
    $grand_total = 0;
    foreach ($items as $item):
        $total = $item['price'] * $item['qty'];
        $grand_total += $total;
    ?>
    <tr>
        <td><?php echo $item['name']; ?></td>
        <td>₹<?php echo number_format($item['price'], 2); ?></td>
        <td><?php echo $item['qty']; ?></td>
        <td>₹<?php echo number_format($total, 2); ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<h2>Total Payable: ₹<?php echo number_format($grand_total, 2); ?></h2>
<p><strong>Payment integration (Razorpay/PayPal) can be added here.</strong></p>

</body>
</html>
