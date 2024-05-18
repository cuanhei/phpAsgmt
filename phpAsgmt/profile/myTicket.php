<?php 
require_once'../database/database.php';
session_start();
?>

<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Project/PHP/PHPProject.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <link href="css/profile.css" rel="stylesheet" type="text/css"/>
                <link href="../events/eventCss/eventCss.css" rel="stylesheet" type="text/css"/>

        <title>Event4U - Profile</title>
        <style>
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
        <?php include '../general/header.php'; ?> 
        <div class="container">
            
        <div class="proHeader">
                <a href="profile.php">My Profile</a>
                <a href="myTicket.php">My Ticket</a>
                <a href="myEvent.php">My Event</a>
        </div>
        
        <h2>Not yet done...</h2>
        <?php  
        if(isset($_POST['btnDelete'])){
            
        }
        ?>
        <?php 
        $conn = new mysqli (DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($conn->connect_error) {
            die("Connection error: " . $conn->connect_error);
        }
        $userID = $_SESSION["logged"]["id"];
        $sql = "SELECT * FROM Reservation r JOIN Event e ON r.EventID = e.EventID WHERE r.UserID = $userID";
        
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
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
                                            <input type='submit' class='btnDelete' value='Cancel name='btnDelete' />
                                            </form>
                                            <button class='blackBtn' id='back'>Back</button>
                      </div>
                      </div>
                       </div>
                       </div>
                        ",
                        $row["EventName"],
                        $row["Image"],
                        $row["EventName"],$row["ShortDesc"],
                            $row["DateStart"], $row["DateEnd"], 
                            $row["TimeStart"], $row["TimeEnd"],
                            $row["HallID"]   , $row["Category"],
                            $row["VipPrice"] , $row["NormalPrice"],
                            $row["LongDesc"] , $row["EventID"], $row["EventID"],$row["EventID"]);

            }
        }
        
        
        ?> 
        
        </div>
        <?php include '../general/footer.php'; ?>
        <script>
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
