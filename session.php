<?php
/**
 * Created by PhpStorm.
 * User: Olivia
 * Date: 2014-11-13
 * Time: 11:23 PM
 */
//Establishing connection with server by passing server_name, user_id, password as param
$connection = mysql_connect("localhost", "root", "");

//Selecting database
$db = mysql_select_db("CPSC304", $connection);

//Starting session
session_start();

//Storing session
$user_check = $_SESSION['login_user'];

//SQL Query to to fetch complete info of user
//$ses_sql = mysql_query("select username from login where username='$user_check'", $connection);
//$row = mysql_fetch_assoc($ses_sql);
//$login_session = $row['username'];
//if(!isset($login_session)) {
//    mysql_close($connection); //Closing connection
//    header('Location: index.php'); //Redirecting to Home Page
//}
