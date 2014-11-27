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
} else if(!ctype_digit($_POST["cid"]) || !ctype_digit($_POST["receiptId"]) || !ctype_digit($_POST["UPC"]) || !ctype_digit($_POST["returnQuantity"]) ){
    if(!ctype_digit($_POST["cid"])){
        echo "Invalid Customer ID: Only numbers allowed <br>";
    }
    if(!ctype_digit($_POST["receiptId"])){
        echo "Invalid Receipt ID: Only numbers allowed <br>";
    }
    if(!ctype_digit($_POST["UPC"])){
        echo "Invalid UPC: Only numbers allowed <br>";
    }
    if(!ctype_digit($_POST["returnQuantity"])){
        echo "Invalid Quantity: Only numbers allowed <br>";
    }
} else {

    $cid = intval($_POST["cid"]);
    $rid = intval($_POST["receiptId"]);
    $upc = intval($_POST["UPC"]);
    $quantity = intval($_POST["returnQuantity"]);
    $queryString = "SELECT pi.receiptId, pi.quantity, p.date, i.price FROM Purchase p, PurchaseItem pi, Item i WHERE p.receiptId = pi.receiptId" .
        " AND i.upc = pi.upc AND pi.upc = ? AND pi.receiptId = ? AND p.cid = ?";
    $stmt = $connection->prepare($queryString);
    $stmt->bind_param("iii", $upc, $rid, $cid);
    $stmt->execute();
    $result = $stmt->get_result();
    $search = $result->fetch_assoc();
    if ($search == NULL) {
        echo "<br>No purchase found for the given UPC, Receipt ID, and Customer ID.<br>";
    } else {
        $amountReturned = $quantity * $search['price']; 
        $daysAgo = new DateTime();
        $receiptDate = date_create($search["date"]);
        $interval = $daysAgo->diff($receiptDate);
        if($interval->d < 15){
            if ($quantity > $search['quantity']) {
                echo "Returning quantity is greater than purchased quantity.<br>";
            } else if ($quantity < 1) {
                echo "Please enter a quantity larger than 0.<br>";
            } else {
                $checkString = "SELECT * FROM Returned r, ReturnItem ri WHERE r.retId = ri.retId AND r.receiptId = ? AND ri.upc = ?";
                $checkStmt = $connection->prepare($checkString);
                echo $connection->error;
                $checkStmt->bind_param("ii", $rid, $upc);
                $checkStmt->execute();
                $result = $checkStmt->get_result();
                $search = $result->fetch_assoc();
                if($search != NULL){
                    echo "You've returned the product in this receipt already<br>";
                } else {
                    $returnString = "INSERT INTO Returned (retid, date, receiptId) VALUES (NULL,CURRENT_DATE(),?)";
                    $retSt = $connection->prepare($returnString);
                    $retSt->bind_param("i", $rid);
                    $retSt->execute();
                    $returnID = $connection->insert_id;
                    echo "<br>Your Return ID  is: " . $returnID . "<br>";
                    $returnItemString = "INSERT INTO ReturnItem(retid, upc, quantity) VALUES (?,?,?)";
                    $riStmt = $connection->prepare($returnItemString);
                    $riStmt->bind_param("iii", $returnID, $upc, $quantity);
                    $riStmt->execute();
                    echo "The refund amount ".$amountReturned." has been issued to your credit card.<br>";
                }
            }
        } else {
            echo "The purchase was made more than 15 days ago. <br>";
        }
    }
}
?>
</body>
</html>
