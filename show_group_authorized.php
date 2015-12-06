<html>
<body>
<?php
//Displays a list of groups that the user if authorized to create events for
include("include.php");
if(isset($_GET["username"]) && isset($_GET["type"]) && $stmt = $mysqli->prepare("SELECT group_id, group_name
	FROM (member natural join belongs_to) join groups using(group_id)
	WHERE authorized = 1 and member.username = ? order by group_name")){
	$stmt->bind_param("s", $_GET["username"]);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($groupID, $groupName);
	if($stmt->num_rows > 0){
		$type = $_GET["type"];
		if ($type == 0) {
			echo '<form action = "create_event.php" method="GET">Choose a group to create an event for<br/>';
		} else if ($type == 1) {
			echo '<form action = "authorize_user.php" method="GET">Choose the group the user is in<br/>';
		}
		echo "<select name='group_id'>";
		while($stmt->fetch()){
			$groupID = htmlspecialchars($groupID);
			$groupName = htmlspecialchars($groupName);
			echo "<option value='$groupID'>$groupName</option>\n";	
		}
		echo '</select><input type = "submit" value = "Enter">';
	}
	else{
		echo "You are not authorized to create an event for any of the groups that you are in <br/>";
		echo "You will be redirected in 5 seconds or click <a href=\"index.php\">here</a>.\n";
		header("refresh: 5; index.php");
	}
	$stmt->close();
}
else{
	echo "Some error has occurred";
}
echo '</br></br><a href="index.php">Go back</a><br /><br />';
$mysqli->close();
?>
</body>
</html>