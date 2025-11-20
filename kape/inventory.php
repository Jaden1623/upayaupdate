<?php
session_start();
include __DIR__ . "/db/upaya_db.php"; 
include __DIR__ . "/crud/crud_inventory.php";

// Redirect if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Get role safely
$role = isset($_SESSION['role']) ? strtolower(trim($_SESSION['role'])) : '';
$isAdmin = ($role === 'admin');

// Admin permissions
$canEdit = $isAdmin;
$canDelete = $isAdmin;
$canAdd = $isAdmin;

// Handle messages
$msg = '';
if (isset($_GET['msg']) && $_GET['msg'] === 'deleted') {
    $msg = "Product deleted successfully!";
}

// Fetch products
$products = readProducts($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Upâyâ Café | Inventory</title>
  <link rel="stylesheet" href="inventory.css" />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@1,600&family=Poppins:wght@400;500&display=swap" rel="stylesheet" />
</head>
<body>

<div class="pos-container">

  <!-- Sidebar -->
  <div class="sidebar">
    <a href="dashboard.php" class="icon">🏠 DASHBOARD</a>
    <a href="users.php" class="icon active">👤 USERS</a>
    <a href="inventory.php" class="icon">📦 INVENTORY</a>
    <a href="orders.php" class="icon">📊 SALES REPORT</a>
    <a href="settings.php" class="icon">⚙️ SETTINGS </a>
  </div>

  <!-- Main content -->
  <div class="main-content">
    <div class="logo">
      <h1>Upâyâ</h1>
      <p>Café</p>
    </div>

    <h1 class="title">Inventory System</h1>

    <!-- Add Product Button (Admin Only) -->
    <?php if ($canAdd): ?>
      <div style="text-align:right; margin-bottom:10px; width:95%;">
        <a href="add_product.php" class="add-btn" style="
          background:#ff3b3b; color:white; padding:8px 12px;
          border-radius:6px; text-decoration:none; font-weight:600;">
          + Add Product
        </a>
      </div>
    <?php endif; ?>

    <!-- Success Message -->
    <?php if (!empty($msg)): ?>
      <div class="message" style="color:#00cc66; font-weight:600; text-align:center; margin:10px;">
        <?= htmlspecialchars($msg); ?>
      </div>
    <?php endif; ?>

    <div class="inventory-box">
      <h2>Inventory</h2>

      <div class="table-container">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Product</th>
              <th>Stock</th>
              <th>Price</th>
              <th>Category</th>
              <?php if ($canEdit || $canDelete): ?>
                <th>Actions</th>
              <?php endif; ?>
            </tr>
          </thead>

          <tbody>
            <!-- PHP Loop for Products -->
            <?php foreach ($products as $product): ?>
            <tr>
              <td><?= htmlspecialchars($product['id']); ?></td>
              <td><?= htmlspecialchars($product['product_name']); ?></td>
              <td><?= htmlspecialchars($product['stock']); ?></td>
              <td><?= htmlspecialchars($product['price']); ?></td>
              <td><?= htmlspecialchars($product['category']); ?></td>

              <?php if ($canEdit || $canDelete): ?>
              <td style="text-align:center;">
                
                <!-- EDIT BUTTON -->
                <?php if ($canEdit): ?>
                  <a href="edit_product.php?id=<?= $product['id']; ?>"
                     style="background:#007BFF; color:white; padding:6px 10px;
                     font-weight:600; text-decoration:none; border-radius:5px; margin-right:5px;">
                    Edit
                  </a>
                <?php endif; ?>

                <!-- DELETE BUTTON -->
                <?php if ($canDelete): ?>
                  <a href="delete_product.php?id=<?= $product['id']; ?>"
                     onclick="return confirm('Are you sure you want to delete this product?');"
                     style="background:#DC3545; color:white; padding:6px 10px;
                     font-weight:600; text-decoration:none; border-radius:5px;">
                    Delete
                  </a>
                <?php endif; ?>

              </td>
              <?php endif; ?>
            </tr>
            <?php endforeach; ?>
          </tbody>

        </table>
      </div>
    </div>
  </div>

</div>

</body>
</html>
