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
    echo "Trying to pay.";
    //TODO Validate. Possibly split expiry into month/year.
    $cardNo = $_POST['cardNo'];
    $expiry = $_POST['expiry'];
    echo $_POST['submitPayment'];
    $receiptId = $basket->checkout($cardNo, $expiry);

    echo "Receipt ID is ".$receiptId;
    if ($receiptId){
        header("Location: receipt.php?receiptId=".$receiptId);
    }
}
?>
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
Expiry Date
<input id="expiry" name="expiry"><br>
<input type="submit" name="submitPayment" value="Pay Now">
<?php
//print_r($details);
?>
