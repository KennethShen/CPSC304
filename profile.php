<?php
/**
 * Created by PhpStorm.
 * User: Olivia
 * Date: 2014-11-13
 * Time: 11:18 PM
 */
include_once("includes/header.php");
?>

<!DOCTYPE html>
<html>
<head>
<title>Your Home Page</title>
    <link href="'style.css" ref="stylesheet" type="text/css">
</head>
<body>
<div id="profile">
    <p id="welcome">Welcome : <i><?php echo $_SESSION['username']; ?></i></p>
    <p><a href="logout.php"><button type="button">LOG OUT</button></a></p>
</div>
</body>
</html>
