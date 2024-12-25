<?php
    session_start();
    include "./Database/ItemHandler.php";
    include "./Database/config.ini.php";
    $requestedItem = $_SESSION["requested-item"];

    $itemHandler = new ItemHandler(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    $data = $itemHandler->readWithForeignKey('users', 'items', 'userID');
    // echo "<pre>";
    //     var_dump($requestedItem);
    // echo "</pre>";


    if(isset($_POST["submit-request"])) {
        $requestDetails = [
            'userID' => $_SESSION["user_id"],
            'itemID' => $requestedItem,
            'userRequested' => $_POST['name'],
            'lotNumber' => $_POST['lot'],
            'contact' => $_POST['contact']
        ];

        $itemHandler->create('requests', $requestDetails);

        header("Location: /home");
    }
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Request Form</title>
  <link rel="stylesheet" href="../../styles/request-form.css">
</head>
<body>
  <div class="form-container">
    
    <!-- Item Details Section -->
    <section class="item-details">
      <h2>Item Details</h2>
        <?php
            foreach($data as $items) :
                
                if($items["itemID"] == $requestedItem) :
        ?>
        <div class="item-image">
        
            <img src="<?= $items["itemImage"]?>" alt="">
            
        </div>

    

                <div class="item-info">
                    <p><strong>Name:</strong><?= $items["itemName"]?></p>
                    <p><strong>Description:</strong><?= $items["itemDescription"]?></p>
                    <p><strong>Category:</strong><?= $items["itemCategory"]?></p>
                </div>
    
    <?php
            endif;
        endforeach;
    
    ?>
    </section>

    <!-- Requester Details Section -->
    <section class="requester-details">
      <form method="post" enctype="multipart/form-data">
        <div class="form-group">
          <label for="name">Name:</label>
          <input type="text" id="name" name="name" placeholder="Your name" required>
        </div>
        <div class="form-group">
          <label for="contact">Contact Number:</label>
          <input type="tel" id="contact" name="contact" placeholder="Your contact number" required>
        </div>
        <div class="form-group">
          <label for="lot">Lot Number:</label>
          <input type="text" id="lot" name="lot" placeholder="Your lot number" required>
        </div>
        <div class="buttons-request" style="display: flex; align-items: center; gap: 1rem">
            
            <button type="submit" name="submit-request">Submit</button>
               
            <a href="/home">Cancel</a>
        </div>
      </form>
    </section>
  </div>
</body>
</html>
