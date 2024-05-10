<?php
/// to do
// Image validation
// make image remain 
// rename the image when save

require_once '../database/database.php';

function checkDescription($desc){
    if($desc == null){
        return "Please input <b>Hall Description</b>.";
    }else if(strlen($desc)>30){
        return "The Description for the Hall is too <b>LONG</b>.";
    }else if(strlen($desc)<=3){
        return "The Description for the Hall is too <b>SHORT</b>.";
    }
}

function checkSeat($numOfSeat, $type){
    if($numOfSeat == NULL && $type == "VIP"){
        return "Please input the number of <b>VIP seat</b>.";
    }
    else if($numOfSeat == NULL && $type != "VIP"){
        return "Please input the number of <b>Normal seat</b>.";
    }
    if($type == "Normal"){
        if($numOfSeat == 0){
            return "The minimum number of normal seat should not be <b>Zero</b>";
        }
    }
    if($numOfSeat <0){
        return "The seat cannot be <b>negative</b>.";
    }
}

function checkPrice($price){
    if($price == NULL){
        return "Please input the <b>price</b>.";
    }
    if($price <= 0){
        return "The price cannot be less than <b>Zero</b>.";
    }
}

function checkFileImage($image){
    if($image["error"]===4){
        return "Please input the <b>image</b> file.";
    }
}
?>
<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Admin - Add Hall</title>
    </head>
    <body>        
        <?php 
            if(isset($_POST["btnInsert"])){
                 // if the submit pressed
                 $hallDesc = trim($_POST["txtHallDesc"]);
                 $hallImage = $_FILES["hallImage"];
                 $numOfVip = trim($_POST["numVipSeat"]);
                 $numOfNormal = trim($_POST["numNormalSeat"]);
                 $availability = $_POST["rbAvailable"];
                 $pricePerHour = $_POST["pricePerHour"];
                   
                 $error["description"] = checkDescription($hallDesc);
                 $error["image"] = checkFileImage($hallImage);
                 $error["vip"] = checkSeat($numOfVip, "VIP");
                 $error["normal"] = checkSeat($numOfNormal, "Normal");
                 $error["price"] = checkPrice($pricePerHour);
                 
                 //--- Reference PHP Practical ---
                 //delete the array if no value
                 $error = array_filter($error);
                 if(empty($error)){
                    //Step 1: create connection between system and DB
                    $con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);

                    //Step 2: sql statement
                    $sql = "INSERT INTO hall 
                           (HallDesc, ImagePath, VipSeat, NormalSeat, Availability, Price) 
                            VALUES (?,?,?,?,?,?)";

                    //Step 2.1 run sql
                    //NOTE: $con->query($sql); << This code is for sql without "?"
                    //NOTE: $con->prepare($sql); << This code is for sql with "?"
                    $stmt = $con->prepare($sql);

                    //Step 2.2 supply data into the "?" parameter in the sql
                    //NOTE: s- string, i- integer, d-double, b-blob (IMG)
                    $folderPath = "../Event/imgHall/";
                    $fileName = $hallImage["name"];
                    $imgPath = $folderPath . $fileName;
                    
                    $stmt ->bind_param('ssiiid', 
                            $hallDesc,  
                            $imgPath, 
                            $numOfVip, 
                            $numOfNormal, 
                            $availability, 
                            $pricePerHour);
                    
                    // Move the uploaded file to the target directory
                    if (!move_uploaded_file($_FILES["hallImage"]["tmp_name"], $imgPath)) {
                        echo "Sorry, there was an error uploading your file.";
                    }
    
                    //Step 3: Execute sql
                    $stmt->execute();

                    if($stmt->affected_rows > 0){
                        //record inserted
                        echo "<script>alert('You successfully added a new hall.');</script>";
                        //header("Location:hall.php");
                    }
                    else{
                        //record unable to insert
                        echo "<div class='error'>Unable to add new Hall. Please try again. [<a href='hall.php'>Back</a>]</div>";
                    }

                    $stmt->close();
                    $con->close();                  
                    
            }else{
                     echo "<p>Failed to add new hall</p>";
                     echo "<ul class='error'>";
                     foreach($error as $value){
                         echo "<li>$value</li>";
                     }
                     echo "</ul>";
                 }
             }
             
        ?>
        
        <form action="" method="POST" enctype="multipart/form-data" id="addHall">
           <h2>Hall Form</h2>
            <table>
              <tr>
                <td><label for="txtHallDesc">Hall Description:</label></td>
                <td><textarea name="txtHallDesc" id="txtHallDesc" rows="4" cols="20"><?php echo (isset($hallDesc)) ? $hallDesc : ""; ?></textarea></td>
              </tr>
              <tr>
                <td><label for="imageInput">Hall Image:</label></td>
                <td> <input type="file" name="hallImage" accept=".jpg, .png, .jpeg" id="imageInput"></td>
              </tr>
              <tr>
                <td><label for="numVipSeat">Number of VIP seat:</label></td>
                <td><input type="number" name="numVipSeat" id="numVipSeat" min="0" step="1" 
                           value="<?php echo (isset($numOfVip)) ? $numOfVip : ""; ?>"></td>
              </tr>
              <tr>
                <td><label for="numNormalSeat">Number of Normal seat:</label></td>
                <td><input type="number" name="numNormalSeat" id="numNormalSeat" min="0" step="1"
                            value="<?php echo (isset($numOfNormal)) ? $numOfNormal : ""; ?>"></td>
              </tr>
              <tr>
                <td><label>Availability:</label></td>
                <td>
                  <input type="radio" name="rbAvailable" id="rbavailable" value="1" checked><label for="rbavailable">Available</label>
                  <input type="radio" name="rbAvailable" id="rbunavailable" value="0"><label for="rbunavailable">Unavailable</label>
                </td>
              </tr>
              <tr>
                <td><label for="pricePerHour">Price per hour (RM):</label></td>
                <td><input type="number" name="pricePerHour" id="pricePerHour" value="<?php echo (isset($pricePerHour)) ? $pricePerHour : ""; ?>"></td>
              </tr>
            </table>
            <div class="btn-group">
              <button type="submit" name="btnInsert">Insert</button>
              <button type="button" name="btnCancel" onclick="location='readhall.php'">Cancel</button>
            </div>
        </form>
    </body>
</html>
