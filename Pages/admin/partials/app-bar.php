<?php
    $admin = new Admin();
    $initialAdmin = $admin->read($_SESSION["initial_adminID"]);

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["profile"])) {
        if ($_FILES["profile"]["error"] == 0) {
            $uploadDir = "uploads/";
            $fileName = basename($_FILES["profile"]["name"]);
            $filePath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES["profile"]["tmp_name"], $filePath)) {
                $admin->update($initialAdmin["adminID"], ["profile" => $filePath]);
                header("Location: /admin-dashboard");
            }
        }
    }
?>


<div class="header">
    <div class="title">Green Things Dashboard</div>
    <div class="user-info">
        <form method="post" id="upload-form" enctype="multipart/form-data">
            <label for="file-upload" style="cursor: pointer; border: none; background: transparent">
                <img src="<?= $initialAdmin["profile"] == null ? 'https://via.placeholder.com/40' : $initialAdmin["profile"] ?>" alt="User Avatar">
            </label>
            <input type="file" id="file-upload" name="profile" onchange="submitForm()">
        </form>
        <div>
            <span>Welcome, <?= $initialAdmin["name"] ?></span>
            <br>
            <small><?= $initialAdmin["userType"] ?></small>
        </div>
    </div>

    <script>
        function submitForm() {
            const fileInput = document.getElementById('file-upload');
            if (fileInput.files.length > 0) {
                // Trigger form submission
                document.getElementById('upload-form').submit();
            }
        }
    </script>
</div>
