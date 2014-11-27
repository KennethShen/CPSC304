<?php
include_once('includes/connection.php');
include_once('./classes/Basket.php');
include_once('includes/header.php');


    echo "<h1>Online Store</h1>";
if (isset($_SESSION['user_id'])){
    echo '<a href="checkout.php"> Checkout </a><br>';
} else {
    echo 'Please login to checkout.';
}
    echo "<form id='Search' method='post'>";
    echo "Title: ";
    echo "<input name='find_title'><br>";
    echo "Category: ";
    echo "<select name='find_category'>";
    echo "<option value ='' selected='selected'>--------------------</option>";
    $categories = ['rock', 'pop', 'rap', 'country', 'classical', 'new age', 'instrumental'];
    foreach ($categories as $cat){
        echo "<option value='$cat'>$cat</option>";
    }
    echo "</select><br>";
    echo 'Lead Singer: ';
    echo '<input name="find_leadsinger"><br>';
    echo "Quantity to buy: ";
    echo '<input type="number" name="want_qty" required><br>';
    echo '<input type="submit" name="submit" value="Search">';
    echo '</form>';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST["submit"]) && $_POST["submit"] == "Search") {

        if (!ctype_digit($_POST["want_qty"])){
            $_SESSION['alert']['error'] = "Need an integer value for quantity.";
        }
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
            $result = $stmt->get_result();
            echo '<table>';
            echo "<table border=1 cellpadding=1 cellspacing=1>";
            echo "<!-- Create the table column headings -->";

            echo "<tr valign=center>";
            echo "<td class=rowheader>UPC</td>";
            echo "<td class=rowheader>Title</td>";
            echo "<td class=rowheader>Category</td>";
            echo "<td class=rowheader>Price</td>";
            echo "<td class=rowheader>Stock</td>";
            echo "</tr>";

            while($row = $result->fetch_assoc()){
                $stock = $row['stock'];
                $upc = $row['upc'];
                if ($result->num_rows == 1){
                   echo "<script>addToBasket($upc,$want_qty,$stock);</script>";
                }
                echo "<td>".$row['upc']."</td>";
                echo "<td>".$row['title']."</td>";
                echo "<td>".$row['category']."</td>";
                echo "<td>".$row['price']."</td>";
                echo "<td>".$row['stock']."</td><td>";
                //Display an option to add this item using the Javascript function and the hidden title_id
                if ($row['stock'] == 0){
                    echo "<b style='color: red'>Out of Stock</b>";
                } else if ( $want_qty > $row['stock']) {
                    echo "<b style='color: red'>Not enough stock.<br>";
                    echo "<a href=\"javascript:addToBasket($upc, $want_qty, $stock);\">Add ".$row['stock']." to Basket instead.</a></b>";
                } else {
                    echo "<a href=\"javascript:addToBasket($upc, $want_qty, $stock);\">Add $want_qty to Basket </a>";
                }
                echo "</td></tr>";
            }
            echo "</table>";
        }
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
function addToBasket(upc, want_qty, stock) {
    'use strict';
    var qty = want_qty;
    if (want_qty > stock && confirm("There is only " + stock + " of the requested item left. Add all of it?")){
        qty = stock;
    }
      // Set the value of a hidden HTML element in this form
      var form = document.getElementById('addform');
      form.want_upc.value = upc;
      form.add_qty.value = qty;
      // Post this form
      form.submit();
}
</script>
