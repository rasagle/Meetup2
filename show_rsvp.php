<html>
<body>
<form action = "get_rsvp.php" method="GET">
	Choose an event<br>
	<select name='event_info'>

	<?php
	//This page displays a clickable tab that shows all events that user can RSVP for
	include("include.php");
	if($stmt = $mysqli->prepare("SELECT title, event_id FROM events
		WHERE start_time > CURDATE() order by title")){
		$stmt->execute();
		$stmt->bind_result($eName, $eId);
		while($stmt->fetch()) {
			$event_name = htmlspecialchars($eName);
			$event_id = htmlspecialchars($eId);
			$user_name = $_GET['username'];
			echo "<option value='$event_id|$user_name'>$event_name</option>\n";	
		}
		echo '</select><input type = "submit" value = "RSVP"></form></br>';
		echo '<a href="index.php">Go back</a><br /><br />';
		$stmt->close();
		$mysqli->close();
	}
	?>

</body>
</html>