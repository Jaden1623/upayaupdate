<?php
session_start();
include __DIR__ . "/db/upaya_db.php";
include __DIR__ . "/crud/crud_users.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Get user ID from URL
if (!isset($_GET['id'])) {
    header("Location: users.php");
    exit;
}

$user_id = $_GET['id'];
$user = getUserById($conn, $user_id); // make sure this function exists in crud_users.php

if (!$user) {
    header("Location: users.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = $_POST['password'];
    $role     = $_POST['role'];

    // If password field is empty → keep the old password
    if (empty($password)) {
        $hashed_password = $user['password'];
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    }

    updateUser($conn, $user_id, $username, $email, $hashed_password, $role);
    header("Location: users.php?msg=updated");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit User</title>
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
        <h2>Edit User</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" value="<?= htmlspecialchars($user['username']); ?>" required>
            <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($user['email']); ?>" required>
            <input type="password" name="password" placeholder="New Password (leave blank to keep current)">
            <select name="role" required style="
                width: 100%; 
                padding: 10px 12px; 
                margin-bottom: 18px; 
                border: none; 
                border-radius: 8px; 
                background: #f2e2ce; 
                color: #3b2417; 
                font-size: 14px;
            ">
                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                <option value="staff" <?= $user['role'] === 'staff' ? 'selected' : ''; ?>>Staff</option>
            </select>
            <button type="submit">Update User</button>
        </form>
    </div>

</div>

</body>
</html>
