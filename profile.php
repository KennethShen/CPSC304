<?php
/**
 * Created by PhpStorm.
 * User: Olivia
 * Date: 2014-11-13
 * Time: 11:18 PM
 */
include('session.php');
?>

<!DOCTYPE html>
<html>
<head>
<title>Your Home Page</title>
    <link href="'style.css" ref="stylesheet" type="text/css">
</head>
<body>
<div id="profile">
    <b id="welcome">Welcome : <i><?php echo $login_session; ?></i></b>
    <b id="logout"><a href="logout.php"?Log Out></a></b>
</div>
</body>
</html>