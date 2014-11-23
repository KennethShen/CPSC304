<?php
    include_once("includes/header.php");
    require_once("includes/connection.php");
    ?>
    
<?php
session_start();
    if(isset($_SESSION["items"])) {
        $total = 0;
        echo '<form method="post" action="PAYMENT-GATEWAY">';
        echo '<ul>';
        $cart_items = 0;
        foreach ($_SESSION["items"] as $cart_itm) {
           $item_upc = $cart_itm["upc"];
           $results = $mysqli->query("SELECT item_title, item_category, price FROM items WHERE item_upc='$item_upc'");
           $obj = $results->fetch_object();
           
            echo '<li class="cart-itm">';
            echo '<span class="remove-itm"><a href="addingItems.php?removep='.$cart_itm["upc"].'&return_url='.$current_url.'">&times;</a></span>';
            echo '<div class="p-price">'.$currency.$obj->price.'</div>';
            echo '<div class="item-category">';
            echo '<h3>'.$obj->item_title.' (Code :'.$item_upc.')</h3> ';
            echo '<div class="p-qty">Qty : '.$cart_itm["qty"].'</div>';
            echo '<div>'.$obj->item_category.'</div>';
            echo '</div>';
            echo '</li>';
            $subtotal = ($cart_itm["price"]*$cart_itm["qty"]);
            $total = ($total + $subtotal);

            echo '<input type="hidden" name="item_ttile['.$cart_items.']" value="'.$obj->item_title.'" />';
            echo '<input type="hidden" name="item_upc['.$cart_items.']" value="'.$item_upc.'" />';
            echo '<input type="hidden" name="item_category['.$cart_items.']" value="'.$obj->item_category.'" />';
            echo '<input type="hidden" name="item_qty['.$cart_items.']" value="'.$cart_itm["qty"].'" />';
            $cart_items ++;
            
        }
        echo '</ul>';
        echo '<span class="check-out-txt">';
        echo '<strong>Total : '.$currency.$total.'</strong>  ';
        echo '</span>';
        echo '</form>';
        
    } else {
        echo 'Your Cart is empty';
    }
?>