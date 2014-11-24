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
    $queryString = "SELECT i.upc, i.category, i.price, sum(quantity), sum(quantity)*i.price AS total " .
        "FROM Purchase p, Item i, PurchaseItem pi " .
        "WHERE p.date = '$dateString' AND pi.upc = i.upc AND pi.receiptId = p.receiptId
         GROUP BY i.upc, i.category ORDER BY i.category, total DESC";
    if(!$result = $connection->query($queryString)){
        die('Error running the query.');
    }
}
if(($_POST["year"] == NULL) ||($_POST["month"]== NULL) || ($_POST["day"] == NULL)){
    header('location: /CPSC304/report.php');
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
    $totalCatUnit = 0;
    $totalCatSale = 0;
    $totalUnit = 0;
    $totalSale = 0;
    while($row = $result->fetch_assoc()){
        if($i == 0){
            $lastCat = $row['category'];
        }
        if($lastCat != $row['category']) {
            echo "<tr>";
            echo "<td>"."</td>";
            echo "<td>"."Total"."</td>";
            echo "<td>"."</td>";
            echo "<td>".$totalCatUnit."</td>";
            echo "<td>".$totalCatSale."</td>";
            $totalCatSale = 0;
            $totalCatUnit = 0;
        }
        echo "<tr>";
        echo "<td>".$row['upc']."</td>";
        echo "<td>".$row['category']."</td>";
        echo "<td>".$row['price']."</td>";
        echo "<td>".$row['sum(quantity)']."</td>";
        echo "<td>".$row['total']."</td>";
        echo "</tr>";
        $totalCatUnit += (double) $row['sum(quantity)'];
        $totalCatSale += (double) $row['total'];
        $lastCat = $row['category'];
        $i += 1;
        $totalUnit += (double) $row['sum(quantity)'];
        $totalSale += (double) $row['total'];
    }
    echo "<tr>";
    echo "<td>"."</td>";
    echo "<td>"."Total"."</td>";
    echo "<td>"."</td>";
    echo "<td>".$totalCatUnit."</td>";
    echo "<td>".$totalCatSale."</td>";
    $totalCatSale = 0;
    $totalCatUnit = 0;
    echo "<tr>";
    echo "<td>"."</td>";
    echo "<td>"."</td>";
    echo "<td>"."</td>";
    echo "<td>"."</td>";
    echo "<td>"."----------"."</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>"."</td>";
    echo "<td>"."Total Daily"."</td>";
    echo "<td>"."Sales"."</td>";
    echo "<td>".$totalUnit."</td>";
    echo "<td>".$totalSale."</td>";
    echo "</tr>";
    echo "</table>";
    mysqli_close($connection);
    ?>
</table>
</body>
</html>