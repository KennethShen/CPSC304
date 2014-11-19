<?php

    $dbhost = "localhost";
    $dbuser = "root";
    $dbpassword = "";
    $dbname = "CPSC304";


    global $connection;
    $connection = new mysqli($dbhost, $dbuser, $dbpassword, $dbname);

    // Check that the connection was successful, otherwise exit
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }

?>
