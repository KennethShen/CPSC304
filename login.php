<?php
/**
 * Created by PhpStorm.
 * User: Olivia
 * Date: 2014-11-13
 * Time: 8:34 PM
 */
require_once("includes/connection.php");
include_once("includes/header.php");
$error=''; //variable to store error message



if(isset($_POST['submit'])) {
    if(empty($_POST['username']) || empty($_POST['password'])) {
        $error = "Please type!";
    }

    else {
        //Define username and password
        $form_username = $_POST['username'];
        $form_password = $_POST['password'];

        $retrieved_password = null;
       //$check_query = "SELECT username, password FROM Customer WHERE username=? AND password=?";
        if ($log_check = $connection->prepare("SELECT cid, password FROM Customer WHERE username=?")) {
            $log_check->bind_param("s", $form_username);
            $log_check->execute();
            $log_check->bind_result($cid, $retrieved_password);
            $log_check->store_result();
            $log_check->fetch();
            $num = $log_check->num_rows;
            echo $num;

                // check password retrieved against user supplied password
                // use function password_verify( $password, $hash ) to check user inputted password vs hash
                if(password_verify($form_password, $retrieved_password)){
                    echo "yay";
                    $_SESSION['username'] = $form_username; //Initializing session
                    // Store cid on login.
                    $_SESSION['user_id'] = $cid;
                    header('Location: profile.php'); //Redirecting to other page
                    $log_check->free_result();

                }
                else {
                    $error = "Username or Password is invalid";
                }
            $log_check->close(); //Closing connection

        }


    }


}




?>


<!DOCTYPE html>
<html>
<head>
        <title>
                Login Form in PHP with Session
            </title>
        <link href = "style.css" rel = "stylesheet" type = "text/css">
    </head>
<body>
<div id="main">

        <div id="login">
            <h2>Login Form</h2>
            <form action="" method="post">
                <label>Username :</label><br>
                <input id="name" name="username" placeholder="username" type="text">
                <label>Password</label><br>
                <input id="password" name="password" placeholder="**********" type="password">
                <input name="submit" type="submit" value="Login ">
                <a href="/CPSC304/registration.php"><button type="button">Register</button></a>
                <span><?php echo $error; ?></span>
                    </form>
                </div>
    </div>
</body>
</html>

