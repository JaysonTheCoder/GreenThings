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
$admin = new Admin();
$itemHandler = new ItemHandler(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Fetching users and admins
$allUsers = $user->read();
$allAdmin = $admin->read();

// Merging user and admin data
$allUserAndAdmin = [];
foreach ($allAdmin as $adminRecord) {
    $allUserAndAdmin[] = [
        "name" => $adminRecord["name"],
        "username" => $adminRecord["username"],
        "status" => $adminRecord["active"] == 1 ? "online" : "offline",
        "userType" => "admin",
        "adminID" => $adminRecord["adminID"]
    ];
}
foreach ($allUsers as $userRecord) {
    $allUserAndAdmin[] = [
        "name" => $userRecord["name"],
        "username" => $userRecord["username"],
        "status" => $userRecord["active"] == 1 ? "online" : "offline",
        "userType" => "user",
        "userID" => $userRecord["userID"]
    ];
}

// Handling user edits
if (isset($_POST["edit-user-button"])) {
    $_SESSION["userToEdit"] = [
        "editableType" => $_POST["editableType"],
        "editableID" => $_POST["editableID"]
    ];
    header("Location: /update-user");
    exit;
}

// Handling user deletion
if (isset($_POST["delete-user-button"])) {
    $deletableID = $_POST["deletableID"];
    $deletableType = $_POST["deletableType"];
    
    if ($deletableType === "admin") {
        $admin->delete($deletableID);
    } elseif ($deletableType === "user") {
        $user->deleteV2($deletableID);
    }
    
    header("Location: /user-management");
    exit;
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../styles/user-management.css">
</head>
<body>

<?php
    
    include "./Pages/admin/partials/sidebar.php";
?>
    <div class="main-content">
        <!-- Header -->
        <div class="header">
            <div class="title">User Management</div>
        </div>

        <!-- User Table -->
        <table class="user-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Status</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>


                <?php
                    foreach($allUserAndAdmin as $users):
                ?>
                    <?php if (isset($users["name"], $users["username"], $users["userType"])): ?>
                        <tr>
                            <td><?= htmlspecialchars($users["name"]) ?></td>
                            <td><?= htmlspecialchars($users["username"]) ?></td>
                            <td>
                                <span style="color: <?= $users["status"] === "online" ? "limegreen" : "red" ?>;">
                                    <?= htmlspecialchars($users["status"]) ?>
                                </span>
                            </td>
                            <td><span class="role"><?= htmlspecialchars($users["userType"]) ?></span></td>
                            <td class="action-buttons">
                                <form method="post">
                                    <input type="hidden" name="editableID" value="<?= isset($users["userID"]) ? $users["userID"] : (isset($users["adminID"]) ? $users["adminID"] : '') ?>">
                                    <input type="hidden" name="editableType" value="<?= htmlspecialchars($users["userType"]) ?>">
                                    <button type="submit" name="edit-user-button">edit</button>
                                </form>
                                <form method="post">
                                    <input type="hidden" name="deletableID" value="<?= isset($users["userID"]) ? $users["userID"] : (isset($users["adminID"]) ? $users["adminID"] : '') ?>">
                                    <input type="hidden" name="deletableType" value="<?= htmlspecialchars($users["userType"]) ?>">
                                    <button class="delete" name="delete-user-button">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endif; ?>

                <?php
                    endforeach;
                
                
                ?>
            </tbody>
        </table>

    
    </div>

</body>
</html>
