<?php
/**
 * Created by PhpStorm.
 * User: Olivia
 * Date: 2014-11-13
 * Time: 8:34 PM
 */
require_once("includes/connection.php");
include_once("includes/header.php");
session_start(); //starting session
$error=''; //variable to store error message



if(isset($_POST['submit'])) {
    if(empty($_POST['username']) || empty($_POST['password'])) {
        $error = "Username or Password is invalid";
    }

    if(!ctype_digit($_POST['username'])) {
        $error = "Invalid username. Input numbers";
    }
    else {
        //Define username and password
        $username = $_POST['username'];
        $password = $_POST['password'];

        try {
            //Establish connection with server by passing server_name, user_id, pw as param
            //$connection = mysql_connect("localhost", "root", "");

            //Protect MySql injection for security
            $username = stripslashes($username);
            $password = stripslashes($password);
            $username = mysql_real_escape_string($username);
            $password = mysql_real_escape_string($password);

            $sql = "INSERT INTO Customer(cid, password, name, address, phone) VALUES(" . $username . ", " . $password . ", null, null, null)";
            $connection->exec($sql);
            echo "New registration created successfully";
        }

        catch(PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }

        //Select database
        $db = mysql_select_db("CPSC304", $connection);

        //SQL query to fetch info of registered users and find user match
        $query = mysql_query("select * from login where username='$username' AND password='$password'", $connection);
        $rows = mysql_num_rows($query);
        if($rows == 1) {
            $_SESSION['login_user']=$username; //Initializing session
            header("location : profile.php"); //Redirecting to other page
        }
        else {
            $error = "Username or Password is invalid";
        }
        mysql_close($connection); //Closing connection
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
        <h1>PHP login session</h1>
        <div id="login">
            <h2>Login Form</h2>
            <form action="" method="post">
                <label>Username :</label><br>
                <input id="name" name="username" placeholder="username" type="text">
                <label>Password</label><br>
                <input id="password" name="password" placeholder="**********" type="password">
                <input name="submit" type="submit" value="Login ">
                <a href="/CPSC304/includes/registration.php"><button type="button">Register</button></a>
                <span><?php echo $error; ?></span>
                    </form>
                </div>
    </div>
</body>
</html>

