<?php
session_start();
if (isset($_SESSION['user_id'])) {
  header('Location: home.php');
  exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Account App</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>Welcome to the Account App!</h1>
  <p>Please <a href="login.php">log in</a> or <a href="signup.php">sign up</a>.</p>
</body>
</html>