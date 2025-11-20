<?php
session_start();

// Clear all session data
session_unset();
session_destroy();

// Redirect to signin page
header("Location: login.php");
exit;
?>
