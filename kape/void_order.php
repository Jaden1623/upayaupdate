<?php
session_start();
include "void_auth.php"; // contains $manager_code

$orders = [];
if (file_exists('orders.json')) {
    $raw = json_decode(file_get_contents('orders.json'), true);

    // Only numeric keys (actual orders)
    $orders = [];
    foreach ($raw as $key => $value) {
        if (is_array($value) && is_numeric($key)) {
            $orders[] = $value;
        }
    }
}

$order_id = $_POST['order_id'] ?? '';
$auth_code = $_POST['auth_code'] ?? '';

// Check manager code
if ($auth_code !== $manager_code) {
    $_SESSION['error'] = "Invalid manager code. Order not voided.";
    header("Location: orders.php");
    exit();
}

// Void the order by index
if ($order_id !== '') {
    if (isset($orders[$order_id])) {
        $orders[$order_id]['status'] = 'Voided'; // add status
        file_put_contents('orders.json', json_encode($orders, JSON_PRETTY_PRINT));
    }
}

header("Location: orders.php");
exit();
