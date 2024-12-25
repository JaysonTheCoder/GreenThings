<?php 
    session_start();
    include "./Database/User.php";
    include "./Database/Admin.php";

    $user = new User();
    $admin = new Admin();



    var_dump($_SESSION["userToEdit"]["editableID"]);


    if ($_SESSION["userToEdit"]["editableType"] == "user" && isset($_POST["update"])) {
        if ($_POST["type"] == "admin") {
            try {
                $userData = $user->read($_SESSION["userToEdit"]["editableID"]);
                
                $admin->create($userData["userID"], $userData["name"], $userData["username"], $_POST["type"], $userData["password"]);
    
                $user->delete($userData["userID"]);
    
                header("Location: /user-management");
                exit();
            } catch (Exception $e) {
                // Handle deletion failure
                echo "Error: " . $e->getMessage();
            }
        }else if($_POST["type"] == "user"){
            header("Location: /user-management");
        }
    }
    else if($_SESSION["userToEdit"]["editableType"] == "admin" && isset($_POST["update"])) {
        if ($_POST["type"] == "user") {
            try {
                $adminData = $admin->read($_SESSION["userToEdit"]["editableID"]);
                // var_dump($adminData);
                $user->create($adminData["adminID"], $adminData["name"], $adminData["username"], $adminData["password"], $_POST["type"]);
    
                $admin->delete($_SESSION["userToEdit"]["editableID"]);
    
                header("Location: /user-management");
                exit();
            } catch (Exception $e) {
                // Handle deletion failure
                echo "Error: " . $e->getMessage();
            }
        }else if($_POST["type"] == "admin"){
            header("Location: /user-management");
        }     
    }
    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update items</title>
    <link rel="stylesheet" href="../../styles/updateForm.css">

</head>
<body>
    <div class="update-form-container">
        

        <form method="post" enctype="multipart/form-data">
            <div class="wrap-input">

                <div class="input-group">
                    <label for="category">User type</label>
                    <select id="category" name="type" required>
                        <option value="" disabled selected>Select type</option>
                        <option value="admin">admin</option>
                        <option value="user">user</option>
                    </select>
                    <span class="error" id="categoryError"></span>
                </div>
                <div class="input-group">
                    <button type="submit" name="update">Update</button>
                    <a href="/user-management">Cancel</a>
                </div>

            </div>
        </form>

    </div>
</body>
</html>
