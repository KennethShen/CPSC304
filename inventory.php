<?php
    include_once("includes/header.php");
?>

<body>

<h1>AMS Item Inventory</h1>
<?php

    require_once("includes/connection.php");
    // Check that the connection was successful, otherwise exit
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
    // Update existing items
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["submit"]) && $_POST["submit"] ==  "UPDATE") {
        
        
        $item_upc = $_POST["new_item_upc"];
		$price = $_POST["new_unit_price"];
        $quantity = $_POST["new_quantity"];
        
         if (ctype_digit($item_upc) == false || is_numeric($price) == false || ctype_digit($quantity) == false) {
 			if(ctype_digit($item_upc) == false )
 		  	echo "<b>Please provide UPC in numerical value.</b><br>";
 		  	if (is_numeric($price) == false) 
 		 	 echo "<b>Please provide Price in numerical value.</b><br>";
 		 	 if (ctype_digit($quantity) == false) 
 		 	 echo "<b>Please provide Quantity in numerical value.</b><br>";
 		  } else {
        $old_q = "SELECT stock FROM Item WHERE upc = ?";
        $r_stmt = $connection->prepare($old_q);
        $r_stmt->bind_param("i", $item_upc);
        $r_stmt->execute();
        $result = $r_stmt->get_result();
        
        $entity = $result->fetch_assoc();
        if($entity == NULL) {
        echo"<b>Cannot find item</b>";
        } else {
        $old_quantity = $entity["stock"];
        }
        
        $quantity += $old_quantity;
       
		if($item_upc == $item_upc) {
    	$stmt = $connection->prepare("UPDATE Item SET price=?, stock = ? WHERE upc= ?");
		$stmt->bind_param("dii", $price, $quantity, $item_upc);
		$stmt->execute();       
       }
       if($stmt->error) {
         printf("<b>Error: %s.</b>\n", $stmt->error);
       } else {
         echo "<b>Successfully updated: UPC ".$item_upc."</b>";
       }
       }
}
      if (isset($_POST["submitDelete"]) && $_POST["submitDelete"] == "CLEAR") {
       /*
          Delete the selected item  using the item_upc
        */
       
       // Create a delete query prepared statement with a ? for the item_upc
       $stmt = $connection->prepare("UPDATE Item SET stock = 0 WHERE upc = ?");
       $deleteUPC = $_POST['item_upc'];
       // Bind the title_id parameter, 's' indicates a string value
       $stmt->bind_param("s", $deleteUPC);
       
       // Execute the delete statement
       $stmt->execute();
          
       if($stmt->error) {
         printf("<b>Error: %s.</b>\n", $stmt->error);
       } else {
         echo "<b>Successfully cleared: 0 quantity for Item UPC ".$deleteUPC."</b>";
       }
            
      } elseif (isset($_POST["submit"]) && $_POST["submit"] ==  "ADD") {       
       /*
        Add an item title using the post variables
        */
        $item_upc = $_POST["new_item_upc"];
        $title = $_POST["new_title"];
        $type = $_POST["new_type"];
        $category = $_POST["new_category"];
        $company = $_POST["new_company"];
        $year = $_POST["new_year"];
		$price = $_POST["new_unit_price"];
        $quantity = $_POST["new_quantity"];
        
 		// Check if valid
 		if (ctype_digit($item_upc) == false || ctype_alpha($type) == false || ctype_alpha($category) == false || ctype_digit($year) == false || is_numeric($price) == false || ctype_digit($quantity) == false) {
 			if(ctype_digit($item_upc) == false )
 		  	echo "<b>Please provide UPC in numerical value.</b><br>";
 		 	if(ctype_alpha($type) == false)
 		  	echo "<b>Please provide Type in one of the following types: cd or dvd.</b><br>";
 		  	if (ctype_alpha($category) == false) 
 		  	echo "<b>Please provide Category in one of the following types: rock, pop, rap, country, classical, new age or instrumental.</b><br>";
 		 	 if (ctype_digit($year) == false) 
 		  	echo "<b>Please provide Year in numerical value.</b><br>";
 		  	if (is_numeric($price) == false) 
 		 	 echo "<b>Please provide Price in numerical value.</b><br>";
 		 	 if (ctype_digit($quantity) == false) 
 		 	 echo "<b>Please provide Quantity in numerical value.</b><br>";
 		  } else {

        $stmt = $connection->prepare("INSERT INTO Item (upc, title, type, category, company, year, price, stock) VALUES (?,?,?,?,?,?,?,?)");
        
        // Bind the title and pub_id parameters
        $stmt->bind_param("issssidi", $item_upc, $title, $type, $category, $company, $year, $price, $quantity);
        
        // If the provided upc already exits, Update
        // Otherwise execute the insert statements
        	
    	//if($item_upc == $item_upc) {
    	//		$stmt = $connection->prepare("UPDATE Item SET price=?, stock =? WHERE upc= ?");
		//		$stmt->bind_param("dii", $price, $quantity, $item_upc);
		//		$stmt->execute();
		//	} else {
    			$stmt->execute();
    	//	}
    		
    	if($stmt->error) {
         printf("<b>Error: %s.</b>\n", $stmt->error);
       } else {
         echo "<b>Successfully added: UPC ".$item_upc."</b>";
       }
    }    
   }
   }
   
