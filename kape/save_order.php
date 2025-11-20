<?php
session_start();
include __DIR__ . "/db/upaya_db.php";

// Assume $cart is your current cart items
$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    die("No items to checkout.");
}

// Load existing orders
$orders = file_exists('orders.json') ? json_decode(file_get_contents('orders.json'), true) : [];

// Generate new order ID
$order_id = count($orders);

// Calculate total
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['qty'];
}

// Add new order
$orders[$order_id] = [
    'order' => $cart,
    'total' => $total,
    'timestamp' => date('Y-m-d H:i:s')
];

// Save to orders.json
file_put_contents('orders.json', json_encode($orders, JSON_PRETTY_PRINT));

// Clear cart
unset($_SESSION['cart']);

// Redirect to order history
header("Location: orders.php");
exit;
