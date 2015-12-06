<!DOCTYPE html>
<!-- Example Blog written by Raymond Mui -->
<html>
<title>Meetup</title>

<?php

include ("include.php");

if(!isset($_SESSION["username"])) {
	echo "Welcome to the meetup, you are not logged in. <br /><br >\n";
	echo 'You may view the events listed below, <a href="login.php">login</a> to create an event or group or <a href="register.php">register</a> if you don\'t have an account yet.';
	echo "\n";
}
else {
	$username = htmlspecialchars($_SESSION["username"]);
	echo "Welcome $username<br/><br/>\n
	<a href=\"view_rsvp.php?username=$username\">View events you RSVP for</a><br/><br/>\n
	<a href=\"show_rsvp.php?username=$username\">RSVP for an event</a><br/><br/>\n
	<a href=\"show_group_authorized.php?username=$username&type=0\">Create an event</a><br/><br/>\n
	<a href=\"create_interest.php?username=$username\">Create an interest</a><br/><br/>\n
	<a href=\"create_group.php?username=$username\">Create a group</a><br/><br/>\n
	<a href=\"show_events_rate.php?username=$username\">Rate an event</a></br></br>\n
	<a href=\"show_all_groups.php?username=$username\">Join a group</a></br></br>\n
	<a href =\"show_group_authorized.php?username=$username&type=1\">Authorize user</a><br/><br/>\n";
	
	if($stmt = $mysqli->prepare("SELECT group_id, group_name 
		FROM groups join belongs_to using (group_id)
		WHERE belongs_to.username = ?")){
		$stmt->bind_param("s", $username);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($gID, $gname);
		if($stmt->num_rows > 0){
			echo "<h4> Groups that you belong to </h4>";
			while($stmt->fetch()){
				echo "<a href='show_user_groups.php?group_id=$gID'> $gname </a> </br></br>";
			}
		}
		else{
			echo "You are not in any groups </br></br>";
		}
	}
	
	echo "<a href=\"logout.php\">Logout</a><br/><br/>\n";
}
if ($stmt = $mysqli->prepare("SELECT title, event_id FROM events
	WHERE start_time <= (CURDATE() + INTERVAL 30 DAY) and (start_time >= CURDATE()) order by title")) {
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($eventName, $eventID);
	if($stmt->num_rows > 0){
		echo "<h4>Events happening in the next 30 days: </h4>";
		while ($stmt->fetch()) {

			if(isset($_SESSION["username"])){
				$username = $_SESSION["username"];
				echo "<a href='view.php?eventID=$eventID&username=$username'> $eventName </a> </br>";
			}
			else {
				echo "<a href='view.php?eventID=$eventID'> $eventName </a> </br>";
			}
		}	
	}
	else{
		echo "<h4>There are no events happening in the next 30 days <h4><br/>";
	}
	
	$stmt->close();
}
else{
	echo "<h4>ERROR</h4>";
}

echo "<h4> Past Events: </h4>";
if($stmt = $mysqli->prepare("SELECT title, event_id FROM events
	WHERE end_time < CURDATE() order by title")){
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($title, $eventID);
	if($stmt->num_rows > 0){
		while ($stmt->fetch()) {
			if(isset($_SESSION["username"])){
				$username = $_SESSION["username"];
				echo "<a href='view.php?eventID=$eventID&username=$username'> $title </a> </br>";
			}
			else {
				echo "<a href='view.php?eventID=$eventID'> $title </a> </br>";
			}
		}
	}
	else{
		echo "There are no past events </br>";
	}
}
$mysqli->close();
?>
<br/>

<form action = "get_interest.php" method="GET">
	<h4>Choose an interest:</h4>
	<select name='interest_name'>

	<?php
	include("include.php");
	if ($stmt = $mysqli->prepare("SELECT interest_name FROM interest")) {
		$stmt->execute();
		$stmt->bind_result($interest_name);
		while($stmt->fetch()) {
			$interest_name = htmlspecialchars($interest_name);
			echo "<option value='$interest_name'>$interest_name</option>\n";	
		}
		$stmt->close();
	}
	$mysqli->close();
	?>
	
	</select><input type = "submit" value = "Show groups with interest">
</form>

</html>