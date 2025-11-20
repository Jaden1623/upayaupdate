<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include __DIR__ . "/db/upaya_db.php"; // DB connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Staff Authorization | Upâyâ Café</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@1,600&family=Poppins:wght@400;500&display=swap" rel="stylesheet">
<style>
/* ===== Reset ===== */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Poppins', sans-serif;
}

/* ===== Body ===== */
body {
  background: linear-gradient(135deg, #2e1b13, #5b3a27, #b98b5c);
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
}

/* ===== Card ===== */
.auth-card {
  background: rgba(255, 255, 255, 0.05);
  backdrop-filter: blur(10px);
  padding: 40px 30px;
  border-radius: 15px;
  width: 320px;
  box-shadow: 0 8px 25px rgba(0,0,0,0.5);
  text-align: center;
  color: #fff;
}

/* Logo */
.auth-card .logo h1 {
  font-family: 'Playfair Display', serif;
  font-size: 36px;
  margin-bottom: 5px;
}
.auth-card .logo p {
  font-size: 18px;
  margin-bottom: 25px;
}

/* Title */
.auth-card h2 {
  font-family: 'Playfair Display', serif;
  font-size: 22px;
  margin-bottom: 25px;
  color: #f9dfc5;
}

/* Input */
.auth-card input[type="password"] {
  width: 100%;
  padding: 12px 15px;
  margin-bottom: 20px;
  border-radius: 10px;
  border: none;
  font-size: 14px;
  outline: none;
}

/* Button */
.auth-card button {
  width: 100%;
  padding: 12px;
  background-color: #b14a4a; /* red like your cards */
  border: none;
  border-radius: 10px;
  color: #fff;
  font-size: 16px;
  font-weight: 500;
  cursor: pointer;
  transition: 0.3s;
}

.auth-card button:hover {
  background-color: #ff5b5b;
}

/* Error message */
.auth-card .error {
  margin-top: 15px;
  font-size: 14px;
  color: #ff4c4c;
}
</style>
</head>
<body>

<div class="auth-card">
  <div class="logo">
    <h1>Upâyâ</h1>
    <p>Café</p>
  </div>
  <h2>Staff Authorization</h2>

  <form method="POST" id="authForm">
    <input type="password" name="check_pass" placeholder="Enter Admin Password" required>
    <button type="submit">Authorize</button>
  </form>

  <div class="error" id="errorMsg"></div>
</div>

<script>
const form = document.getElementById('authForm');
const errorMsg = document.getElementById('errorMsg');

form.addEventListener('submit', async (e) => {
  e.preventDefault();

  const formData = new FormData(form);

  const res = await fetch('void_auth.php', {
    method: 'POST',
    body: formData
  });

  const result = await res.json();
  if(result.valid) {
    errorMsg.style.color = '#00ff99';
    errorMsg.textContent = 'Access Granted!';
    setTimeout(() => { window.location.href = 'admin.php'; }, 1000);
  } else {
    errorMsg.style.color = '#ff4c4c';
    errorMsg.textContent = result.error || 'Access Denied';
  }
});
</script>

</body>
</html>
