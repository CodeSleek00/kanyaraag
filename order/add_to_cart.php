<?php
session_start();
require_once '../database_connect/db_connect.php';

if (!isset($_GET['id'])) {
    header("Location: cart.php");
    exit();
}

$product_id = intval($_GET['id']);

// Fetch product from DB
$sql = "SELECT * FROM products WHERE id = $product_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();

    // Create cart if not exists
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // If product already in cart, increase quantity
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['qty']++;
            $found = true;
            break;
        }
    }

    // If not found, add new product
    if (!$found) {
        $_SESSION['cart'][] = [
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => ($product['discount_price'] > 0 ? $product['discount_price'] : $product['original_price']),
            'image' => $product['image_path'],
            'qty' => 1
        ];
    }
}

header("Location: cart.php");
exit();
