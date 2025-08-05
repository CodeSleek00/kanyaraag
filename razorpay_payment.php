<?php
session_start();
require_once 'config/database.php';

// Razorpay configuration
$razorpay_key_id = 'rzp_test_YOUR_KEY_ID'; // Replace with your Razorpay test key
$razorpay_key_secret = 'YOUR_KEY_SECRET'; // Replace with your Razorpay test secret

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'] ?? '';
    $amount = $_POST['amount'] ?? '';
    
    if (empty($order_id) || empty($amount)) {
        echo json_encode(['error' => 'Invalid order data']);
        exit();
    }
    
    try {
        // Get order details
        $stmt = $pdo->prepare("SELECT * FROM orders WHERE order_id = ?");
        $stmt->execute([$order_id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$order) {
            echo json_encode(['error' => 'Order not found']);
            exit();
        }
        
        // Create Razorpay order
        $razorpay_order_data = [
            'receipt' => $order['order_number'],
            'amount' => $amount * 100, // Razorpay expects amount in paise
            'currency' => 'INR',
            'notes' => [
                'order_id' => $order_id,
                'customer_email' => $_POST['email'] ?? ''
            ]
        ];
        
        // Initialize Razorpay
        $api = new Razorpay\Api\Api($razorpay_key_id, $razorpay_key_secret);
        
        // Create Razorpay order
        $razorpay_order = $api->order->create($razorpay_order_data);
        
        // Update order with Razorpay order ID
        $stmt = $pdo->prepare("UPDATE orders SET razorpay_payment_id = ? WHERE order_id = ?");
        $stmt->execute([$razorpay_order->id, $order_id]);
        
        // Return payment data
        echo json_encode([
            'success' => true,
            'razorpay_order_id' => $razorpay_order->id,
            'amount' => $amount,
            'currency' => 'INR',
            'key_id' => $razorpay_key_id,
            'order_number' => $order['order_number']
        ]);
        
    } catch (Exception $e) {
        echo json_encode(['error' => 'Payment initialization failed: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Processing - Kanyaraag</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body>
    <div class="checkout-form">
        <h2>Payment Processing</h2>
        <div id="payment-status">
            <p>Initializing payment...</p>
        </div>
    </div>

    <script>
        // This would be called after successful payment
        function handlePaymentSuccess(response) {
            // Send payment verification to server
            fetch('verify_payment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    razorpay_payment_id: response.razorpay_payment_id,
                    razorpay_order_id: response.razorpay_order_id,
                    razorpay_signature: response.razorpay_signature,
                    order_id: '<?php echo $_GET['order_id'] ?? ''; ?>'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'order_success.php?order_id=' + data.order_id + '&order_number=' + data.order_number;
                } else {
                    document.getElementById('payment-status').innerHTML = '<p style="color: red;">Payment verification failed: ' + data.error + '</p>';
                }
            })
            .catch(error => {
                document.getElementById('payment-status').innerHTML = '<p style="color: red;">Payment verification failed: ' + error.message + '</p>';
            });
        }

        // Initialize Razorpay payment
        function initializePayment() {
            const options = {
                key: '<?php echo $razorpay_key_id; ?>',
                amount: <?php echo $_GET['amount'] ?? 0; ?> * 100,
                currency: 'INR',
                name: 'Kanyaraag',
                description: 'Order Payment',
                order_id: '<?php echo $_GET['razorpay_order_id'] ?? ''; ?>',
                handler: handlePaymentSuccess,
                prefill: {
                    name: '<?php echo $_GET['customer_name'] ?? ''; ?>',
                    email: '<?php echo $_GET['customer_email'] ?? ''; ?>',
                    contact: '<?php echo $_GET['customer_phone'] ?? ''; ?>'
                },
                theme: {
                    color: '#e74c3c'
                }
            };

            const rzp = new Razorpay(options);
            rzp.open();
        }

        // Auto-initialize payment when page loads
        window.onload = function() {
            if ('<?php echo $_GET['razorpay_order_id'] ?? ''; ?>') {
                initializePayment();
            }
        };
    </script>
</body>
</html> 