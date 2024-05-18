<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Sign Up | Event4U</title>
        <link href="css/login.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>

        <?php 
        require_once '../database/database.php';
        include '../general/header.php';
        ?>
 
        
   
<h1 style='padding:3%;'></h1>      

        <div class="login" id="login">

            
            <div class="title">
        <h2>Create an account</h2>
        <p class="title">Enter your email to sign up for this app</p>
        </div>
            <div class="form">
        <form action="" method="POST" class="loginForm">
            <label>Email</label> <br>
            <input type="email" name="Email" value="" placeholder="example@email.com" required/> <br><br>
            
            <label>Name</label> <br>
            <input type="text" name="Username" value="" placeholder="Username" required/> <br><br>
            
            <div class="passContainer">
            <label>Password</label> <br>
            <input type="password" id="Password" name="Password" value="" placeholder="Password" required/>
            <img src="image/UnShowPass.png" class="showPass2" id="showPass2" width="20px" height="20px" onclick="Pass1()">
            </div>
             <p style="font-size:10px; color:grey; text-shadow:none;">Password format:Minimum 12 character and at least 1 special character </p>
             
            <label>Confirm Password</label> <br>
            <div class="conPassContainer">
            <input type="password" id="conPassword" name="ConfirmPassword" value="" placeholder="Confirm Password" required/>
            <img src="image/unShowPass.png" class="showPass3" id="showPass3" width="20px" height="20px" onclick="Pass2()">
            </div>
            <p style="font-size:10px; color:grey; text-shadow:none;">Password format:Minimum 12 character and at least 1 special character </p>
            
<?php

$con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['btnSignIn'])) {
    $email = $_POST['Email'];
    $name = $_POST['Username'];
    $password = $_POST['Password'];
    $confirmPassword = $_POST['ConfirmPassword'];

    // Password validation
    $passwordValid = strlen($password) >= 12 && preg_match('/[\W]/', $password);

    if (!$passwordValid) {
        echo '<p style="color:red;">Password must be at least 12 characters long and contain at least one special character.</p>';
    } elseif ($password !== $confirmPassword) {
        echo '<p style="color:red;">Passwords do not match. Please enter the same password.</p>';
    } else {
        // Check if email already exists
        $sql = "SELECT email FROM user WHERE email = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo '<p style="color:red;">The email is already registered. Please use a different email.</p>';
        } else {



            $sql = "INSERT INTO user (name, email, password) VALUES (?, ?, ?)";
            $stmt = $con->prepare($sql);
            $stmt->bind_param('sss', $name, $email, $password);

            if ($stmt->execute()) {
                // Redirect to login.php after successful registration
                header("Location: login.php");
                exit();
            } else {
                echo '<p style="color:red;">Error: ' . $stmt->error . '</p>';
            }

            $stmt->close();
        }
    }
}

$con->close();
?>

          
            
            
            <br><br>
            <br>
            
            <input type="submit" value="Sign Un" name="btnSignIn" class="btnSignIn"/>    
        
            
            <p class="otherWay"><s>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</s> Or, continue with <s>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</s></p>

            <div class="gotAccount">
                <a href="login.php">Have an account? Proceed to <b>Login</b></a>
            </div>
            <br><br>
            <p class="terms">By clicking continue, you agree to our <a href="" class="terms">Terms of Service</a> and <a href="" class="terms">Privacy Policy</a></p>
            
            
           
            </form>     
             
            
          
        </div>
        </div>
        <?php include '../general/footer.php' ?>
        
        <script>
function Pass1() {
  var x = document.getElementById("Password");
  if (x.type === "password") {
    x.type = "text";
    document.getElementById('showPass2').src='image/showPass.png';

  } else {
    x.type = "password";
    document.getElementById('showPass2').src='image/unShowPass.png';    
  }
}

function Pass2() {
  var x = document.getElementById("conPassword");
  if (x.type === "password") {
    x.type = "text";
    document.getElementById('showPass3').src='image/showPass.png';

  } else {
    x.type = "password";
    document.getElementById('showPass3').src='image/unShowPass.png';    
  }
}
</script>

    </body>
</html>


