<?php
session_start();

// Set inactivity timeout in seconds (e.g., 5 minutes)
$timeout_duration = 300; // 5 minutes

// Check if last activity is set
if (isset($_SESSION['LAST_ACTIVITY']) && 
    (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    // Session timed out
    session_unset();
    session_destroy();
    header("Location: signin.php");
    exit();
}

// Update last activity time
$_SESSION['LAST_ACTIVITY'] = time();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Upâyâ Café | Settings</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
body {
    font-family: 'Poppins', sans-serif;
    background-color: #3a2818;
    margin: 0;
    padding: 0;
}
.logo {
    position: fixed;
    top: 15px;
    left: 25px;
    color: #fff;
}
.logo h1 { margin: 0; font-size: 22px; }
.logo p { margin: 0; font-size: 14px; color: #d7ccc8; }

.pos-container { display: flex; }
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 80px;
    height: 100%;
    background: #5d4037;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding-top: 20px;
}
.sidebar .icon {
    color: white;
    font-size: 24px;
    margin: 15px 0;
    text-decoration: none;
    transition: 0.2s;
}
.sidebar .icon.active { color: #ffb74d; }

.content-container {
    margin-left: 80px;
    width: calc(100% - 80px);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.logout-card {
    background: #c7a989;
    padding: 40px 60px;
    border-radius: 16px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.2);
    text-align: center;
    color: #3a2818;
}
.logout-card h2 { margin-bottom: 15px; font-size: 28px; }
.logout-card p { font-size: 16px; margin-bottom: 25px; }
.logout-btn {
    padding: 15px 30px;
    background: #5d4037;
    color: white;
    font-size: 16px;
    font-weight: 600;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    transition: 0.2s;
}
.logout-btn:hover { background: #3e2723; }
</style>
</head>
<body>


<div class="pos-container">
    <div class="sidebar">
        <a href="admin.php" class="icon">🏠</a>
    </div>

    <div class="content-container">
        <div class="logout-card">
            <h2>Log Out</h2>
            <p>You will be automatically logged out after inactivity.</p>
            <form action="login.php" method="POST">
                <button class="logout-btn" type="submit">LOG OUT NOW</button>
            </form>
        </div>
    </div>
</div>

<!-- Optional JS warning before logout -->
<script>
let timeout = <?= $timeout_duration ?> * 1000; // milliseconds
let warningTime = 60 * 1000; // 1 minute warning
let logoutTimer = setTimeout(() => {
    alert("Session timed out due to inactivity.");
    window.location.href = "signin.php";
}, timeout);

// Reset timer on any activity
['mousemove', 'keypress', 'click'].forEach(evt => {
    document.addEventListener(evt, () => {
        clearTimeout(logoutTimer);
        logoutTimer = setTimeout(() => {
            alert("Session timed out due to inactivity.");
            window.location.href = "signin.php";
        }, timeout);
    });
});
</script>

</body>
</html>
