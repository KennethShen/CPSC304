
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
        $error = "Please type!";
        echo $error;
    } else {

        $date_top = $_POST['date_top'];
        $howmany = $_POST['howmany'];
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
            echo "<tr>";
            echo "<td>" . $row['upc'] . "</td>";
            echo "<td>" . $row['category'] . "</td>";
            echo "<td>" . $row['price'] . "</td>";
            echo "<td>" . $row['total'] . "</td>";
            echo "</tr>";

        }

    }
}

?>
    </table>
</body>
</html>
