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
    $sharedItems = $itemHandler->readWithForeignKey('users', 'items', 'userID');
    
    // Handle delete action
    if (isset($_POST["delete-button"])) {
        $itemIDToDelete = $_POST["item-id"];
        $itemHandler->delete('items', $itemIDToDelete);
        header("Location: /items-shared");
        exit();
    }

    // Handle edit action
    if (isset($_POST["edit-button"])) {
        $_SESSION["editable-itemID"] = $_POST["item-id"];
        header("Location: /update-item");
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
                <p>Items shared</p>
                <div style="width: 100%; display: flex; justify-content: flex-end; padding-right: 3rem">
                    <p style="font-weight: bold;"><?= $InitialUser["name"]?></p>
                </div>
            </div>
            <div class="content-wrap">
                
                <div class="content">
                    <div class="content-title">
                        <h3>Shared items</h3>
                        <div class="add-new">
                            <button>
                                <a href="/add-item">New</a>
                            </button>
                        </div>
                    </div>



                    <div class="item-lists">
                        <?php
                            if ($sharedItems && isset($_SESSION["user_id"]) ) {
                                for ($i = 0; $i < count($sharedItems); $i++):
                                    if ($sharedItems[$i]["userID"] == $_SESSION["user_id"]):
                        ?>
                                        <div class="list-card">
                                            
                                        <div class="item" style="height: 100%; display: flex; align-items: center; gap: 10px">
                                            
                                            <div style="height: 100%;">
                                                <img src="<?= $sharedItems[$i]["itemImage"] ?>" style="all: inherit;"/>
                                            </div>
                                            <div class="item-info">
                                                <h4><?= $sharedItems[$i]["itemName"] ?> - <?= $sharedItems[$i]["itemCategory"] ?></h4>
                                                <p><?= $sharedItems[$i]["itemDescription"] ?></p>
                                            </div>
                                            <p style="color: red"> <?php
                                                if($sharedItems[$i]["expired"] == 1) {
                                                    echo "- unavailable";
                                                }
                                            
                                            ?></p>
                                        </div>
                                        
                                        <div class="item-action">
                                            <div class="buttons">

                                                <?php
                                            
                                                    if($sharedItems[$i]["expired"] == 0 ) :
                                                
                                                ?>
                                                <form method="post">
                                                    <input type="hidden" name="item-id" value="<?= $sharedItems[$i]["itemID"] ?>">
                                                    <button type="submit" name="edit-button">
                                                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 24 24">
                                                            <path d="M 18.414062 2 C 18.158062 2 17.902031 2.0979687 17.707031 2.2929688 L 15.707031 4.2929688 L 14.292969 5.7070312 L 3 17 L 3 21 L 7 21 L 21.707031 6.2929688 C 22.098031 5.9019687 22.098031 5.2689063 21.707031 4.8789062 L 19.121094 2.2929688 C 18.926094 2.0979687 18.670063 2 18.414062 2 z M 18.414062 4.4140625 L 19.585938 5.5859375 L 18.292969 6.8789062 L 17.121094 5.7070312 L 18.414062 4.4140625 z M 15.707031 7.1210938 L 16.878906 8.2929688 L 6.171875 19 L 5 19 L 5 17.828125 L 15.707031 7.1210938 z"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                                <?php
                                            
                                                    endif;


                                                ?>
                                                <form method="post">
                                                    <input type="hidden" name="item-id" value="<?= $sharedItems[$i]["itemID"] ?>">
                                                    <button type="submit" name="delete-button">
                                                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 16 16">
                                                            <path d="M 6.496094 1 C 5.675781 1 5 1.675781 5 2.496094 L 5 3 L 2 3 L 2 4 L 3 4 L 3 12.5 C 3 13.328125 3.671875 14 4.5 14 L 10.5 14 C 11.328125 14 12 13.328125 12 12.5 L 12 4 L 13 4 L 13 3 L 10 3 L 10 2.496094 C 10 1.675781 9.324219 1 8.503906 1 Z M 6.496094 2 L 8.503906 2 C 8.785156 2 9 2.214844 9 2.496094 L 9 3 L 6 3 L 6 2.496094 C 6 2.214844 6.214844 2 6.496094 2 Z M 5 5 L 6 5 L 6 12 L 5 12 Z M 7 5 L 8 5 L 8 12 L 7 12 Z M 9 5 L 10 5 L 10 12 L 9 12 Z"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>

                                            
                                        </div>
                                    </div>
                        <?php
                                    endif;
                                endfor;
                            } else {
                        ?>
                            <div class="message" style="display: flex; height: 100%; width: 100%; align-items: center; justify-content: center; flex-direction: column">
                                <p>No item available.</p>
                                <button style="background-color: lightgrey; border: none; padding: 10px; border-radius: 0.3rem; margin: 10px">
                                    <a href="/add-item" style="font-size: 12px; color: #000000; text-decoration: none">Share now</a>
                                </button>
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