<?php
session_start();

include "./Database/ItemHandler.php";
include './Database/config.ini.php';
include './Database/User.php';

if(!isset($_SESSION["user_id"])) {
    header("Location: /admin-login");
    exit();
    die();
}



$itemHandler = new ItemHandler(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$user = new User();
$data = $itemHandler->readWithForeignKey('users', 'items', "userID");
// var_dump($data);
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["item_id"])) {
    $itemId = $_POST["item_id"];
    foreach ($data as $item) {
        if ($item["itemID"] == $itemId) {
            $_SESSION['requested-item'] = $item["itemID"];
            header("Location: /request-form");
            exit();
        }
    }
}


$InitialUser = $user->read($_SESSION["user_id"]);
// echo $InitialUser["name"];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="../../styles/userhomepage.css">
</head>
<body>
    <div class="container">
        <?php include 'partials/side-bar.php'; ?>
        <div class="content-container">
            <div class="app-bar">
                <p>Homepage</p>
                <div style="width: 100%; display: flex; justify-content: flex-end; padding-right: 3rem">
                    <p style="font-weight: bold;"><?= $InitialUser["name"]?></p>
                </div>
            </div>
            <div class="content-wrap">
                <div class="content">
                    <div class="content-title">
                        <h3>All items</h3>
                    </div>
                    <div class="all-item-card">
                        <?php 

                            if($data) {
                            foreach ($data as $item): 
                                if($item["expired"] <= 0) :
                        ?>
                            <div class="card">
                                <img src="<?= $item["itemImage"] ?>" alt="Item Image" class="card-image">
                                
                                <div class="card-content">
                                    <div class="owner">
                                        <p><?= ucfirst($item["name"]." - "."owner");?></p>
                                    </div>
                                    <h2 class="card-title"><?= $item["itemName"] ?></h2>
                                    <p class="card-category">Category: <?= $item["itemCategory"] ?></p>
                                    <p class="card-description"><?= $item["itemDescription"] ?></p>
                                    <form method="post">
                                        <input type="hidden" name="item_id" value="<?= $item["itemID"] ?>">
                                        <button class="request-button" type="submit">Request</button>
                                    </form>
                                </div>
                            </div>
                        <?php 
                                endif;
                            endforeach; 
                            } else {
                                
                            
                        ?>
                            <div class="message" style="display: flex; height: 100%; width: 100%; align-items: center; justify-content: center">
                                <p>No item available.</p>
                            </div>
                        <?php
                        
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
