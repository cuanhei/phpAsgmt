<?php 
require_once '../database/database.php';
?>
<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Admin - Hall</title>
    </head>
    <body>
        <div>
            <?php
            //--- reference php practical list-student.php --- 
                // Create connection
                $conn = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
             
                // Check connection
                if ($conn->connect_error) {
                   die("Connection error: " . $conn->connect_error);
                }
                // SQL query to retrieve all data from a table
                $sql = "SELECT * FROM hall"; 
                // Execute the query
                $result = $conn->query($sql);
                // Check if there are any rows returned
                if ($result->num_rows > 0) {
                    // Output data of each row
                    while($row = $result->fetch_assoc()) {
                        printf("
                            
                           <div>
                                <img src='%s'>
                                <h2>Hall %d</h2>
                                <p>%s</p>
                                <a href='updateHall.php?hallIndex=%d'>Update</a>
                                <a href='deleteHall.php?hallIndex=%d'>Update</a>
                           </div>
                           
                               ",$row["ImagePath"], $row["HallID"], $row["HallDesc"], $row["HallID"], $row["HallID"]);
                    }
                }
                // Close connection
                $conn->close();
                
            ?>
            <a href='addHall.php'>Add Hall</a>
        </div>
    </body>
</html>
