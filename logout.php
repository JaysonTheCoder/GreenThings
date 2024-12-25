<?php
    session_start();
    include "./Database/User.php";

    $user = new User();


    $user->update($_SESSION["user_id"], ["active" => 0]);

    session_destroy();
    header("Location: /");
    exit();

?>