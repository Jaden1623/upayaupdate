<?php
session_start();

$admin_pin = "1234"; // Or fetch from database securely

$entered_pin = $_POST['pin'] ?? '';

if ($entered_pin === $admin_pin) {
    unset($_SESSION['order']); // Void the order
    echo "Order voided successfully.";
} else {
    http_response_code(403);
    echo "Invalid PIN!";
}
?>
