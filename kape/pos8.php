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
        <button><a href="pos1.php">PREMIUM MATCHA SERIES</a></button>
        <button><a href="pos2.php">NON-COFFEE DRINKS</a></button>
        <button><a href="pos3.php">FRAPPE</a></button>
        <button><a href="pos4.php">FRUIT SODA</a></button>
        <button><a href="pos5.php">PREMIUM TEA SERIES</a></button>
        <button><a href="pos6.php">ADD-ONS</a></button>
        <button><a href="pos7.php">COOKIES & MUFFINS</a></button>
        <button><a href="pos8.php"><h3>WAFFLES</h3></a></button>
        <button><a href="pos9.php">FLAVORED FRIES</a></button>
        <button><a href="pos10.php">PASTA</a></button>
      </div>

      <div class="product-grid">
        <h3>WAFFLES</h3>
        <div class="items">
          <div class="item">Classic Maple - 90</div>
          <div class="item">Chocolate - 90</div>
          <div class="item">Strawberry - 90</div>
          <div class="item">Blueberry - 90</div>
          <div class="item">Caramel - 90</div>
          <div class="item">Peanut Butter - 90</div>
          <div class="item">Combo Waffle - 100</div>
          <div class="item">Extra Toppings - 25</div>
          
          
          
          
          
          
          
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