<?php
/**
 * Created by PhpStorm.
 * User: Olivia
 * Date: 2014-11-13
 * Time: 11:44 PM
 */
session_start();

//Destroying all session
if(session_destroy()) {
    $_SESSION['user_id'] = NULL;
    header("Location: login.php"); //Redirecting to Home Page
}
