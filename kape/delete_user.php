<?php
session_start();
include __DIR__ . "/db/upaya_db.php";
include __DIR__ . "/crud/crud_users.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    deleteUser($conn, $id);   // <-- pass $conn here
    header("Location: users.php?msg=deleted");
    exit;
} else {
    echo "User ID missing!";
}

?>
<p>User deleted successfully. <a href="users.php">Go back to Users</a></p>