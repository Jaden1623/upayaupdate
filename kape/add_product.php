<?php
session_start();
include __DIR__ . "/db/upaya_db.php"; 
include __DIR__ . "/crud/crud_inventory.php";

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST['product_name'];
    $price        = $_POST['price'];
    $stock        = $_POST['stock'];
    $category     = $_POST['category'];

    addProduct($conn, $product_name, $price, $stock, $category);
    header("Location: inventory.php?msg=added");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Product</title>
<link rel="stylesheet" href="add_user.css" />
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@1,600&family=Poppins:wght@400;500&display=swap" rel="stylesheet" />
</head>
<body>

    <div class="logo">
      <h1>Upâyâ</h1>
      <p>Café</p>
    </div>

<div class="pos-container" style="display:flex; justify-content:center; align-items:center; height:100vh; position:relative;">


    <div class="form-box">
        <div class="user-icon" style="height: 90px;"></div>
        <h2 style="margin-bottom:20px; color:#f4e7d5;">Add Product</h2>
        <form method="POST">
            <label for="product_name">Product Name</label>
            <input type="text" name="product_name" placeholder="Product Name" required>

            <label for="price">Price</label>
            <input type="number" name="price" placeholder="Price" required>

            <label for="stock">Stock</label>
            <input type="number" name="stock" placeholder="Stock" required>

            <label for="category">Category</label>
            <input type="text" name="category" placeholder="Category">

            <button type="submit">Add Product</button>
        </form>
    </div>

</div>

</body>
</html>
