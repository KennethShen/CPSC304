<?php
include_once("includes/connection.php");
include_once("includes/header.php");

if(isset($_GET['receiptId']) && is_numeric($_GET['receiptId'])){

    $stmt = $connection->prepare("SELECT * FROM Purchase WHERE receiptId = ?");
    $stmt->bind_param('i', $_GET['receiptId']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0){
        $_SESSION['alert']['error'] = "Receipt ID ".$_GET['receiptId']." not valid.";
        header("Location: receipt.php");
    }

    $receipt = $result->fetch_assoc();
    echo "<p><table>";
    echo "<tr><th>Receipt ID</th><td>".$receipt['receiptId']."</td></tr>";
    echo "<tr><th>Purchase Date</th><td>".$receipt['date']."</td></tr>";
    echo "<tr><th>Expected Date</th><td>".$receipt['expectedDate']."</td></tr>";
    echo "<tr><th>Delivered Date</th><td>";
    if (is_null($receipt['deliveredDate'])){
        echo "N/A";
    } else {
        echo $receipt['deliveredDate'];
    }
    echo "</td></tr>";
    echo "</table><p>";

    $sqlquery = "SELECT pi.upc AS upc, i.title, type, category, company, year, price, quantity, price * quantity AS subtotal ";
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
        echo "<th>Type</th>";
        echo "<th>Category</th>";
        echo "<th>Company</th>";
        echo "<th>Year</th>";
        echo "<th>Unit Price</th>";
        echo "<th>Units</th>";
        echo "<th>Price</th>";
        echo "</tr></thead><tbody>";
        $total = 0;
        while($row = $result->fetch_assoc()){
            echo "<tr>";
            echo "<td>".$row['upc']."</td>";
            echo "<td>".$row['title']."</td>";
            echo "<td>".$row['type']."</td>";
            echo "<td>".$row['category']."</td>";
            echo "<td>".$row['company']."</td>";
            echo "<td>".$row['year']."</td>";
            echo "<td>".$row['price']."</td>";
            echo "<td>".$row['quantity']."</td>";
            echo "<td>".$row['subtotal']."</td>";
            echo "</tr>";
            $total += $row['subtotal'];
        }
        echo "</tbody><tfoot><tr>";
        echo "<th colspan=8></th><th>------------</th>";
        echo "</tr><tr>";
        echo "<th colspan=8 style='text-align: right'>Total</th>";
        echo "<th>".$total."</td>";
    } 
} else {
        echo "Receipt ID not valid.";
}
