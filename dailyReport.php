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
    $queryString = "SELECT i.upc, i.category, i.price, sum(quantity), sum(quantity)*i.price " .
        "FROM Purchase p, Item i, PurchaseItem pi " .
        "WHERE p.date = '$dateString' AND pi.upc = i.upc AND pi.receiptId = p.receiptId
         GROUP BY i.upc ORDER BY i.price*sum(quantity)";
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
        <th class=rowheader>UPC</th>
        <th class=rowheader>Category</th>
        <th class=rowheader>Unit Price</th>
        <th class=rowheader>Units</th>
        <th class=rowheader>Total Value</th>
    </tr>
    <?php
        $i = 0;
        while($row = $result->fetch_assoc() AND $i<10 ){
            echo "<tr>";
            echo "<td>".$row['upc']."</td>";
            echo "<td>".$row['category']."</td>";
            echo "<td>".$row['price']."</td>";
            echo "<td>".$row['sum(quantity)']."</td>";
            echo "<td>".$row['sum(quantity)*i.price']."</td>";
            echo "</tr>";
            $i += 1;
        }
    echo "</table>";
    mysqli_close($connection);
    ?>
</table>
</body>
</html>