<?php
session_start();
include __DIR__ . "/db/upaya_db.php";

// Default SQL for users
$sql = "SELECT * FROM users";
$search = "";
$filter = "";

// Search handler
if (isset($_GET['search']) && isset($_GET['filter'])) {
    $search = $_GET['search'];
    $filter = $_GET['filter'];

    if (!empty($search) && !empty($filter)) {
        $allowed_filters = ['id', 'username', 'email', 'role'];
        if (in_array($filter, $allowed_filters)) {
            $sql = "SELECT * FROM users WHERE $filter LIKE '%" . $conn->real_escape_string($search) . "%'";
        }
    }
}

// Run query
$result = $conn->query($sql);
if ($result === false) {
    die("SQL Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Upâyâ Café | Staff Management</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="pos.css">
<style>
/* Keep most of your previous styles for layout and buttons */
body {
    font-family: 'Poppins', sans-serif;
    background-color: #3a2818;
    margin: 0;
}
.settings-container {
    width: calc(100% - 80px);
    margin-left: 80px;
    padding: 25px;
}
.settings-title {
    font-size: 28px;
    font-weight: 600;
    margin-bottom: 20px;
    color: #f5e9dd;
}
.settings-wrapper {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
table { width: 100%; border-collapse: collapse; margin-top: 15px; }
thead { background: #3a2818; color: white; }
th, td { padding: 12px; border-bottom: 1px solid #d8c2a8; font-size: 14px; }
tr:hover td { background: #c7a989; }
.btn-add, .btn-search { background: #3a2818; color: white; padding: 10px 14px; border: none; border-radius: 6px; cursor: pointer; font-weight: 500; }
.btn-reset { background: #777; color: white; padding: 10px 14px; border-radius: 6px; border: none; }
.btn-edit { background: #c58c2c; color: white; padding: 6px 10px; border-radius: 6px; text-decoration: none; }
.btn-delete { background: #a83232; color: white; padding: 6px 10px; border-radius: 6px; text-decoration: none; }
</style>
</head>
<body>

<!-- LOGOUT BUTTON -->
<form action="signin.php" method="POST" style="display:inline;">
    <button class="logout-btn" type="submit">LOG OUT</button>
</form>

<!-- LOGO -->
<div class="logo">
    <h1>Upâyâ</h1>
    <p>Café</p>
</div>

<div class="pos-container">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <a href="admin.php" class="icon active">🏠</a>
        <a href="orderhistory.php" class="icon active">📝</a>
        <a href="inventory.php" class="icon active">📦</a>
        <a href="salesreport.php" class="icon active">📊</a>
        <a href="settings.php" class="icon active">⚙️</a>
        <a href="logout.php" class="icon active">⬅️</a>
    </div>

    <!-- CONTENT -->
    <div class="settings-container">
        <h2 class="settings-title">Staff Management</h2>

        <div class="settings-wrapper">

            <!-- SEARCH + FILTER -->
            <form method="GET" class="row" style="display:flex; gap:10px; margin-bottom:20px;">
                <input 
                    type="text" 
                    name="search" 
                    value="<?= htmlspecialchars($search) ?>" 
                    placeholder="Search staff..." 
                    style="flex:1; padding:10px; border-radius:6px; border:1px solid #bca58c;"
                >

                <select name="filter" 
                    style="padding:10px; border-radius:6px; border:1px solid #bca58c;">
                    <option value="">Filter</option>
                    <option value="username" <?= ($filter=="username")?"selected":"" ?>>Username</option>
                    <option value="email" <?= ($filter=="email")?"selected":"" ?>>Email</option>
                    <option value="role" <?= ($filter=="role")?"selected":"" ?>>Role</option>
                </select>

                <button type="submit" class="btn-search">Search</button>
                <a href="settings.php" class="btn-reset">Reset</a>
            </form>

            <!-- ADD BUTTON -->
            <a href="add_user.php" class="btn-add">+ Add New Staff</a>

            <!-- STAFF TABLE -->
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                <?php
                if ($result === false) {
                    echo "<tr><td colspan='5' style='color:red;'>SQL Error: " . $conn->error . "</td></tr>";
                } else {
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['username']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['role']}</td>
                                <td>
                                    <a class='btn-edit' href='edit_user.php?id={$row['id']}'>Edit</a>
                                    <a class='btn-delete' href='delete_user.php?id={$row['id']}' onclick=\"return confirm('Delete this staff?');\">Delete</a>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' style='text-align:center; color:#555;'>No staff found</td></tr>";
                    }
                }
                ?>
                </tbody>
            </table>

        </div>
    </div>

</div>

</body>
</html>
