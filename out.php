<?php
    session_start();
    include "./Database/Admin.php";

    $admin = new Admin();


    $admin->update($_SESSION["initial_adminID"], ["active" => 0]);

    session_destroy();
    header("Location: /admin-login");
    exit();

?>