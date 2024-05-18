
<html>
    <head>
        <meta charset="UTF-8">
        <style>
            *{
                font-family: arial;
            }
            .header{
                position:absolute;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                width: 100%;
                height: 50px;
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
                border: none;
                border-radius: 3px;
                font-size: 16px;
                cursor: pointer;
                position: relative;
                transition: all 0.2s ease-in-out;
            }                       
        </style>
    </head>
    <body>

        <header>
            <div class="header">
            <a href="../home/index.php" class="left"><b>Events4you</b></a>
            
            <?php 

            // Get the current request URI
            $urlName = $_SERVER['REQUEST_URI'];

                       
            // Check if the current URI matches Sign Up or Login URIs
            if(strpos($urlName, 'login') !== false){
                echo '<a href="signUp.php" class="right profile" style="color:black;">Sign Up</a>';
            }
            elseif(strpos($urlName, 'signUp') !== false){
                echo '<a href="login.php" class="right profile" style="color:black;">Login</a>'; 
            }
            else if (isset($_SESSION["logged"])){
                echo '<a href="../profile/profile.php" class="right" >Profile</a>'; 
            }
            else{
                echo '<a href="../profile/signUp.php" class="right profile" style="color:black;">Login / Sign Up</a>'; 
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
      
    </body>
</html>