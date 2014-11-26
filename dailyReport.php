<html>
<body>
<form action = "" method = "post">
    Year : <input type = "text" name = "year">
    Month: <input type = "text" name = "month">
    Day  : <input type = "text" name = "day">
    <input type = "submit">
</form>
<?php
require_once("includes/connection.php");
include_once("includes/header.php");
if(mysqli_connect_errno()){
    printf("Connection fail: %s\n", mysqli_connect_errno());
    exit();
}

?>
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

    if((!isset($_POST["year"])) ||(!isset($_POST["month"])) || (!isset($_POST["day"]))){
        Echo "Please specify a date.";
    } else {
    $year = $_POST["year"];
    $month = $_POST["month"];
    $day = $_POST["day"];
    $dateString = $year."-".$month."-".$day;
    $queryString = "SELECT i.upc, i.category, i.price, sum(quantity), sum(quantity)*i.price AS total " .
        "FROM Purchase p, Item i, PurchaseItem pi " .
        "WHERE p.date = ? AND pi.upc = i.upc AND pi.receiptId = p.receiptId
         GROUP BY i.upc, i.category ORDER BY i.category, total DESC";
    $stmt = $connection->prepare($queryString);
    $stmt->bind_param("s", $dateString);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result == NULL){
        die('Error running the query.');
    }
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
    }
    ?>
</table>
</body>
</html>