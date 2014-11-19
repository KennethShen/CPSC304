<?php
include_once("includes/header.php");
require_once("includes/connection.php");



//Display registration form
function register_form() {
    $date = date('D, M, Y');

    echo "<form action='?act=register' method='post'>"
    ."Username: <input type='text' name='username' size='30'><br>"
        ."Password: <input type='password' name='password' size='30'><br>"
        ."Confirm your password: <input type='password' name='password_conf' size='30'><br>"
        ."Name: <input type='text' name='name' size='30'><br>"
        ."Address: <input type='text' name='address' size='60'><br>"
        ."Phone Number: <input type='text' name='phone number' size='10'><br>"
        ."<input type='hidden' name='date' value='$date'>"
        ."<input type='submit' value='Register'>"
        ."</form>";
}

//Register user's data
function register() {
    //Connect to database
    $connection;

    if(!$connection) {
        die(mysql_error());
    }

    //Select database
    $select_db = mysql_select_db("CPSC304", $connection);

    if(!$select_db){
        die(mysql_error());
    }

    //Collecting info
    $username=$_POST['username'];
    $password=$_POST['password'];
    $pass_conf=$_POST['password_conf'];
    $name=$_POST['name'];
    $address=$_POST['address'];
    $phone=$_POST['phone number'];
    $date=$_POST['date'];

    //Check for empty fields
    if(empty($username)){
        die("Please enter your username!<br>");
    }

    if(empty($password)){
        die("Be safe and enter your password!<br>");
    }

    if(empty($pass_conf)){
        die("Enter your password again!<br>");
    }

    if(empty($name)){
        die("What's your name homie?<br>");
    }

    if(empty($address)){
        die("You got no home?<br>");
    }

    if(empty($phone_number)){
        die("Can I get your number?<br>");
    }

    //Check username is already in use
    $user_check=mysql_query("SELECT username FROM Customer WHERE username='$username'");
    $do_user_check = mysql_num_rows($user_check);

    if($do_user_check > 0){
        die("Username is already in use");
    }

    //Check if passwords match
    if($password != $pass_conf){
        die("Passwords don't match!");
    }

    //Now register if everything is okie
    $insert = mysql_query("INSERT INTO Customer(username, password, name, address, phone) VALUES ('$username', '$password','$name', '$address', '$phone')");

    if(!$insert){
        die("There is something wrong with registration");
    }

    echo $username.", you are now registered. Thank you!<br><a href=login.php>Login</a> | <a href=index.php>Index</a>";
}
$act="form";

if(isset($_GET['act'])){
    $act=$_GET['act'];
}



switch($act){
    case "form";
        register_form();
        break;

    case "register";
        register();
        break;
}

