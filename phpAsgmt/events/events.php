<?php
//--- LIM CUAN HEI ---
//todo list 
//add the href to the buy ticket form
include_once '../database/database.php';
session_start();
$filter= (empty($_GET))?"%":$_GET["filter"];
function categoryArray(){
    return array(
        "sp" => "Speech",
        "cn" => "Concert",
        "sh" => "Show",
        "ot" => "Other"
    );
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
        <title>Events4you - Events</title>
        <link href="../general/globalcss.css" rel="stylesheet" type="text/css"/>
        <link href="eventCss/eventCss.css" rel="stylesheet" type="text/css"/>
        <>
    </head>
    <body>
        <?php require '../general/header.php';?>
        <div class="hero whitetext">
            <div>
            <h1>Events</h1>
            <h3>Woah! There are so many events are hosted in Events4you.</h3>
            <p>You can buy the ticket for every event you saw in here as long as it has available seat.</p>
            <p>You can also rent the hall for creating your events too.</p>
            <p>Enjoy our service by the way, Take Care!</p>
            </div>
        </div>
        <div class="middleDiv">
            <div class="filterBar">
                <nav>
                    <ul>
            <?php
            //--- refer practical list-student.php ---
                printf("<li><a href='?filter=%s' id='%s'>All</a></li> ","%", ($filter == '%')?"filtering":"" );
                foreach(categoryArray() as $key => $value){
                   printf("<li><a href='?filter=%s' id='%s'>%s</a></li>", $key, ($filter == $key)?"filtering":"",$value );
                }
            ?>
                    </ul>
                </nav>
            </div>
            
            <?php 
            if(isset($_SESSION["logged"])){
                printf("<a class='createEvent' href='createEvent.php'>Create Event </a>");
            }else{
                echo "<button class='createEvent' onclick=\"alert('You need to login to an account to create event.');\">Create Event </button>" ;
            }
            ?>
        </div>
        <div class="lastDiv">
        <?php
            //--- reference php practical list-student.php --- 
            // Create connection
            $found = 0; // to show the no item found message
            $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            // Check connection
            if ($conn->connect_error) {
               die("Connection error: " . $conn->connect_error);
            }
            
            
            // SQL query to retrieve all data from a table
            $sql ="SELECT * FROM Event WHERE Category LIKE '$filter'";
  
            // Execute the query
            $result = $conn->query($sql);
            // Check if there are any rows returned
            $ownerID = isset($_SESSION["logged"]["id"])?$_SESSION["logged"]["id"]:NULL;
            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    if ($row["DateStart"] >= date('Y-m-d') &&
                            $row["OwnerID"] != $ownerID) //Show the event that not yet started
                    {
                        $found = 1;
                        printf("                            
                           <div class='events'>
                                <h2>%s</h2>
                                <img src='%s'>
                                <p>%s</p>
                                <button class='btnMore'>More</button>                 
                            </div>      
                            <div class='popUpDetails'>
                                <div class='popUpContent'>
                                    <div class='imageContainer'>
                                        <img src='%s' alt='Event Image'>
                                    </div>
                                    <div class='textContent'>
                                        <h2>%s</h2>
                                        <p>%s</p>
                                        <p>Date: %s - %s</p>
                                        <p>Time: %s - %s</p>
                                        <p>Location: Hall %d</p>
                                        <p>Category: %s</p>
                                        <p>Price for VIP: RM%.2f</p>
                                        <p>Price for Normal: RM%.2f</p>
                                        <p>%s</p>
                                        <div class='buttons'>
                                            <form action='../reservation/reservation.php' method='POST'>
                                                <input type='hidden' name='eventID' value='%d' />
                                                %s
                                            </form>
                                            <button class='blackBtn' id='back'>Back</button>
                                        </div>
                                    </div>
                                </div>
                            </div>"
                            ,
                            $row["EventName"], $row["Image"],
                            $row["ShortDesc"], $row["Image"],
                            $row["EventName"],$row["ShortDesc"],
                            $row["DateStart"], $row["DateEnd"], 
                            $row["TimeStart"], $row["TimeEnd"],
                            $row["HallID"]   , categoryArray()[$row["Category"]],
                            $row["VipPrice"] , $row["NormalPrice"],
                            $row["LongDesc"] , $row["EventID"],
                            isset($_SESSION["logged"])?"<input type='submit' value='Buy Ticket' name='btnBuyTicket' class='greenBtn'/>":"");
                        }
                    }
                }
                // Close connection
                $conn->close();
                if($found != 1){
                    printf("<p class='error'>
                            Sorry, currently no event under the category <b>%s</b>.
                            </p>", categoryArray()[$filter]);
                }
        ?>
        </div>
        <?php require '../general/footer.php';?>
        <script>
            // all javascript below is for the pop up form
            document.querySelectorAll('.btnMore').forEach((btn, index) => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.popUpDetails')[index].style.display = 'block';
                });
            });

            document.querySelectorAll('.blackBtn').forEach((btn, index) => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.popUpDetails')[index].style.display = 'none';
                });
            });

            window.addEventListener('click', function(event) {
                document.querySelectorAll('.popUpDetails').forEach(popup => {
                    if (event.target === popup) {
                        popup.style.display = 'none';
                    }
                });
            });
            document.getElementById('back').addEventListener('click', function() {
                document.querySelectorAll('.popUpDetails').forEach(popup => {
                    popup.style.display = 'none';
                });
            });
        </script>
    </body>
</html>
