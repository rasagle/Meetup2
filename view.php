<!DOCTYPE html>
<html>

<?php

include ("include.php");

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
if ($stmt = $mysqli->prepare("SELECT user_comment, username, ts FROM events natural join comments WHERE event_id = ? order by ts")){
	$stmt->bind_param('i', $_GET['eventID']);
	$stmt->store_result();
	$stmt->bind_result($comment, $user, $ts);
	if($stmt->num_rows == 0){
		echo "This event has no comments </br></br>";
	}
	else{
		while($stmt->fetch()){
			echo "$user, $ts </br>";
			echo "$comment </br></br>";
		}
	}
}

echo '<a href="index.php">Go back</a><br /><br />';
echo "\n";


$mysqli->close();
?>

</html>