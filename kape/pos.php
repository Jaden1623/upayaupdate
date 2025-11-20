<?php
session_start();

// Handle checkout form submission
$checkoutMessage = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    // Optionally get payment type from POST if exists
    $paymentType = $_POST['paymentType'] ?? 'cash';

    // Order array from session or empty
    $order = $_SESSION['order'] ?? [];

    // Compute total price
    $total = 0.0;
    foreach ($order as $item) {
        $total += $item['price'] * $item['qty'];
    }

    // Save order to file (or you can save to DB)
    file_put_contents('orders.json', json_encode([
        'order' => $order,
        'total' => $total,
        'payment' => $paymentType,
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_PRETTY_PRINT));

    // Clear session order after checkout
    unset($_SESSION['order']);

    $checkoutMessage = "Order paid. Total: ₱" . number_format($total, 2);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Upâyâ Café | POS System</title>
  <link rel="stylesheet" href="pos.css" />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@1,600&family=Poppins:wght@400;500&display=swap" rel="stylesheet" />
</head>
<body>
  

  <div class="logo">
    <h1>Upâyâ</h1>
    <p>Café</p>
    
  </div>

  <div class="pos-container">
    <!-- Sidebar -->
    <div class="sidebar">
      <a href="pos.php" class="icon <?= isset($activePage) && $activePage == 'pos.php' ? 'active' : '' ?>">🏠</a>
       <a href="settings.php"
   class="icon <?= isset($activePage) && $activePage == 'settings.php' ? 'active' : '' ?>">⚙️</a> 
      
    </div>
    

    <!-- Main Menu Section -->
    <div class="menu-section">
      <div class="search-bar">
        <input type="text" placeholder="SEARCH FOR PRODUCT" id="search-box" />
      </div>

      <div class="category-tabs">
        <button><a href="pos.php"><h3>COFFEE</h3></a></button>
        <button><a href="pos1.php">PREMIUM MATCHA SERIES</a></button>
        <button><a href="pos2.php">NON-COFFEE DRINKS</a></button>
        <button><a href="pos3.php">FRAPPE</a></button>
        <button><a href="pos4.php">FRUIT SODA</a></button>
        <button><a href="pos5.php">PREMIUM TEA SERIES</a></button>
        <button><a href="pos6.php">ADD-ONS</a></button>
        <button><a href="pos7.php">COOKIES & MUFFINS</a></button>
        <button><a href="pos8.php">WAFFLES</a></button>
        <button><a href="pos9.php">FLAVORED FRIES</a></button>
        <button><a href="pos10.php">PASTA</a></button>
      </div>

      <div class="product-grid" id="product-grid">
        <h3>ESPRESSO</h3>
        <div class="items">
          <div class="item" data-name="Americano" data-price="110">Americano - 110</div>
          <div class="item" data-name="Cafe Latte" data-price="120">Cafe Latte - 120</div>
          <div class="item" data-name="Caramel Macchiato" data-price="135">Caramel Macchiato - 135</div>
          <div class="item" data-name="Iced Mocha" data-price="125">Iced Mocha - 125</div>
          <div class="item" data-name="White Chocolate Mocha" data-price="135">White Chocolate Mocha - 135</div>
          <div class="item" data-name="Salted Caramel Latte" data-price="135">Salted Caramel Latte - 135</div>
          <div class="item" data-name="Spanish Latte" data-price="130">Spanish Latte - 130</div>
          <div class="item" data-name="Hazelnut Latte" data-price="130">Hazelnut Latte - 130</div>
          <div class="item" data-name="French Vanilla Latte" data-price="110">French Vanilla Latte - 110</div>
          <div class="item" data-name="English Toffee Latte" data-price="120">English Toffee Latte - 120</div>
          <div class="item" data-name="Short Bread Cookie Latte" data-price="130">Short Bread Cookie Latte - 130</div>
        </div>

        <h3>MUST-TRY COFFEE FLAVORS</h3>
        <div class="items">
          <div class="item" data-name="Butterscotch Latte" data-price="135">Butterscotch Latte - 135</div>
          <div class="item" data-name="Pumpkin Spice Latte" data-price="130">Roasted Almond Latte - 130</div>
          <div class="item" data-nname="Macadamia Nut Latte" data-price="130">Macadamia Nut Latte - 130</div>
          <div class="item" data-name="Toasted Marshmallow Latte" data-price="135">Toasted Marshmallow Latte - 135</div>
        </div>

        <h3>SPECIAL COFFEE FLAVORS</h3>
        <div class="items">
          <div class="item" data-name="Sea Salt Latte" data-price="140">Sea Salt Latte - 140</div>
          <div class="item" data-name="Pumpkin Spice Latte" data-price="140">Pumpkin Spice Latte - 140</div>
          <div class="item" data-name="Choco Mint Latte" data-price="145">Choc*Nut Latte - 145</div>
          <div class="item" data-name="Biscoff Latte" data-price="145">Biscoff Latte - 165</div>
        </div>
      </div>
    </div>

    <!-- Order Summary -->
    <div class="order-summary">
      
      <h3>Order Summary</h3>
      <div class="summary-box" id="order-summary-box">
        <p>No items added yet.</p>
      </div>

       <div class="checkout-row">
      <button class="clear">Clear</button>
      <button class="void">Void</button>
      <form action="receipt.php" method="POST">
      <button class="checkout">CHECKOUT ORDER</button>
      </form>
    </div>  
  </div>

<script src="pos.js"></script>


</body>
</html>