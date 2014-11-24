<?php
/**
 * Created by PhpStorm.
 * User: Olivia
 * Date: 2014-11-23
 * Time: 1:34 PM
 */
include_once("includes/header.php");
$date_top = $_POST['date_top'];
$howmany = $_POST['howmany'];
$topView = "CREATE VIEW topsell AS SELECT TOP (@$howmany) i.upc, i.category, i.price, sum(quantity) AS total " .
    "FROM Purchase p, Item i, PurchaseItem pi " .
    "WHERE p.date = '$date_top' AND pi.upc = i.upc AND pi.receiptId = p.receiptId
         GROUP BY i.upc ORDER BY quantityDESC";

$topstmt = $connection->prepare($topView);
$topstmt->bind_param($)

?>

<!DOCTYPE html>
    <html>
<body>
<div id="login">
    <h1>Top Selling Items</h1>
    <form action="" method ="GET">
    <label>Date(YYYY-MM-DD)</label><br>
    <input id="text" name="date_top" value=""><br>
        <label>How many top selling items?</label><br>
        <input id="text" name="howmany" value=""><br>
        <input type="submit" value="submit">
    </form>
</div>
</body>
    </html>
