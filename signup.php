<!DOCTYPE html>
<html>
<head>
  <title>Sign Up</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <?php if ($_SERVER['REQUEST_METHOD'] === 'POST') { ?>
    <?php
    require_once('db.php');
    
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate user input
    $errors = array();
    if (empty($username)) {
      $errors[] = 'Username is required.';
    }
    if (empty($password)) {
      $errors[] = 'Password is required.';
    }
    if ($password !== $confirm_password) {
      $errors[] = 'Passwords do not match.';
    }
    
    // Check if username is already taken
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();
    if ($user) {
      $errors[] = 'Username is already taken.';
    }
    
    // Create new user account if input is valid
    if (empty($errors)) {
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $pdo->prepare('INSERT INTO users (username, password) VALUES (:username, :password)');
      $stmt->execute(['username' => $username, 'password' => $hashed_password]);
      
      header('Location: login.php');
      exit;
    }
    ?>
    <?php if (!empty($errors)) { ?>
      <ul>
        <?php foreach ($errors as $error) { ?>
          <li><?php echo $error; ?></li>
        <?php } ?>
      </ul>
    <?php } ?>
  <?php } ?>
  <div class="signup">
  <h1>Sign Up</h1>
  <form method="post">
    <input type="text" name="username" id="username" required placeholder="Username"><br>
    <input type="password" name="password" id="password" required placeholder="Password"><br>
    <input type="password" name="confirm_password" id="confirm_password" required placeholder="Re-Password"><br>
    <button type="submit" class="btn btn-primary btn-block btn-large">Sign Up</button>
  </form>
  <p>Already have an account? <a href="login.php">Log in</a>.</p>
  </div>
</body>
</html>
