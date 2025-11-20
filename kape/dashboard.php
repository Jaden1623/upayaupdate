<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['role'] !== 'admin') {
    header("Location: admin_pos.php");
    exit;
}

$role = isset($_SESSION['role']) ? strtolower(trim($_SESSION['role'])) : '';
$isAdmin = ($role === 'admin');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Upâyâ Café | Dashboard</title>
  <link rel="stylesheet" href="dashboard.css" /> <!-- use same CSS -->
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@1,600&family=Poppins:wght@400;500&display=swap" rel="stylesheet" />
</head>
<body>

<div class="pos-container">


  <!-- Main content -->
  <div class="main-content">
    <div class="logo">
      <h1>Upâyâ</h1>
      <p>Café</p>
    </div>

    <h1 class="title">Dashboard</h1>

    <div class="stats-grid">

  <?php if ($isAdmin): ?>
  <a href="users.php" class="card">
      <h2>Users List</h2>
      <p>Manage all user accounts</p>
  </a>
  <?php endif; ?>

  <a href="inventory.php" class="card">
      <h2>Inventory</h2>
      <p>View and manage stocks</p>
  </a>

  <a href="admin.php" class="card">
      <h2>Start Ordering</h2>
      <p>Proceed to POS</p>
  </a>

</div>

  </div>

</div>

</body>
</html>
