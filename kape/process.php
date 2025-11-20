<?php
// Start session to track orders
session_start();

// Database connection
$host = 'localhost';
$db = 'upaya_cafe';
$user = 'root';
$pass = ''; // Replace with your actual password

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle incoming POST data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $items = $_POST['items']; // Expecting array of item names
    $payment_method = $_POST['payment']; // 'cash' or 'gcash'

    $total = 0;
    $order_details = [];

    foreach ($items as $item_name) {
        // Fetch item price from database
        $stmt = $conn->prepare("SELECT price FROM products WHERE name = ?");
        $stmt->bind_param("s", $item_name);
        $stmt->execute();
        $stmt->bind_result($price);
        if ($stmt->fetch()) {
            $total += $price;
            $order_details[] = ['name' => $item_name, 'price' => $price];
        }
        $stmt->close();
    }

    // Insert order into database
    $stmt = $conn->prepare("INSERT INTO orders (items, total, payment_method) VALUES (?, ?, ?)");
    $items_json = json_encode($order_details);
    $stmt->bind_param("sis", $items_json, $total, $payment_method);
    $stmt->execute();
    $stmt->close();

    echo json_encode([
        'status' => 'success',
        'total' => $total,
        'payment' => $payment_method,
        'items' => $order_details
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}

$conn->close();
?>
