
<?php
include_once("includes/header.php");
?>
<!DOCTYPE html>
<html>
<body>
<div id="login">
    <h1>Top Selling Items</h1>
    <form action="" method ="POST">
        <label>Date(YYYY-MM-DD)</label><br>
        <input id="text" name="date_top" value=""><br>
        <label>How many top selling items?</label><br>
        <input id="text" name="howmany" value=""><br>
        <input type="submit" name="submit" value="submit">
    </form>
</div>
</body>
<body>

<table>
    <tr valign = center>
        <th class=rowheader>UPC</th>
        <th class=rowheader>Category</th>
        <th class=rowheader>Unit Price</th>
        <th class=rowheader>Quantity</th>

    </tr>


<?php
/**
 * Created by PhpStorm.
 * User: Olivia
 * Date: 2014-11-23
 * Time: 1:34 PM
 */

include_once("includes/connection.php");


if(isset($_POST['submit'])) {
    if (empty($_POST['date_top']) || empty($_POST['howmany'])) {
        $error = "Please fill in every blank!";
        echo $error;
    } else {
        $howmany = $_POST['howmany'];
        $date_top = $_POST['date_top'];
        $date_split = explode("-", $date_top);
        $date_val = checkdate(intval($date_split[1]), intval($date_split[2]), intval($date_split[0]));

        if(ctype_digit($howmany) == true && $date_val == true) {
            $topView = "SELECT upc, category, price, total
                        FROM topsell
                        WHERE date=?
                        ORDER BY total DESC
                        LIMIT ?";

            $topstmt = $connection->prepare($topView);
            $topstmt->bind_param("si", $date_top, $howmany);
            $topstmt->execute();
            $result = $topstmt->get_result();
            while ($row = $result->fetch_assoc()) {
                if($row == NULL){
                    break;
                }
                echo "<tr>";
                echo "<td>" . $row['upc'] . "</td>";
                echo "<td>" . $row['category'] . "</td>";
                echo "<td>" . $row['price'] . "</td>";
                echo "<td>" . $row['total'] . "</td>";
                echo "</tr>";
            }
        }
        else {
            if(ctype_digit($howmany) == false){
                echo "Please type number for how many top selling items you want to see!<br>";
            }

            if ($date_val == false) {
                echo "Please type valid date!<br><br>";
            }
        }



            }


}

?>
    </table>
</body>
</html>
