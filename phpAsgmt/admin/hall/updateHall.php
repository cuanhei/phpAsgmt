<?php
/// to do
// Image validation
// make image remain 
// rename the image when save

require_once '../../database/database.php';
session_start();
//(isset($_SESSION["logged"]["role"]))?"":header("location:../../home/index.php");

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
        <title>Admin - Update Hall</title>
        <link href="hallCss/addHallCss.css" rel="stylesheet" type="text/css"/>
        <style>
            label {
    text-align : right;
}
        </style>
    </head>
    <body>      
        <?php include '../general/adminHeader.php'; ?>
        <h1>Update Hall</h1>
        <?php 
        //--- reference php practical updateStudent.php
            if($_SERVER["REQUEST_METHOD"] == "GET"){
            //Get Method
            //Show the detail in form to edit
            if(isset($_GET["hallIndex"])){
                $hallIndex = $_GET['hallIndex'];
            }else{
                header("location:hall.php");
            }
            
            //Step 1: Create connection between system and DB
            $con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
            //clean id by removing special character or symbol , prevent sql injection attack
            $hallIndex = $con->real_escape_string($hallIndex);
            
            //Step 2: run sql statement
            $sql ="SELECT * FROM Hall WHERE HallID = '$hallIndex'";
            $result = $con->query($sql);
            if ($row = $result->fetch_object()){
                //Record found
                   $hallIndex = $row->HallID; // PINK COLOR MUST BE SAME  AS WHAT IN THE TABLE COLUMN   
                   $hallDesc = $row->HallDesc;
                   $imagePath = $row-> ImagePath;
                   $numOfVip = $row->VipSeat;
                   $numOfNormal = $row->NormalSeat;
                   $availability = $row->Availability;
                   $pricePerDay = $row->Price;
            }
            else{
                //record no found
                echo"<div class='error'>Something went wrong! Please try again [<a href='hall.php'>Back</a>]</div>";
            }
            //for security purpose
            $con->close();
            $result->free();
        }
            else{
                
                //--- POST method ---
                //Update action
                ($_GET['hallIndex']==NULL)?header("location:hall.php"):"";
                $hallIndex = $_GET['hallIndex'];
                //check if someone use POST for wrong url
                isset($_POST["btnInsert"])?"":header("location:hall.php");
 
                $hallDesc = trim($_POST["txtHallDesc"]);
                $image = ($_FILES["hallImage"]["error"]===4)?NULL:$_FILES["hallImage"];
                $imagePath = $_POST["imgPath"];
                $numOfVip = $_POST["numVipSeat"];
                $numOfNormal = $_POST["numNormalSeat"];
                $availability = $_POST["rbAvailable"];
                $pricePerDay = $_POST["pricePerDay"];

                //check for error
                $error["description"] = checkDescription($hallDesc);
                $error["image"] = ($image)?validateImage($image):NULL;
                $error["vip"] = checkSeat($numOfVip, "VIP");
                $error["normal"] = checkSeat($numOfNormal, "Normal");
                $error["price"] = checkPrice($pricePerDay);
                
                //remove null value
                $error = array_filter($error);          
                if(empty($error)){
                    //No Error
                    $con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
                    
                    //prevent sql injection
                    $hallIndex = $con->real_escape_string($hallIndex);

                    //Step 2: sql statement
                    $sql = "UPDATE Hall SET HallDesc = ?, ImagePath = ?,
                            VipSeat = ?, NormalSeat = ?, Availability = ?,
                            Price = ? WHERE HallID = ?";

                    //Step 2.1 run sql
                    //NOTE: $con->query($sql); << This code is for sql without "?"
                    //NOTE: $con->prepare($sql); << This code is for sql with "?"
                    $stmt = $con->prepare($sql);

                    //Step 2.2 supply data into the "?" parameter in the sql
                    //NOTE: s- string, i- integer, d-double, b-blob (IMG)
                    if($image!=NULL){
                        if(!unlink("../".$imagePath)){
                            echo "Image not found...";
                        }               
                        $folderPath = "../images/hall/";
                        $breakedImg = explode('.', $image["name"]);
                        $extension = strtolower(end($breakedImg)); 
                        $imagePath = $folderPath. uniqid() . '_hallImg.' . $extension;
                        if (!move_uploaded_file($image['tmp_name'], '../'.$imagePath)) {
                            echo"Something went wrong !";
                        }
                                    
                    }
                    
                    $stmt ->bind_param('ssiiidi', $hallDesc,
                                                $imagePath,
                                                $numOfVip,$numOfNormal,
                                                $availability,$pricePerDay,
                                                $hallIndex);

                    //Step 3: Execute sql
                    $stmt->execute();

                    if($stmt->affected_rows > 0){
                        //record updated
                        printf("
                                <p class='info'>You successfully updated <b>Hall %d</b>.</p>"
                                ,$hallIndex);    
                    }
                    $stmt->close();
                    $con->close();
                }else{
                    //Have Error
                    echo "<ul class='error'>";
                    foreach ($error as $value) {
                        echo"<li>$value</li>";
                    }
                        echo "</ul>";
                }

            }
            
        ?>
        <div class="shadow" style="margin-top:50px;">
            
        <form action="" method="POST" enctype="multipart/form-data" id="addHallForm">
            <br><h2><?php echo "Hall $hallIndex";?></h2><br>
            <table>
              <tr>
                <td><label for="txtHallDesc">Hall Description:</label></td>
                <td><textarea name="txtHallDesc" id="txtHallDesc" rows="4" cols="20"><?php echo (isset($hallDesc)) ? $hallDesc : ""; ?></textarea></td>
              </tr>
              <tr>
                  <td></td>
                  <td>
                      <label for="imageInput" style="margin-left:20px;">
                          <img id="showHallImg" src="../<?php echo "$imagePath"?>" 
                               alt="Hall Image" for="hallImage" width="200px">
                          <input type="hidden" name="imgPath" value="<?php echo "$imagePath"?>" /></label>
                  </td>
              </tr>
              <tr>
                <td><label for="imageInput">Hall Image:</label></td>
                <td><input type="file" name="hallImage" accept=".jpg, .png, .jpeg" id="imageInput" 
                            onchange="previewImage(event)">
                </td>
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
                    <input type="radio" name="rbAvailable" id="rbavailable" value="1" <?php echo ($availability)?"checked":"";?>><label for="rbavailable" style="margin-right:20px; margin-top:3px;">Available</label>
                  <input type="radio" name="rbAvailable" id="rbunavailable" value="0" <?php echo (!$availability)?"checked":"";?>><label for="rbunavailable" style="margin-right:20px; margin-top:3px;">Unavailable</label>
                </td>
              </tr>
              <tr>
                <td><label for="pricePerHour" >Price Per Day (RM):</label></td>
                <td><input type="number" name="pricePerDay" id="pricePerDay" step="0.1" value="<?php echo (isset($pricePerDay)) ? $pricePerDay : ""; ?>"></td>
              </tr>
            </table>
            <div class="btn-group">
              
              <button type="button" name="btnCancel" onclick="location='hall.php'">Cancel</button>
              <button type="submit" name="btnInsert">Update</button>
            </div>
        </form>
        </div>
        <?php include '../general/adminFooter.php'; ?>
        
        <script>
         //function to show message when the input file is inputed
            function previewImage(event) {
                var input = event.target;
                var reader = new FileReader();

                reader.onload = function(){
                  var imagePreview = document.getElementById('showHallImg');
                  imagePreview.src = reader.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        </script>
    </body>
</html>
