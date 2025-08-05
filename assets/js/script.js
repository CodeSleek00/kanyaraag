// Cart functionality
let cart = JSON.parse(localStorage.getItem('cart')) || [];
let cartTotal = 0;

// Initialize cart on page load
document.addEventListener('DOMContentLoaded', function() {
    updateCartDisplay();
});

// Toggle cart sidebar
function toggleCart() {
    const cartSidebar = document.getElementById('cart-sidebar');
    cartSidebar.classList.toggle('active');
}

// Add item to cart
function addToCart(productId, productName, productPrice, regularPrice, discountPercentage) {
    const existingItem = cart.find(item => item.id === productId);
    
    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({
            id: productId,
            name: productName,
            price: productPrice,
            regularPrice: regularPrice,
            discountPercentage: discountPercentage,
            quantity: 1
        });
    }
    
    saveCart();
    updateCartDisplay();
    
    // Show success message
    showNotification('Product added to cart!', 'success');
}

// Remove item from cart
function removeFromCart(productId) {
    cart = cart.filter(item => item.id !== productId);
    saveCart();
    updateCartDisplay();
    showNotification('Product removed from cart!', 'info');
}

// Update quantity
function updateQuantity(productId, change) {
    const item = cart.find(item => item.id === productId);
    if (item) {
        item.quantity += change;
        if (item.quantity <= 0) {
            removeFromCart(productId);
        } else {
            saveCart();
            updateCartDisplay();
        }
    }
}

// Save cart to localStorage
function saveCart() {
    localStorage.setItem('cart', JSON.stringify(cart));
}

// Update cart display
function updateCartDisplay() {
    const cartItems = document.getElementById('cart-items');
    const cartCount = document.getElementById('cart-count');
    const cartSubtotalElement = document.getElementById('cart-subtotal');
    const cartDiscountElement = document.getElementById('cart-discount');
    const cartTotalElement = document.getElementById('cart-total');
    
    // Update cart count
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    cartCount.textContent = totalItems;
    
    // Update cart items
    if (cart.length === 0) {
        cartItems.innerHTML = '<p style="text-align: center; color: #666; padding: 2rem;">Your cart is empty</p>';
        cartSubtotalElement.textContent = '0';
        cartDiscountElement.textContent = '0';
        cartTotalElement.textContent = '0';
        return;
    }
    
    let cartHTML = '';
    let subtotal = 0;
    let totalDiscount = 0;
    
    cart.forEach(item => {
        const regularTotal = item.regularPrice * item.quantity;
        const discountedTotal = item.price * item.quantity;
        const itemDiscount = regularTotal - discountedTotal;
        
        subtotal += regularTotal;
        totalDiscount += itemDiscount;
        
        cartHTML += `
            <div class="cart-item">
                <div class="cart-item-info">
                    <div class="cart-item-name">${item.name}</div>
                    <div class="cart-item-price">
                        ${item.discountPercentage > 0 ? 
                            `<span style="text-decoration: line-through; color: #999; font-size: 0.8rem;">₹${item.regularPrice.toLocaleString()}</span><br>` : 
                            ''
                        }
                        ₹${item.price.toLocaleString()}
                    </div>
                    <div class="cart-item-quantity">
                        <button class="quantity-btn" onclick="updateQuantity(${item.id}, -1)">-</button>
                        <span>${item.quantity}</span>
                        <button class="quantity-btn" onclick="updateQuantity(${item.id}, 1)">+</button>
                    </div>
                </div>
                <button class="remove-item" onclick="removeFromCart(${item.id})">Remove</button>
            </div>
        `;
    });
    
    cartTotal = subtotal - totalDiscount;
    
    cartItems.innerHTML = cartHTML;
    cartSubtotalElement.textContent = subtotal.toLocaleString();
    cartDiscountElement.textContent = totalDiscount.toLocaleString();
    cartTotalElement.textContent = cartTotal.toLocaleString();
}

// Proceed to checkout
function proceedToCheckout() {
    if (cart.length === 0) {
        showNotification('Your cart is empty!', 'error');
        return;
    }
    
    try {
        // Create a form to submit cart data
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'checkout.php';
        
        // Add cart data as hidden inputs
        const cartDataInput = document.createElement('input');
        cartDataInput.type = 'hidden';
        cartDataInput.name = 'cart_data';
        cartDataInput.value = JSON.stringify(cart);
        form.appendChild(cartDataInput);
        
        const cartTotalInput = document.createElement('input');
        cartTotalInput.type = 'hidden';
        cartTotalInput.name = 'cart_total';
        cartTotalInput.value = cartTotal;
        form.appendChild(cartTotalInput);
        
        // Submit the form
        document.body.appendChild(form);
        form.submit();
    } catch (error) {
        // Fallback: use localStorage and redirect
        console.log('Form submission failed, using fallback method');
        localStorage.setItem('checkoutCart', JSON.stringify(cart));
        localStorage.setItem('checkoutTotal', cartTotal);
        window.location.href = 'checkout.php';
    }
}

// Show notification
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    
    // Style the notification
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        padding: 15px 20px;
        border-radius: 5px;
        color: white;
        font-weight: bold;
        z-index: 10000;
        animation: slideIn 0.3s ease-out;
    `;
    
    // Set background color based on type
    switch(type) {
        case 'success':
            notification.style.backgroundColor = '#27ae60';
            break;
        case 'error':
            notification.style.backgroundColor = '#e74c3c';
            break;
        case 'info':
            notification.style.backgroundColor = '#3498db';
            break;
        default:
            notification.style.backgroundColor = '#3498db';
    }
    
    // Add to page
    document.body.appendChild(notification);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease-in';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

// Add CSS animations for notifications
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// Close cart when clicking outside
document.addEventListener('click', function(event) {
    const cartSidebar = document.getElementById('cart-sidebar');
    const cartIcon = document.querySelector('.cart-icon');
    
    if (cartSidebar.classList.contains('active') && 
        !cartSidebar.contains(event.target) && 
        !cartIcon.contains(event.target)) {
        cartSidebar.classList.remove('active');
    }
});

// Smooth scrolling for navigation links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
}); 