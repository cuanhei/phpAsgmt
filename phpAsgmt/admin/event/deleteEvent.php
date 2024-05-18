<?php
session_start();
//(isset($_SESSION["logged"]["role"]))?"":header("location:../../home/index.php");
?> 

<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Admin - Event Delete</title>
    </head>
    <body>
        <?php
            require_once '../../database/database.php';

            if(isset($_POST["eventID"])){

                $eventID = $_POST["eventID"];

                //Step 1: Create connection between system and DB
                $con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);

                //Step 2: run sql statement
                $sql ="DELETE FROM Event WHERE EventID = $eventID";
                $con->query($sql);
                $con->close();

            }
            header('location:event.php');
        ?>
        
    </body>
</html>
