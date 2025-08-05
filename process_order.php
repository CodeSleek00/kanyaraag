<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Check if user has cart session
        if (!isset($_SESSION['cart_session_id'])) {
            throw new Exception('No cart session found');
        }
        
        $session_id = $_SESSION['cart_session_id'];
        
        // Get cart data from session
        $cartData = isset($_SESSION['checkout_cart']) ? $_SESSION['checkout_cart'] : [];
        $cartTotal = isset($_SESSION['checkout_total']) ? $_SESSION['checkout_total'] : 0;
        $cartSubtotal = isset($_SESSION['checkout_subtotal']) ? $_SESSION['checkout_subtotal'] : 0;
        $cartDiscount = isset($_SESSION['checkout_discount']) ? $_SESSION['checkout_discount'] : 0;
        
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
        
        // Generate order number
        $order_number = 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid());
        
        // Start transaction
        $pdo->beginTransaction();
        
        // Insert order
        $stmt = $pdo->prepare("INSERT INTO orders (order_number, subtotal, discount_amount, total_amount, status, payment_status, payment_method) VALUES (?, ?, ?, ?, 'pending', 'pending', ?)");
        $stmt->execute([$order_number, $cartSubtotal, $cartDiscount, $cartTotal, $payment_method]);
        $order_id = $pdo->lastInsertId();
        
        // Insert order items
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, product_name, quantity, unit_price, discount_price, total_price) VALUES (?, ?, ?, ?, ?, ?, ?)");
        foreach ($cartData as $item) {
            $regularPrice = $item['regular_price'];
            $discountPrice = $item['unit_price'];
            $totalPrice = $discountPrice * $item['quantity'];
            
            $stmt->execute([$order_id, $item['product_id'], $item['product_name'], $item['quantity'], $regularPrice, $discountPrice, $totalPrice]);
            
            // Update product stock
            $updateStock = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE product_id = ?");
            $updateStock->execute([$item['quantity'], $item['product_id']]);
        }
        
        // Insert customer details
        $stmt = $pdo->prepare("INSERT INTO order_customer_details (order_id, first_name, last_name, email, phone, address, city, state, pincode) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$order_id, $first_name, $last_name, $email, $phone, $address, $city, $state, $pincode]);
        
        // Clear cart after successful order
        $stmt = $pdo->prepare("DELETE FROM cart WHERE session_id = ?");
        $stmt->execute([$session_id]);
        
        // Commit transaction
        $pdo->commit();
        
        // Clear session data
        unset($_SESSION['checkout_cart']);
        unset($_SESSION['checkout_total']);
        unset($_SESSION['checkout_subtotal']);
        unset($_SESSION['checkout_discount']);
        
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