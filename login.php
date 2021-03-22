<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
  header("location: scramble.php");
  exit;
}

 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";

 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Παρακαλώ εισάγετε το όνομα χρήστη.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Παρακαλώ εισάγετε τον κωδικό πρόσβασης.";
    } else{
        $password = trim($_POST["password"]);
    }
   

    // Validate credentials
    if(empty($username_err) && empty($password_err)){
    	if(($username == "ADMIN") && ($password=="1234")) {
                            	header("location: admin.php");
                           
                        }
        // Prepare a select statement
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
       
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $password);
                    
                    if(mysqli_stmt_fetch($stmt)){
                        
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                           
                            
                            // Redirect user to welcome page
                            header("location: scramble.php");
                    }else{
                            // Display an error message if password is not valid
                            $password_err = "Ο κωδικός πρόσβασης δεν είναι έγκυρος.";
                        }
                    	
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = "Δεν βρέθηκε λογαριασμός με το συγκεκριμένο όνομα χρήστη.";
                }
            } else{
                echo "Κάτι πήγε στραβά. Παρακαλώ δοκιμάστε αργότερα.";
            }
           
            // Close statement
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
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="stylesheet" href="mystyle.css">
    <style type="text/css">
        body{ 
            font: 16px Arial, sans-serif bold;
            background-image: url("back1.jpg");
            height: 100%;
            background-size: cover; }
        </style>
</head>
<body>
    <div class="wrapper">
        <h1 style="font-size:60px;">Scramble</h1>
        <h1>Είσοδος στο παιχνίδι</h1>
        <p>Παρακαλώ συμπληρώστε τα στοιχεία σας.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Όνομα χρήστη</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Κωδικός πρόσβασης</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Είσοδος">
            </div>
            <p>Δεν έχετε λογαριασμό? <a href="register.php">Εγγραφή στην εφαρμογή</a>.</p>
        </form>
    </div>  
</body>
</html>