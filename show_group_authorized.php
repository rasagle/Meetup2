<html>
<body>
<?php
//Displays a list of groups that the user if authorized to create events for
include("include.php");
if($stmt = $mysqli->prepare("SELECT group_id, group_name
	FROM (member natural join belongs_to) join groups using(group_id)
	WHERE authorized = 1 and member.username = ? order by group_name")){
	$stmt->bind_param("s", $_GET["username"]);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($groupID, $groupName);
	if($stmt->num_rows > 0){
		echo '<form action = "create_event.php" method="GET">';
		echo "Choose a group to create an event for<br/>";
		echo "<select name='group_id'>";
		while($stmt->fetch()){
			$groupID = htmlspecialchars($groupID);
			$groupName = htmlspecialchars($groupName);
			echo "<option value='$groupID'>$groupName</option>\n";	
		}
		echo '</select><input type = "submit" value = "Create Event">';
		$stmt->close();
	}
	else{
		$stmt->close();
		echo "You are not authorized to create an event for any of the groups that you are in <br/>";
		echo "You will be redirected in 5 seconds or click <a href=\"index.php\">here</a>.\n";
		header("refresh: 5; index.php");
	}
	
}
else{
	echo "Some error has occured";
}
?>
</body>
</html>