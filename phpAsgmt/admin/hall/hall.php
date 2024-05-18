<?php
//--- LIM CUAN HEI ---
require_once '../../database/database.php';
session_start();
//(isset($_SESSION["logged"]["roles"]))?"":header("location:../../home/index.php");
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
        <link href="../../general/globalcss.css" rel="stylesheet" type="text/css"/>
        <link href="hallCss/hallCss.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <?php include '../general/adminHeader.php'; ?>
        <div class="hero whitetext">
            <div>
            <h1>HALL</h1>
            <h3>Adding the hall only when it is exists!</h3>
            <p>This is the page for admin to add the hall.</p>
            <p>You must ensure that the hall you adding is exists, do not add fake hall.</p>
            <p>Adding fake hall will destroy our brand!</p>
            </div>
        </div>
        <button class="btnAddHall" onclick="location='addHall.php'">Add Hall</button>
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
                echo"<div class='hallContainer'>";
                if ($result->num_rows > 0) {
                    // Output data of each row
                    while($row = $result->fetch_assoc()) {
                        printf("  
                           <div class='hall'>
                                <img src='%s'>
                                <h2>Hall %d</h2>
                                <p>%s</p>
                                <a href='updateHall.php?hallIndex=%d' class='btnUpdate'>Update</a>
                                <a href='deleteHall.php?hallIndex=%d' class='btnDelete'>Delete</a>
                           </div>
                           
                               ","../".$row["ImagePath"], $row["HallID"], $row["HallDesc"], $row["HallID"], $row["HallID"]);
                    }
                }
                echo "</div>";
                // Close connection
                $conn->close();
                
            ?>
        
        <?php include '../general/adminFooter.php'; ?>
    </body>
</html>
