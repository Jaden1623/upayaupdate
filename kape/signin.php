<?php

session_start();
include __DIR__ . "/db/upaya_db.php";
include __DIR__ . "/crud/crud_users.php";



$msg = ""; // message to display on login page

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $msg = "Please enter username and password!";
    } else {

        $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();

        if ($password === $user['password']) {

            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = strtolower(trim($user['role']));

            // CLEAR ANY EXISTING POS ORDER
            $_SESSION['order'] = [];

            // REDIRECT BASED ON ROLE
            if ($_SESSION['role'] === 'admin') {
                header("Location: dashboard.php");
                exit;
            }
            if ($_SESSION['role'] === 'staff') {
                header("Location: admin.php");
                exit;
            }

            $msg = "Unknown role assigned.";
        }
            else {
                $msg = "Invalid credentials!";
            }
        } 
        else {
            $msg = "Invalid credentials!";
        }

        $stmt->close();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Upaya Café Login</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <!-- <div class="top-bar">
    <span>12:00 AM</span>
    <span>Thu Sept 25</span>
    <div class="right">
      <span>100%</span>
      <i class="wifi">📶</i>
      <i class="battery">🔋</i>
    </div> -->
  </div>

  <div class="container">
    <div class="logo">
      <h1>Upâyâ</h1>
      <p>Café</p>
    </div>

    <div class="login-box">
      <div class="user-icon">
        <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png" alt="user">
      </div>

         <div class="msg">
      <?php if (!empty($msg)) { echo htmlspecialchars($msg); } ?>
    </div>

    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
     <label for="username">USERNAME:</label>
     <input type="text" id="username" name="username" required>

     <label for="password">PASSWORD:</label>
     <input type="password" id="password" name="password" required>

     <button type="submit">LOGIN</button><br>
    
    </form>     
    </form>
    </div>
  </div>
</body>
</html>