?>

<h2>Item Titles in Category Order</h2>
<!-- Set up a table to view the book titles -->
<table border=0 cellpadding=0 cellspacing=7>
<!-- Create the table column headings -->

<tr valign=center>
<td class=rowheader>UPC</td>
<td class=rowheader>Title</td>
<td class=rowheader>Type</td>
<td class=rowheader>Category</td>
<td class=rowheader>Company</td>
<td class=rowheader>Year</td>
<td class=rowheader>Unit Price</td>
<td class=rowheader>Quantity</td>
</tr>

<?php
    
   // Select all of the item rows columns upc, title and quantity...
    if (!$result = $connection->query("SELECT upc, title, type, category, company, year, price, stock FROM Item ORDER BY Category")) {
        die('There was an error running the query [' . $connection->error . ']');
    }

    // Avoid Cross-site scripting (XSS) by encoding PHP_SELF (this page) using htmlspecialchars.
    echo "<form id=\"delete\" name=\"delete\" action=\"";
    echo htmlspecialchars($_SERVER["PHP_SELF"]);
    echo "\" method=\"POST\">";
    // Hidden value is used if the delete link is clicked
    echo "<input type=\"hidden\" name=\"item_upc\" value=\"-1\"/>";
   // We need a submit value to detect if delete was pressed 
    echo "<input type=\"hidden\" name=\"submitDelete\" value=\"CLEAR\"/>";

    /****************************************************
     STEP 4: Display the list of item titles
     ****************************************************/
    // Display each item title databaserow as a table row
    while($row = $result->fetch_assoc()){
       echo "<tr>";
       echo "<td>".$row['upc']."</td>";
       echo "<td>".$row['title']."</td>";
       echo "<td>".$row['type']."</td>";
       echo "<td>".$row['category']."</td>";
       echo "<td>".$row['company']."</td>";
       echo "<td>".$row['year']."</td>";
       echo "<td>".$row['price']."</td>";
	   echo "<td>".$row['stock']."</td><td>";
       
       //Display an option to delete this title using the Javascript function and the hidden item_upc
       echo "<a href=\"javascript:formSubmit('".$row['upc']."');\">CLEAR</a>";
       echo "</td></tr>";
        
    }
    echo "</form>";
    
	echo "</table>";
    // Close the connection to the database once we're done with it.
   // mysqli_close($connection);
?>

</table>

<h2>Add a New Item</h2>



<form id="add" name="add" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <table border=0 cellpadding=1 cellspacing=>
        <tr><td>UPC:</td><td><input type="text" size=30 name="new_item_upc"</td></tr>
        <tr><td>Item Title:</td><td><input type="text" size=30 name="new_title"</td></tr>
        <tr><td>Item Type:</td><td><select name="new_type">
        <option value='' selected='selected'>-------</option>
        <option value='CD'>CD</option>
        <option value='DVD'>DVD</option>
        </select></td></tr>
        <tr><td>Category:</td><td><select name='new_category'>
        <option value ='' selected='selected'>------------------</option>
<?php
    $categories = ['rock', 'pop', 'rap', 'country', 'classical', 'new age', 'instrumental'];
    foreach ($categories as $cat){
        echo "<option value='$cat'>$cat</option>";
    }
?>
        </select></td></tr>
        <tr><td>Company Name:</td><td> <input type="text" size=30 name="new_company"></td></tr>
        <tr><td>Year:</td><td> <input type="text" size=30 name="new_year"></td></tr>
        <tr><td>Unit Price:</td><td> <input type="text" size=5 name="new_unit_price"></td></tr>
        <tr><td>Quantity:</td><td> <input type="text" size=5 name="new_quantity"></td></tr>
        <tr><td></td><td><input type="submit" name="submit" border=0 value="ADD"></td></tr>
    </table>
</form>

<h2>Update an Existing Item</h2>



<form id="add" name="add" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <table border=0 cellpadding=1 cellspacing=>
        <tr><td>UPC:</td><td><input type="text" size=30 name="new_item_upc"</td></tr>
        <tr><td>Unit Price:</td><td> <input type="text" size=5 name="new_unit_price"></td></tr>
        <tr><td>Quantity:</td><td> <input type="text" size=5 name="new_quantity"></td></tr>
        <tr><td></td><td><input type="submit" name="submit" border=0 value="UPDATE"></td></tr>
    </table>
</form>
<!--
    Javascript to submit a title_id as a POST form, used with the "clear" links
-->
<script>
function formSubmit(titleId) {
    'use strict';
    if (confirm('Are you sure you want to clear this item?')) {
      // Set the value of a hidden HTML element in this form
      var form = document.getElementById('delete');
      form.item_upc.value = titleId;
      // Post this form
      form.submit();
    }
}
</script>
</body>
</html>
