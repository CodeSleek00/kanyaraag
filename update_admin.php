<?php
// Script to update admin username from 'admin' to 'faiz'
require_once 'config/database.php';

try {
    // Check current admin users
    echo "Current admin users:\n";
    $stmt = $pdo->query("SELECT username, email, full_name FROM admin_users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach($users as $user) {
        echo "Username: " . $user['username'] . ", Email: " . $user['email'] . ", Name: " . $user['full_name'] . "\n";
    }
    
    // Update admin user to faiz
    echo "\nUpdating admin user to 'faiz'...\n";
    
    $updateStmt = $pdo->prepare("UPDATE admin_users SET username = ?, email = ?, full_name = ? WHERE username = ?");
    $result = $updateStmt->execute(['faiz', 'faiz@kanyaraag.com', 'Faiz Administrator', 'admin']);
    
    if ($result) {
        echo "✅ Successfully updated admin username to 'faiz'\n";
        
        // Show updated users
        echo "\nUpdated admin users:\n";
        $stmt = $pdo->query("SELECT username, email, full_name FROM admin_users");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach($users as $user) {
            echo "Username: " . $user['username'] . ", Email: " . $user['email'] . ", Name: " . $user['full_name'] . "\n";
        }
        
        echo "\n🎉 Now you can login with:\n";
        echo "Username: faiz\n";
        echo "Password: admin123\n";
        
    } else {
        echo "❌ Failed to update admin username\n";
    }
    
} catch(Exception $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}
?>