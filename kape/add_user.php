<?php
session_start();
include __DIR__ . "/db/upaya_db.php";
include __DIR__ . "/crud/crud_users.php";

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = $_POST['password'];
    $role     = $_POST['role'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    addUser($conn, $username, $email, $hashed_password, $role);
    header("Location: users.php?msg=added");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add User</title>
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
    <div class="user-icon">
        <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png" alt="user">
      </div>

        <h2>Add User</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>

            <!-- Styled Select -->
            <label for="role" style="display:none;">Role</label>
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
                <option value="" disabled selected>Select Role</option>
                <option value="admin">Admin</option>
                <option value="staff">Staff</option>
            </select>

            <button type="submit">Add User</button>
        </form>
    </div>

</div>

</body>
</html>
