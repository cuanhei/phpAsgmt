<?php
//to do list
//all validation
//remain image when update threr
//update image path not working

function checkHallIndex($hallIndex){
    if($hallIndex == NULL){
        return "Something went wrong...";
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
    </head>
    <body>
        <?php require_once '../database/database.php'; ?>
        <h2>Update Hall</h2>
        <?php 
        //--- reference php practical updateStudent.php
            if($_SERVER["REQUEST_METHOD"] == "GET"){
            //Get Method
            //Show the detail in form to edit
            if(isset($_GET["hallIndex"])){
                $hallIndex = $_GET['hallIndex'];
            }else{
                header("location:readHall.php");
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
                   $pricePerHour = $row->Price;
            }
            else{
                //record no found
                echo"<div>Database Error! Please try again [<a href='readHall.php'>Back</a>]</div>";
            }
            //for security purpose
            $con->close();
            $result->free();
        }
            else{
                //--- POST method ---
                //Update action
                
                //check if someone use POST for wrong url
                isset($_POST["hallIndex"])?"":header("location:readHall.php");

                $hallIndex = $_POST["hallIndex"];
                $hallDesc = trim($_POST["txtHallDesc"]);
                $image = $_FILES["hallImage"];
                $numOfVip = $_POST["numVipSeat"];
                $numOfNormal = $_POST["numNormalSeat"];
                $availability = $_POST["rbAvailable"];
                $pricePerHour = $_POST["pricePerHour"];

                //check for error
                $error["hallIndex"] = checkHallIndex($hallIndex);
                
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
                    $stmt ->bind_param('ssiiidi', $hallDesc,$imagePath,$numOfVip,$numOfNormal,$availability,$pricePerHour,$hallIndex);

                    //Step 3: Execute sql
                    $stmt->execute();

                    if($stmt->affected_rows > 0){
                        //record updated
                        printf("
                                <div class='info'>
                                    Hall <b>%d</b> has been updated. [<a href='readHall.php'>Back</a>]
                                </div>"
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
        

        <form action="" method="POST" enctype="multipart/form-data" id="updateHall">
            <table>
              <tr>
                  <td>
                      <h2><?php echo($hallIndex !=NULL)? "Hall $hallIndex":"Error !"?></h2>
                      <input type="hidden" name="hallIndex" value="<?php echo isset($hallIndex)?$hallIndex:"";?>"/>
                  </td>
              </tr>
              <tr>
                <td><label for="txtHallDesc">Hall Description:</label></td>
                <td><textarea name="txtHallDesc" id="txtHallDesc" rows="4" cols="20"><?php echo (isset($hallDesc)) ? $hallDesc : ""; ?></textarea></td>
              </tr>
              <tr>
                  <td><label for="imageInput">Hall Image:<img src="<?php echo "$imagePath"?>"></label></td>
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
              <button type="submit" name="btnInsert">Update</button>
              <button type="button" name="btnCancel" onclick="location='readHall.php'">Cancel</button>
            </div>
        </form>
        
    </body>
</html>
