<!DOCTYPE html>

<html>
<?php

include "include.php";

//perform SQL query
if(isset($_GET["interest_name"])) {
    $input_iname = $_GET["interest_name"];
    if ($stmt = $mysqli->prepare("SELECT group_name, description FROM interest natural join group_interest natural join groups
		WHERE interest_name=?")) {
        $stmt->bind_param("s", $input_iname);
        $stmt->execute();
		$stmt->store_result();
        $stmt->bind_result($group_name, $group_descrip);
		if($stmt->num_rows > 0){
			// Printing results in HTML
			echo "<table border = '1'>\n";
			echo "<tr>";
			echo "<th>Group Name </th><th>Group Description</th>";
			echo "<tr>";
			while ($stmt->fetch()) {
				echo "<tr>";
				echo "<td>$group_name</td><td>$group_descrip</td>";
				echo "</tr>\n";
			}
			echo "</table>\n";
		}
		else{
			echo "No group has this interest yet";
		}
        
        $stmt->close();
		$mysqli->close();
    }
}
else {
    echo "Interest not set\n";
}
?>
</html>
