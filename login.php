<?php
session_start();
if (isset($_SESSION['user_id'])) {
  header('Location: home.php');
  exit;
}

require_once('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $stmt = $pdo->prepare('SELECT id, password FROM users WHERE username = :username');
  $stmt->execute(['username' => $username]);
  $user = $stmt->fetch();

  if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    header('Location: home.php');
    exit;
  } else {
    $error = 'Invalid username or password.';
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Log In</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <?php if (isset($error)) { echo "<p>$error</p>"; } ?>
  <div class="login">
  <h1>Log In</h1>
  <form method="post">
    <input type="text" name="username" required placeholder="Username"><br>
    <input type="password" name="password" required placeholder="Password"><br>
    <button type="submit" class="btn btn-primary btn-block btn-large">Log In</button>
  </form>
  <p>Don't have an account? <a href="signup.php">Sign up</a>.</p>
  </div>
</body>
</html>