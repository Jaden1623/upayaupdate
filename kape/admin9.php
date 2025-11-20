<?php
session_start();
include __DIR__ . "/db/upaya_db.php";

// Handle checkout form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    $paymentType = $_POST['paymentType'] ?? 'cash';
    $order = $_SESSION['order'] ?? [];

    $total = 0.0;
    foreach ($order as $item) {
        $total += $item['price'] * $item['qty'];
    }

    // Load existing orders
    $existingOrders = [];
    if (file_exists('orders.json')) {
        $existingOrders = json_decode(file_get_contents('orders.json'), true);
        if (!is_array($existingOrders)) {
            $existingOrders = [];
        }
    }

    // Add new order to list
    $existingOrders[] = [
        'order' => $order,
        'total' => $total,
        'payment' => $paymentType,
        'timestamp' => date('Y-m-d H:i:s')
    ];

    // Save back to file
    file_put_contents('orders.json', json_encode($existingOrders, JSON_PRETTY_PRINT));

    // Do not clear session here; let receipt.php handle it
    // unset($_SESSION['order']);

    header("Location: receipt.php");
    exit();
}

// Handle AJAX addItem requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addItem'])) {
    $name = $_POST['name'];
    $price = (float)$_POST['price'];
    $qty = 1;

    if (!isset($_SESSION['order'])) {
        $_SESSION['order'] = [];
    }

    $found = false;
    foreach ($_SESSION['order'] as &$item) {
        if ($item['name'] === $name) {
            $item['qty'] += $qty;
            $found = true;
            break;
        }
    }
    if (!$found) {
        $_SESSION['order'][] = ['name' => $name, 'price' => $price, 'qty' => $qty];
    }

    header('Content-Type: application/json');
    echo json_encode($_SESSION['order']);
    exit();
}

// Handle Clear order button AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clearOrder'])) {
    unset($_SESSION['order']);
    header('Content-Type: application/json');
    echo json_encode([]);
    exit();
}

// Prepare order summary HTML for page load (without total)
$order = $_SESSION['order'] ?? [];
$orderItemsHtml = '';
if (!empty($order)) {
    foreach ($order as $item) {
        $subtotal = $item['price'] * $item['qty'];
        $orderItemsHtml .= "<div class='order-item'>".htmlspecialchars($item['name'])." x{$item['qty']} - ₱{$subtotal}</div>";
    }
} else {
    $orderItemsHtml = "<p>No items added yet.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Upâyâ Café | FLAVORED FRIES</title>
  <link rel="stylesheet" href="pos.css" />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@1,600&family=Poppins:wght@400;500&display=swap" rel="stylesheet" />
</head>
<body>

<div class="logo">
  <h1>Upâyâ</h1>
  <p>Café</p>
</div>

<div class="pos-container">
  <!-- Sidebar (same as admin.php) -->
  <div class="sidebar">
    <a href="admin.php" class="icon active">🏠</a>
    <a href="inventory.php" class="icon">📦</a>
    <a href="orders.php" class="icon">📊</a>
    <a href="settings.php" class="icon">⚙️</a>
  </div>

  <!-- Main Menu Section -->
  <div class="menu-section">
    <div class="search-bar">
      <input type="text" placeholder="SEARCH FOR PRODUCT" id="search-box" />
    </div>

    <div class="category-tabs">
      <button><a href="admin.php">COFFEE</a></button>
      <button><a href="admin1.php">PREMIUM MATCHA SERIES</a></button>
      <button><a href="admin2.php">NON-COFFEE DRINKS</a></button>
      <button><a href="admin3.php">FRAPPE</a></button>
      <button><a href="admin4.php">FRUIT SODA</a></button>
      <button><a href="admin5.php">PREMIUM TEA SERIES</a></button>
      <button><a href="admin6.php">ADD-ONS</a></button>
      <button><a href="admin7.php">COOKIES & MUFFINS</a></button>
      <button><a href="admin8.php">WAFFLES</a></button>
      <button class="active"><a href="admin9.php"><h3>FLAVORED FRIES</h3></a></button>
      <button><a href="admin10.php">PASTA</a></button>
    </div>

    <div class="product-grid" id="product-grid">
      <h3>FLAVORED FRIES</h3>
      <div class="items">
        <div class="item" data-name="Cheese" data-price="95">Cheese - 95</div>
        <div class="item" data-name="Sour Cream" data-price="95">Sour Cream - 95</div>
        <div class="item" data-name="BBQ" data-price="95">BBQ - 95</div>
        <div class="item" data-name="Chili BBQ" data-price="95">Chili BBQ - 95</div>
        <div class="item" data-name="Cheese Dip" data-price="25">Cheese Dip - 25</div>
      </div>
    </div>
  </div>

  <!-- Order Summary -->
  <div class="order-summary">
    <h3>Order Summary</h3>
    <div class="summary-box" id="order-summary-box"><?= $orderItemsHtml ?></div>

    <div class="checkout-row">
      <button class="clear">Clear</button>
      <button class="void">Void</button>
      <form method="POST" action="receipt.php">
        <input type="hidden" name="checkout" value="1" />
        <button type="submit" class="checkout">CHECKOUT ORDER</button>
      </form>
    </div>
  </div>
</div>

<script src="admin.js"></script>
<script>
// Same client-side logic as admin.php

document.querySelectorAll('.item').forEach(item => {
  item.addEventListener('click', () => {
    const name = item.getAttribute('data-name');
    const price = item.getAttribute('data-price');
    const data = new FormData();
    data.append('addItem', '1');
    data.append('name', name);
    data.append('price', price);
    fetch('', { method: 'POST', body: data })
      .then(res => res.json())
      .then(order => updateOrderSummary(order));
  });
});

function updateOrderSummary(order) {
  const container = document.getElementById('order-summary-box');
  if (!order || order.length === 0) {
    container.innerHTML = '<p>No items added yet.</p>';
    return;
  }
  let html = '';
  order.forEach(item => {
    const subtotal = item.price * item.qty;
    html += `<div class="order-item">${item.name} x${item.qty} - ₱${subtotal}</div>`;
  });
  container.innerHTML = html;
}

document.querySelector('.clear').addEventListener('click', () => {
  if (!confirm('Clear the entire order?')) return;
  fetch('', { method: 'POST', headers: {'Content-Type': 'application/x-www-form-urlencoded'}, body: 'clearOrder=1' })
    .then(res => res.json())
    .then(() => {
      updateOrderSummary([]);
    });
});

document.querySelector('.void').addEventListener('click', () => {
  alert("Void functionality not implemented yet.");
});

</script>
</body>
</html>
