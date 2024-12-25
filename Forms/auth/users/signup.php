
<?php
require_once './Database/Auth.php';
require('./generateID.php');
$auth = new Auth();




if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['firstname']." ".$_POST['lastname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $userType = "user";
    $result = $auth->register($name, $username, $userType, $password, generateNumericId(7));

    if ($result === true) {
        header("Location: /");
    } else {
        echo $result;
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Signup Form</title>
  <link rel="stylesheet" href="../../styles/signup.css">
</head>
<body>
  <div class="container">
    <div class="signup-form">
      <h2>Sign Up</h2>
      <form method="POST">
        <div class="input-group">
          <label for="firstname">First Name</label>
          <input type="text" name="firstname" id="firstname" placeholder="Enter your first name" required>
        </div>
        <div class="input-group">
          <label for="lastname">Last Name</label>
          <input type="text" name="lastname" id="lastname" placeholder="Enter your last name" required>
        </div>
        <div class="input-group">
          <label for="email">Username</label>
          <input type="text" name="username" id="username" placeholder="Enter your username" required>
        </div>
        <div class="input-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Create a password" required>
        </div>
        <div class="input-group">
          <label for="confirm-password">Confirm Password</label>
          <input type="password" name="confirm-password" id="confirm-password" placeholder="Confirm your password" required>
        </div>
        <button type="submit" name="submit" class="btn">Sign Up</button>
      </form>
      <div class="footer">
        <p>Already have an account? <a href="/">Login</a></p>
      </div>
    </div>
    <button class="theme-toggle" id="themeToggle">Toggle Dark Mode</button>
  </div>
  <script>

    const themeToggle = document.getElementById('themeToggle');
    themeToggle.addEventListener('click', () => {
    document.body.classList.toggle('dark-mode');
    });

  </script>
</body>
</html>
