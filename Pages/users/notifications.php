<?php
    session_start();
    include './Database/ItemHandler.php';
    include './Database/config.ini.php';
    include './Database/User.php';
    include './Database/Requests.php';


    $request = new Requests();
    $user = new User();
    $itemHandler = new ItemHandler(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $InitialUser = $user->read($_SESSION["user_id"]);
    
    $notificationForMe = $itemHandler->readWithThreeTables("approveditems", "requests", "items", "requestID", "itemID", "approveditems.dateToReturn, approveditems.message, requests.userID, items.itemName, items.itemImage, items.itemCategory, items.userID as ownerID");

    // echo "<pre>";
    //     print_r($allTableData);
    // echo "</pre>";


?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shared</title>
    <link rel="stylesheet" href="../../styles/item-shared.css">
</head>
<body>
    <div class="container">
        <?php
        
            include 'partials/side-bar.php';
        ?>
        <div class="content-container">
            <div class="app-bar">
                <p>Notifications</p>
                <div style="width: 100%; display: flex; justify-content: flex-end; padding-right: 3rem">
                    <p style="font-weight: bold;"><?= $InitialUser["name"]?></p>
                </div>
            </div>
            <div class="content-wrap">
                
                <div class="content">
                    <div class="content-title">
                        <h3>Requests update</h3>
                    </div>



                    <div class="item-lists">

                        <?php
                            if($notificationForMe) {
                            foreach($notificationForMe as $notif) :
                                $itemOwner = $user->read($notif["ownerID"]);
                        ?>
                        <div class="list-card">
                            <div class="item" style="height: 100%; display: flex; align-items: center; gap: 10px">
                                            
                                <div style="height: 100%;">
                                    <img src="<?= $notif["itemImage"]?>" alt="imag" style="all: inherit;"/>
                                </div>
                                <div class="item-info">
                                    <div class="item-infoo" style="display: flex; align-items: center; gap: 4px">
                                        <h4><?= $notif["itemName"]?></h4> - 
                                        <p style="font-size: 12px;"><?= $notif["itemCategory"]?></p>
                                    </div>
                                    <div class="owner" style="display: flex; align-items: center; gap: 4px">
                                        <h5><?= $itemOwner["name"]?></h5> 

                                    </div>
                                    <p style="font-size: 10px;"><?= $notif["message"]?></p>
                                </div>
                            </div>
                            <div style="flex: 1">
                                <p style="font-size: 12px;">Date to return</p>
                                <p><?= $notif["dateToReturn"]?></p>
                            </div>
                            <p style="font-size: 11px; color: green; margin-right: 2rem">Approved.</p>
                        </div>
                        <?php
                            endforeach;
                        }else {
                        ?>

                            <div class="error-message" style="display: flex; align-items: center; justify-content: center; height: 100%;">
                                <p>No notifications available.</p>
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