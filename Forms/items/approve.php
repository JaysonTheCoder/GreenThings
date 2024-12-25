
<?php
    session_start();
    include "./Database/ItemHandler.php";
    include "./Database/config.ini.php";

    $itemHandler = new ItemHandler(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    // echo "itemID: ".$_SESSION["approved-itemID"];
    // echo "requestID: ".$_SESSION["approved-requestID"];

    if(isset($_POST["submit-button"])) {
        $data = [
            "requestID" => $_SESSION["approved-requestID"],
            "message" => $_POST["message"],
            "dateToReturn" => $_POST["return-date"],
            "itemID" => $_SESSION["approved-itemID"],
            "userID" => $_SESSION["user_id"],
        ];
        $itemsAndUserData = $itemHandler->create('approveditems', $data);
        $itemHandler->update("items", ["expired" => 1], $_SESSION["approved-itemID"]);

        header("Location: /requests");
        exit();
    }
    

?>






<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Approval Form</title>
  <link rel="stylesheet" href="../../styles/approval.css">
</head>
<body>
  <div class="form-container">
    <h2>Approval Form</h2>
    <form method="post">
      <div class="form-group">
        <label for="message">Message</label>
        <textarea id="message" name="message" placeholder="Enter your message" required></textarea>
      </div>
      <div class="form-group">
        <label for="return-date">Date of Return</label>
        <input type="date" id="return-date" name="return-date">
      </div>
      <!-- <div class="form-group">
        <input type="checkbox" id="permanent" name="permanent" onchange="toggleDateInput()">
        <label for="permanent" class="permanent-label">Permanent Item</label>
      </div> -->
      <div class="button-group">
        <button type="submit" name="submit-button" class="submit-btn">Submit</button>
        <button type="button" class="cancel-btn"><a href="/requests">Cancel</a></button>
      </div>

    </form>
  </div>

  <script>
    function toggleDateInput() {
      const permanentCheckbox = document.getElementById('permanent');
      const returnDateInput = document.getElementById('return-date');

      if (permanentCheckbox.checked) {
        returnDateInput.disabled = true;
        returnDateInput.value = ''; // Clear the date if the checkbox is checked
      } else {
        returnDateInput.disabled = false;
      }
    }
  </script>
</body>
</html>
