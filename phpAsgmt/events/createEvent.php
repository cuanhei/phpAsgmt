<?php
//--- LIM CUAN HEI ---
//todo list
//owner id get from session
require_once '../database/database.php';
session_start();

$step = 1;

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

function validateDate($dateStart, $dateEnd){
    if($dateEnd < $dateStart){
        return "The date end cannot <b>before</b> the date start.";
    }
}

function validateTime($timeStart, $timeEnd){
    if($timeEnd < $timeStart){
        return "The time end cannot <b>before</b> the time start.";
    }
}
function getAllHall(){
    $conn = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);

    // Check connection
    if ($conn->connect_error) {
       die("Connection error: " . $conn->connect_error);
    }
    // SQL query to retrieve all data from a table
    $sql = "SELECT * FROM hall WHERE Availability = 1"; 
    // Execute the query
    $result = $conn->query($sql);
    $rows = array();
    
     // Check if any rows were returned
    if ($result->num_rows > 0) {
        // Fetch associative array of all rows
        while($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }        
    }
    
    $conn->close();
    return $rows;
}

function isDateInRange($startDate, $endDate, $dateToCheck) {
    return ($dateToCheck >= $startDate && $dateToCheck <= $endDate);
}
function getValidHalls(){
    $allHalls = getAllHall();
    $invalidHallID = array();
    $conn = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
     
    // Check connection
    if ($conn->connect_error) {
       die("Connection error: " . $conn->connect_error);
    }
    // SQL query to retrieve all data from a table
    $sql = "SELECT * FROM event"; 
    // Execute the query
    $result = $conn->query($sql);
    $rows = array();
    
     // Check if any rows were returned
    if ($result->num_rows > 0) {
        // Fetch associative array of all rows
        while($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
    }
    
    $conn->close();
    for($i = 0; $i < count($rows); $i++){
        if(isDateInRange($_SESSION["createEvent"]["dateStart"], $_SESSION["createEvent"]["dateEnd"], $rows[$i]["DateStart"]) ||
            isDateInRange($_SESSION["createEvent"]["dateStart"], $_SESSION["createEvent"]["dateEnd"], $rows[$i]["DateEnd"]))
        {
            $invalidHallID[] = $rows[$i]["HallID"];
        }
    }
    
    for($i = 0; $i< count($allHalls); $i++){
        for($x = 0; $x <count($invalidHallID); $x++){
            if($allHalls[$i]["HallID"] == $invalidHallID[$x]){
                unset($allHalls[$i]);
                break;
            }
        }
    }
    
    return $allHalls;
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

function calculateBookingDay($dateStart,$dateEnd){
    $date1 = new DateTime($dateStart);
    $date2 = new DateTime($dateEnd);

    // Calculate the difference
    $interval = $date1->diff($date2);

    // Get the difference in days
    return $daysDifference = $interval->days +1;
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
        <title>Events4you - Create Events</title>
        <link href="../general/globalcss.css" rel="stylesheet" type="text/css"/>
        <link href="eventCss/createEvent.css" rel="stylesheet" type="text/css"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <style>
            .selectHallDiv img{
                margin-top:50px;
                border-radius: 5px;
                width:50%;
                height:200px;
            }
        </style>
    </head>
    <body
        <?php include'../general/header.php';?>
        <div class="hero whitetext">
            <div>
                
            <h1>Create Event</h1>
            <h3>Thank you for choosing us to host your events.</h3>
            <p>We hope we can give you a good experience in this cooperate.</p>
            <p>We provide the best hall for you to host your event!</p>
            <p>Thanks again for choosing us.</p>
            </div>
        </div>
         <?php 

         if(isset($_POST["btnStep1"])){
             
                 unset($_SESSION['createEvent']);
                 $eventDateStart = $_POST["dateStart"];
                 $eventDateEnd = $_POST["dateEnd"];

                 $error["datestart"] = ($eventDateStart == NULL)?"Please input <b>date start</b>.":NULL;
                 $error["dateend"] = ($eventDateEnd == NULL)?"Please input <b>date end</b>.":NULL;
                 $error["date"] = validateDate($eventDateStart, $eventDateEnd);

                 //--- Reference PHP Practical ---
                 //delete the array if no value
                 $error = array_filter($error);
                 if(empty($error)){
                     $_SESSION['createEvent']["dateStart"] = $eventDateStart;
                     $_SESSION['createEvent']["dateEnd"] = $eventDateEnd;
                     $step = 2;
                 }
                 else{
                         echo "<ul class='error'>";
                         foreach($error as $value){
                             echo "<li>$value</li>";
                         }
                         echo "</ul>";
                         $step = 1;
                 }
            }
            
            else if (isset($_POST["btnStep2"])){
                
                $hallID = $_POST["selectedHall"];
                
                if(!empty($hallID)){
                    $_SESSION['createEvent']["hallID"] = $hallID;
                    $_SESSION['createEvent']["createdImg"] = 0; //to let the step 3 can create image
                    $step = 3;
                }else{
                    echo"<p class='error'>Please select the <b>Hall</b>.</p>";
                    $step = 2;
                }
                
            }
            
            else if (isset($_POST["btnStep3"])){
                 $eventName = trim($_POST["txtEventName"]);
                 $eventShortDesc = trim($_POST["txtShortDesc"]); 
                 $eventLongDesc = trim($_POST["txtLongDesc"]);
                 $eventTimeStart = $_POST["timeStart"];
                 $eventTimeEnd = $_POST["timeEnd"];
                 $eventImage = $_FILES["eventImage"];
                 $eventVipPrice = $_POST["vipPrice"];
                 $eventNormalPrice = $_POST["normalPrice"];
                 $eventCategory = ($_POST["ddlCategory"]==NULL)?"ot":$_POST["ddlCategory"];
                 
                 $error["name"] = ($eventName == NULL)?"Please input <b>event name</b>":NULL;
                 $error["shortdesc"] = validateDesc($eventShortDesc, "Short");
                 $error["longdesc"] = validateDesc($eventLongDesc, "Long");
                 $error["timestart"] = ($eventTimeStart == NULL)?"Please input the <b>time start</b>.":NULL;
                 $error["timeend"] = ($eventTimeEnd == NULL)?"Please input the <b>time end</b>.":NULL;
                 $error["time"] = validateTime($eventTimeStart, $eventTimeEnd);
                 $error["image"] = validateImage($eventImage);
                 $error["vip"] = ($eventVipPrice == NULL)?"Please input the <b>VIP</b> seat price.":NULL;
                 $error["normal"] = ($eventNormalPrice == NULL)?"Please input the <b>Normal</b> seat price.":NULL;
                 $error["category"] = validateCategory($eventCategory);
                 
                 
                 $error = array_filter($error);
                 if(empty($error)){
                    $breakedImg = explode('.', $eventImage['name']);
                    $extension = strtolower(end($breakedImg)); 
                    //--- do this to prevent when user refresh it save again ---
                    if($_SESSION['createEvent']["createdImg"]!=1){
                        $eventImgPath = '../images/event/'. uniqid() . '_eventImg.' . $extension;
                        if (!move_uploaded_file($eventImage['tmp_name'], $eventImgPath)) {
                            echo"Something went wrong !";
                        }
                    } else{
                        $eventImgPath = $_SESSION['createEvent']["eventImgPath"];
                    }
                    $_SESSION['createEvent']["eventName"] = $eventName;
                    $_SESSION['createEvent']["eventShortDesc"] = $eventShortDesc;
                    $_SESSION['createEvent']["eventLongDesc"] = $eventLongDesc;
                    $_SESSION['createEvent']["eventTimeStart"] = $eventTimeStart;
                    $_SESSION['createEvent']["eventTimeEnd"] = $eventTimeEnd;
                    $_SESSION['createEvent']["eventImgPath"] = $eventImgPath;
                    $_SESSION['createEvent']["eventVipPrice"] = $eventVipPrice;
                    $_SESSION['createEvent']["eventNormalPrice"] = $eventNormalPrice;
                    $_SESSION['createEvent']["eventCategory"] = $eventCategory;                  
                                           
                    $step = 4;
                    
                    $_SESSION['createEvent']["createdImg"] = 1;
                    
                    //print_r($_SESSION['createEvent']);
                    
                    
                 }
                 else{
                         echo "<ul class='error'>";
                         foreach($error as $value){
                             echo "<li>$value</li>";
                         }
                         echo "</ul>";
                         $step = 3;
                 }               
            }
            else if (isset($_POST["btnStep4"])){
                //Step 1: create connection between system and DB
                $con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);

                //Step 2: sql statement
                $sql = "INSERT INTO Event 
                        (EventName, ShortDesc, LongDesc, DateStart, DateEnd, TimeStart, TimeEnd, 
                        Image, VipPrice, NormalPrice, Category, HallID, OwnerID) 
                        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";

                //Step 2.1 run sql
                //NOTE: $con->query($sql); << This code is for sql without "?
                //NOTE: $con->prepare($sql); << This code is for sql with "?
                $stmt = $con->prepare($sql);         
                $stmt ->bind_param('ssssssssddsii', 
                                    $_SESSION['createEvent']["eventName"] ,
                                    $_SESSION['createEvent']["eventShortDesc"], 
                                    $_SESSION['createEvent']["eventLongDesc"],
                                    $_SESSION['createEvent']["dateStart"],
                                    $_SESSION['createEvent']["dateEnd"] ,
                                    $_SESSION['createEvent']["eventTimeStart"],
                                    $_SESSION['createEvent']["eventTimeEnd"] ,
                                    $_SESSION['createEvent']["eventImgPath"],
                                    $_SESSION['createEvent']["eventVipPrice"] ,
                                    $_SESSION['createEvent']["eventNormalPrice"],
                                    $_SESSION['createEvent']["eventCategory"] ,
                                    $_SESSION['createEvent']["hallID"],
                                    $_SESSION['logged']['id']);
                    
                //Step 3: Execute sql
                $stmt->execute();

                if($stmt->affected_rows > 0){
                    //record inserted
                    echo "<script>alert('You successfully created a new event.');</script>";
                }
                else{
                    //record unable to insert
                    echo "<div class='error'>Unable to create new event. Please try again. [<a href='CreateEvent.php'>Back</a>]</div>";
                }

                $stmt->close();
                $con->close();      
                    $_SESSION["emailAPI"]["to"] = $_SESSION["logged"]["email"];
                    $_SESSION["emailAPI"]["subject"] = "You successfully created an Event in Events4you!";
                    $_SESSION["emailAPI"]["body"] = "<h1>Thank you for choosing EVENT4YOU to host your event.</h1>
                                                      <p>Your event ".$_SESSION['createEvent']["eventName"]."has been successfully created.</p>
                                                      <p>Start from <b>".$_SESSION['createEvent']["dateStart"]."</b> to <b>".$_SESSION['createEvent']["dateEnd"]."</b></p>";
                unset($_SESSION['createEvent']);
                include '../emailAPI.php';
            }
            else if (isset($_POST["btnCancel"])){
               //remove the saved image and then show back step3
               $step = 3;
                if(unlink($_SESSION['createEvent']["eventImgPath"])){
                    //i do this to let when submit step 3 it can save the image again
                    $_SESSION['createEvent']["createdImg"] = 0;
                }else{
                    echo "Image not found...";
                }               
            }
            
            
            echo "<div class='stepContainer'>";
            $percentage = ($step / 4) * 100;  // Calculate the percentage of the current step
            echo "<div class='progressBar'>";
            echo "<div class='progress' style='width: {$percentage}%;'></div>";
            echo "</div>";
            echo "</div>";
        ?>
        
        <div class="shadow <?php echo ($step != 1)?"hide":"show";?>"  id="step1" >
            <h1 style="padding:20px;">Please choose the <b>DATE</b>.</h1>
            <form action="" method="POST">
                <table>
                    <tr>
                        <td>Date Start : </td>
                        <td>
                            <input type="date" name="dateStart" id="dateStart" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>"
                                   value="<?php echo isset($eventDateStart)?$eventDateStart:"";?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>Date End : </td>
                        <td>
                            <input type="date" name="dateEnd" id="dateEnd" min="<?php echo date('Y-m-d',strtotime('+1 day')); ?>"
                                   value="<?php echo isset($eventDateEnd)?$eventDateEnd:"";?>"/>
                        </td>
                    </tr>
                </table>
                <input type="submit" value="Next" name="btnStep1" /><br>
                <input type="button" value="Back" name="btnBack" onclick="location='events.php'"/>
            </form>
        </div>
            
        <div class="shadow <?php echo ($step != 2)?"hide":"show";?>"  id="step2">
            <form action="" method="POST"> 
                <h1 style="padding:20px;">Select the <b>HALL</b> based on the amount of people for your event.</h1>
                <div>
                    <?php
                    $gotHall = 0;
                    $allHalls = getValidHalls();
                    for($i = 0; $i<count($allHalls);$i++){
                        if(isset($allHalls[$i])){
                            $gotHall = 1;
                        printf("<div class='shadow selectHallDiv' style='margin:auto;margin-top:5px;' id='%d'>
                                <img src='%s'>
                                <h3>%s</h4>
                                <h5>%s</h5>
                                <p>Number of VIP seat    : %d<p>
                                <p>Number of Normal seat : %d<p>
                                <p>Total amount people can hold : %d</p>
                                <p>Price Per Day : RM%.2f</p>
                                </div>",
                                $allHalls[$i]["HallID"],
                                $allHalls[$i]["ImagePath"],
                                "Hall ".$allHalls[$i]["HallID"],
                                $allHalls[$i]["HallDesc"],
                                $allHalls[$i]["VipSeat"],
                                $allHalls[$i]["NormalSeat"],
                                $allHalls[$i]["NormalSeat"] + $allHalls[$i]["VipSeat"],
                                $allHalls[$i]["Price"]
                        );}
                    }
                    echo ($gotHall==0)?"<p class='error' style='width:50%;margin:auto;margin-top:50px;margin-bottom:50px;'>Sorry, currently have no available hall between the date you choose. [Press the <b>BACK</b> button to choose another date]</p>":"";
                    ?>
                    <input type="hidden" name="selectedHall" id="selectedHall" />
                    <input type="submit" value="Next" name="btnStep2" /><br>
                    <input type="button" value="Back" id="btnBackStep1"/>
                </div>
            </form>
        </div>
        
        <div class="shadow <?php echo ($step != 3)?"hide":"show";?>"  id="step3"> 
            <h1 style="padding:20px;">Please insert the <b>DETAILS</b> of your event.</h1>
            <!-- The enctype="multipart/form-data" used for upload file -->
            <form action="" method="POST"  enctype="multipart/form-data">
                <table>
                    <tr>
                        <td>Event Name : </td>
                        <td><input type="text" name="txtEventName" value="<?php echo isset($eventName)?$eventName:"";?>" /></td>
                    </tr>
                    <tr>
                        <td>Event Short Description : </td>
                        <td><input type="text" name="txtShortDesc" value="<?php echo isset($eventShortDesc)?$eventShortDesc:"";?>" /></td>
                    </tr>
                    <tr>
                        <td>Event Long Description : </td>
                        <td><input type="text" name="txtLongDesc" value="<?php echo isset($eventLongDesc)?$eventLongDesc:"";?>" /></td>
                    </tr>
                    <tr>
                        <td>Time Start : </td>
                        <td><input type="time" name="timeStart" value="<?php echo isset($eventTimeStart)? $eventTimeStart:"";?>" /></td>
                    </tr>
                      <tr>
                        <td>Time End : </td>
                        <td><input type="time" name="timeEnd" value="<?php echo isset($eventTimeEnd)? $eventTimeEnd:"";?>" /></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><label for="eventImage" style="margin-left:20px;"><img id="showEventImg" src="../images/event/add.png" alt="Event Image" for="eventImage" width="100px"></label></td>
                    </tr>
                    <tr>
                        <td>Event Image : </td>
                        <td><input type="file" name="eventImage" id="eventImage" value="" accept=".jpg , .jpeg, .png" onchange="previewImage(event)"/></td>
                    </tr>
                    <tr>
                        <td>VIP Seat Price : </td>
                        <td><input type="number" name="vipPrice" min="0" step="0.01" value="<?php echo isset($eventVipPrice)?  $eventVipPrice:"";?>" /></td>
                    </tr>
                    <tr>
                        <td>Normal Seat Price : </td>
                        <td><input type="number" name="normalPrice"  min="0" step="0.01" value="<?php echo isset($eventNormalPrice)? $eventNormalPrice:"";?>" /></td>
                    </tr>
                    <tr>
                        <td>Category :</td>
                        <td>
                            <select name="ddlCategory">
                                <option></option>
                                <?php
                                //--- reference from php practical insert-student program ---
                                   foreach(categoryArray() as $key => $value){
                                       printf("<option value='%s' %s>%s</option>", $key, (isset($eventCategory))?"selected":"",$value);
                                    }
                                ?>
                            </select>
                        </td>
                    </tr>
                </table>
                <input type="submit" name="btnStep3" value="Create Event" /><br>
                <input type="button" value="Back" id="btnBackStep2"/>
            </form>
        </div>
        
        <div class="shadow <?php echo ($step != 4)?"hide":"show";?>"  id="step4">
            <form action="" method="POST">
                <table>
                    <tr>
                        <td rowspan='9'><img src="<?php echo $_SESSION["createEvent"]["eventImgPath"]?>" alt="event image"/></td>
                        <td><?php echo "<h2>".$_SESSION["createEvent"]["eventName"]."</h2>"?></td>
                    </tr>
                    <tr>
                        <td><?php echo "<h3>".$_SESSION["createEvent"]["eventShortDesc"]."</h3>"?></td>
                    </tr>
                    <tr>
                        <td><?php echo "<p>Date     :".$_SESSION["createEvent"]["dateStart"] ." - ". $_SESSION["createEvent"]["dateEnd"]."</p>"?></td>
                    </tr>
                    <tr>
                        <td><?php echo "<p>Time     :".$_SESSION["createEvent"]["eventTimeStart"] ." - ". $_SESSION["createEvent"]["eventTimeEnd"]."</p>"?></td>
                    </tr>
                    <tr>
                        <td><?php echo "<p>VIP      : RM".$_SESSION["createEvent"]["eventVipPrice"]."</p>"?></td>
                    </tr>
                    <tr>
                        <td><?php echo "<p>Normal   : RM".$_SESSION["createEvent"]["eventNormalPrice"]."</p>"?></td>
                    </tr>
                    <tr>
                        <td><?php echo "<p>Category : ". categoryArray()[$_SESSION["createEvent"]["eventCategory"]]. "</p>"?></td>
                    </tr>
                    <tr>
                        <td><?php echo "<p>" . $_SESSION["createEvent"]["eventLongDesc"] . "</p>"?></td>
                    </tr>
                    <tr>
                        <td>
                            <?php
                            $halls = getAllHall();
                            $hallPrice = 0;
                            for($i = 0; $i<count($halls);$i++){
                                if($halls[$i]["HallID"] == $_SESSION["createEvent"]["hallID"]){
                                    $hallPrice = $halls[$i]["Price"];
                                }
                            }
                            printf("
                                        <p>Amount to pay : <b>RM%.2f</b></p>
                                    ",
                           (calculateBookingDay($_SESSION["createEvent"]["dateStart"], $_SESSION["createEvent"]["dateEnd"]))*$hallPrice);
                            ?>
                        </td>
                    </tr>
                </table>
                <input type="submit" value="Create Event" name="btnStep4" /><br>
                <input type="button" value="Cancel" id="btnBackStep3" name="btnCancel">
            </form>
        </div>
        <?php include'../general/footer.php';?>
        <script>
            $(document).ready(function() {
                $('.selectHallDiv').click(function() {
                    // Remove 'selected' class from all divs
                    $('.selectHallDiv').removeClass('selected');
                    // Add 'selected' class to the clicked div
                    $(this).addClass('selected');
                    // Set the value of hidden input to the id of the clicked div
                    $('#selectedHall').val($(this).attr('id'));
                });
                $('#btnBackStep1').click(function(){
                    $('#step2').removeClass('show');
                    $('#step2').addClass('hide');
                    $('#step1').removeClass('hide');
                    $('#step1').addClass('show');
                    $('.progress').css('width','25%');
                    $('.error').addClass('hide');
                });
                $('#btnBackStep2').click(function(){
                    $('#step3').removeClass('show');
                    $('#step3').addClass('hide');
                    $('#step2').removeClass('hide');
                    $('#step2').addClass('show');
                    $('.progress').css('width','50%');
                    $('.error').addClass('hide');
                });
                $('#btnBackStep3').click(function(){
                    $('#step4').removeClass('show');
                    $('#step4').addClass('hide');
                    $('#step3').removeClass('hide');
                    $('#step3').addClass('show');   
                    $('.progress').css('width','75%');
                });
            });
            
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

<!--https://youtu.be/sj2UbYNCkBw?si=GUzLw8Ho6hbP4i0W    payment youtube reference-->