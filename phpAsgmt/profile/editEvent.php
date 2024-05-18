<?php 
   session_start();
   require_once '../database/database.php';
function categoryArray(){
    return array(
        "sp" => "Speech",
        "cn" => "Concert",
        "sh" => "Show",
        "ot" => "Other"
    );
}
function validateDesc($desc, $shortLong){
    if($desc == NULL){
        return "Please input the event <b>$shortLong</b> Description.";
    }
    if($shortLong == "Short"){
        if(strlen($desc)<=3){
            return "The event <b>Short Description </b>is too short, minimum 4 characters.";
        }
        else if(strlen($desc) >= 100){
            return "The event <b>Short Description </b>is too long, maximum 99 characters.";
        }
    }
    else if($shortLong == "Long"){
        if(strlen($desc)<=10){
            return "The event <b>Long Description </b>is too short, minimum 10 characters.";
        }
        else if(strlen($desc) >= 500){
            return "The event <b>Long Description </b>is too long, maximum 499 characters.";
        }
    }
}

function validateTime($timeStart, $timeEnd){
    if($timeEnd < $timeStart){
        return "The time end cannot <b>before</b> the time start.";
    }
}

function validateImage($imageFile){
    $validExtensions = ['jpg', 'jpeg', 'png'];

    // Formula for 100MB size is reference
    $maxSize = 100 * 1024 * 1024; // 100 MB
    if($imageFile["error"] === 4){
        return "Please input the <b>event image</b>.";
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

function validateCategory($categoryID){
    if ($categoryID != NULL){
        return (isset(categoryArray()["$categoryID"]))?NULL:"Invalid category id HACKERRRR";
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
        <title>Admin - Edit Event</title>
        <link href="../events/eventCss/createEvent.css" rel="stylesheet" type="text/css"/>
        <style>
            
            h1{
                margin-top:50px;
                padding:20px;
                padding-top:150px;
                padding-bottom:40px;
                box-shadow: inset 0 0 8px 2px rgba(0, 0, 0, 0.5);
                text-align: center;
            }
            div.shadow{
                margin-top:50px;
                padding-bottom:50px;
            }
            .error, .info
{
    padding: 5px;
    margin: 5px;
    font-size: 0.9em;
    list-style-position: inside;
}

.error
{
    border: 2px solid #FBC2C4;
    background-color: #FBE3E4;
    color: #8A1F11;
}

.info
{
    border: 2px solid #92CAE4;
    background-color: #D5EDF8;
    color: #205791;
}
        </style>
    </head>
    <body>
        <?php include '../general/header.php'; ?>
        <h1>Edit Event</h1>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "GET"){
            isset($_GET["eventID"])?"":header('location:myEvent.php'); 
            $eventID = $_GET["eventID"];
            
            $sql ="SELECT * FROM Event WHERE EventID = $eventID";
            $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            // Execute the query
            $result = $conn->query($sql);
            // Check if there are any rows returned
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                $eventName = $row["EventName"];
                $shortDesc = $row["ShortDesc"];
                $longDesc = $row["LongDesc"];
                $timeStart = $row["TimeStart"];
                $timeEnd = $row["TimeEnd"];
                $imgPath = $row["Image"];
                $vipPrice = $row["VipPrice"];
                $normalPrice = $row["NormalPrice"];
                $category = $row["Category"];
                $ownerID = $row["OwnerID"];
                }
                //sent them back if other people 
                //use the url to try to edit other poeple event
                if($ownerID != $_SESSION["logged"]["id"]){
                    header("location:profile.php");
                }
            }
            else{
                    printf("<p class='error'>
                            Sorry, something went wrong.
                            </p>");
            }
                // Close connection
            $conn->close();
                
        }
        else if (isset($_POST["btnUpdate"])) {
            //POST

            $eventID = $_GET["eventID"];
            $eventName = trim($_POST["txtEventName"]);
            $shortDesc = trim($_POST["txtShortDesc"]); 
            $longDesc = trim($_POST["txtLongDesc"]);
            $timeStart =  $_POST["timeStart"];
            $timeEnd = $_POST["timeEnd"];
            $image = ($_FILES["eventImage"]["error"]===4)?NULL:$_FILES["eventImage"];
            $imgPath = $_POST["imgPath"];
            $vipPrice = $_POST["vipPrice"];
            $normalPrice = $_POST["normalPrice"];
            $category = ($_POST["ddlCategory"]==NULL)?"ot":$_POST["ddlCategory"];
            
            //check for error
            $error["name"] = ($eventName == NULL)?"Please input <b>event name</b>":NULL;
            $error["shortdesc"] = validateDesc($shortDesc, "Short");
            $error["longdesc"] = validateDesc($longDesc, "Long");
            $error["timestart"] = ($timeStart == NULL)?"Please input the <b>time start</b>.":NULL;
            $error["timeend"] = ($timeEnd == NULL)?"Please input the <b>time end</b>.":NULL;
            $error["time"] = validateTime($timeStart, $timeEnd);
            $error["vip"] = ($vipPrice == NULL)?"Please input the <b>VIP</b> seat price.":NULL;
            $error["normal"] = ($normalPrice == NULL)?"Please input the <b>Normal</b> seat price.":NULL;
            $error["category"] = validateCategory($category);
            
            if($image!=NULL){
                $error["image"] = validateImage($image);
            }
            
            $error = array_filter($error);
            if(empty($error)){
                if($image!=NULL){
                    
                   if(!unlink($imgPath)){
                       echo "Image not found...";
                   }               
                   $folderPath = "../images/event/";
                   $breakedImg = explode('.', $image["name"]);
                   $extension = strtolower(end($breakedImg)); 
                   $imgPath = $folderPath. uniqid() . '_eventImg.' . $extension;
                   if (!move_uploaded_file($image['tmp_name'], $imgPath)) {
                       echo"Something went wrong !";
                   }         
                }
            
                $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                $sql = "UPDATE Event SET EventName = ?, ShortDesc = ?,
                        LongDesc = ?, TimeStart = ?, TimeEnd = ?,
                        Image = ?, VipPrice = ?, NormalPrice = ?, Category = ?
                        WHERE EventID = ?";

                //NOTE: $con->query($sql); << This code is for sql without "?"
                //NOTE: $con->prepare($sql); << This code is for sql with "?"
                $stmt = $con->prepare($sql);

                $stmt ->bind_param('ssssssddsi', 
                                            $eventName,
                                            $shortDesc, $longDesc,
                                            $timeStart,$timeEnd,
                                            $imgPath, 
                                            $vipPrice, $normalPrice,
                                            $category,
                                            $eventID);

                //Step 3: Execute sql
                $stmt->execute();

                if($stmt->affected_rows > 0){
                    //record updated
                    printf("
                            <p class='info'>You successfully updated Event <b>%s</b>.</p>"
                            ,$eventName);    
                }
                $stmt->close();
                $con->close();

               }
            else{
                    echo "<ul class='error'>";
                    foreach($error as $value){
                        echo "<li>$value</li>";
                    }
                    echo "</ul>";
            }               
        }
        else{
            header("event.php");
        }
        
        ?>
        <div class="shadow">
        <form action="" method="POST" enctype="multipart/form-data">
            <table>
            <tr>
                <td>Event Name : </td>
                <td><input type="text" name="txtEventName" value="<?php echo isset($eventName)?$eventName:"";?>" /></td>
            </tr>
            <tr>
                <td>Event Short Description : </td>
                <td><input type="text" name="txtShortDesc" value="<?php echo isset($shortDesc)?$shortDesc:"";?>" /></td>
            </tr>
            <tr>
                <td>Event Long Description : </td>
                <td><input type="text" name="txtLongDesc" value="<?php echo isset($longDesc)?$longDesc:"";?>" /></td>
            </tr>
            <tr>
                <td>Time Start : </td>
                <td><input type="time" name="timeStart" value="<?php echo isset($timeStart)? $timeStart:"";?>" /></td>
            </tr>
              <tr>
                <td>Time End : </td>
                <td><input type="time" name="timeEnd" value="<?php echo isset($timeEnd)? $timeEnd:"";?>" /></td>
            </tr>
            <tr>
                <td></td>
                <td><label for="eventImage" style="margin-left:20px;">
                        <img id="showEventImg" src="<?php echo $imgPath;?>" 
                             alt="Event Image" for="eventImage" width="100px">
                    <input type="hidden" name="imgPath" value="<?php echo "$imgPath"?>"> 
                           </label>
                </td>
            </tr>
            <tr>
                <td>Event Image : </td>
                <td><input type="file" name="eventImage" id="eventImage" value="" accept=".jpg , .jpeg, .png" onchange="previewImage(event)"/></td>
            </tr>
            <tr>
                <td>VIP Seat Price : </td>
                <td><input type="number" name="vipPrice" min="0" step="0.01" value="<?php echo isset($vipPrice)?  $vipPrice:"";?>" /></td>
            </tr>
            <tr>
                <td>Normal Seat Price : </td>
                <td><input type="number" name="normalPrice"  min="0" step="0.01" value="<?php echo isset($normalPrice)? $normalPrice:"";?>" /></td>
            </tr>
            <tr>
                <td>Category :</td>
                <td>
                    <select name="ddlCategory">
                        <option></option>
                        <?php
                        //--- reference from php practical insert-student program ---
                           foreach(categoryArray() as $key => $value){
                               printf("<option value='%s' %s>%s</option>", $key, (isset($category))?"selected":"",$value);
                            }
                        ?>
                    </select>
                </td>
            </tr>
            </table>
            <input type="submit" value="Update" name="btnUpdate" />
            <input type="button" value="Cancel" name="btnCancel" onclick="location='myEvent.php'" />
        </form>
        </div>
        <?php include '../general/Footer.php'; ?>
        <script>
            //function to show message when the input file is inputed
            function previewImage(event) {
                var input = event.target;
                var reader = new FileReader();

                reader.onload = function(){
                  var imagePreview = document.getElementById('showEventImg');
                  imagePreview.src = reader.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        </script>
    </body>
</html>
