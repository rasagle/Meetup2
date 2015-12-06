<html>
<?php
include('include.php');
if($stmt = $mysqli->prepare("SELECT * FROM groups WHERE group_id = ?")){
	$stmt->bind_param("i", $_GET['group_id']);
	$stmt->execute();
	$stmt->bind_result($gID, $gname, $desc, $creator);
	echo "<table border = '1'>\n";
	echo "<tr>";
	echo "<th>Group ID</th><th>Group Name </th><th>Group Description</th><th>Creator ID</th>";
	echo "</tr>";
	while($stmt->fetch()){
		echo "<tr>";
		echo "<td>$gID</td><td>$gname </td><td>$desc</td><td>$creator</td>";
		echo "</tr>";
	}
	echo "</table>";
	$stmt->close();
}



?>

</html>