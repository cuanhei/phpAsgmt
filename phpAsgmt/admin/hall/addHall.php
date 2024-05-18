<?php
/// to do
// Image validation
// make image remain 
// rename the image when save
session_start();
//(isset($_SESSION["logged"]["role"]))?"":header("location:../../home/index.php");
require_once '../../database/database.php';

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

function validateImage($imageFile){
    $validExtensions = ['jpg', 'jpeg', 'png'];

    // Formula for 100MB size is reference
    $maxSize = 100 * 1024 * 1024; // 100 MB
    if($imageFile["error"] === 4){
        return "Please input the <b>hall image</b>.";
    }
    
    // Get the file extension
    $breakExtension = explode('.', $imageFile['name']);
    $imageFileExtension = end($breakExtension);
    if (!in_array($imageFileExtension, $validExtensions)){
        return "You can only upload images with extension <b>[.jpg  .jpeg  .png]</b>.";
    }
    else if($imageFile['size'] >= $maxSize){
        return "The image size is too big, the maximum size is only <b>100MB</b>";
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
        <link href="hallCss/addHallCss.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>      
        <?php include '../general/adminHeader.php'; ?>
        <h1>Add Hall</h1>
        <?php 
            if(isset($_POST["btnInsert"])){
                 // if the submit pressed
                 $hallDesc = trim($_POST["txtHallDesc"]);
                 $hallImage = $_FILES["hallImage"];
                 $numOfVip = trim($_POST["numVipSeat"]);
                 $numOfNormal = trim($_POST["numNormalSeat"]);
                 $availability = $_POST["rbAvailable"];
                 $pricePerDay = $_POST["pricePerDay"];
                   
                 $error["description"] = checkDescription($hallDesc);
                 $error["image"] = validateImage($hallImage);
                 $error["vip"] = checkSeat($numOfVip, "VIP");
                 $error["normal"] = checkSeat($numOfNormal, "Normal");
                 $error["price"] = checkPrice($pricePerDay);
                 
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
                    $folderPath = "../images/hall/";
                    $breakedImg = explode('.', $hallImage["name"]);
                    $extension = strtolower(end($breakedImg)); 
                    $imgPath = $folderPath. uniqid() . '_hallImg.' . $extension;
                    if (!move_uploaded_file($hallImage['tmp_name'], '../'.$imgPath)) {
                        echo"Something went wrong !";
                    }
                    
                    $stmt ->bind_param('ssiiid', 
                            $hallDesc,  
                            $imgPath, 
                            $numOfVip, 
                            $numOfNormal, 
                            $availability, 
                            $pricePerDay);
                       
                    //Step 3: Execute sql
                    $stmt->execute();

                    if($stmt->affected_rows > 0){
                        //record inserted
                        echo "<div class='info'>You <b>Successfully</b> added the Hall.</div>";
                    }
                    else{
                        //record unable to insert
                        echo "<div class='error'>Unable to add new Hall. Please try again. [<a href='hall.php'>Back</a>]</div>";
                    }

                    $stmt->close();
                    $con->close();                  
                    
            }else{
                     echo "<div class='error'>"; 
                     echo "<h3 >Error!</h3><br>";
                     echo "<ul>";
                     foreach($error as $value){
                         echo "<li>$value</li>";
                     }
                     echo "</ul>";
                     echo "</div>";
                 }
             }
             
        ?>       
        <div class="shadow" style="margin-top:50px;">
        <form action="" method="POST" enctype="multipart/form-data" id="addHallForm">
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
                <td style="display:flex;">
                  <input type="radio" name="rbAvailable" id="rbavailable" value="1" checked><label for="rbavailable" style="margin-right:20px; margin-top:3px;">Available</label>
                  <input type="radio" name="rbAvailable" id="rbunavailable" value="0"><label for="rbunavailable" style="margin-right:20px; margin-top:3px;">Unavailable</label>
                </td>
              </tr>
              <tr>
                <td><label for="pricePerDay" >Price Per Day (RM):</label></td>
                <td><input type="number" name="pricePerDay" id="pricePerDay" step="0.1" value="<?php echo (isset($pricePerDay)) ? $pricePerDay : ""; ?>"></td>
              </tr>
            </table>
            <div class="btn-group">
              
              <button type="button" name="btnCancel" onclick="location='hall.php'">Cancel</button>
              <button type="submit" name="btnInsert">Add</button>
            </div>
        </form>
        </div>
        <?php include '../general/adminFooter.php'; ?>
    </body>
</html>
