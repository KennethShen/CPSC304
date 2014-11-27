<?php
require_once("connection.php");
include_once("header.php");

?>

<body>
<br>
<form action = "" method = "post">
    Receipt ID: <input type = "text" name = "ReceiptID"><br><br>
    Year: <input type = "text" name = "Year">
    Month: <input type = "text" name = "Month">
    Day: <input type = "text" name = "Day">
    <input type = "submit">
</form>

<?php
    if(isset($_POST["ReceiptID"]) && !empty($_POST["Year"]) && !empty($_POST["Month"]) && !empty($_POST["Day"])){
        $rID = $_POST["ReceiptID"];
        $curDate = new DateTime();
        if(!ctype_digit($_POST["Year"]) || !ctype_digit($_POST["Month"]) || !ctype_digit($_POST["Day"])){
            if(!ctype_digit($_POST["Year"])){
                echo "Invalid Year: Only numbers allowed <br>";
            }
            if(!ctype_digit($_POST["Month"])){
                echo "Invalid Month: Only numbers allowed <br>";
            }
            if(!ctype_digit($_POST["Day"])){
                echo "Invalid Day: Only numbers allowed <br>";
            }
        } else {
            if(checkdate(intval($_POST["Month"]), intval($_POST["Day"]), intval($_POST["Year"]))) {
                $stringDate = $_POST["Year"] . "-" . $_POST["Month"] . "-" . $_POST["Day"];
                $enteredDate = date_create($stringDate);
                $dif = $enteredDate->diff($curDate);
                if ($dif->d >= 0) {
                    $queryString = "UPDATE purchase SET deliveredDate = ? WHERE receiptId = ?";
                    $updateStmt = $connection->prepare($queryString);
                    $updateStmt->bind_param("si", $stringDate, $rID);
                    $updateStmt->execute();
                    $query = "SELECT receiptId, deliveredDate FROM purchase WHERE receiptId = ?";
                    $stmt = $connection->prepare($query);
                    $stmt->bind_param("i", $rID);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result == NULL) {
                        echo "No purchase found with given receipt ID.<br>";
                    } else {
                        echo "Delivery Date successful updated. <br>";
                    }
                } else {
                    echo " Please choose a future date. <br>";
                }
            } else {
                echo "Please enter a valid date.<br>";
            }
        }
    }
    if(!isset($_POST["ReceiptID"]) && !isset($_POST["Year"]) && !isset($_POST["Month"]) && !isset($_POST["Day"])){
        echo "Enter the Receipt ID.<br>";
    } else if(isset($_POST["ReceiptID"])) {
        $rID = $_POST["ReceiptID"];
        $query = "SELECT receiptId, deliveredDate FROM purchase WHERE receiptId = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("i", $rID);
        $stmt->execute();
        $result = $stmt->get_result();
        $entity = $result->fetch_assoc();
        if($entity == NULL){
            echo "No purchase found with given receipt ID.<br>";
        } else {
            echo "The receipt with ID: " . $entity["receiptId"] ." has the delivery date of ". $entity["deliveredDate"]."<br>";
            echo "Enter a date to change the delivery date. <br>";
        }
    }
?>