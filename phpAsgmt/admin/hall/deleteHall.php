<?php
require_once'../../database/database.php';
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
        <title>Admin - Delete Hall</title>
        <link href="hallCss/addHallCss.css" rel="stylesheet" type="text/css"/>
        <style>
            td{
                width:50%;
            }
            img.hall{
                width:100%;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            }
            button.red{
                background-color: #dc3545;  
            }
            button.red:hover{
                background-color: #c82333;
            }
            button.grey{
                background-color: grey;
            }
        </style>
    </head>
    <body>
        <?php include '../general/adminHeader.php'; ?>
        <h1>Delete Hall</h1>
        <?php
        if($_SERVER["REQUEST_METHOD"]=="GET"){
            //Get Method
            //Show the detail in form to delete
            if(isset($_GET["hallIndex"])){
                $hallIndex = $_GET['hallIndex'];
            }else{
                header("location:hall.php");
            }
            
            //Step 1: Create connection between system and DB
            $con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
            //clean id by removing special character or symbol , prevent sql injection attack
            $hallIndex = $con->real_escape_string($hallIndex);
            
            //Step 2: run sql statement
            $sql ="SELECT * FROM Hall WHERE HallID = '$hallIndex'";
            $result = $con->query($sql);
            if ($row = $result->fetch_object()){
                //Record found
                   $hallIndex = $row->HallID; // PINK COLOR MUST BE SAME  AS WHAT IN THE TABLE COLUMN   
                   $hallDesc = $row->HallDesc;
                   $imagePath = $row-> ImagePath;
                   $numOfVip = $row->VipSeat;
                   $numOfNormal = $row->NormalSeat;
                   $availability = $row->Availability;
                   $pricePerDay = $row->Price;
            }
            else{
                //record no found
                echo"<div class='error'>Something went wrong! Please try again [<a href='hall.php'>Back</a>]</div>";
            }
            //for security purpose
            $con->close();
            $result->free();
        }
        
        else{
            //POST
            ($_GET["hallIndex"]==NULL)?header("location:hall.php"):NULL;
            $hallIndex = $_GET["hallIndex"];
            $imagePath = $_POST["imgPath"];
            //Step 1: Create connection between system and DB
            $con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);

            //Step 2: run sql statement
            $sql ="DELETE FROM Hall WHERE HallID = $hallIndex";
            $con->query($sql);
            $con->close();

            header("location:hall.php");
        }
        ?>
        
         <div class="shadow" style="margin-top:50px;">
            
        <form action="" method="POST" enctype="multipart/form-data" id="addHallForm">
            <table>
                <tr>
                    <td rowspan="6"><img class="hall" src="<?php echo "../$imagePath"?>">
                    <input type="hidden" name="imgPath" value="<?php echo $imagePath?>" /></td> 
                    <td><h2><?php echo"Hall $hallIndex"?></h2></td>
                </tr>
                <tr>
                    <td><?php echo"<p>$hallDesc</p>";?></td>
                </tr>
                <tr>
                    <td><?php echo"<p>VIP Seat : <b>$numOfVip</b></p>";?></td>
                </tr>
                <tr>
                    <td><?php echo"<p>Normal Seat : <b>$numOfNormal</b></p>";?></td>
                </tr>
                <tr>
                    <td><?php   echo"<p>Status : <b>";
                                echo ($availability)?"Available":"Unavailable";
                                echo "</b></p>";
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><?php echo"<p>Price Per Day : RM<b>$pricePerDay</b></p>";?></td>
                </tr>
            </table>
            <div class="btn-group">
            <button type="button" name="btnCancel" onclick="location='hall.php'" class="grey">Cancel</button>
            <button type="submit" name="btnDelete" class="red">Delete</button>
            </div>
        </form>
        </div>
        <?php include '../general/adminFooter.php'; ?>
    </body>
</html>
