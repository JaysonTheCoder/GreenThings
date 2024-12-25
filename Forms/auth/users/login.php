
<?php
require('./Database/Auth.php');
require('./Database/User.php');
$user = new User();
$auth = new Auth();

unset($_SESSION["username-empty"]);
unset($_SESSION["password-empty"]);
if (isset($_POST["submit"])) {
    $username = $_POST['username']; 
    $password = $_POST['password'];

    $result = $auth->login($username, $password);
    // echo "ok: ", $result;
    if ($result === true) {
        $user->update($_SESSION["user_id"], ["active" => true] );
        header("Location: home");
        exit();
    }

    if(!$_POST["username"]) {
      $_SESSION["username-empty"] = "Username is required";
    }
    if(!$_POST["password"]) {
      $_SESSION["password-empty"] = "Password is required";
    }


}


?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Responsive Login Form</title>
  <link rel="stylesheet" href="../../styles/login.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=light_mode" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=dark_mode" />
  <style>
.material-symbols-outlined {
  font-variation-settings:
  'FILL' 0,
  'wght' 400,
  'GRAD' 0,
  'opsz' 24
}
</style>
</head>
<body>
  <div class="container">
    <div class="login-form">
      <h2>Login</h2>
      <form method="POST">
        <div class="input-group">
          <label for="username">Username</label>
          <input style="border: 2px solid <?= isset($_SESSION["username-empty"]) ? "rgba(183, 16, 16, 0.5)":"initial" ?>; box-shadow: 0 0 5px <?= isset($_SESSION["username-empty"]) ? "rgba(183, 16, 16, 0.1)":"initial" ?>" type="text" id="username" name="username" placeholder="Enter your username">
        </div>
        <?php
          if(isset($_SESSION["username-empty"])) {
            echo "<p class='error-message'>".$_SESSION["username-empty"]."</p>";
          }
        
        ?>

        <div class="input-group">
          <label for="password">Password</label>
          <input style="border: 2px solid <?= isset($_SESSION["password-empty"]) ? "rgba(183, 16, 16, 0.5)":"initial" ?>; box-shadow: 0 0 5px <?= isset($_SESSION["password-empty"]) ? "rgba(183, 16, 16, 0.1)":"initial" ?>" type="password" id="password" name="password" placeholder="Enter your password">
        </div>
        <?php
          if(isset($_SESSION["password-empty"])) {
            echo "<p class='error-message'>".$_SESSION["password-empty"]."</p>";
          }
        
        ?>
        <button type="submit" name="submit" class="btn">Login</button>
      </form>
      <div class="footer">
        <p>Don't have an account? <a href="/signup">Sign up</a></p>
      </div>
    </div>
</body>
</html>



