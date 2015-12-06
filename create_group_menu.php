<html>
<body>
<?php
include("include.php");
if ($stmt = $mysqli->prepare("SELECT interest_name FROM interest order by interest_name")) {
	$stmt->execute();
	$stmt->bind_result($interest_name);
	$username = $_GET["username"];
	echo '<form action = "create_group.php" method="GET">';
	echo "Choose an interest for your group<br/>";
	echo "<select name='interest_info'>";
	while($stmt->fetch()) {
		$interest_name = htmlspecialchars($interest_name);
		echo "<option value='$interest_name|$username'>$interest_name</option>\n";	
	}
	echo '</select><input type = "submit" value = "Next">';
	$stmt->close();
}
$mysqli->close();
?>
</form>
<body/>
</html>