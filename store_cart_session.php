<?php
session_start();

// Get JSON data from request
$input = json_decode(file_get_contents('php://input'), true);

if ($input) {
    $_SESSION['checkout_cart'] = $input['cart_data'] ?? [];
    $_SESSION['checkout_total'] = floatval($input['cart_total'] ?? 0);
    
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'No data received']);
}
?> 