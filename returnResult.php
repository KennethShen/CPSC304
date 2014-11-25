<?php

    require_once("includes/connection.php");
    include_once("includes/header.php");
    $cid = $_POST["cid"];
    $rid = $_POST["receiptId"];
    $upc = $_POST["UPC"];
    $quantity = $_POST["returnQuantity"];
    $queryString = "SELECT pi.receiptId, pi.quantity, p.date FROM Purchase p, PurchaseItem pi, Item i WHERE p.receiptId = pi.receiptId" .
                   " AND i.upc = pi.upc AND pi.upc = ? AND pi.receiptId = ? AND p.cid = ?";
    $stmt = $connection->prepare($queryString);
    $stmt->bind_param("iii", $upc, $rid, $cid);
    $stmt->execute();
    $result = $stmt->get_result();
    $search = $result->fetch_assoc();
    if($search == NULL){
        echo "No purchase found base on the Customer ID, Receipt ID, and UPC";
    } else {
        if($quantity > $search['quantity']){
            echo "Returning quantity is greater than purchased quantity.";
        } else {

        }
    }

?>