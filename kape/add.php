<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Upâyâ Café | Add User</title>
  <link rel="stylesheet" href="add_user.css">
</head>
<body>

  <div class="container">
    <div class="logo">
      <h1>Upâyâ</h1>
      <p>Café</p>
    </div>

    <div class="form-box">
      <div class="user-icon">
        <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png" alt="user">
      </div>

      <!-- ✅ Correct form setup -->
      <form action="signin.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <label for="email">Email:</label>
        <input type="text" id="email" name="email" required>

        <label for="number">Mobile number:</label>
        <input type="number" id="number" name="number" required>

        <button type="submit">ADD USER</button>
      </form>
    </div>
  </div>

</body>
</html>
