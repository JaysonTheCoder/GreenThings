<?php
    session_start();
    include "./Database/ItemHandler.php";
    include "./Database/User.php";
    include "./Database/Admin.php";

    if(!isset($_SESSION["initial_adminID"])) {
        header("Location: /admin-login");
        exit();
        die();
    }
    $user = new User();
    $admin = new admin();
    $allUsers = $user->read();
    $allAdmin = $admin->read();

    $initialAdmin = $admin->read($_SESSION["initial_adminID"]);


    $itemHandler = new ItemHandler(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    $allItems = $itemHandler->readAllItems("items");
    $allApprovedItems = $itemHandler->readAllItems("approveditems");

    $onlineUsers = 0;

    foreach($allUsers as $users) {
        if($users["active"] == 1) {
            $onlineUsers += 1;
        }
    }
    $onlineAdmin = 0;

    foreach($allAdmin as $admin) {
        if($admin["active"] == 1) {
            $onlineAdmin += 1;
        }
    }

    echo $_SESSION["initial_adminID"];
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neighborhood Sharing Platform - Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../styles/admin-dashboard.css">
</head>
<body>

    <?php
    
        include "./Pages/admin/partials/sidebar.php";
    ?>
    <div class="main-content">
        

        <?php
        
            include "./Pages/admin/partials/app-bar.php";
        
        
        ?>
        <div class="stats">
            <div class="stat-card">
                <div class="icon">👥</div>
                <h3>Total Users</h3>
                <div class="value"><?= count($allUsers)?></div>
            </div>
            <div class="stat-card">
                <div class="icon"> 🌍</div>
                <h3>Online users</h3>
                <div class="value"><?= $onlineUsers?></div>
            </div>
            <div class="stat-card">
                <div class="icon">🧑‍💼</div>
                <h3>Total admin</h3>
                <div class="value"><?= count($allAdmin)?></div>
            </div>
            <div class="stat-card">
                <div class="icon">🏷️</div>
                <h3>Total items</h3>
                <div class="value"><?= count($allItems)?></div>
            </div>
            <div class="stat-card">
                <div class="icon">✔️</div>
                <h3>Items Approved</h3>
                <div class="value"><?= count($allApprovedItems)?></div>
            </div>
            <div class="stat-card">
                <div class="icon">🟢</div>
                <h3>Online admin</h3>
                <div class="value"><?= $onlineAdmin?></div>
            </div>
        </div>

    </div>

</body>
</html>
