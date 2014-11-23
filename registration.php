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
        ."Phone Number: <input type='text' name='phone_number' size='10'><br>"
        ."<input type='hidden' name='date' value='$date'>"
        ."<input type='submit' value='Register'>"
        ."</form>";
}

//Register user's data
function register() {
    //Connect to database
    global $connection;

    //Collecting info
    $username=$_POST['username'];
    $password=$_POST['password'];
    $pass_conf=$_POST['password_conf'];
    $name=$_POST['name'];
    $address=$_POST['address'];
    $phone_number=$_POST['phone_number'];
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

    $user_result = null;
    $password_result = null;
    if ($user_stmt = $connection->prepare("SELECT username, password FROM Customer WHERE username=?")){
        $user_stmt->bind_param("s", $username);
        $user_stmt->execute();
        $user_stmt->bind_result($user_result, $password_result);
        $user_stmt->fetch();
        $user_stmt->close();
    }

    if($user_result != null) {
        die("Username is already in use");
    }

    //Check if passwords match
    if($password != $pass_conf){
        die("Passwords don't match!");
    }

    //Now register if everything is okie
    $password = password_hash($password, PASSWORD_BCRYPT);
    $insert_stmt = $connection->prepare("INSERT INTO Customer(username, password, name, address, phone) VALUES (?,?,?,?,?)");
    if($insert_stmt) {
        $insert_stmt->bind_param("sssss", $username, $password, $name, $address, $phone_number);
        $insert_stmt->execute();
        $insert_stmt->close();
    }
    echo mysqli_insert_id($connection);
    if (mysqli_insert_id($connection) > -1) {
        echo $username.", you are now registered. Thank you!<br><a href=logout.php>Logout</a> | <a href=index.php>Index</a>";
    } else echo "An error has occured. Please call help\n";

    $connection->close();

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

