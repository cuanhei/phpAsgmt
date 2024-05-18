<?php
require_once '../database/database.php';
session_start();

?>
<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Events4you - Reservation</title>
        <style>
            *{
                margin:0;
            }
            h1{
                margin-top:50px;
                padding:20px;
                padding-top:50px;
                padding-bottom:40px;
                box-shadow: inset 0 0 8px 2px rgba(0, 0, 0, 0.5);
                text-align: center;
            }
            input[type="checkbox"]{
                width: 50px;
                height: 50px;
                margin: 5px;
                background-color: #ccc;
                display: inline-block;
                cursor: pointer;
            }
            .container {
                margin: auto;
                margin-top:50px;
                width:50%;
                box-shadow: 0 0 8px 2px rgba(0, 0, 0, 0.5);
                padding:50px;
                border-radius: 10px;
            }

            .seating-table {
                width: 100%;
                border-collapse: collapse;
            }

            .section-title {
                font-weight: bold;
            }

            .seat-list {
                list-style-type: none;
                padding: 0;
            }

            .seat-list li {
                margin-bottom: 5px;
            }

            .total-amount {
                font-weight: bold;
                padding: 10px 0;
            }

            .buy-button ,.btnNext{
                padding: 10px 20px;
                background-color: #4CAF50;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }

            .buy-button:hover , .btnNext{
                background-color: #45a049;
            }

            .cancel-link , .btnCancel{
                border:none;
                display: inline-block;
                padding: 10px 20px;
                text-decoration: none;
                color: #333;
                background-color: #f2f2f2;
                border-radius: 5px;
            }

            .cancel-link:hover , .btnCancel{
                background-color: #ddd;
            }
                       .btn-group {
                display: flex;
                justify-content: space-between;
                margin-top: 20px;
            }   
            div.green{
                text-align: center;
                background-color: green;
            }
            div.dark{
                text-align: center;
                background-color: rgba(0,0,0,0.4);
            }
            div.booked{
                width: 50px;
                height: 50px;
                margin: 5px;
                background-color: red;
                display: inline-block;
            }
            .error, .info
{
    padding: 5px;
    margin: 5px;
    font-size: 0.9em;
    list-style-position: inside;
}

.error
{
    border: 2px solid #FBC2C4;
    background-color: #FBE3E4;
    color: #8A1F11;
}

.info
{
    border: 2px solid #92CAE4;
    background-color: #D5EDF8;
    color: #205791;
}

        </style>
    </head>
    <body>
        <?php include '../general/header.php';?>
        <?php 
            if (isset($_POST["btnBuyTicket"])){
                  unset($_SESSION['reservation']);
                  $eventID = $_POST["eventID"];
                  $numOfVipSeat = 0;
                  $numOfNormalSeat = 0;
                  $vipPrice = 0;
                  $normalPrice = 0;
                  
                  $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                  if ($conn->connect_error) {
                     die("Connection error: " . $conn->connect_error);
                  }
                  
                  // SQL query to retrieve all data from a table
                  $sql1 ="SELECT * FROM Event e JOIN Hall h ON e.HallID = h.HallID WHERE e.EventID = $eventID"; 
                  $sql2 ="SELECT * FROM Reservation WHERE EventID = $eventID";
                  
                  $result1 = $conn->query($sql1);
                  // Check if there are any rows returned
                  if ($result1->num_rows > 0) {
                      while ($row = $result1->fetch_assoc()) {
                          $eventName = $row["EventName"];
                          $numOfVipSeat = $row["VipSeat"];
                          $numOfNormalSeat = $row["NormalSeat"];
                          $vipPrice = $row["VipPrice"];
                          $normalPrice = $row["NormalPrice"];
                      }
                  }
                  $result2 = $conn->query($sql2);
                  $seatList = array();
                  if ($result1->num_rows > 0) {
                      while ($row = $result2->fetch_assoc()) {
                          $seatList[] = $row["Seats"];
                      }
                  }
                  
                  $conn->close();
                  $_SESSION["reservation"]["eventID"] = $eventID;
                  $_SESSION["reservation"]["eventName"] = $eventName;
                  $_SESSION["reservation"]["vipPrice"] = $vipPrice;
                  $_SESSION["reservation"]["normalPrice"] = $normalPrice;
                  
                  
                  $seats = array();
                  foreach($seatList as $key => $value){
                      $seats[] = explode(",",$value);
                  }
                  //This function is refer to https://stackoverflow.com/questions/6535444/merge-two-numerically-keyed-associative-arrays-and-preserve-the-original-keys
                  $seats = array_merge(...$seats);
                  //print_r($seats);
                  
                  echo "<h1>$eventName</h1>";
                  printf("<ul class='info'><b>Note:</b>
                           <li><b>Red</b> Seats are booked by others.</li>
                           <li><b>White</b> Seats are available.</li>
                           <li>Seats under <b>green</b> area are <b>VIP Seat</b>.</li>
                           <li>Seats under <b>grey</b> area are <b>Normal Seat</b>.</li>
                         </ul>");
                  
                  echo"<form action='' method='POST'>";
                  echo "<div class='green'>";
                  for($i = 0; $i<$numOfVipSeat; $i++){
                      if($i %10 == 0){
                          echo "<br>";
                      }
                      if(!in_array("v$i", $seats)){
                        echo "<input type='checkbox' name='vip[]' value='v$i'/>";
                      }else{
                          echo "<div class='booked'></div>";
                      }
                  }
                  echo "</div>";
                  //echo"<br><br>";
                  echo "<div class='dark'>";
                  for($i = 0; $i<$numOfNormalSeat; $i++){
                      if($i %10 == 0){
                          echo "<br>";
                      }
                      if(!in_array("n$i", $seats)){
                         echo "<input type='checkbox' name='normal[]' value='n$i'/>";
                      }else{
                          echo "<div class='booked'></div>";
                      }
                  }
                  echo "</div>";
                    
               echo "<div class='btn-group'>";
                echo '<input type = "button" value = "Cancel" onclick="location=\'../events/events.php\'" class="btnCancel"/>';
                 echo '<input type = "submit" value = "Next" name = "btnSubmit" class ="btnNext"/>';
                 echo "</div>";
                echo "</form>";
            }
            else if(isset($_POST["btnSubmit"])){
                unset($_SESSION['buySeat']);
                $eventName = $_SESSION["reservation"]["eventName"]; 
                $vipPrice = $_SESSION["reservation"]["vipPrice"];
                $normalPrice = $_SESSION["reservation"]["normalPrice"];
                
                $amountOfVip =  isset($_POST["vip"])?count($_POST["vip"]):0;  
                $amountOfNormal =  isset($_POST["normal"])?count($_POST["normal"]):0; 
                
                echo "<h1>$eventName</h1>";
                $vipSeat = isset($_POST["vip"])?implode(',',$_POST["vip"]):NULL;
                $normalSeat = isset($_POST["normal"])?implode(',',$_POST["normal"]):NULL;
                $vipList = explode(',', $vipSeat);
                $normalList = explode(',', $normalSeat);
                
                $totalAmount = ($amountOfVip * $vipPrice) + ($amountOfNormal * $normalPrice);
                $_SESSION["reservation"]["total"] = $totalAmount;
                
                $_SESSION['buySeat']["seats"] = $vipSeat .",". $normalSeat;
                $_SESSION['buySeat']["userID"] = $_SESSION["logged"]["id"];
                $_SESSION['buySeat']["eventID"] =  $_SESSION["reservation"]["eventID"];
                $_SESSION['buySeat']["paymentAmount"] =  $_SESSION["reservation"]["total"];
                
                echo "<div class='container'>";
                echo "<form action='' method='POST'>";
                echo "<table class='seating-table'>";
                echo "<tr>";
                echo "<td>";
                echo "<b class='section-title'>VIP Seat List :</b>";
                echo "</td>";
                echo "<td>";
                echo "<ul class='seat-list'>";
                foreach ($vipList as $key => $value) {
                    echo "<li>$value</li>";
                }
                echo "</ul>";
                echo "</td>";
                echo "</tr>";
                echo"<tr><td colspan='2'><hr><td></tr>";
                echo "<tr>";
                echo "<td>";
                echo "<b class='section-title'>Normal Seat List :</b>";
                echo "</td>";
                echo "<td>";
                echo "<ul class='seat-list'>";
                foreach ($normalList as $key => $value) {
                    echo "<li>$value</li>";
                }
                echo "</ul>";
                echo "</td>";
                echo"</tr>";
                echo"<tr><td colspan='2'><hr><td></tr>";
                echo "<tr>";
                echo "<td colspan='2' class='total-amount'>";
                printf("Total amount to pay : <b>RM%.2f</b>", $totalAmount);
                echo "</td>";
                echo "</tr>";

                echo "</table>";
                
                echo"<div class='btn-group'>";
                echo "<a href='../events/events.php' class='cancel-link'>Cancel</a>";
                echo "<input type='submit' value='Buy' name='btnBuy' class='buy-button' />";
                echo "</div>";
                echo "</form>";
                echo "</div>";
            }
            else if(isset($_POST["btnBuy"])){
                
                $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                if ($conn->connect_error) {
                     die("Connection error: " . $conn->connect_error);
                  }
                  
                // SQL query 
                $sql ="INSERT INTO Reservation (Seats, UserID, EventID, PaymentAmount)
                        VALUES (?,?,?,?)"; 
                $stmt = $conn->prepare($sql);
                
                
                //--- Got some error so i cancel ---
                //When link into the file cannot perform insert the reservation
                //
                //-----------------------------------
                //include 'payment.php';
                //-----------------------------------
                //
                //
                //Step 2.2 supply data into the "?" parameter in the sql
                //NOTE: s- string, i- integer, d-double, b-blob (IMG)
                $stmt ->bind_param('siid', 
                        $_SESSION['buySeat']["seats"],
                        $_SESSION['buySeat']["userID"],
                        $_SESSION['buySeat']["eventID"],
                        $_SESSION['buySeat']["paymentAmount"]);

                //Step 3: Execute sql
                $stmt->execute();

                if($stmt->affected_rows > 0){
                    //record inserted
                    echo "<h1  class='info' style='margin-top:300px'>You <b>Successfully</b> bought those seats.<a href='../events/events.php'>Back</a></h1><br>";
                }
                else{
                    //record unable to insert
                    echo "<div class='error'>Something went wrong. Please try again. [<a href='../events/events.php'>Back</a>]</div>";
                }

                $stmt->close();   
                $conn->close();
                include 'payment.php';
            }
            else{
                 header("location:lol.php");   
            }
            
        ?>
        <?php include '../general/footer.php'?>
    </body>
</html>
