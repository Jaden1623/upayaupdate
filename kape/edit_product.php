<?php
session_start();
include __DIR__ . "/db/upaya_db.php";
include __DIR__ . "/crud/crud_inventory.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Get product ID from URL
if (!isset($_GET['id'])) {
    header("Location: inventory.php");
    exit;
}

$product_id = $_GET['id'];
$product = getProductById($conn, $product_id); // make sure this function exists

if (!$product) {
    header("Location: inventory.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST['product_name'];
    $price        = $_POST['price'];
    $stock        = $_POST['stock'];
    $category     = $_POST['category'];

    updateProduct($conn, $product_id, $product_name, $price, $stock, $category); // make sure this function exists
    header("Location: inventory.php?msg=updated");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Product</title>
<link rel="stylesheet" href="add_user.css" />
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@1,600&family=Poppins:wght@400;500&display=swap" rel="stylesheet" />
</head>
<body>

<div class="pos-container">

    <div class="logo">
      <h1>Upâyâ</h1>
      <p>Café</p>
    </div>

    <div class="form-box">
        <h2>Edit Product</h2>
        <form method="POST">
            <input type="text" name="product_name" placeholder="Product Name" value="<?= htmlspecialchars($product['product_name']); ?>" required>
            <input type="number" name="price" placeholder="Price" value="<?= htmlspecialchars($product['price']); ?>" required>
            <input type="number" name="stock" placeholder="Stock" value="<?= htmlspecialchars($product['stock']); ?>" required>
            <input type="text" name="category" placeholder="Category" value="<?= htmlspecialchars($product['category']); ?>">
            <button type="submit">Update Product</button>
        </form>
    </div>

</div>

</body>
</html>
