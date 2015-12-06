<!DOCTYPE html>
<html>

<?php

include ("include.php");
echo '<a href="index.php">Go back</a><br /><br />';
if ($stmt = $mysqli->prepare("SELECT event_id, title, description, TIME_FORMAT(start_time, '%h:%i %p'), TIME_FORMAT(end_time,'%h:%i %p'), DATE_FORMAT(start_time, '%m-%d-%Y')
	FROM events WHERE event_id = ?")) {
	$stmt->bind_param("s", $_GET["eventID"]);
	$stmt->execute();
	$stmt->bind_result($eId, $eName, $eDesc, $eStart, $eEnd, $eDate);
	if($stmt->fetch()) {

		echo "<table border = 1>\n";
		echo"<tr><th>Event ID </th> <th>Event Name</th>
			<th>Description</th><th>Start time</th><th>End time</th><th>Date</th></tr>\n";
		echo"<tr><td>$eId</td><td>$eName</td><td>$eDesc</td><td>$eStart</td>
		<td>$eEnd</td><td>$eDate</td></tr>";
		echo "</table>";
	}
	else {
		echo "Event not found. \n";
		echo "You will be redirected in 3 seconds or click <a href=\"index.php\">here</a>.\n";
		header("refresh: 3; index.php");
	}
	$stmt->close();
}
echo "</br>";
if ($stmt = $mysqli->prepare("SELECT user_comment, username, DATE_FORMAT(ts, '%h:%i:%s %p %m-%d%-%y') FROM events natural join comments WHERE comments.event_id = ? order by ts")){
	$stmt->bind_param('s', $_GET['eventID']);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($comment, $user, $ts);
	if($stmt->num_rows > 0){
		echo "<hr>";
		while($stmt->fetch()){
			echo "<p>$user, $ts: </p>";
			echo "$comment </br>";
			echo "<hr>";
		}
		
	}
	else{
		echo "This event has no comments </br></br>";
	}
	$stmt->close();
}

if(isset($_GET["username"])){
	if(isset($_POST["comments"]) && !empty($_POST["comments"])){
		if($stmt = $mysqli->prepare("INSERT INTO comments (event_id, username, user_comment) VALUES(?, ?, ?)")){
			$comment = htmlspecialchars($_POST['comments']);
			$stmt->bind_param('sss', $_GET['eventID'], $_GET['username'], $comment);
			$stmt->execute();
			$stmt->close();
			header("refresh: 0.25; view.php?eventID=$_GET[eventID]&username=$_GET[username]");
		}
	}
	$eventID = $_GET["eventID"];
	$username = $_GET["username"];
	echo "Enter your comment: <br /><br />\n";
	echo "<form action='view.php?eventID=$eventID&username=$username' method='POST'>";
	echo '<textarea name="comments" cols = 50 rows = 10> </textarea> </br>';
	echo '<input type="submit" value="Comment" />';
	echo '</form>';
	
}
else{
	echo "You are not logged in, please click <a href='login.php'> here </a> to log in so you can comment. </br>";
}
echo '<a href="index.php">Go back</a><br /><br />';
echo "\n";
$mysqli->close();
?>

</html>