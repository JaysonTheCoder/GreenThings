<?php
    session_start();
    include './Database/ItemHandler.php';
    include './Database/config.ini.php';
    include './Database/User.php';

    if(!isset($_SESSION["user_id"])) {
        header("Location: /admin-login");
        exit();
        die();
    }
    


    $itemHandler = new ItemHandler(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $user = new User();
    $RequestsItems = $itemHandler->readWithThreeTables('users', 'requests', 'items', 'userID', 'itemID');

    
    // echo "<pre>";
    //  var_dump($RequestsItems);
    // echo "</pre>";
    
    // Handle delete action
    if (isset($_POST["delete-button"])) {
        $itemIDToDelete = $_POST["item-id"];
        $itemHandler->delete('items', $itemIDToDelete);
        header("Location: /items-shared");
        exit();
    }

    // Handle approve action
    if (isset($_POST["approve-button"])) {
        $_SESSION["approved-itemID"] = $_POST["item-id"];
        $_SESSION["approved-requestID"] = $_POST["request-id"];
        header("Location: /approval-form");
        exit();
    }

    $InitialUser = $user->read($_SESSION["user_id"]);
    // echo $InitialUser["name"];

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
                <p>Requests</p>
                <div style="width: 100%; display: flex; justify-content: flex-end; padding-right: 3rem">
                    <p style="font-weight: bold;"><?= $InitialUser["name"]?></p>
                </div>
            </div>
            <div class="content-wrap">
                
                <div class="content">
                    <div class="content-title">
                        <h3>Requested items</h3>
                    </div>



                    <div class="item-lists">
                        <?php
                            if ($RequestsItems && isset($_SESSION["user_id"])):
                                for ($i = 0; $i < count($RequestsItems); $i++):
                                    if ($RequestsItems[$i]["userID"] == $_SESSION["user_id"] && $RequestsItems[$i]["expired"] <= 0) {
                                        unset($_SESSION["no_request"]);
                        ?>  

                                    <p style="font-size: 12px;margin-left: 10px">Request from <?= $RequestsItems[$i]["userRequested"]?> - <?= $RequestsItems[$i]["contact"]?></p>
                                    <div class="list-card">

                                        <div class="item" style="height: 100%; display: flex; align-items: center; gap: 10px">
                                            <div style="height: 100%;">
                                                <img src="<?=$RequestsItems[$i]["itemImage"] ?>" style="all: inherit;"/>
                                            </div>
                                            <div class="item-info">
                                                <h4><?= $RequestsItems[$i]["itemName"] ?> - <?= $RequestsItems[$i]["itemCategory"] ?></h4>
                                                <p><?= $RequestsItems[$i]["itemDescription"] ?></p>
                                            </div>
                                        </div>

                                        <div class="item-action">
                                            <div class="buttons">
                                                <!-- Edit button -->
                                                <form method="post">
                                                    <input type="hidden" name="item-id" value="<?= $RequestsItems[$i]["itemID"] ?>">
                                                    <input type="hidden" name="request-id" value="<?= $RequestsItems[$i]["requestID"] ?>">
                                                    <button type="submit" name="approve-button">
                                                        <img width="24" height="24" src="https://img.icons8.com/ios/50/checked-user-male.png" alt="checked-user-male"/>
                                                    </button>
                                                </form>

                                                <!-- Delete button -->
                                                <form method="post">
                                                    <input type="hidden" name="item-id" value="<?= $sharedItems[$i]["itemID"] ?>">
                                                    <input type="hidden" name="request-id" value="<?= $sharedItems[$i]["requestID"] ?>">
                                                    <button type="submit" name="delete-button">
                                                    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 50 50">
                                                    <path d="M 25 2 C 12.309534 2 2 12.309534 2 25 C 2 37.690466 12.309534 48 25 48 C 37.690466 48 48 37.690466 48 25 C 48 12.309534 37.690466 2 25 2 z M 25 4 C 36.609534 4 46 13.390466 46 25 C 46 36.609534 36.609534 46 25 46 C 13.390466 46 4 36.609534 4 25 C 4 13.390466 13.390466 4 25 4 z M 32.990234 15.986328 A 1.0001 1.0001 0 0 0 32.292969 16.292969 L 25 23.585938 L 17.707031 16.292969 A 1.0001 1.0001 0 0 0 16.990234 15.990234 A 1.0001 1.0001 0 0 0 16.292969 17.707031 L 23.585938 25 L 16.292969 32.292969 A 1.0001 1.0001 0 1 0 17.707031 33.707031 L 25 26.414062 L 32.292969 33.707031 A 1.0001 1.0001 0 1 0 33.707031 32.292969 L 26.414062 25 L 33.707031 17.707031 A 1.0001 1.0001 0 0 0 32.990234 15.986328 z"></path>
                                                    </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                        <?php
                                    } else {
                                        $_SESSION["no_request"] = "No available request</p>";
                                    }
                                endfor;
                            endif;

                            if(isset($_SESSION["no_request"])) {
                                
                            
                        ?>
                                <div class="no-request-message" style="height: 100%; display: flex; align-items: center; justify-content: center">
                                    <p><?= $_SESSION["no_request"]?></p>
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