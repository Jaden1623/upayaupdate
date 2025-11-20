<?php
session_start();
include __DIR__ . "/db/upaya_db.php";
include __DIR__ . "/crud/crud_inventory.php";

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    if (deleteProduct($conn, $id)) {
        // Redirect back to inventory page with success message
        header("Location: inventory.php?msg=deleted");
        exit;
    } else {
        echo "<p>Failed to delete product. <a href='inventory.php'>Go back to Inventory</a></p>";
    }
} else {
    echo "<p>Product ID missing! <a href='inventory.php'>Go back to Inventory</a></p>";
}
?>
