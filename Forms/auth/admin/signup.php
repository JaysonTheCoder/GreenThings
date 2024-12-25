<?php
    session_start();

    include "./Database/Admin.php";
    include "./generateID.php";
    $admin = new Admin();

    if(isset($_POST["singup-button"])) {
        $name = $_POST["firstname"]." ".$_POST["lastname"];
        $admin->register(generateNumericId(8), $name, $_POST["username"], $_POST["password"]);

        header("Location: /admin-login");
        exit();
        die();
    }

?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up</title>
  <link rel="stylesheet" href="../../../styles/admin-signup.css">
</head>
<body>
  <div class="signup-container">
    <div class="signup-card">
      <h1 class="signup-title">Create an Account</h1>
      <form method="post" class="signup-form">
        <div class="form-group">
          <label for="firstname">First Name</label>
          <input type="text" id="firstname" name="firstname" placeholder="Enter your first name" required>
        </div>
        <div class="form-group">
          <label for="lastname">Last Name</label>
          <input type="text" id="lastname" name="lastname" placeholder="Enter your last name" required>
        </div>
        <div class="form-group">
          <label for="email">Username</label>
          <input type="text" name="username" id="email" placeholder="Enter your username" required>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" name="password" id="password" placeholder="Create a password" required>
        </div>
        <button type="submit" name="singup-button" class="signup-btn">Sign Up</button>
      </form>
      <div class="signup-footer">
        <p>Already have an account? <a href="/admin-login" class="login-link">Login</a></p>
      </div>
    </div>
  </div>
</body>
</html>



