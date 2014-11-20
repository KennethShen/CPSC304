<?php
    include_once("includes/header.php");
    require_once("includes/connection.php");
    ?>
	
<?php
	session_start();

	//empty cart by destroying current session
	//if(isset($_GET["emptycart"]) && $_GET["emptycart"]==1) {
   	 //$return_url = base64_decode($_GET["return_url"]); //return url
   	 
    	//session_destroy();
    	//header('Location: ' . $_SERVER['HTTP_REFERER']);
	//}

	//add item in shopping cart
	if(!empty($_POST["title"]) && $_POST["title"]=='add'){
	
    $upc = filter_var($_POST["upc"], FILTER_SANITIZE_STRING); 
    $quantity = filter_var($_POST["quantity"], FILTER_SANITIZE_NUMBER_INT);
    $return_url = base64_decode($_POST["return_url"]);

    //MySqli query - get details of item from db using item upc HAVE TO EDIT LATER: ITEM REFERENCE NEEDED
    $results = $connection->query("SELECT item_title, price FROM item WHERE item_upc ='$item_upc'");
    $obj = $results->fetch_object();
    
    if ($results) { //we have the item info 
        
        //prepare array for the session variable
        $new_product = array(array('title'=>$obj->item_title, 'upc'=>$item_upc, 'quantity'=>$product_quantity, 'price'=>$obj->price));
        
        if(isset($_SESSION["Item"])) //if we have the session {
            $found = false; //set found item to falses
            
            foreach ($_SESSION["Item"] as $cart_itm) { //loop through session array 
                if($cart_itm["upc"] == $item_upc) { //the item exist in array

                    $items[] = array('title'=>$cart_itm["title"], 'upc'=>$cart_itm["upc"], 'quantity'=>$product_quantity, 'price'=>$cart_itm["price"]);
                    $found = true;
                } else {
                    //item doesn't exist in the list, just retrive old info and prepare array for session var
                    $items[] = array('title'=>$cart_itm["title"], 'upc'=>$cart_itm["upc"], 'quantity'=>$cart_itm["quantity"], 'price'=>$cart_itm["price"]);
                }
            }
            
            if($found == false) //we didn't find item in array
            {
                //add new user item in array
                $_SESSION["Item"] = array_merge($item, $new_item);
                } else {
                //found user item in array list, and increased the quantity
                $_SESSION["Item"] = $item;
            }
            
        } else {
            //create a new session var if does not exist
            $_SESSION["Item"] = $new_item;
        } 
    }
    
    //redirect back to original page
  	 //header('Location:'.$return_url);

//remove item from shopping cart
if(isset($_GET["removep"]) && isset($_GET["return_url"]) && isset($_SESSION["Item"])) {
    $upc = $_GET["removep"]; //get the product code to remove
    $return_url = base64_decode($_GET["return_url"]); //get return url
    
    foreach ($_SESSION["Item"] as $cart_itm) {  //loop through session array var
        if($cart_itm["upc"]!=$item_upc){ //item does,t exist in the list
            $items[] = array('title'=>$cart_itm["title"], 'upc'=>$cart_itm["upc"], 'quantity'=>$cart_itm["quantity"], 'price'=>$cart_itm["price"]);
        }
        
        //create a new product list for cart
        $_SESSION["Item"] = $item;
    }
    
    //redirect back to original page
    header('Location:'.$return_url);
}
?>