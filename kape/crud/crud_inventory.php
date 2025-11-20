<?php
// Function to read all products
function readProducts($conn) {
    $sql = "SELECT * FROM inventory"; // table name in your DB
    $result = $conn->query($sql);

    if ($result === false) {
        die("Error reading products: " . $conn->error);
    }

    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    return $products;
}

// Function to add a product
function addProduct($conn, $name, $price, $quantity, $category) {
    $stmt = $conn->prepare("INSERT INTO inventory (product_name, price, stock, category) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdis", $name, $price, $quantity, $category);
    $stmt->execute();
    $stmt->close();
}

// Function to delete a product
function deleteProduct($conn, $id) {
    // Make sure table name and id column match your DB
    $stmt = $conn->prepare("DELETE FROM inventory WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

// Function to get a product by ID
function getProductById($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM inventory WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}
// Function to update a product
function updateProduct($conn, $id, $name, $category, $price, $stock) {
    $stmt = $conn->prepare("UPDATE inventory SET product_name = ?, category = ?, price = ?, stock = ? WHERE id = ?");
    $stmt->bind_param("ssdii", $name, $category, $price, $stock, $id);
    return $stmt->execute();
}

?>
