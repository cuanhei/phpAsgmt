<?php
//todo list
//prompt sure delete or not
//
//something error breh
//(isset($_SESSION["logged"]["role"]))?"":header("location:../../home/index.php");
require_once '../../database/database.php';
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
        <title>Admin - Events</title>
        <link href="../../events/eventCss/eventCss.css" rel="stylesheet" type="text/css"/>
        <style>
            *{
                margin:0;
            }
            .whitetext{
                color:white;
            }
            .events {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width:80%;
            margin:auto;
            padding:20px;
            margin-top:20px;
        }
        .events h2 {
            margin: 0;
        }
        .events .actions {
            display: flex;
            gap: 10px; /* Adjust spacing between buttons/links */
        }
        .btnMores{
            border-radius: 20px;
        }
        .btnMores, .btnUpdate, .btnDelete {
            padding: 10px 10px;
            text-decoration: none;
            color: white;
            background-color: #007BFF; /* Primary color */
            border: none;
            border-radius: 3px;
            cursor: pointer;
            text-align:center;
        }
        .btnUpdate {
            background-color: #28A745; /* Update color */
        }
        .btnDelete {
            background-color: #DC3545; /* Delete color */
            width:100%;
            padding: 10px 10px;
        }


        </style>
    </head>
    <body>
        <?php include '../general/adminHeader.php'; ?>
        <div class="hero whitetext">
            <div>
            <h1>EVENTS</h1>
            <h3>Below are the events that created by the member.</h3>
            <p>You can edit their event details, but please inform them.</p>
            <p>Remember to ask for their permission if you want to edit their event.</p>
            <p>Do not delete the event by yourself!</p>
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
                printf("<a href='../../events/createEvent.php' class='createEvent'>Create Event</a>");
            }else{
                echo "<button class='createEvent' onclick=\"alert('You need to login to an account to create event.');\">Create Event </button>" ;
            }
            ?>
        </div>
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
            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    if ($row["DateStart"] >= date('Y-m-d')) //Show the event that not yet started
                    {
                        $found = 1;
                        printf("                            
                           <div class='events'>
                                <h2>%s</h2>
                                <button class='btnMores'>More</button>  
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
                                            <a href='editEvent.php?eventID=%d' class='btnUpdate'>Update</a> 
                                            <form action='deleteEvent.php' method='POST'>
                                            <input type='hidden' name='eventID' value='%d' />
                                            <input type='submit' class='btnDelete' value='Delete' name='btnDelete' />
                                            </form>
                                            <button class='blackBtn' id='back'>Back</button>
                                        </div>
                                    </div>
                                </div>
                            </div>"
                            ,
                            $row["EventName"], 
                            "../".$row["Image"],
                            $row["EventName"],$row["ShortDesc"],
                            $row["DateStart"], $row["DateEnd"], 
                            $row["TimeStart"], $row["TimeEnd"],
                            $row["HallID"]   , categoryArray()[$row["Category"]],
                            $row["VipPrice"] , $row["NormalPrice"],
                            $row["LongDesc"] , $row["EventID"], $row["EventID"],$row["EventID"]
                            );
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
        <?php include '../general/adminFooter.php'; ?>
        <script>
            // all javascript below is for the pop up form
            document.querySelectorAll('.btnMores').forEach((btn, index) => {
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
