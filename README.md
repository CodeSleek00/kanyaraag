# Kanyaraag E-commerce Website

A complete e-commerce website built with PHP, MySQL, HTML, CSS, and JavaScript. This project includes a user-friendly shopping experience with an admin panel for managing products and orders.

## Features

### Customer Features
- **Product Catalog**: Browse and view all available products with pricing
- **Discount Display**: Clear display of regular price, discount price, and discount percentage
- **Shopping Cart**: Add/remove items, update quantities with subtotal and discount calculation
- **Checkout Process**: Simple checkout without registration
- **Payment Options**: Cash on Delivery (COD) and Razorpay online payment
- **Order Tracking**: Order confirmation and status updates

### Admin Features
- **Dashboard**: Overview of sales, orders, and products
- **Product Management**: Add, edit, delete products
- **Order Management**: View and update order status
- **Customer Management**: View customer details
- **Secure Admin Panel**: Protected login system

## Technology Stack

- **Frontend**: HTML5, CSS3, JavaScript
- **Backend**: PHP 7.2+
- **Database**: MySQL 5.7+
- **Icons**: Font Awesome
- **Styling**: Custom CSS with responsive design

## Installation

### Prerequisites
- PHP 7.2 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- PHP extensions: PDO, PDO_MySQL

### Setup Instructions

1. **Clone or Download the Project**
   ```bash
   git clone <repository-url>
   cd kanyaraag
   ```

2. **Database Setup**
   - Create a MySQL database named `u298112699_kanyaraag`
   - Import the database schema from `database/schema.sql`
   - Update database credentials in `config/database.php`

3. **Configure Database Connection**
   Edit `config/database.php`:
   ```php
   $host = 'localhost';
   $dbname = 'u298112699_kanyaraag';
   $username = 'your_username';
   $password = 'your_password';
   ```

4. **Web Server Configuration**
   - Place the project in your web server's document root
   - Ensure the web server has read/write permissions
   - Create an `assets/images/` directory for product images

5. **Default Admin Login**
   - Username: `admin`
   - Password: `admin123`
   - Access admin panel at: `your-domain.com/admin/login.php`

## Project Structure

```
kanyaraag/
├── admin/                 # Admin panel files
│   ├── login.php         # Admin login
│   ├── dashboard.php     # Admin dashboard
│   ├── products.php      # Product management
│   ├── orders.php        # Order management
│   └── logout.php        # Admin logout
├── assets/               # Static assets
│   ├── css/
│   │   └── style.css     # Main stylesheet
│   ├── js/
│   │   └── script.js     # JavaScript functionality
│   └── images/           # Product images
├── config/
│   └── database.php      # Database configuration
├── database/
│   └── schema.sql        # Database schema
├── index.php             # Homepage
├── checkout.php          # Checkout page
├── process_order.php     # Order processing
├── order_success.php     # Order confirmation
└── README.md            # This file
```

## Database Schema

The application uses the following main tables:
- `products`: Product information with pricing and discount details
- `orders`: Order details with subtotal, discount, and payment information
- `order_items`: Individual items in orders with product names and pricing
- `order_customer_details`: Customer information (simplified)
- `admin_users`: Admin user accounts
- `payment_transactions`: Razorpay payment transaction records
- `categories`: Product categories for better organization

## Usage

### For Customers
1. Visit the homepage to browse products
2. Add items to cart
3. Proceed to checkout
4. Fill in delivery details
5. Choose payment method
6. Complete order

### For Administrators
1. Login to admin panel
2. Manage products (add, edit, delete)
3. View and update order status
4. Monitor sales and customer data

## Payment Integration

The system supports two payment methods:
- **Cash on Delivery (COD)**: No additional setup required
- **Razorpay Online Payment**: Supports cards, UPI, net banking, and digital wallets

### Razorpay Setup
1. Sign up for a Razorpay account
2. Get your API keys from the Razorpay dashboard
3. Update the keys in `razorpay_payment.php` and `verify_payment.php`
4. Install Razorpay PHP SDK: `composer require razorpay/razorpay`

## Security Features

- SQL injection prevention using prepared statements
- Session-based authentication for admin panel
- Input validation and sanitization
- Secure password hashing

## Customization

### Adding New Products
1. Login to admin panel
2. Go to Products section
3. Click "Add New Product"
4. Fill in product details
5. Upload product image
6. Save product

### Modifying Styles
Edit `assets/css/style.css` to customize the appearance:
- Color scheme
- Layout
- Typography
- Responsive design

### Adding Payment Gateways
To integrate real payment gateways:
1. Modify `checkout.php` payment section
2. Update `process_order.php` for payment processing
3. Add payment gateway SDK/API

## Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Check database credentials in `config/database.php`
   - Ensure MySQL service is running
   - Verify database exists

2. **Image Upload Issues**
   - Check `assets/images/` directory permissions
   - Ensure directory exists and is writable

3. **Admin Login Issues**
   - Verify admin user exists in database
   - Check password hashing compatibility

4. **Cart Not Working**
   - Ensure JavaScript is enabled
   - Check browser console for errors
   - Verify localStorage is available

## Support

For technical support or questions:
- Check the troubleshooting section above
- Review PHP error logs
- Ensure all prerequisites are met

## License

This project is open source and available under the MIT License.

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

---

**Note**: This is a basic e-commerce solution. For production use, consider adding:
- SSL certificate for security
- Real payment gateway integration
- Email notifications
- Advanced inventory management
- Customer reviews and ratings
- SEO optimization
- Performance optimization