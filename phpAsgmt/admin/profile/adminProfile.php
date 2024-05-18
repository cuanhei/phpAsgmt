<?php
session_start(); // Start or resume the session

if (!isset($_SESSION['logged'])) {
    // If the user is not logged in, redirect them to the login page
    header("Location: ../../profile/login.php");
    exit();
}


    
    //Log out the account
    if(isset($_POST['btnLogOut']))  {
        session_destroy();
        header("Location: ../../profile/login.php");
    }
    ?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin Profile | Event4you</title>
    <link href="adminProfile.css" rel="stylesheet" type="text/css"/>
</head>
<body>
    <?php 
    include '../general/adminHeader.php'; 
    require_once '../../database/database.php';
    ?> 

    <div class="container">

        
        <br><br>

                    <div class="proImage">
                <h3>Avatar and Cover Image</h3>

                
            <?php
            
             $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                
                

        $userId = $_SESSION['logged']['id'];

        // Prepare the SQL query to fetch user details
        $sql = "SELECT  avatar, cover FROM user WHERE userId = ?";
        $stmt = $con->prepare($sql);

        if ($stmt) {
            $stmt->bind_param('i', $userId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $userDetails = $result->fetch_assoc();
                
                
                if(!empty($_SESSION['logged']['avatar'])){
                    $defaultPath = $userDetails['avatar'];
                }else if(empty($_SESSION['logged']['avatar'])){
                 // Define the default path to the current avatar image
                 $defaultPath = 'image/defaultPic.png';
                }
                 // Check if a new picture is selected and save it to the session
                 if (isset($_GET['pic'])) {
                     $selectedPic = $_GET['pic'];
                     $_SESSION['avatar'] = 'image/' . $selectedPic . '.jpg';
                 }

                 // Get the current avatar path from the session or use the default path
                 $path = isset($_SESSION['avatar']) ? $_SESSION['avatar'] : $defaultPath;

                 // Display the current avatar and change picture button
                 printf("
                     <p><b>Avatar</b></p>
                     <img src='%s' width='138px' height='138px' alt='Profile Image'><br><br>
                     <form method='GET'>
                         <input type='submit' value='Choose Proflie Image' name='proPic' id='proPic' />
                     </form>
                 ", $path);

                 // Check if the 'Change picture' button was clicked
                 if (isset($_GET["proPic"])) {
                     $images = array("pic1", "pic2", "pic3", "pic4", "pic5", "pic6");

                     // Display options for the user to choose a new avatar
                     foreach ($images as $value) {
                         // Define the path to each image
                         $imgPath = 'image/' . $value . '.jpg';

                         // Display each image as a link
                         printf("
                             <a href='?pic=%s'><img src='%s' style='border:none; padding:10px; width:138px; height:138px;' /></a>
                         ", $value, $imgPath);
                     }
                 }

                 
                    $defaultCoverPath = $userDetails['cover'];
                    
                if(empty($_SESSION['logged']['cover'])){
                 // Define the default path to the current avatar image
                 $defaultCoverPath = 'image/bg1.jpg';
                }
                // Check if a new cover picture is selected and save it to the session
                 if (isset($_GET['bg'])) {
                     $selectedCover = $_GET['bg'];
                     $_SESSION['cover'] = 'image/' . $selectedCover . '.jpg';
                 }

                 // Get the current cover path from the session or use the default cover path
                 $coverPath = isset($_SESSION['cover']) ? $_SESSION['cover'] : $defaultCoverPath;
                 
                 // Display the current cover and change cover picture button
                 printf("
                     <p><b>Cover Image</b></p>
                     <img src='%s' width='680px' height='250px' alt='Cover Image'><br><br>
                     <form method='get'>
                         <input type='submit' value='Choose Cover Image' name='proCover' id='proCover' />
                     </form>
                 ", $coverPath);

                 
                // Check if the 'Change picture' button was clicked
                 $cover = array();
                 
                 if (isset($_GET["proCover"])) {
                     $cover = array("bg1", "bg2", "bg3", "bg4");
                     
                 }
                    // Display options for the user to choose a new cover
                     foreach ($cover as $key) {
                         // Define the path to each image
                         $coverPath = 'image/' . $key . '.jpg';
                     
                         // Display each image as a link
                         printf("
                             <a href='?bg=%s'><img src='%s' style='border:none; padding:10px; width:138px; height:138px;' /></a>
                         ", $key, $coverPath);
                     
                 }
            } 
            }
            ?>


            </div>

        <br><br>

        <div class="displayPro">
                
            <h3>Profile Details  <button type="button" onclick="edit()" class="btnEditPro">Edit</button> </h3> 
              
                <?php

                
                

        $userId = $_SESSION['logged']['id'];

        // Prepare the SQL query to fetch user details
        $sql = "SELECT name, email, phone, about, gender, avatar, cover FROM user WHERE userId = ?";
        $stmt = $con->prepare($sql);

        if ($stmt) {
            $stmt->bind_param('i', $userId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $userDetails = $result->fetch_assoc();

                // Use printf to output the user details
                printf("
                    <table class='proDetails'>
                        <tr>
                            <td><b>Name:</b> %s</td>
                            <td><b>About:</b></td>
                            
                        </tr>
                        <tr>
                            <td><b>Email:</b> %s</td>
                            <td class='about' rowspan='3'>%s</td>
                        </tr>
                        <tr>
                            <td><b>Phone:</b> %s</td>

                        </tr>
                        <tr>
                            <td><b>Gender:</b> %s</td>

                        </tr>


                    </table>
                ", $userDetails['name'],
                   $userDetails['email'],
                   $userDetails['about'],
                   $userDetails['phone'],
                   $userDetails['gender'],
                   
                   
                        );
            } else {
                echo '<p>No user details found.</p>';
            }

            $stmt->close();
        } 

?>
          
                
                <form action="" method="POST" >
                    <div class="editPro" id="editPro" style="display: none;">
                        <hr>
                        <h3>Edit Profile</h3>
                        <hr>
                    <label>Name</label><br>
                    <input type="text" name="Username" id="Username" value="" placeholder="Please Insert Name" /><br><br>
                    <label>About</label><br>
                    <textarea id="about" name="about" rows="5" cols="80"></textarea><br><br>
                    <label>Gender</label><br>
                    <input type="text" name="gender" id="gender" maxlength="5" placeholder="Male / Female"  /><br><br>
                    
                    <h3>Contact</h3>
                    <label>Email</label><br>
                    <input type="email" name="email" value="" placeholder="xxx@xxx.com" /><br><br>
                    <label>Phone Number</label><br>
                    <input type="tel" name="phoneNum" value="" placeholder="###-### ####" /><br><br>
                    </div>
                    <br><br>
        <input class="btnSave bottomBtn" type="submit" value="Save" name="btnSave" />
        <input class="btnLogOut bottomBtn" type="submit" value="Log Out" name="btnLogOut" />
        <input class="btnDelete bottomBtn" type="submit" value="Delete Account" name="btnDeleteAcc" />
                </form>
            
</div>
    </div>
    
    
<?php




if (isset($_POST['btnSave'])) {
    $email = $_POST['email'];
    $name = $_POST['Username'];
    $about = $_POST['about'];
    $gender = $_POST['gender'];
    $phone = $_POST['phoneNum'];
    $userImgPath = $path;
    $userCoverPath = $coverPath;
    $userId = $_SESSION['logged']['id'];

    
                       
    
    if(empty($_POST['email'])){
        $email = $userDetails['email'];
    }
    elseif (empty($_POST['Username'])) {
    $name = $userDetails['name'];
}
    elseif (empty($_POST['phoneNum'])) {
    $name = $userDetails['phone'];
}
    elseif (empty($_POST['about'])) {
    $about = $userDetails['about'];
}
    elseif (empty($_POST['gender'])) {
    $gender = $userDetails['gender'];
}
    
    
    
    // Check if email is different from the one currently logged in
    if ($email != $_SESSION['logged']['email']) {
        // Check if email already exists
        $sql = "SELECT email FROM user WHERE email = ?";
        $stmt = $con->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                echo '<p style="color:red;">The email is already registered. Please use a different email.</p>';
                $stmt->close();
                $con->close();
                exit();
            }
            $stmt->close();
        }
    }

    // Update the user's information
    $sql = "UPDATE user SET name=?, email=?, phone=?, cover=?, about=?, gender=?, avatar=? WHERE userId=?";
    $stmt = $con->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('sssssssi', $name, $email, $phone, $userCoverPath, $about, $gender, $userImgPath, $userId);
        if ($stmt->execute()) {
            echo '<p style="color:green;">User information updated successfully.</p>';
        } else {
            echo '<p style="color:red;">Error updating user information: ' . $stmt->error . '</p>';
        }
        $stmt->close();
    } else {
        echo '<p style="color:red;">Error preparing statement: ' . $con->error . '</p>';
    }
       
    echo '<script>location.reload()<script>;';

}


// Check if the Delete Account button is clicked
    if (isset($_POST['btnDeleteAcc'])) {
        
        echo '<form method="GET">'
        .'<input type="submit" value="Cancel" name="btnCancel" onclick="cancel()" class="btnCancel" />'
        . '<input class="btnDeleteCon" id="btnDeleteCon" type="submit" value="Do you want to delete this Account?" name="btnDeleteCon" onclick="displayDelete" />'
                . '<form>';
         
         
        if(isset($_GET['btnDeleteCon'])){
   $userId = $_SESSION['logged']['id'];  

    // Delete the user's data
    $sql = "DELETE FROM user WHERE userId = ?";
    $stmt = $con->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('i', $userId);
        
        if ($stmt->execute()) {
            echo '<p style="color:green;">;Your account has been deleted successfully.</p>';

               session_destroy(); 
        } else {
            echo '<p style="color:red;">Error deleting account: ' . $stmt->error . '</p>';
        }

        $stmt->close();
    }

    }
    
    }



$con->close();
?>

    
    
    <script>
    function edit(){
       
         document.getElementById('editPro').style.display = 'block';
    }
    
    function displayDelete(){
        document.getElementById('btnDeleteCon').style.display = 'block';
        document.getElementById('btnCancel').style.display = 'block';
    }
    
    function cancel(){
        document.getElementById('btnDeleteCon').style.display = 'none';
        document.getElementById('btnCancel').style.display = 'none';
    }
    
    
    </script>

    <?php include '../general/adminFooter.php'; ?>


</body>
</html>
