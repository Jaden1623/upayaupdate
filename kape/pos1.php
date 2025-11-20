<!DOCTYPE php>
<php lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Upâyâ Café | POS System</title>
  <link rel="stylesheet" href="pos.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@1,600&family=Poppins:wght@400;500&display=swap" rel="stylesheet">

</head>
<body>
  
  <!-- <div class="top-bar">
    <span>12:00 AM</span>
    <span>Thu Sept 25</span>
    <div class="right">
      <span>100%</span>
      <span>📶</span>
      <span>🔋</span>
    </div> -->
  </div>

  <div class="logo">
    <h1>Upâyâ</h1>
    <p>Café</p>
  </div>

  <div class="pos-container">
    <!-- Sidebar -->
    <div class="sidebar">
      <a href="pos.php" class="icon active">🏠</a>
       <a href="settings.php"
   class="icon <?= isset($activePage) && $activePage == 'settings.php' ? 'active' : '' ?>">⚙️</a> 
      
    </div>

    <!-- Main Menu Section -->
    <div class="menu-section">
      <div class="search-bar">
        <input type="text" placeholder="SEARCH FOR PRODUCT">
      </div>

      <div class="category-tabs">
        <button><a href="pos.php">COFFEE</a></button>
        <button><a href="pos1.php"><h3>PREMIUM MATCHA SERIES</h3></a></button>
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

      <div class="product-grid">
        <h3>PREMIUM MATCHA SERIES</h3>
        <div class="items">
          <div class="item" data-name="Matcha Latte" data-price="145">Matcha Latte - 145</div>
          <div class="item" data-name="Strawberry Matcha Latte" data-price="160">Strawberry Matcha Latte - 160</div>
          <div class="item" data-name="Blueberry Matcha Latte" data-price="170">Blueberry Matcha Latte - 170</div>
          <div class="item" data-name="Salted Matcha Latte" data-price="160">Salted Matcha Latte - 160</div>
          <div class="item" data-name="Banana Matcha Latte" data-price="160">Banana Matcha Latte - 160</div>
          <div class="item" data-name="Pure Matcha Latte" data-price="160">Pure Matcha Latte - 170</div>
          <div class="item" data-name="Creamy Matcha Latte" data-price="160">Creamy Matcha Latte - 160</div>
          <div class="item" data-name="Peach Matcha Latte" data-price="160">Peach Matcha Latte - 165</div>
          <div class="item" data-name="Dirty Matcha Latte" data-price="160">Dirty Matcha Latte - 130</div>
          <div class="item" data-name="Peach Mango Matcha Latte" data-price="160">Peach Mango Matcha Latte - 155</div>
          <div class="item" data-name="Strawberry Matcha Latte" data-price="160">Mango Matcha Latte - 160</div>
          <div class="item">Biscoff Matcha Latte - 180</div>
          <div class="item">Hazelnut Matcha Latte - 160</div>
          <div class="item">White Choco Matcha Latte - 120</div>
          <div class="item">Vanilla Matcha Latte - 160</div>
        </div>
      </div>
    </div>

    <!-- Order Summary -->
   <div class="order-summary">
  <h3>Order Summary</h3>
  <div class="summary-box" id="order-summary-box">
    <p> </p>
  </div>  
 

     <div class="checkout-row">
      <button class="clear">Clear</button>
      <button class="void">Void</button>
      <form action="receipt.php" method="POST">
      <button class="checkout">CHECKOUT ORDER</button>
      </form>
    </div>  
      </div>
    </div>
  </div>
</body>
</php>