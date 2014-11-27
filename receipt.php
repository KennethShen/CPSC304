<?php
include_once("includes/connection.php");
include_once("includes/header.php");

if(isset($_GET['receiptId']) && is_numeric($_GET['receiptId'])){
    $sqlquery = "SELECT pi.upc AS upc, i.title, price, quantity, price * quantity AS subtotal ";
    $sqlquery .= "FROM PurchaseItem pi LEFT JOIN Item i ON pi.upc = i.upc ";
    $sqlquery .= "WHERE receiptId = ?";
    $stmt = $connection->prepare($sqlquery);
echo $stmt->error;
    $stmt->bind_param("i", $_GET['receiptId']);
    $stmt->execute();
        $result = $stmt->get_result();
    if ($result->num_rows){

        echo "<table>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>UPC</th>";
        echo "<th>Title</th>";
        echo "<th>Unit Price</th>";
        echo "<th>Units</th>";
        echo "<th>Price</th>";
        echo "</tr></thead><tbody>";
        $total = 0;
        while($row = $result->fetch_assoc()){
            echo "<tr>";
            echo "<td>".$row['upc']."</td>";
            echo "<td>".$row['title']."</td>";
            echo "<td>".$row['price']."</td>";
            echo "<td>".$row['quantity']."</td>";
            echo "<td>".$row['subtotal']."</td>";
            echo "</tr>";
            $total += $row['subtotal'];
        }
        echo "</tbody><tfoot><tr>";
        echo "<th colspan=4></th><th>------------</th>";
        echo "</tr><tr>";
        echo "<th colspan=4 style='text-align: right'>Total</th>";
        echo "<th>".$total."</td>";
    } else {
        echo "Receipt not found.";
    }
}
