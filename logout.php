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
    // Clear the session variables.
    session_unset();
    header("Location: login.php"); //Redirecting to Home Page
}
