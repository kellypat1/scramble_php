<?php
// Include config file
require_once "config.php";
error_reporting(E_ALL); ini_set('display_errors', 'on');
// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Παρακαλώ εισάγετε όνομα χρήστη.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "Το συγκεκριμένο όνομα χρήστη χρησιμοποιείται ήδη.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Κάτι πήγε στραβά. Παρακαλώ δοκιμάστε ξάνα!";

            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Παρακαλώ εισάγετε κωδικό πρόσβασης.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Ο κωδικός πρόσβασης πρέπει να περιλαμβάνει τουλάχιστον 6 χαρακτήρες!";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Επιβεβαιώστε τον κωδικό πρόσβασης που εισάγατε.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Οι κωδικοί πρόσβασης δεν ταιριάζουν μεταξύ τους.";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users VALUES (NULL,?,?,now(),0,0)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);

            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
               
                header("location: login.php");
            } else{
                echo "Κάτι πήγε στραβά. Παρακαλώ δοκιμάστε ξάνα!";
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
    <title>Sign Up</title>
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
        <h1>Εγγραφή στο παιχνίδι</h1>
        <p>Παρακαλώ συμπληρώστε την φόρμα για να δημιουργήσετε λογαριασμό.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Όνομα χρήστη</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Κωδικός πρόσβασης</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Επαλήθευση κωδικού πρόσβασης</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Υποβολή">
            </div>
            <p>Έχετε ήδη λογαριασμό; <a href="login.php">Είσοδος εδώ</a>.</p>
        </form>
    </div>    
</body>
</html>