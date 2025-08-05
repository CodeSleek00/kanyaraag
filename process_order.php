<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get cart data from session
        $cartData = isset($_SESSION['checkout_cart']) ? $_SESSION['checkout_cart'] : [];
        $cartTotal = isset($_SESSION['checkout_total']) ? $_SESSION['checkout_total'] : 0;
        
        if (empty($cartData)) {
            throw new Exception('No items in cart');
        }
        
        // Get form data
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $pincode = $_POST['pincode'];
        $payment_method = $_POST['payment_method'];
        
        // Calculate order totals
        $subtotal = 0;
        $totalDiscount = 0;
        
        foreach ($cartData as $item) {
            $regularTotal = $item['regularPrice'] * $item['quantity'];
            $discountedTotal = $item['price'] * $item['quantity'];
            $subtotal += $regularTotal;
            $totalDiscount += ($regularTotal - $discountedTotal);
        }
        
        $finalTotal = $subtotal - $totalDiscount;
        
        // Generate order number
        $order_number = 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid());
        
        // Start transaction
        $pdo->beginTransaction();
        
        // Insert order
        $stmt = $pdo->prepare("INSERT INTO orders (order_number, subtotal, discount_amount, total_amount, status, payment_status, payment_method) VALUES (?, ?, ?, ?, 'pending', 'pending', ?)");
        $stmt->execute([$order_number, $subtotal, $totalDiscount, $finalTotal, $payment_method]);
        $order_id = $pdo->lastInsertId();
        
        // Insert order items
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, product_name, quantity, unit_price, discount_price, total_price) VALUES (?, ?, ?, ?, ?, ?, ?)");
        foreach ($cartData as $item) {
            $regularPrice = $item['regularPrice'];
            $discountPrice = $item['price'];
            $totalPrice = $discountPrice * $item['quantity'];
            
            $stmt->execute([$order_id, $item['id'], $item['name'], $item['quantity'], $regularPrice, $discountPrice, $totalPrice]);
            
            // Update product stock
            $updateStock = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE product_id = ?");
            $updateStock->execute([$item['quantity'], $item['id']]);
        }
        
        // Insert customer details
        $stmt = $pdo->prepare("INSERT INTO order_customer_details (order_id, first_name, last_name, email, phone, address, city, state, pincode) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$order_id, $first_name, $last_name, $email, $phone, $address, $city, $state, $pincode]);
        
        // Commit transaction
        $pdo->commit();
        
        // Clear cart from session
        unset($_SESSION['checkout_cart']);
        unset($_SESSION['checkout_total']);
        
        // Redirect to success page
        header('Location: order_success.php?order_id=' . $order_id . '&order_number=' . $order_number);
        exit();
        
    } catch (Exception $e) {
        // Rollback transaction on error
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        
        // Redirect back to checkout with error
        header('Location: checkout.php?error=' . urlencode($e->getMessage()));
        exit();
    }
} else {
    // If not POST request, redirect to home
    header('Location: index.php');
    exit();
}
?> 