<?php
session_start();
require_once 'config/database.php';

// Check if user has cart session
if (!isset($_SESSION['cart_session_id'])) {
    header('Location: index.php');
    exit();
}

$session_id = $_SESSION['cart_session_id'];

// Get cart items from database
$stmt = $pdo->prepare("SELECT c.*, p.image FROM cart c LEFT JOIN products p ON c.product_id = p.product_id WHERE c.session_id = ? ORDER BY c.created_at DESC");
$stmt->execute([$session_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// If no cart items, redirect to home
if (empty($cart_items)) {
    header('Location: index.php');
    exit();
}

// Calculate totals
$subtotal = 0;
$total_discount = 0;
$total_items = 0;

foreach ($cart_items as $item) {
    $regular_total = $item['regular_price'] * $item['quantity'];
    $discounted_total = $item['unit_price'] * $item['quantity'];
    $item_discount = $regular_total - $discounted_total;
    
    $subtotal += $regular_total;
    $total_discount += $item_discount;
    $total_items += $item['quantity'];
}

$final_total = $subtotal - $total_discount;

// Store cart data in session for order processing
$_SESSION['checkout_cart'] = $cart_items;
$_SESSION['checkout_total'] = $final_total;
$_SESSION['checkout_subtotal'] = $subtotal;
$_SESSION['checkout_discount'] = $total_discount;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Kanyaraag</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header>
        <nav>
            <div class="nav-container">
                <div class="logo">
                    <h1><a href="index.php" style="text-decoration: none; color: #e74c3c;">Kanyaraag</a></h1>
                </div>
                <div class="nav-links">
                    <a href="cart.php">Back to Cart</a>
                </div>
            </div>
        </nav>
    </header>

    <div class="checkout-form">
        <h2><i class="fas fa-shopping-cart"></i> Checkout</h2>
        
        <!-- Order Summary -->
        <div style="background: #f8f9fa; padding: 1rem; border-radius: 5px; margin-bottom: 2rem;">
            <h3>Order Summary</h3>
            <div id="order-items">
                <?php foreach ($cart_items as $item): ?>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span><?php echo $item['product_name']; ?> x <?php echo $item['quantity']; ?></span>
                        <span>₹<?php echo number_format($item['unit_price'] * $item['quantity']); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
            <div style="border-top: 1px solid #ddd; padding-top: 1rem; margin-top: 1rem;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span>Subtotal:</span>
                    <span>₹<?php echo number_format($subtotal); ?></span>
                </div>
                <?php if ($total_discount > 0): ?>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; color: #27ae60;">
                        <span>Discount:</span>
                        <span>-₹<?php echo number_format($total_discount); ?></span>
                    </div>
                <?php endif; ?>
                <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 1.1rem;">
                    <span>Total:</span>
                    <span>₹<?php echo number_format($final_total); ?></span>
                </div>
            </div>
        </div>

        <form id="checkout-form" method="POST" action="process_order.php">
            <h3>Customer Information</h3>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="first_name">First Name *</label>
                    <input type="text" id="first_name" name="first_name" required>
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name *</label>
                    <input type="text" id="last_name" name="last_name" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number *</label>
                    <input type="tel" id="phone" name="phone" required>
                </div>
            </div>

            <div class="form-group">
                <label for="address">Address *</label>
                <textarea id="address" name="address" rows="3" required></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="city">City *</label>
                    <input type="text" id="city" name="city" required>
                </div>
                <div class="form-group">
                    <label for="state">State *</label>
                    <input type="text" id="state" name="state" required>
                </div>
            </div>

            <div class="form-group">
                <label for="pincode">Pincode *</label>
                <input type="text" id="pincode" name="pincode" required>
            </div>

            <h3>Payment Information</h3>
            
            <div class="form-group">
                <label for="payment_method">Payment Method *</label>
                <select id="payment_method" name="payment_method" required>
                    <option value="">Select Payment Method</option>
                    <option value="cod">Cash on Delivery</option>
                    <option value="razorpay">Pay Online (Razorpay)</option>
                </select>
            </div>

            <div id="payment-details" style="display: none;">
                <!-- Payment details will be shown based on selected method -->
            </div>

            <button type="submit" class="submit-btn">
                <i class="fas fa-lock"></i> Place Order
            </button>
        </form>
    </div>

    <script>
        // Handle payment method selection
        document.getElementById('payment_method').addEventListener('change', function() {
            const paymentDetails = document.getElementById('payment-details');
            const method = this.value;
            
            if (method === 'cod') {
                paymentDetails.style.display = 'none';
            } else if (method === 'razorpay') {
                paymentDetails.innerHTML = `
                    <div style="background: #f8f9fa; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;">
                        <p><strong>Secure Payment via Razorpay</strong></p>
                        <p>You will be redirected to Razorpay's secure payment gateway to complete your transaction.</p>
                        <p>Supported payment methods:</p>
                        <ul style="margin-left: 1rem;">
                            <li>Credit/Debit Cards</li>
                            <li>UPI</li>
                            <li>Net Banking</li>
                            <li>Digital Wallets</li>
                        </ul>
                    </div>
                `;
                paymentDetails.style.display = 'block';
            }
        });

        // Form validation
        document.getElementById('checkout-form').addEventListener('submit', function(e) {
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = '#e74c3c';
                } else {
                    field.style.borderColor = '#ddd';
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields.');
            }
        });
    </script>
</body>
</html> 