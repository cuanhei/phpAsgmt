<!--change to post method-->

<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Admin - Delete Hall</title>
    </head>
    <body>
        <h1>Delete Hall</h1>
        <?php
        require_once'../database/database.php';
        if($_SERVER["REQUEST_METHOD"]=="GET"){
            //GET 
            //Show the details of the hall that going to delete
            if(isset($_GET["hallIndex"])){
                $hallIndex = $_GET["hallIndex"];
                
                //Step 1: Create connection between system and DB
                $con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);

                //Step 2: run sql statement
                $sql ="DELETE FROM Hall WHERE HallID = $hallIndex";
                $con->query($sql);
                $con->close();
                
            }else{
                    //Record not found when retreive
                    echo"<div class='error'>Database Error! Please try again [<a href='readHall.php'>Back to List</a>]</div>";
            }
        }
        else{
                //sent back to the readHall.php
                header("location:readHall.php");
            }   
        ?>
      
    </body>
</html>
