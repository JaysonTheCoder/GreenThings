<?php
session_start();
include "./Database/ItemHandler.php";
include "./Database/config.ini.php";

$itemHandler = new ItemHandler(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (isset($_POST["submit"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $data = [];
        $data["itemName"] = $_POST["itemName"];
        $data["itemCategory"] = $_POST["category"];
        $data["itemDescription"] = $_POST["itemdes"];
        $data["userID"] = $_SESSION["user_id"];
        $data["expired"] = 0;

        if (isset($_FILES["file"]) && $_FILES["file"]["error"] == UPLOAD_ERR_OK) {
            // Set the upload directory relative to the public web directory
            $uploadDir = '/uploads/';  // Make sure this is relative to the web root
            $fileName = uniqid() . '_' . basename($_FILES["file"]["name"]);
            $targetFilePath = $_SERVER['DOCUMENT_ROOT'] . $uploadDir . $fileName;

            if (!is_dir($_SERVER['DOCUMENT_ROOT'] . $uploadDir)) {
                mkdir($_SERVER['DOCUMENT_ROOT'] . $uploadDir, 0777, true);
            }

            if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
                $data["itemImage"] = $uploadDir . $fileName; // Store relative path in the database
            } else {
                throw new Exception("Failed to upload file.");
            }
        }

        $up = $itemHandler->create('items', $data);
        
        if ($up) {
            header("Location: items-shared");
        
            exit();
        } else {
            echo "Failed to update item.";
        }
    } catch (Exception $err) {
        echo "ERROR-adding: " . $err->getMessage();
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../styles/add-item.css">
</head>
<body>
    <div class="add-form-container">
    <form method="post" enctype="multipart/form-data">
            <div class="wrap-input">

                <div class="input-group">
                    <label for="itemname">New item name</label>
                    <input type="text" name="itemName" id="itemname">
                </div>
                <div class="input-group">
                    <label for="category">Category</label>
                    <select id="category" name="category" required>
                        <option value="" disabled selected>Select a category</option>
                        <option value="electronics">Electronics</option>
                        <option value="furniture">Furniture</option>
                        <option value="clothing">Clothing</option>
                        <option value="books">Books</option>
                    </select>
                </div>
                
                <div class="input-group">
                    <label for="itemdes">New item description</label>
                    <input type="text" name="itemdes" id="itemdes" >
                </div>
                <div class="input-group">
                    <label for="file">Choose file to upload</label>
                    <input type="file" name="file" id="file">
                </div>
                <div class="input-group">
                    <button type="submit" name="submit">submit</button>
                    <a href="/items-shared">cancel</a>
                </div>

            </div>
        </form>
    </div>
</body>
</html>