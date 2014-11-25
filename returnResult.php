<?php

    require_once("includes/connection.php");
    include_once("includes/header.php");
    $cid = $_POST["cid"];
    $rid = $_POST["receiptId"];
    $upc = $_POST["UPC"];
    $quantity = $_POST["returnQuantity"];
    $queryString = "SELECT pi.receiptId, pi.quantity, p.date FROM Purchase p, PurchaseItem pi, Item i WHERE p.receiptId = pi.receiptId" .
                   " AND i.upc = pi.upc AND pi.upc = ? AND pi.receiptId = ? AND p.cid = ? AND p.date > CURRENT_DATE() + 15";
    $stmt = $connection->prepare($queryString);
    $stmt->bind_param("iii", $upc, $rid, $cid);
    $stmt->execute();
    $result = $stmt->get_result();
    $search = $result->fetch_assoc();
    if($search == NULL){
        echo "The Purchase Date is more than 15 days ago or No purchase found for given UPC, Receipt ID, and Customer ID";
    } else {
        if($quantity > $search['quantity']){
            echo "Returning quantity is greater than purchased quantity.";
        } else if ($quantity < 1) {
            echo "Please enter quantity larger than 0";
        } else  {
            $returnString = "INSERT INTO returned (retid, date, receiptId) VALUES (NULL,CURRENT_DATE(),?)";
            $retSt = $connection->prepare($returnString);
            $retSt->bind_param("i", $rid);
            $retSt->execute();
            $returnID= $connection->insert_id;
            echo "Your Return ID  is: " .$returnID;
            $returnItemString = "INSERT INTO returnItem(retid, upc, quantity) VALUES (?,?,?)";
            $riStmt = $connection->prepare($returnItemString);
            $riStmt->bind_param("iii", $returnID, $upc, $quantity);
            $riStmt->execute();
            echo "\r\nThe fund amount has been issued to your credit card.\r\n";
            $updateString = "UPDATE PurchaseItem SET receiptId = receiptId, upc = upc, quantity = ? WHERE receiptId = ? AND upc = ?";
            $newQuantity = $search['quantity'] - $quantity;
            $updateStmt = $connection->prepare($updateString);
            $updateStmt->bind_param('iii', $newQuantity, $rid, $upc);
            $updateStmt->execute();
        }
    }

?>