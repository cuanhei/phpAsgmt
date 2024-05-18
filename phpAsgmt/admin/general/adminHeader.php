
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
                border-radius: 15px;
            }                       
        </style>
    </head>
    <body>

        <header>
            <div class="header">
            <a href="../profile/adminProfile.php" class="left"><b>Events4you</b></a>
  
            <a href="../reservation/reservation.php" class="right">Reservation</a>
            <a href="../member/member.php" class="right">Member</a>
            <a href="../hall/hall.php" class="right">Halls</a>
            <a href="../event/event.php" class="right">Events</a>
            <a href="../profile/adminProfile.php" class="right">My Profile</a>
            </div>
        
        </header>
      
    </body>
</html>