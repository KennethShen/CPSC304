<?php
include_once('includes/connection.php');
include_once('./classes/Basket.php');
include_once('includes/header.php');
session_start();

    echo '<a href="checkout.php"> Checkout </a>';

    echo "<form id='Search' method='post'>";
    echo "<input name='find_title'>";
    echo "<select name='find_category'>";
    echo "<option value ='' selected='selected'>";
    $categories = ['rock', 'pop', 'rap', 'country', 'classical', 'new age', 'instrumental'];
    foreach ($categories as $cat){
        echo "<option value='$cat'>$cat</option>";
    }
    echo "</select>";
    echo '<input name="find_leadsinger">';
    echo '<input name="want_qty">';
    echo '<input type="submit" name="submit" value="Search">';
    echo '</form>';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST["submit"]) && $_POST["submit"] == "Search") {
       /*
        Add a book title using the post variables title_id, title and pub_id.
        */

        $title = "%".$_POST["find_title"]."%";
        $category = $_POST["find_category"];
        $leadsinger = $_POST["find_leadsinger"];
        $want_qty = $_POST["want_qty"];

        $querystr = "SELECT i.upc, title, category, price, stock ".
                    "FROM Item i LEFT JOIN LeadSinger l ".
                    "ON i.upc = l.upc ".
                    "WHERE i.title LIKE ?";
        if (!empty($category)){
            $querystr .= " AND i.category = ?";
        }
        if (!empty($leadsinger)){
            $querystr .= " AND l.name LIKE ?";
        }

        $stmt = $connection->prepare($querystr);


        // Bind the title and pub_id parameters, 'sss' indicates 3 strings
        if (empty($leadsinger) && empty ($category)){
            $stmt->bind_param("s", $title);
        } elseif (empty($category)){
            $leadsinger = "%".$leadsinger."%";
            $stmt->bind_param("ss", $title, $leadsinger);
        } elseif (empty($leadsinger)){
            $stmt->bind_param("ss", $title, $category);
        } else {
            $leadsinger = "%".$leadsinger."%";
            $stmt->bind_param("sss", $title, $category, $leadsinger);
        }
        // Execute the select statement
        $stmt->execute();

        if($stmt->error) {
          printf("<b>Error: %s.</b>\n", $stmt->error);
        } else {
            echo '<table>';
            echo "<table border=0 cellpadding=0 cellspacing=0>";
            echo "<!-- Create the table column headings -->";

            echo "<tr valign=center>";
            echo "<td class=rowheader>UPC</td>";
            echo "<td class=rowheader>Title</td>";
            echo "<td class=rowheader>Category</td>";
            echo "<td class=rowheader>Price</td>";
            echo "<td class=rowheader>Stock</td>";
            echo "</tr>";

            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()){
print_r($row);
                echo "<td>".$row['upc']."</td>";
                echo "<td>".$row['title']."</td>";
                echo "<td>".$row['category']."</td>";
                echo "<td>".$row['price']."</td><td>";
                echo "<td>".$row['stock']."</td><td>";
                //Display an option to add this item using the Javascript function and the hidden title_id
                echo "<a href=\"javascript:addToBasket(".$row['upc'].",".$want_qty.");\">Add to Basket ".$want_qty."</a>";
                echo "</td></tr>";
            }
            echo "</table>";
        }
        print_r($_SESSION);
      } else if (isset($_POST['SUBMITADD']) && $_POST['SUBMITADD'] == "basket"){
          $basket = new Basket();
          $basket->addItem($_POST['want_upc'],$_POST['add_qty']);
      }
    }


?>

<form id="addform" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
<input type ="hidden" name="want_upc" id="want_upc" value="" />
<input type ="hidden" name="add_qty" id="add_qty" value="" />
<input type ="hidden" name="SUBMITADD" value="basket" />
</form>

<script>
function addToBasket(upc, want_qty) {
    'use strict';
    if (confirm('Are you sure you want to add the cart?')) {
      // Set the value of a hidden HTML element in this form
      var form = document.getElementById('addform');
      form.want_upc.value = upc;
      form.add_qty.value = want_qty;
      // Post this form
      form.submit();
    }
}
</script>
