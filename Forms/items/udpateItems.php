<?php 
session_start();
include './Database/ItemHandler.php';
include './Database/config.ini.php';

$itemHandler = new ItemHandler(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (isset($_POST["update"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $data = [];
        $data["itemName"] = $_POST["itemName"];
        $data["itemCategory"] = $_POST["category"];
        $data["itemDescription"] = $_POST["itemdes"];

        // Check for empty fields
        if (empty($data["itemName"]) || empty($data["itemCategory"]) || empty($data["itemDescription"])) {
            throw new Exception("Please fill in all the required fields.");
        }

        if (isset($_FILES["file"]) && $_FILES["file"]["error"] == UPLOAD_ERR_OK) {
            $uploadDir = './uploads/';
            $fileName = basename($_FILES["file"]["name"]);
            $targetFilePath = $uploadDir . $fileName;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
                $data["itemImage"] = $targetFilePath; 
            } else {
                throw new Exception("Failed to upload file.");
            }
        }

        $up = $itemHandler->update('items', $data, $_SESSION["editable-itemID"]);
        if ($up) {
            header("Location: items-shared");
            exit;
        } else {
            echo "Failed to update item.";
        }
    } catch (Exception $err) {
        echo "ERROR-updating: " . $err->getMessage();
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
    <script>
        // Client-side validation for empty fields
        function validateForm() {
            var isValid = true;
            var errorMessages = document.getElementsByClassName("error");
            for (var i = 0; i < errorMessages.length; i++) {
                errorMessages[i].innerHTML = ""; // Reset errors
            }

            var itemName = document.getElementById("itemname");
            var category = document.getElementById("category");
            var itemDes = document.getElementById("itemdes");

            if (itemName.value.trim() === "") {
                document.getElementById("itemNameError").innerHTML = "Item name is required!";
                isValid = false;
            }

            if (category.value === "") {
                document.getElementById("categoryError").innerHTML = "Category is required!";
                isValid = false;
            }

            if (itemDes.value.trim() === "") {
                document.getElementById("itemDesError").innerHTML = "Description is required!";
                isValid = false;
            }

            return isValid;
        }
    </script>
</head>
<body>
    <div class="update-form-container">
        

        <form method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
            <div class="wrap-input">

                <div class="input-group">
                    <label for="itemname">New item name</label>
                    <input type="text" name="itemName" id="itemname">
                    <span class="error" id="itemNameError"></span>
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
                    <span class="error" id="categoryError"></span>
                </div>
                
                <div class="input-group">
                    <label for="itemdes">New item description</label>
                    <input type="text" name="itemdes" id="itemdes">
                    <span class="error" id="itemDesError"></span>
                </div>
                <div class="input-group">
                    <label for="file">Choose file to upload</label>
                    <input type="file" name="file" id="file">
                </div>
                <div class="input-group">
                    <button type="submit" name="update">Update</button>
                    <a href="/items-shared">Cancel</a>
                </div>

            </div>
        </form>

    </div>
</body>
</html>
