<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
  header("location: welcome.php");
  exit;
}
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$phoneNo = $password = "";
$phoneNo_err = $password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if phoneNo is empty
    if(empty(trim($_POST["phoneNo"]))){
        $phoneNo_err = "Please enter phoneNo.";
    } else{
        $phoneNo = trim($_POST["phoneNo"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($phoneNo_err) && empty($password_err)){
        // Prepare a select statement
        //$sql = "SELECT id, phoneNo, password FROM users WHERE phoneNo = ?";
        //$sql = "SELECT id, phoneNo, firstName, password FROM users WHERE phoneNo = ?";
        $sql = "SELECT id, phoneNo, firstName, role, password FROM users WHERE phoneNo = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_phoneNo);
            
            // Set parameters
            $param_phoneNo = $phoneNo;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if phoneNo exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    //mysqli_stmt_bind_result($stmt, $id, $phoneNo, $hashed_password);
                    mysqli_stmt_bind_result($stmt, $id, $phoneNo, $firstName, $role, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["phoneNo"] = $phoneNo;
                            $_SESSION["firstName"] = $firstName;
                            $_SESSION["role"] = $role;
                            
                            // Redirect user to welcome page
                            header("location: welcome.php");
                        } else{
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else{
                    // Display an error message if phoneNo doesn't exist
                    $phoneNo_err = "No account found with that phoneNo.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GenPal Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link href="style.css" rel="stylesheet" type="text/css">
    <style type="text/css">
        body{ font: 14px helvetica; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
<div class="row" align="center">
        <div class="logo">
        <img src="logo.png">
        <label class="platform">GenPal</label>
        </div>
            
    <ul class="main-nav">    
        <li class="active"><a href=""> HOME </a></li>
        <li><a href="about.php"> ABOUT </a></li>
        <li><a href="contact.php"> CONTACT </a></li>
    </ul>    
        
</div>
<div align="center">
<div class="wrapper" align="center">
        <h2>GenPal Login</h2>
        <p>Please fill in your credentials to login.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($phoneNo_err)) ? 'has-error' : ''; ?>">
                <label>Phone Number</label>
                <input type="text" name="phoneNo" class="form-control" maxlength="10" value="<?php echo $phoneNo; ?>">
                <span class="help-block"><?php echo $phoneNo_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" maxlength="16" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
        </form>
    </div>
</div>
        
</body>
</html>