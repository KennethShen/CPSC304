<?php
require_once("includes/connection.php");
include_once("includes/header.php");

if(mysqli_connect_errno()){
    printf("Connection fail: %s\n", mysqli_connect_errno());
    exit();
}
function getDailySalesReport($year, $month, $day) {
    global $connection;
    global $result;
    $dateString = $year."-".$month."-".$day;
    $date = date_create($dateString);
    $queryString = "SELECT i.upc, i.category, i.price, sum(quantity) " .
        "FROM Purchase p, Item i, PurchaseItem pi " .
        "WHERE p.date = '$dateString' AND pi.upc = i.upc AND pi.receiptId = p.receiptId
         GROUP BY i.upc";
    if(!$result = $connection->query($queryString)){
        die('Error running the query.');
    }
}
    getDailySalesReport($_POST["year"], $_POST["month"], $_POST["day"]);
?>
<html>
<body>
<table>
    <tr valign = center>
        <td class=rowheader>UPC</td>
        <td class=rowheader>Category</td>
        <td class=rowheader>Unit Price</td>
        <td class=rowheader>Units</td>
        <td class=rowheader>Total Value</td>
    </tr>
    <?php
        while($row = $result->fetch_assoc()){
            echo "<td>".$row['upc']."</td>";
            echo "<td>".$row['category']."</td>";
            echo "<td>".$row['price']."</td>";
            echo "<td>".$row['sum(quantity)']."</td>";
            echo "<td>".$row['upc']."</td>";
        }
    echo "</table>";
    mysqli_close($connection);
    ?>
</table>
</body>
</html>