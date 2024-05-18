
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login | Event4U</title>
    <link href="css/login.css" rel="stylesheet" type="text/css"/>
</head>
<body>
    <?php 
    include '../general/header.php';
?>


    <h1 style='padding:3%;'></h1>
    
    <div class="login" id="login">
        <div class="title">
            <h2>Login</h2>
            <p class="title">Enter your email to Login for this app</p>
        </div>
        <div class="form">
            <form action="" method="POST" class="loginForm">
                <label>Email</label> <br>
                <input type="email" name="Email" value="" placeholder="example@email.com" required/> <br><br>
                
                <label>Password</label> <br>
                <div class="passContainer">
                    <input type="password" id="Password" name="Password" value="" placeholder="Password" required />
                    <img src="image/unShowPass.png" class="showPass1" id="showPass" width="20px" height="20px" onclick="Pass()">
                </div>
                
                <?php 

require_once '../database/database.php';

session_start(); // Start or resume the session

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['btnLogin'])) {
    $email = $_POST['Email'];
    $password = $_POST['Password'];

    $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    // Check if the database connection is successful
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }



$sql = "SELECT * FROM user WHERE email = ?";
$stmt = $con->prepare($sql);

// Bind the parameter
$stmt->bind_param('s', $email);

// Execute the query
if ($stmt->execute()) {
    // Store the result
    $result = $stmt->get_result();
    
    // Check if the email exists
    if ($result->num_rows > 0) {
        // Fetch the user row
        $row = $result->fetch_assoc();
        
        // Get the hashed password from the database
        $hashedPassword = $row['password'];
        
        // Verify the password using password_verify()
        if ($password == $hashedPassword) {
            
//            $_SESSION['logged'] = true;
            
            // Password matches, set session and redirect to profile
            
            $_SESSION['logged'] = array();
            
            $_SESSION['logged']['id']= $row['userId']; 
            $_SESSION['logged']['name']= $row['name']; 
            $_SESSION['logged']['email']= $row['email']; 
            $_SESSION['logged']['password']= $row['password']; 
            $_SESSION['logged']['avatar']= $row['avatar']; 
            $_SESSION['logged']['phone']= $row['phone']; 
            $_SESSION['logged']['cover']= $row['cover']; 
            $_SESSION['logged']['about']= $row['about']; 
            $_SESSION['logged']['gender	']= $row['gender']; 
            $_SESSION['logged']['role']= $row['role']; 
           
            print_r($_SESSION); 
            
            if(empty($_SESSION['logged']['role'])){
            header("Location: profile.php"); 
            }
            else if(!empty($_SESSION['logged']['role'])){
                header("Location: ../admin/profile/adminProfile.php"); 
            }
            
            exit();
        } else {
            echo '<p style="color:red;">Wrong password. Please try again.</p>';
        }
    } else {
        echo '<p style="color:red;">Email not found. Please register an account.</p>';
    }
} else {
    // Handle the query execution error
    echo "Error: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$con->close();

}
?>

                
                <br><br>
                
                <input type="submit" value="Login" name="btnLogin" class="btnLogin"/>    
                
                <p class="otherWay"><s>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</s> Or, continue with <s>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</s></p>

                <div class="noAccount">
                    <a href="signUp.php">Does not have any account? Proceed to <b>Sign Up</b></a>
                </div>
                <br><br>
                <p class="terms">By clicking continue, you agree to our <a href="" class="terms">Terms of Service</a> and <a href="" class="terms">Privacy Policy</a></p>
            </form>
        </div>
    </div>
    
    <?php include '../general/footer.php'; ?>

    <script>
    function Pass() {
        var x = document.getElementById("Password");
        if (x.type === "password") {
            x.type = "text";
            document.getElementById('showPass').src = 'image/ShowPass.png';
        } else {
            x.type = "password";
            document.getElementById('showPass').src = 'image/unShowPass.png';
        }
    }
    </script>
</body>
</html>