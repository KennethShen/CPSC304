<?php
    include_once("includes/header.php");
    require_once("includes/connection.php");
    ?>
    
<div class="Item">
<?php
//addingItems.php redirects back to this URL
$current_url = base64_encode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
    	
    
    $results = $connection->query("SELECT * FROM Item ORDER BY upc ASC");
    if ($results) { 
        //output results from database
        while($obj = $results->fetch_object())
        {
            
            echo '<div class="Item">'; 
            echo '<form method="post" action="addingItems.php">';
            echo '<div class="product-content"><h3>'.$obj->title.'</h3>';
            echo '<div class="product-desc">'.$obj->category.'</div>';
            echo '<div class="product-info">Price '.$currency.$obj->price.' <button class="add_to_cart">Add To Cart</button></div>';
            echo '</div>';
            echo '<input type="hidden" name="upc" value="'.$obj->upc.'" />';
            echo '<input type="hidden" name="type" value="add" />';
            echo '<input type="hidden" name="return_url" value="'.$current_url.'" />';
            echo '</form>';
            echo '</div>';
        }
    
}
?>
</div>

<div class="shopping-cart">
<h2>Your Shopping Cart</h2>
<?php
if(isset($_SESSION["Item"]))
{
    $total = 0;
    echo '<ol>';
    foreach ($_SESSION["Item"] as $cart_itm)
    {
        echo '<li class="cart-itm">';
        echo '<span class="remove-itm"><a href="addingItems.php?removep='.$cart_itm["upc"].'&return_url='.$current_url.'">&times;</a></span>';
        echo '<h3>'.$cart_itm["title"].'</h3>';
        echo '<div class="p-code">P code : '.$cart_itm["code"].'</div>';
        echo '<div class="p-quantity">quantity : '.$cart_itm["quantity"].'</div>';
        echo '<div class="p-price">Price :'.$currency.$cart_itm["price"].'</div>';
        echo '</li>';
        $subtotal = ($cart_itm["price"]*$cart_itm["quantity"]);
        $total = ($total + $subtotal);
    }
    echo '</ol>';
    echo '<span class="check-out-txt"><strong>Total : '.$currency.$total.'</strong> <a href="view_cart.php">Check-out!</a></span>';
    echo '<span class="empty-cart"><a href="addingItems.php?emptycart=1&return_url='.$current_url.'">Empty Cart</a></span>';
} else {
    echo 'Your Cart is empty';
}
?>
</div>
