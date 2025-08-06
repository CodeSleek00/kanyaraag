<?php
// Setup script for Kanyaraag E-commerce
echo "<h1>Kanyaraag E-commerce Setup</h1>";

// Check PHP version
echo "<h2>System Requirements Check</h2>";
$php_version = phpversion();
echo "PHP Version: " . $php_version . "<br>";

if (version_compare($php_version, '7.2.0', '>=')) {
    echo "✅ PHP version is compatible<br>";
} else {
    echo "❌ PHP version must be 7.2 or higher<br>";
}

// Check required extensions
echo "<h3>Required Extensions:</h3>";
$required_extensions = ['pdo', 'pdo_mysql', 'json', 'session'];

foreach ($required_extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "✅ $ext extension is loaded<br>";
    } else {
        echo "❌ $ext extension is missing<br>";
    }
}

// Check directory permissions
echo "<h3>Directory Permissions:</h3>";
$directories = [
    'assets/images' => 'Product images directory',
    'config' => 'Configuration directory'
];

foreach ($directories as $dir => $description) {
    if (!is_dir($dir)) {
        if (mkdir($dir, 0755, true)) {
            echo "✅ Created directory: $description ($dir)<br>";
        } else {
            echo "❌ Failed to create directory: $description ($dir)<br>";
        }
    } else {
        if (is_writable($dir)) {
            echo "✅ $description ($dir) is writable<br>";
        } else {
            echo "❌ $description ($dir) is not writable<br>";
        }
    }
}

// Database connection test
echo "<h2>Database Connection Test</h2>";
if (file_exists('config/database.php')) {
    echo "✅ Database configuration file exists<br>";
    
    // Try to connect
    try {
        require_once 'config/database.php';
        echo "✅ Database connection successful<br>";
        
        // Check if tables exist
        $tables = ['products', 'orders', 'order_items', 'order_customer_details', 'admin_users'];
        foreach ($tables as $table) {
            $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
            if ($stmt->rowCount() > 0) {
                echo "✅ Table '$table' exists<br>";
            } else {
                echo "❌ Table '$table' is missing<br>";
            }
        }
        
    } catch (Exception $e) {
        echo "❌ Database connection failed: " . $e->getMessage() . "<br>";
        echo "<p><strong>Please update config/database.php with your database credentials:</strong></p>";
        echo "<pre>";
        echo "&lt;?php\n";
        echo "\$host = 'localhost';\n";
        echo "\$dbname = 'u298112699_kanyaraag';\n";
        echo "\$username = 'your_username';\n";
        echo "\$password = 'your_password';\n";
        echo "?&gt;";
        echo "</pre>";
    }
} else {
    echo "❌ Database configuration file missing<br>";
    echo "<p>Please create config/database.php with your database credentials.</p>";
}

// Installation instructions
echo "<h2>Installation Instructions</h2>";
echo "<ol>";
echo "<li>Create a MySQL database named 'u298112699_kanyaraag'</li>";
echo "<li>Import the database schema from database/schema.sql</li>";
echo "<li>Update database credentials in config/database.php</li>";
echo "<li>Upload product images to assets/images/ directory</li>";
echo "<li>Access your website at: " . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . "</li>";
echo "<li>Access admin panel at: " . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . "/admin/login.php</li>";
echo "</ol>";

    echo "<h3>Default Admin Login:</h3>";
    echo "<p><strong>Username:</strong> faiz<br>";
    echo "<strong>Password:</strong> admin123</p>";

echo "<h3>Sample Products:</h3>";
echo "<p>The database includes sample products. You can manage them through the admin panel.</p>";

echo "<hr>";
echo "<p><em>Setup completed. You can delete this file after successful installation.</em></p>";
?> 