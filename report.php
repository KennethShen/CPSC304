<script>
    function getDailySalesReport($year, $month, $day){
        var $dateString = $year + "-" + $month + "-" + $day;
        var $date = new Date('Y-m-d', strtotime($dateString));
        var $queryString = "SELECT upc, category, price, quantity " +
        "FROM Purchase p, Item i, PurchaseItem pi" +
        "WHERE Date(date) = '{$date]' ORDER BY upc"
        if(!$result = $connection->query($queryString)){
            die('Error running the query: '. $db->error. '.');
        }
    }
</script>

<?php
require_once("includes/connection.php");
include_once("includes/header.php");

if(mysqli_connect_errno()){
    printf("Connection fail: %s\n", mysql_connect_errno());
    exit();
}

?>
