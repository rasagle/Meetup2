<!DOCTYPE html>

<html>
<body>

<form action = "get_interest.php" method="GET">
Choose a username<br>
	<select name='interest_name'>

	<?php

	include "connectdb.php";

	if ($stmt = $mysqli->prepare("select distinct interest_name from interest")) {
		$stmt->execute();
		$stmt->bind_result($interst_name);
		while($stmt->fetch()) {
			$interest_name = htmlspecialchars($interest_name);
			echo "<option value='$interst_name'>$interest_name</option>\n";	
		}
		$stmt->close();
	}
	$mysqli->close();
	?>
	
	</select><input type = "submit" value = "Show info">
</form>
</body>
</html>
