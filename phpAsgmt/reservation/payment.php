
<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Payment</title>
    </head>
    <body>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;300;400;500;600&display=swap');

*{
  font-family: 'Poppins', sans-serif;
  margin:0; padding:0;
  box-sizing: border-box;
  outline: none; border:none;
  text-transform: capitalize;
  transition: all .2s linear;
}

.container{
  display: flex;
  justify-content: center;
  align-items: center;
  padding:25px;
  min-height: 100vh;
  background: linear-gradient(90deg, #2ecc71 60%, #27ae60 40.1%);
}

.container form{
  margin:30px;
  padding:30px;
  width:600px;
  background: #fff;
  box-shadow: 0 5px 10px rgba(0,0,0,.1);
}

.container form .row{
  display: flex;
  flex-wrap: wrap;
  gap:15px;
}

.container form .row .col{
  flex:1 1 250px;
}

.container form .row .col .title{
  font-size: 20px;
  color:#333;
  padding-bottom: 5px;
  text-transform: uppercase;
}

.container form .row .col .inputBox{
  margin:15px 0;
}

.container form .row .col .inputBox span{
  margin-bottom: 10px;
  display: block;
}

.container form .row .col .inputBox input{
  width: 100%;
  border:1px solid #ccc;
  padding:10px 15px;
  font-size: 15px;
  text-transform: none;
}

.container form .row .col .inputBox input:focus{
  border:1px solid #000;
}

.container form .row .col .flex{
  display: flex;
  gap:15px;
}

.container form .row .col .flex .inputBox{
  margin-top: 5px;
}

.container form .row .col .inputBox img{
  height: 34px;
  margin-top: 5px;
  filter: drop-shadow(0 0 1px #000);
}

.container form .submit-btn{
  width: 100%;
  padding:12px;
  font-size: 17px;
  background: #27ae60;
  color:#fff;
  margin-top: 5px;
  cursor: pointer;
}

.container form .submit-btn:hover{
  background: #2ecc71;
}

.header{
                position:absolute;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                width: 100%;
                height: 60px;
                display: block;
                padding-top: 15px;
               top:0;
               left:0;
               background-color: black;
               color: white;
               position:fixed;
               z-index:999;
            }            
           .header a{
             text-decoration: none;   
             color: white;
             cursor: pointer;
             font-size: 20px;            
            }            
            a.left{
                margin: 5%;
                font-size: 30px;
            }
            .right{
                float: right;  
                padding: 7px;
                margin-right: 40px;
            }            
            .profile{
                background-color: white;
                color: black;
                border-radius: 15px;
            }                       

            .tableCss tr,.tableCss td,.tableCss th{
                border:none;
                width: 5%;
                text-align: center;
                padding-bottom:10px; 
                padding-top:10px; 
                transition: 0.2s linear;
            }
                        
            .tableCss td a:hover,tableCss th a:hover{
                border-bottom: black solid 3px;
            }
            
           .tableCss td a{
                font-size: 13px;
                color: grey;
                text-decoration: none;
            }
            
            footer{
                margin-top: 5%;
            }
            
            .tableCss td a.image,tableCss td a:image:hover{
                border:none;
                border-bottom: none;
            }
            
            .success{
                margin:50px;
                background: #166045;
                color:white;
                font-size:20px
            }
        </style>
        
        <header>
            <header>
            <div class="header">
            <a href="homepage" class="left"><b>Events4you</b></a>
            
            <?php 

            $urlName = $_SERVER['REQUEST_URI'];

            // Check if the current URI matches Sign Up or Login URIs
            if(strpos($urlName, 'login') !== false){
                echo '<a href="../profile/signUp.php" class="right profile" style="color:black;">Sign Up</a>';
            }
            else if(strpos($urlName, 'signUp') !== false){
                echo '<a href="../profile/login.php" class="right profile" style="color:black;">Login</a>'; 
            }
            else if(isset($_SESSION['logged'])){
                 echo '<a href="../profile/profile.php" class="right profile" style="color:black;">Profile</a>'; 
            }
            else{
                echo '<a href="../profile/login.php" class="right profile" style="color:black;">Login / Sign Up</a>'; 
            }

            //if no user login it will show login
            //if the user have login the top right will display user profile pic and profile button
            
            

            ?>
            
            
            <?php 
            echo isset($_SESSION["logged"])?"<a href='../events/createEvent.php' class='right'>Create Event</a>":
                    "<a onclick=\"alert('You must log in to an account to create event.');\" class='right'>Create Event</a>";?>
            <a href="../events/events.php" class="right">Events</a>
            <a href="../home/index.php" class="right">Home</a>
            </div>
        
        </header>

        </header>
        <div class="container">
             <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "asgmt";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            if ($_SERVER["REQUEST_METHOD"] == "GET") {
                $name = $_GET['name'];
                $email = $_GET['email'];
                $address = $_GET['address'];
                $city = $_GET['city'];
                $state = $_GET['state'];
                $card = $_GET['card'];

                if (!empty($name) && !empty($email) && !empty($address) && !empty($city) && !empty($state) && !empty($card)) {
                    $stmt = $conn->prepare("INSERT INTO payment (name, email, address, city, state, card) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssssss", $name, $email, $address, $city, $state, $card);

                    if ($stmt->execute()) {
                        echo "<div class='success'>Payment information saved successfully.</div>";
                    } else {
                        echo "Error: " . $stmt->error;
                    }

                    $stmt->close();
                    
                    
                } else {
                    echo "<div class='success'>All fields are required.</div>";
                }
            }
            $conn->close();
            
            
            ?>
            
            
            <form method="GET" action="payment.php">
            <div class="row">
                <div class="col">
                    <h3 class="title">Billing Address</h3>
                    
                    <div class="inputBox">
                        <span>Full Name : </span>
                        <input type="text" name="name" placeholder="john dea" >
                    </div>
                    <div class="inputBox" method="POST">
                        <span>Email : </span>
                        <input type="email" name="email" placeholder="example@example.com" >
                    </div>
                    <div class="inputBox">
                        <span>Address : </span>
                        <input type="text" name="address" placeholder="" >
                    </div>
                    <div class="inputBox">
                        <span>City : </span>
                        <input type="text" name="city" placeholder="Penang" >
                    </div>
                    <div class="flex">
                        <div class="inputBox">
                            <span>State : </span>
                            <input type="text" name="state" placeholder="Malaysia" >
                        </div>
                    </div>
                </div>
                
                 <div class="col">

                <h3 class="title">Payment</h3>

                <div class="inputBox">
                    <span>Cards Accepted :</span>
                    <img src="card_img.png" alt="">
                </div>
                <div class="inputBox">
                    <span>credit card number :</span>
                    <input type="number" name="card" placeholder="1111-2222-3333" >
                </div>
                <div class="inputBox">
                    <span>exp month :</span>
                    <input type="text" placeholder="january" >
                </div>

                <div class="flex">
                    <div class="inputBox">
                        <span>exp year :</span>
                        <input type="number" placeholder="2022" >
                    </div>
                    <div class="inputBox">
                        <span>CVV :</span>
                        <input type="text" placeholder="1234" >
                    </div>
                </div>

            </div>
    
        </div>

        <button type="button" class="submit-btn" style="background-color: red" onclick="location.href='../events/events.php'">Cancel Checkout</button>
        <input type="submit" value="proceed to checkout" class="submit-btn">
            </form>
            
            
        </div>
        <footer>
            <hr>
            <div class="footer">
                
                
                <table class='tableCss'>
                    <tr>
                        <th colspan='1' rowspan=''3>Event4You</th>
                        <td></td>
                        <th>Topic</th>
                        <th>Topic</th>
                        <th>Topic</th>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td><a href=''>Page</a></td>
                        <td><a href=''>Page</a></td>
                        <td><a href=''>Page</a></td>
                        </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td><a href=''>Page</a></td>
                        <td><a href=''>Page</a></td>
                        <td><a href=''>Page</a>
                    </tr>
                    <tr>
                        <td class='image'>
                        <a href='https://web.facebook.com' class='image'><img src='image/facebookGrey.png' alt='facebook'  width='30px' height='30px'></a>&nbsp;&nbsp;
                        <a href='https://www.instagram.com/' class='image'><img src='image/instagramGrey.png' alt='instagram' width='30px' height='30px' ></a>&nbsp;&nbsp;
                        <a href='https://www.youtube.com/' class='image'><img src='image/youtubeGrey.png' alt='youtube' width='32px' height='30px' ></a>&nbsp;&nbsp;
                        
                        </td>
                        <td></td>
                        <td><a href='https://web.facebook.com'>Facebook</a></td>
                        <td><a href='https://www.instagram.com/'>Instagram</a></td>
                        <td><a href='https://www.youtube.com/'>Youtube</a></td>
                    </tr>
                </table>
            </div>"
        </footer>
    </body>
</html>
