<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <!--<style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
        .signout{
            padding-top: 15px;
            padding-right: 15px;
        }
        
    </style>
    -->
</head>
<body backgroun="https://wallpapercave.com/wp/tmx6W6N.png">
    <div class="signout" align="right">
        <a href="logout.php" class="btn btn-danger">Sign Out</a>
    </div>
    <h3>Hi <b><?php echo $_SESSION["firstName"]." (".$_SESSION["role"].")"; ?></b>. Welcome to GenPal!</h3>

    <p>
    <!--<a href="reset-password.php" class="btn btn-warning">Reset Your Password</a>
    <a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a>-->      
 
    <h1>Pals Around You</h1>
    <div id='listings' class='listings'></div>
    

    <div id="map" class="map pad2"></div>
    <script src="mapper.js"></script>
    <br>
    <?php include 'index.html';?>
    
</body>
</html>