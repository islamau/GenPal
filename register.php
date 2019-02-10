<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$phoneNo = $password = $confirm_password = "";
$phoneNo_err = $password_err = $confirm_password_err = $firstName_err = $lastName_err = $role_err = $interest_err = $postalCode_err = $meetupMethod_err = "";
$firstName = $lastName = $role = $interest = $postalCode = $meetupMethod = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate phoneNo
    if(empty(trim($_POST["phoneNo"]))){
        $phoneNo_err = "Please enter a phoneNo.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE phoneNo = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_phoneNo);
            
            // Set parameters
            $param_phoneNo = trim($_POST["phoneNo"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $phoneNo_err = "This phoneNo is already taken.";
                } else{
                    $phoneNo = trim($_POST["phoneNo"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    //-------------------------------------------------------
    // first name
    if(empty(trim($_POST["firstName"]))) {
        $firstName_err = "Please enter your first name.";  
    } else {
        $firstName = trim($_POST["firstName"]);
    }

    // last name
    if(empty(trim($_POST["lastName"]))) {
        $lastName_err = "Please enter your last name.";  
    } else {
        $lastName = trim($_POST["lastName"]);
    }

    // role
    if(empty($_POST["lastName"])) {
        $role_err = "Please select a role.";  
    } else {
        $role = $_POST["role"];
    }

    // user interests
    if(empty($_POST["interest"])) {
        $interest_err = "Please select an interest.";  
    } else {
        $interest = $_POST["interest"];
    }

    // postal codes
    if(empty($_POST["postalCode"])) {
        $postalCode_err = "Please enter a postal code.";  
    } else {
        $postalCode = $_POST["postalCode"];
    }

    // meetup method
    if(empty($_POST["meetupMethod"])) {
        $meetupMethod_err = "Please enter your preferred method of meetup.";  
    } else {
        $meetupMethod = $_POST["meetupMethod"];
    }
    

    //-------------------------------------------------------
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

    
    
    // Check input errors before inserting in database
    if(empty($phoneNo_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (phoneNo, firstName, lastName, role, interest, postalCode, meetupMethod, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssssss", $param_phoneNo, $param_firstName, $param_lastName, $param_role, $param_interest, $param_postalCode, $meetupMethod, $param_password);
            
            // Set parameters
            $param_phoneNo = $phoneNo;
            $param_firstName = $firstName;
            $param_lastName = $lastName;
            $param_role = $role;
            $param_interest = $interest;
            $param_postalCode = $postalCode;
            $param_meetupMethod = $meetupMethod;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            //$param_password = $password;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }

        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }

    if ($role == "senior") {
        $sql = "INSERT INTO senior (phoneNo, firstName, lastName) VALUES (?, ?, ?)";
        if($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "sss", $param_phoneNo, $param_firstName, $param_lastName);
            $param_phoneNo = $phoneNo;
            $param_firstName = $firstName;
            $param_lastName = $lastName;
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    } else if ($role == "volunteer") {
        $sql = "INSERT INTO volunteer (phoneNo, firstName, lastName) VALUES (?, ?, ?)";
        if($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "sss", $param_phoneNo, $param_firstName, $param_lastName);
            $param_phoneNo = $phoneNo;
            $param_firstName = $firstName;
            $param_lastName = $lastName;
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
        .platform
        {
    font: 400 15px/22px 'Source Sans Pro', 'Helvetica Neue', Sans-serif;
    font-size:60px;
    font-style: oblique;
    -webkit-font-smoothing:antialiased;
}
    </style>
</head>
<body background=https://wallpapercave.com/wp/tmx6W6N.png>
    <br>
    <div class="row" align="center">
        <div class="logo">
        <img src="logo.png">
        <label class="platform">GenPal</label>
        </div>
    
    </div>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($phoneNo_err)) ? 'has-error' : ''; ?>">
                <label>Phone Number</label>
                <input type="text" name="phoneNo" class="form-control" maxlength="10" value="<?php echo $phoneNo; ?>">
                <span class="help-block"><?php echo $phoneNo_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($firstName_err)) ? 'has-error' : ''; ?>">
                <label>First Name</label>
                <input type="text" name="firstName" class="form-control" maxlength="25" value="<?php echo $firstName; ?>">
                <span class="help-block"><?php echo $firstName_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($lastName_err)) ? 'has-error' : ''; ?>">
                <label>Last Name</label>
                <input type="text" name="lastName" class="form-control" maxlength="25" value="<?php echo $lastName; ?>">
                <span class="help-block"><?php echo $lastName_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($role_err)) ? 'has-error' : ''; ?>">
                <label>Role</label><br>
                <input type="radio" name="role" value="senior" checked="true">Senior
                <input type="radio" name="role" value="volunteer">Volunteer
                <span class="help-block"><?php echo $role_err; ?></span>
            </div>
            <div class="form-group" <?php echo (!empty($interest_err)) ? 'has-error' : ''; ?> >
                <label>Choose your interests</label><br>
                <input type="checkbox" name="interest" value="reading"> Reading<br>
                <input type="checkbox" name="interest" value="board_games"> Board Games<br>
                <input type="checkbox" name="interest" value="movies"> Movies<br>
                <input type="checkbox" name="interest" value="baking"> Baking/Cooking<br>
                <span class="help-block"><?php echo $interest_err; ?></span>
            </div>
            <div class="form-group" <?php echo (!empty($postalCode_err)) ? 'has-error' : ''; ?> >
                <label>Postal Code</label><br>
                <input type="text" name="postalCode" maxlength="6">
                <span class="help-block"><?php echo $postalCode_err; ?></span>
            </div>
            <div class="form-group" <?php echo (!empty($meetupMethod_err)) ? 'has-error' : ''; ?> >
                <label>Meetup Option(s)</label><br>
                <input type="checkbox" name="meetupMethod" value="travel">Willing to Travel<br>
                <input type="checkbox" name="meetupMethod" value="home">Stay at Home<br>
                <span class="help-block"><?php echo $meetupMethod_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <h4>Already have an account? <a href="login.php" style="color: black;">Login here</a>.</h4>
        </form>
    </div>    
</body>
</html>