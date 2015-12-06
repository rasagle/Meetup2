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
	echo "Welcome $username. You are logged in.<br /><br />\n";
	 
	echo '<a href="view_rsvp.php?username=';
	echo htmlspecialchars($_SESSION["username"]);
	/*
	echo '">View RSVP events</a>, or <a href="post.php">post on your blog</a>, or <a href="logout.php">logout</a>.';
	echo "\n";
	*/
	echo '">View events you RSVP for</a>';
	echo "<br/><br/>";
	
	
	echo '<a href ="show_rsvp.php?username=';
	echo htmlspecialchars($_SESSION["username"]);
	echo '">RSVP for an event</a>';
	echo "<br/><br/>";
	
	echo '<a href ="show_group_authorized.php?username=';
	echo htmlspecialchars($_SESSION["username"]);
	echo '">Create an event</a>';
	echo "<br/><br/>";
	
	echo '<a href ="create_interest.php?username=';
	echo htmlspecialchars($_SESSION["username"]);
	echo '">Create an interest</a>';
	echo "<br/><br/>";
	
	echo '<a href ="create_group.php?username=';
	echo htmlspecialchars($_SESSION["username"]);
	echo '">Create a group</a>';
	echo "<br/><br/>";
	 
	echo '<a href="logout.php">logout</a>';
	
	echo "<br /><br />\n";


}
if ($stmt = $mysqli->prepare("SELECT title, event_id FROM events
	WHERE start_time <= (CURDATE() + INTERVAL 30 DAY) and (start_time >= CURDATE()) order by title")) {
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($eventName, $eventID);
	if($stmt->num_rows > 0){
		echo "<h4>Events happening in the next 30 days: </h4>";
		while ($stmt->fetch()) {
			/*
			$eventName = htmlspecialchars($eventName);
			$info = (string)$eventID + '|' + htmlspecialchars($_SESSION["username"]);
			echo '<a href="view.php?event_info=';
			echo $info;
			echo "\">$eventName</a><br />\n";
			*/
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
	$mysqli->close();
}
else{
	echo "<h4>ERROR</h4>";
}

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
		$mysqli->close();
	}

	?>
	
	</select><input type = "submit" value = "Show groups with interest">
</form>

</html>