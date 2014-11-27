
<nav>
<a href="index.php">Home</a> | 
<a href="shop.php">Shop</a> | 
<a href="inventory.php">Inventory</a>|
<a href="dailyReport.php">Daily Report</a> |
<a href="topSelling.php">Top Selling</a> |
<a href="return.php">Return</a> |
<a href="deliveryOrder.php">Delivery Date Change</a> |
<?php
if(!isset($_SESSION['user_id']) || !isset($_SESSION['username'])){
    echo '<a href="registration.php">Register</a> | ';
    echo '<a href="login.php">Login</a> | ';
} else {
    echo ' Hello '.$_SESSION['username'].' | ';
    echo '<a href="logout.php">Logout</a>';
}
?>
</nav>
