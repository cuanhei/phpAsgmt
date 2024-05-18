<?php
//--- LIM CUAN HEI ---
//todo list 
//add the href to the buy ticket form
//the show this week some error
session_start();
include_once '../database/database.php';

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
        <title>Events4you - Home</title>
        <link href="../general/globalcss.css" rel="stylesheet" type="text/css"/>
        <link href="homeCss/indexCss.css" rel="stylesheet" type="text/css"/>
    </head>
    <style>
        /*
          sometime the external css does not work idk why
          so i put embeded here 
        */
        .imageContainer img{
            width: 300px;
            height: 400px;
            border-radius: 10px;
        }
    </style>
    <body>
        <?php require '../general/header.php';?>
        <div class="hero whitetext">
            <div>
            <h1>Home</h1>
            <h3>Welcome to Events4you webpage !</h3>
            <p>To do any performance in our website make sure you did sign up and login to your account.</p>
            <p>You can buy any ticket of any events that are available in our system.</p>
            <p>If you want to reserve a hall to host your event, we provide that service too!</p>
            </div>
        </div>
        

        <div class="eventsDiv ">
            <h2>This Week Upcoming Events</h2>
            <?php 
            //--- reference php practical list-student.php --- 
            $found = 0;
            // Create connection
            $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            // Check connection
            if ($conn->connect_error) {
               die("Connection error: " . $conn->connect_error);
            }
            // SQL query to retrieve all data from a table
            $sql = "SELECT * FROM Event"; 
            // Execute the query
            $result = $conn->query($sql);
            // Check if there are any rows returned
            $ownerID = isset($_SESSION["logged"]["id"])?$_SESSION["logged"]["id"]:NULL;
            if ($result->num_rows > 0) {
                // Output data of each row
                $thisWeek = date('Y-m-d', strtotime('+7 days'));
                while ($row = $result->fetch_assoc()) {
                    if ($row["DateStart"] >= date('Y-m-d') 
                        && $row["DateStart"] <= $thisWeek
                        && $row["OwnerID"] != $ownerID) //show only this week upcoming event
                    {
                        $found =1;
                            printf("                            
                               <div class='events'>
                                    <div class='eventText'>
                                        <h2>%s</h2>
                                        <p>%s</p>
                                        <p>Date : %s - %s</p>
                                        <p>Time : %s to %s</p>
                                        <button class='btnMore'>More</button>            
                                    </div>            
                                <div class='eventImg'><img src='%s'></div>
                                </div>      
                                <div class='popUpDetails'>
                                    <div class='popUpContent'>
                                        <div class='imageContainer'>
                                        <img src='%s'>
                                        </div>
                                        <div class='textContent'>
                                            <h2>%s</h2>
                                            <p>%s</p>
                                            <p>Date : %s - %s</p>
                                            <p>Time : %s to %s</p>
                                            <p>Location : Hall %d</p>
                                            <p>Category : %s</p>
                                            <p>Price for VIP : RM%.2f</p>
                                            <p>Price for Normal : RM%.2f</p>
                                            <p>%s</p>
                                            <form action='../reservation/reservation.php' method='POST'>
                                                <input type='hidden' name='eventID' value='%d'/>
                                                %s<br><br>
                                                <input type='button' value='Back' name='btnBack' class='blackBtn'/>
                                            </form>
                                        </div>
                                    </div>
                                </div>"

                                ,
                                $row["EventName"], $row["ShortDesc"], 
                                $row["DateStart"], $row["DateEnd"], 
                                $row["TimeStart"], $row["TimeEnd"],
                                $row["Image"], 
                                    
                                $row["Image"],
                                $row["EventName"], $row["ShortDesc"], 
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
                if($found !=1){
                    echo"<p class='error'>This week have no any events.[<a href='../events/events.php'><b>More</b></a>]</p>";
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
        
    </script>
    </body>
</html>


