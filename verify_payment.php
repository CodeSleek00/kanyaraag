<?php
session_start();
require_once 'config/database.php';

// Razorpay configuration
$razorpay_key_secret = 'YOUR_KEY_SECRET'; // Replace with your Razorpay test secret

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $razorpay_payment_id = $input['razorpay_payment_id'] ?? '';
    $razorpay_order_id = $input['razorpay_order_id'] ?? '';
    $razorpay_signature = $input['razorpay_signature'] ?? '';
    $order_id = $input['order_id'] ?? '';
    
    if (empty($razorpay_payment_id) || empty($razorpay_order_id) || empty($razorpay_signature) || empty($order_id)) {
        echo json_encode(['error' => 'Missing payment verification data']);
        exit();
    }
    
    try {
        // Verify payment signature
        $attributes = [
            'razorpay_order_id' => $razorpay_order_id,
            'razorpay_payment_id' => $razorpay_payment_id,
            'razorpay_signature' => $razorpay_signature
        ];
        
        // Initialize Razorpay
        $api = new Razorpay\Api\Api('rzp_test_YOUR_KEY_ID', $razorpay_key_secret);
        
        // Verify signature
        $api->utility->verifyPaymentSignature($attributes);
        
        // Payment is verified, update order status
        $pdo->beginTransaction();
        
        // Update order payment status
        $stmt = $pdo->prepare("UPDATE orders SET payment_status = 'paid', razorpay_payment_id = ? WHERE order_id = ?");
        $stmt->execute([$razorpay_payment_id, $order_id]);
        
        // Insert payment transaction record
        $stmt = $pdo->prepare("INSERT INTO payment_transactions (order_id, razorpay_payment_id, razorpay_order_id, amount, status, payment_method) VALUES (?, ?, ?, ?, 'captured', 'razorpay')");
        
        // Get order amount
        $orderStmt = $pdo->prepare("SELECT total_amount FROM orders WHERE order_id = ?");
        $orderStmt->execute([$order_id]);
        $order = $orderStmt->fetch(PDO::FETCH_ASSOC);
        
        $stmt->execute([$order_id, $razorpay_payment_id, $razorpay_order_id, $order['total_amount']]);
        
        $pdo->commit();
        
        // Get order number for redirect
        $stmt = $pdo->prepare("SELECT order_number FROM orders WHERE order_id = ?");
        $stmt->execute([$order_id]);
        $orderData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true,
            'message' => 'Payment verified successfully',
            'order_id' => $order_id,
            'order_number' => $orderData['order_number']
        ]);
        
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        
        echo json_encode(['error' => 'Payment verification failed: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
?> 