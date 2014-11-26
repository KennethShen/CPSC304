<?php
    include_once("includes/header.php");
?>

<body>

<h1>Manage Item Inventory</h1>
<?php

    require_once("includes/connection.php");
    // Check that the connection was successful, otherwise exit
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
    /****************************************************
     STEP 2: Detect the user action

     Next, we detect what the user did to arrive at this page
     There are 3 possibilities 1) the first visit or a refresh,
     2) by clicking the Delete link beside a book title, or
     3) by clicking the bottom Submit button to add a book title
     
     NOTE We are using POST superglobal to safely pass parameters
        (as opposed to URL parameters or GET)
     ****************************************************/

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

      if (isset($_POST["submitDelete"]) && $_POST["submitDelete"] == "CLEAR") {
       /*
          Delete the selected item title using the item_upc
        */
       
       // Create a delete query prepared statement with a ? for the item_upc
       //$stmt = $connection->prepare("DELETE FROM Item WHERE upc=?");
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
        Add an item title using the post variables item_upc, upc and quantity.
        */
        $item_upc = $_POST["new_item_upc"];
        $title = $_POST["new_title"];
        $type = $_POST["new_type"];
        $category = $_POST["new_category"];
        $company = $_POST["new_company"];
        $year = $_POST["new_year"];
		$price = $_POST["new_unit_price"];
        $quantity = $_POST["new_quantity"];
       
    
        $stmt = $connection->prepare("INSERT INTO Item (upc, title, type, category, company, year, price, stock) VALUES (?,?,?,?,?,?,?,?)");
        
        // Bind the title and pub_id parameters, 'sss' indicates 3 strings
        $stmt->bind_param("issssidi", $item_upc, $title, $type, $category, $company, $year, $price, $quantity);
        
        // Execute the insert statement
        if ($item_upc==0) {
    		die("There was no item to be added. To add a new item, please go back and specify the fields. ");
    	} else {
    		if($stmt->error) {       
          printf("<b>Error: %s.</b>\n", $stmt->error); 
        $stmt = $connection->prepare("UPDATE Item SET price=?, stock =? WHERE upc= ?");
		$stmt->bind_param("dii", $price, $quantity, $item_upc);
		$stmt->execute();
        } else {
          echo "<b>Successfully added: Item ".$title."</b>";
        }
    	$stmt->execute();
    	}
      }
   }
?>

<h2>Item Titles in Alphabetical Order</h2>
<!-- Set up a table to view the book titles -->
<table border=0 cellpadding=0 cellspacing=0>
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
    /****************************************************
     STEP 3: Select the most recent list of item titles
     ****************************************************/

   // Select all of the item rows columns upc, title and quantity...
    if (!$result = $connection->query("SELECT upc, title, type, category, company, year, price, stock FROM Item ORDER BY title")) {
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

<h2>Add a New Item Title</h2>

<!--
  /****************************************************
   STEP 5: Build the form to add a book title
   ****************************************************/
    Use an HTML form POST to add a book, sending the parameter values back to this page.
    Avoid Cross-site scripting (XSS) by encoding PHP_SELF using htmlspecialchars.

    This is the simplest way to POST values to a web page. More complex ways involve using
    HTML elements other than a submit button (eg. by clicking on the delete link as shown above).
-->

<form id="add" name="add" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <table border=0 cellpadding=0 cellspacing=0>
        <tr><td>UPC:</td><td><input type="text" size=30 name="new_item_upc"</td></tr>
        <tr><td>Item Title:</td><td><input type="text" size=30 name="new_title"</td></tr>
        <tr><td>Item Type CD/ DVD:</td><td> <input type="text" size=30 name="new_type"></td></tr>
        <tr><td>Item Category:</td><td> <input type="text" size=30 name="new_category"></td></tr>
        <tr><td>Company Name:</td><td> <input type="text" size=30 name="new_company"></td></tr>
        <tr><td>Year:</td><td> <input type="text" size=30 name="new_year"></td></tr>
        <tr><td>Unit Price:</td><td> <input type="text" size=5 name="new_unit_price"></td></tr>
        <tr><td>Quantity:</td><td> <input type="text" size=5 name="new_quantity"></td></tr>
        <tr><td></td><td><input type="submit" name="submit" border=0 value="ADD"></td></tr>
    </table>
</form>

<!--
    Javascript to submit a title_id as a POST form, used with the "delete" links
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
