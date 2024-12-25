<?php
session_start();
include "./Database/Admin.php";

$usernameError = $passwordError = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    unset($_SESSION["invalid-user"]);
    $isValid = true;

    if (empty($_POST["username"])) {
        $usernameError = "Username is required.";
        $isValid = false;
    }
    if (empty($_POST["password"])) {
        $passwordError = "Password is required.";
        $isValid = false;
    }

    if ($isValid) {
        $admin = new Admin();
        $result = $admin->login($_POST["username"], $_POST["password"]);
        $admin->update($_SESSION["initial_adminID"], ["active" => 1]);
        if(!$result) {
            $_SESSION["invalid-user"] = "Invalid username or password.";
        }
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login</title>
  <link rel="stylesheet" href="../../../styles/admin-login.css">
  <style>
    .error-message {
      color: red;
      font-size: 12px;
      margin-top: 5px;
    }
  </style>
</head>
<body>
  <div class="login-container">
    
    <div class="login-card">

        <?php
            if(isset($_SESSION["invalid-user"])) {
                
            
        
        ?>
            <div class="invalid-message">
                <p>Invalid username or password.</p>
            </div>
        <?php
            }
        ?>
      <h1 class="login-title">Admin Login</h1>
      <form method="post" class="login-form">
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" placeholder="Enter your username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
          <?php if (!empty($usernameError)) { ?>
            <p class="error-message"><?php echo $usernameError; ?></p>
          <?php } ?>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Enter your password">
          <?php if (!empty($passwordError)) { ?>
            <p class="error-message"><?php echo $passwordError; ?></p>
          <?php } ?>
        </div>
        <button type="submit" name="submit" class="login-btn">Login</button>
      </form>
      <div class="login-footer">
        <p style="font-size: 12px;">Don't have an account? <a href="/admin-signup" class="forgot-password">Create account</a></p>
      </div>
    </div>
  </div>
</body>
</html>
