<?php
session_start();
require_once 'config/database.php';
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
                    <a href="index.php">Back to Shop</a>
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
                <!-- Order items will be displayed here -->
            </div>
            <div style="border-top: 1px solid #ddd; padding-top: 1rem; margin-top: 1rem;">
                <strong>Total: ₹<span id="order-total">0</span></strong>
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
        // Load cart data from localStorage
        document.addEventListener('DOMContentLoaded', function() {
            const cartData = JSON.parse(localStorage.getItem('checkoutCart') || '[]');
            const cartTotal = parseFloat(localStorage.getItem('checkoutTotal') || 0);
            
            if (cartData.length === 0) {
                alert('Your cart is empty!');
                window.location.href = 'index.php';
                return;
            }
            
            // Display order items
            const orderItems = document.getElementById('order-items');
            let itemsHTML = '';
            
            cartData.forEach(item => {
                itemsHTML += `
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span>${item.name} x ${item.quantity}</span>
                        <span>₹${(item.price * item.quantity).toLocaleString()}</span>
                    </div>
                `;
            });
            
            orderItems.innerHTML = itemsHTML;
            document.getElementById('order-total').textContent = cartTotal.toLocaleString();
            
            // Store cart data in session for order processing
            fetch('store_cart_session.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    cart_data: cartData,
                    cart_total: cartTotal
                })
            });
        });

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