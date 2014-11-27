<?php
include_once(dirname(__FILE__)."/includes/header.php");
require_once(dirname(__FILE__)."/includes/connection.php");
require_once(dirname(__FILE__)."/classes/Basket.php");
    ?>

<?php
$basket = new Basket();
if (!isset($_SESSION['user_id'])){
    // User not logged in. Redirect.
    header("Location: shop.php");
}
else if (isset($_POST['submitPayment'])){
    echo "Trying to pay.<br>";
    //TODO Validate. Possibly split expiry into month/year.
    $cardNo = $_POST['cardNo'];
    $expYear = $_POST['year'];
    $expMonth = $_POST['month'];
    if(!ctype_digit($cardNo) || !ctype_digit($expYear) || !ctype_digit($expMonth)) {
        if (!ctype_digit($cardNo)) {
            echo "Invalid card number. Enter numbers only. <br>";
        }
        if (!ctype_digit($expYear)) {
            echo "Invalid year. Enter numbers only.<br>";
        }
        if (!ctype_digit($expMonth)) {
            echo "Invalid month. Enter numbers only.<br>";
        }
    } else {
        if(checkdate(intval($expMonth), 1, intval($expYear))) {
            echo $_POST['submitPayment'];
            $expiry = $expYear."-".$expMonth."-01";
            echo $expiry;
            $receiptId = $basket->checkout($cardNo, $expiry);

            echo "Receipt ID is " . $receiptId;
            if ($receiptId) {
                header("Location: receipt.php?receiptId=" . $receiptId);
            }
        } else {
            echo "Invalid expiry date. <br>";
        }
    }
} else if (isset($_POST['emptyCart'])){
    $basket->emptyBasket();
    $_SESSION['alert']['info'] = "Cleared Basket.";
    header("Location: checkout.php");
} else if (isset($_POST['removeUpc'])) {
    $basket->removeItem($_POST['removeUpc']);
        header("Location: checkout.php");
}
?>

<br>
<form name="emptyCartForm" method="POST">
<input type="submit" name="emptyCart" value="Empty Cart">
</form>

<table>
<thead>
<tr>
<th>Item Name</th>
<th>Unit Price</th>
<th>Purchase Quantity</th>
<th>Price</th>
</tr>
</thead>
<tbody>
<?php 
$totalprice = 0;
$contents = $basket->getContents();
if (!empty($contents)){

    $details = $basket->getDetails();
    foreach ($contents as $item_upc => $unit_qty){
        $unit_price = $details[$item_upc]['price'];
        $subtotal = $unit_price * $unit_qty;
        $totalprice += $subtotal;

        echo '<tr style="text-align: right">';
        echo '<td>'.$details[$item_upc]['title'].'</td>';
        echo '<td>'.$unit_price.'</td>';
        echo '<td>'.$unit_qty.'</td>';
        echo '<td>'.$subtotal.'</td>';
        echo '</tr>';
    } 
} else {
    echo "<tr><td colspan=4 style='text-align:center'>No Items in your Basket <br><a href=\"shop.php\">Go and shop.</td></tr>";
}
?>
</tbody>
<tfoot><tr>
<th colspan=3 style="text-align: right">Total</th>
<th><?php echo $totalprice ?></td>
</tr></tfoot>
</table>
<br>
<br>
<form id="payment" name="payment" method="POST">
Card Number
<input id="cardNo" name="cardNo"><br>
Expiry Date:<br> Year:
<input id="expiry" name="year" maxlength = "4">
Month:
<input id="expiry" name="month" maxlength = "2"><br>
<input type="submit" name="submitPayment" value="Pay Now">
<?php
//print_r($details);
?>
