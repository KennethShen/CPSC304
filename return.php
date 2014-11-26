<?php
require_once("includes/connection.php");
include_once("includes/header.php");

?>

<body>
<br>
<form action = "" method = "post">
    Customer ID: <input type = "text" name = "cid"><br>
    Receipt ID: <input type = "text" name = "receiptId"><br>
    Item UPC  : <input type = "text" name = "UPC"><br>
    Quantity  : <input type = "text" name = "returnQuantity"><br>
    <input type = "submit">
</form>
<?php
echo "Please enter your Customer ID, Receipt ID, UPC of the item you wish to return, <br> and the quantity of the item that you are returning.<br>";
if(!isset($_POST["cid"]) || !isset($_POST["receiptId"]) || !isset($_POST["UPC"]) || !isset($_POST["returnQuantity"])){
} else {
    $cid = intval($_POST["cid"]);
    $rid = intval($_POST["receiptId"]);
    $upc = intval($_POST["UPC"]);
    $quantity = intval($_POST["returnQuantity"]);
    $queryString = "SELECT pi.receiptId, pi.quantity, p.date FROM Purchase p, PurchaseItem pi, Item i WHERE p.receiptId = pi.receiptId" .
        " AND i.upc = pi.upc AND pi.upc = ? AND pi.receiptId = ? AND p.cid = ?";
    $stmt = $connection->prepare($queryString);
    $stmt->bind_param("iii", $upc, $rid, $cid);
    $stmt->execute();
    $result = $stmt->get_result();
    $search = $result->fetch_assoc();
    if ($search == NULL) {
        echo "<br>No purchase found for the given UPC, Receipt ID, and Customer ID.<br>";
    } else {
        $queryString = "SELECT pi.receiptId, pi.quantity, p.date FROM Purchase p, PurchaseItem pi, Item i WHERE p.receiptId = pi.receiptId" .
            " AND i.upc = pi.upc AND pi.upc = ? AND pi.receiptId = ? AND p.cid = ? AND p.date > CURRENT_DATE() - 15";
        $stmt = $connection->prepare($queryString);
        $stmt->bind_param("iii", $upc, $rid, $cid);
        $stmt->execute();
        $result = $stmt->get_result();
        $search = $result->fetch_assoc();
        if($search != NULL){
            if ($quantity > $search['quantity']) {
                echo "Returning quantity is greater than purchased quantity.<br>";
            } else if ($quantity < 1) {
                echo "Please enter a quantity larger than 0.<br>";
            } else {
                $returnString = "INSERT INTO returned (retid, date, receiptId) VALUES (NULL,CURRENT_DATE(),?)";
                $retSt = $connection->prepare($returnString);
                $retSt->bind_param("i", $rid);
                $retSt->execute();
                $returnID = $connection->insert_id;
                echo "<br>Your Return ID  is: " . $returnID . "<br>";
                $returnItemString = "INSERT INTO returnItem(retid, upc, quantity) VALUES (?,?,?)";
                $riStmt = $connection->prepare($returnItemString);
                $riStmt->bind_param("iii", $returnID, $upc, $quantity);
                $riStmt->execute();
                echo "The refund amount has been issued to your credit card.<br>";
                $updateString = "UPDATE PurchaseItem SET receiptId = receiptId, upc = upc, quantity = ? WHERE receiptId = ? AND upc = ?";
                $newQuantity = $search['quantity'] - $quantity;
                $updateStmt = $connection->prepare($updateString);
                $updateStmt->bind_param('iii', $newQuantity, $rid, $upc);
                $updateStmt->execute();
            }
        } else {
            echo "The purchase was made more than 15 days ago. <br>";
        }
    }
}
?>
</body>
</html>
