<?php
require '../../database/database.php'; 


 $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$sql = "SELECT * FROM user";
$result = $con->query($sql);

$users = [];
if ($result->num_rows > 0) {
    $users = $result->fetch_assoc();
}


if (isset($_POST['btnDeleteAcc'])) {
    $userId = $_POST['userId'];

    // Prepare the DELETE statement
    $stmt = $con->prepare("DELETE FROM user WHERE userId = ?");
    $stmt->bind_param('i', $userId);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Record deleted successfully.";
    } else {
        echo "Error deleting record: " . $con->error;
    }

    $stmt->close();
}

// Fetch all user details
$sql = "SELECT * FROM user";
$result = $con->query($sql);

$users = [];
if ($result && $result->num_rows > 0) {
    $users = $result->fetch_object();
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Members Details | Event4you</title>
    <link href="member.css" rel="stylesheet" type="text/css"/>
</head>
<body>
    
        <?php 
    include '../general/adminHeader.php'; 
?>
    
    
    <h1 style="margin-top:100px ">User Details</h1>
    
    <table class="memberDetails">
        <tr>
            <th>User Id</th>
            <th>Name</th>
            <th>Email</th>
            <th>Gender</th>
            <th>About</th>
            <th>Delete</th>
            
        </tr>
    <?php
    if($result -> num_rows >0){
                        //record found
                        //why while loop? while i can still access th record,
                        //we will retreive
                        //fetch_object() - take record 1 by 1 form $result
                        while($row = $result->fetch_object()){
                            printf("
                                   <tr>
                                   <td>%s</td>
                                   <td>%s</td>
                                   <td>%s</td>
                                   <td>%s</td>
                                   <td>%s</td>
                                   
                                   <td>
                                   <form action='' method=POST>
                                   <input type='hidden' name='userId' value='%s' />
                                   <input class='btnDelete' type='submit' value='Delete Account' name='btnDeleteAcc' />
                                    </form>
                                   </td>

                                    </tr> 
                                   ",$row->userId 
                                    ,$row->name
                                    ,$row->email
                                    ,$row->gender
                                    ,$row->about 
                                    ,$row->phone 
                                    ,$row->userId
                                            );
                        }
                        
                        
                        $result->free();
                        $con->close();

                    }
                        ?>
    </table>
                      
    <script>
    document.querySelectorAll('.btnDelete').forEach(button => {
        button.addEventListener('click', function(event) {
            if (!confirm('Are you sure you want to delete this account?')) {
                event.preventDefault();
            }
        });
    });
</script>

    
    <?php include '../general/adminFooter.php';  ?>
</body>
</html>
